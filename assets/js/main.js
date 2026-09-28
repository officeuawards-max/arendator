/* ==========================================================================
   ARENDATOR AWARDS — лендинг билетов
   Без зависимостей. Если на странице есть jQuery (layout основного сайта),
   форма оплаты вставляется через $.html(), чтобы отработали её скрипты.
   ========================================================================== */
(function () {
  'use strict';

  var CFG = window.LANDING_CONFIG || {};
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var root = document.documentElement;

  /* ------------------------------------------------------------------
     Аналитика: цели Метрики
     ------------------------------------------------------------------ */
  function goal(key, params) {
    var a = CFG.analytics || {};
    var name = a.goals && a.goals[key];
    if (!name || !a.metrikaId) return;
    try { if (typeof window.ym === 'function') window.ym(a.metrikaId, 'reachGoal', name, params || {}); } catch (e) {}
  }

  /* ------------------------------------------------------------------
     Разбивка заголовков на слова (для анимации «из-под маски»)
     ------------------------------------------------------------------ */
  function splitHeading(el) {
    var i = 0;
    (function walk(node) {
      Array.prototype.slice.call(node.childNodes).forEach(function (child) {
        if (child.nodeType === 3) {
          var parts = child.textContent.split(/([ \t\r\n]+)/);
          var frag = document.createDocumentFragment();
          parts.forEach(function (p) {
            if (!p) return;
            if (/^[ \t\r\n]+$/.test(p)) { frag.appendChild(document.createTextNode(' ')); return; }
            var outer = document.createElement('span');
            outer.className = 'aa-split-word';
            var inner = document.createElement('span');
            inner.style.setProperty('--i', i++);
            inner.textContent = p;
            outer.appendChild(inner);
            frag.appendChild(outer);
          });
          node.replaceChild(frag, child);
        } else if (child.nodeType === 1) {
          walk(child);
        }
      });
    })(el);
    el.setAttribute('aria-label', el.textContent.replace(/\s+/g, ' ').trim());
  }
  $$('[data-split]').forEach(splitHeading);

  /* ------------------------------------------------------------------
     Появление блоков при скролле
     ------------------------------------------------------------------ */
  var revealTargets = $$('[data-reveal], [data-split]');
  if ('IntersectionObserver' in window && !reduceMotion) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-in');
        io.unobserve(e.target);
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -6% 0px' });
    revealTargets.forEach(function (el) { io.observe(el); });
  } else {
    revealTargets.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ------------------------------------------------------------------
     Счётчики цифр (11+, 85+, 300+)
     ------------------------------------------------------------------ */
  function countUp(el) {
    var to = parseInt(el.getAttribute('data-count'), 10) || 0;
    if (reduceMotion) { el.textContent = to; return; }
    var t0 = null, dur = 1800;
    function frame(t) {
      if (!t0) t0 = t;
      var p = Math.min((t - t0) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 4);
      el.textContent = Math.round(to * eased);
      if (p < 1) requestAnimationFrame(frame);
    }
    el.textContent = '0';
    requestAnimationFrame(frame);
  }
  if ('IntersectionObserver' in window) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        countUp(e.target);
        cio.unobserve(e.target);
      });
    }, { threshold: 0.6 });
    $$('[data-count]').forEach(function (el) { cio.observe(el); });
  }

  /* ------------------------------------------------------------------
     Навигация, прогресс чтения, таймлайн, мобильная кнопка
     ------------------------------------------------------------------ */
  var nav = $('[data-nav]');
  var bar = $('.aa-progress span');
  var hero = $('.aa-hero');
  var timeline = $('[data-timeline]');
  var tlItems = $$('.aa-timeline__item');
  var sticky = $('[data-sticky-cta]');
  var packages = $('#packages');
  var footer = $('.aa-footer');
  var lastY = window.scrollY;
  var ticking = false;

  function onScroll() {
    var y = window.scrollY;
    var vh = window.innerHeight;
    var docH = document.documentElement.scrollHeight - vh;

    if (bar) bar.style.setProperty('--p', docH > 0 ? (y / docH).toFixed(4) : 0);

    if (nav) {
      nav.classList.toggle('is-scrolled', y > 30);
      var goingDown = y > lastY && y > (hero ? hero.offsetHeight * 0.6 : 400);
      if (!nav.classList.contains('is-open')) nav.classList.toggle('is-hidden', goingDown);
    }

    if (timeline) {
      var r = timeline.getBoundingClientRect();
      var mid = vh * 0.6;
      var p = Math.max(0, Math.min(1, (mid - r.top) / r.height));
      timeline.style.setProperty('--tl', p.toFixed(4));
      tlItems.forEach(function (it) { it.classList.toggle('is-active', it.getBoundingClientRect().top < mid); });
    }

    if (sticky) {
      var past = hero ? y > hero.offsetHeight * 0.8 : y > 600;
      var inPack = false, nearEnd = false;
      if (packages) { var pr = packages.getBoundingClientRect(); inPack = pr.top < vh * 0.8 && pr.bottom > vh * 0.2; }
      if (footer) nearEnd = footer.getBoundingClientRect().top < vh;
      sticky.classList.toggle('is-visible', past && !inPack && !nearEnd);
    }

    lastY = y;
    ticking = false;
  }
  window.addEventListener('scroll', function () {
    if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
  }, { passive: true });
  window.addEventListener('resize', onScroll);
  onScroll();

  // бургер
  var burger = $('[data-burger]');
  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', open);
      nav.classList.remove('is-hidden');
    });
    $$('[data-menu] a').forEach(function (a) {
      a.addEventListener('click', function () { nav.classList.remove('is-open'); burger.setAttribute('aria-expanded', 'false'); });
    });
  }

  /* ------------------------------------------------------------------
     Бегущая строка логотипов: дублируем набор для бесшовной прокрутки
     ------------------------------------------------------------------ */
  $$('[data-marquee]').forEach(function (m) {
    var track = $('.aa-marquee__track', m);
    if (!track || reduceMotion) return;
    $$('li', track).forEach(function (li) {
      var c = li.cloneNode(true);
      c.setAttribute('aria-hidden', 'true');
      track.appendChild(c);
    });
    // скорость ~ 60px/сек независимо от количества логотипов
    requestAnimationFrame(function () {
      track.style.setProperty('--dur', Math.max(20, track.scrollWidth / 2 / 60) + 's');
    });
  });
  // если картинка логотипа ещё не загружена/битая — показываем название
  $$('.aa-logo img').forEach(function (img) {
    if (img.complete && img.naturalWidth === 0) img.closest('.aa-logo').classList.add('is-missing');
  });
  // то же для остальных медиа, упавших до старта скрипта
  $$('[data-media] img').forEach(function (img) {
    if (img.complete && img.naturalWidth === 0 && !img.getAttribute('data-alt-src')) {
      img.classList.add('is-broken');
      img.closest('[data-media]').classList.add('is-missing');
    }
  });

  /* ------------------------------------------------------------------
     Видео в галерее: грузим только когда блок виден
     ------------------------------------------------------------------ */
  $$('[data-lazy-video]').forEach(function (v) {
    if (!('IntersectionObserver' in window)) return;
    var loaded = false;
    new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          if (!loaded) {
            $$('source[data-src]', v).forEach(function (s) { s.src = s.getAttribute('data-src'); });
            v.load(); loaded = true;
          }
          if (!reduceMotion) { var pr = v.play(); if (pr && pr.catch) pr.catch(function () {}); }
        } else if (loaded) { v.pause(); }
      });
    }, { threshold: 0.25 }).observe(v);
  });

  /* ------------------------------------------------------------------
     Карточки пакетов: световое пятно за курсором
     ------------------------------------------------------------------ */
  $$('.aa-card').forEach(function (card) {
    card.addEventListener('pointermove', function (e) {
      var r = card.getBoundingClientRect();
      card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      card.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  /* ------------------------------------------------------------------
     Слайдер отзывов (нативный scroll-snap + стрелки)
     ------------------------------------------------------------------ */
  var slider = $('[data-slider]');
  if (slider) {
    var navBtns = $$('[data-slide]');
    var step = function () { var c = $('.aa-review', slider); return c ? c.getBoundingClientRect().width + 16 : 400; };
    navBtns.forEach(function (b) {
      b.addEventListener('click', function () {
        slider.scrollBy({ left: step() * parseInt(b.getAttribute('data-slide'), 10), behavior: reduceMotion ? 'auto' : 'smooth' });
      });
    });
    var sync = function () {
      var max = slider.scrollWidth - slider.clientWidth - 2;
      navBtns[0].disabled = slider.scrollLeft <= 2;
      navBtns[1].disabled = slider.scrollLeft >= max;
    };
    slider.addEventListener('scroll', function () { requestAnimationFrame(sync); }, { passive: true });
    window.addEventListener('resize', sync);
    sync();
  }

  /* ------------------------------------------------------------------
     Таймер «До церемонии осталось»
     ------------------------------------------------------------------ */
  var timer = $('[data-countdown]');
  if (timer && CFG.event && CFG.event.startsAt) {
    var target = new Date(CFG.event.startsAt).getTime();
    var forms = { d: ['день', 'дня', 'дней'], h: ['час', 'часа', 'часов'], m: ['минута', 'минуты', 'минут'], s: ['секунда', 'секунды', 'секунд'] };
    var plural = function (n, f) {
      var a = n % 100, b = n % 10;
      if (a > 10 && a < 20) return f[2];
      if (b === 1) return f[0];
      if (b > 1 && b < 5) return f[1];
      return f[2];
    };
    var prev = {};
    var render = function () {
      var diff = Math.max(0, target - Date.now());
      var v = {
        d: Math.floor(diff / 864e5),
        h: Math.floor(diff / 36e5) % 24,
        m: Math.floor(diff / 6e4) % 60,
        s: Math.floor(diff / 1e3) % 60
      };
      Object.keys(v).forEach(function (k) {
        if (prev[k] === v[k]) return;
        var num = $('[data-unit="' + k + '"]', timer);
        var lbl = $('[data-lbl="' + k + '"]', timer);
        num.textContent = String(v[k]).padStart(2, '0');
        lbl.textContent = plural(v[k], forms[k]);
        if (prev[k] !== undefined && !reduceMotion) {
          num.classList.remove('is-tick'); void num.offsetWidth; num.classList.add('is-tick');
        }
        prev[k] = v[k];
      });
      if (diff === 0) { timer.classList.add('is-done'); clearInterval(tid); }
    };
    var tid = setInterval(render, 1000);
    render();
  }

  /* ------------------------------------------------------------------
     Попапы
     ------------------------------------------------------------------ */
  var lastFocus = null;
  function openModal(m) {
    lastFocus = document.activeElement;
    m.classList.add('is-open');
    m.setAttribute('aria-hidden', 'false');
    root.classList.add('aa-modal-lock');
    // фокус на сам диалог (не в поле ввода — иначе на телефоне сразу выскакивает клавиатура)
    setTimeout(function () { $('.aa-modal__dialog', m).focus({ preventScroll: true }); }, 60);
  }
  function closeModal(m) {
    m.classList.remove('is-open');
    m.setAttribute('aria-hidden', 'true');
    if (!$('.aa-modal.is-open')) root.classList.remove('aa-modal-lock');
    if (lastFocus && lastFocus.focus) lastFocus.focus({ preventScroll: true });
  }
  $$('[data-modal]').forEach(function (m) {
    m.addEventListener('click', function (e) { if (e.target.closest('[data-close]')) closeModal(m); });
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { var m = $('.aa-modal.is-open'); if (m) closeModal(m); }
  });

  /* Вставка HTML с выполнением <script> (для формы оплаты) */
  function injectHTML(container, html) {
    if (window.jQuery) { window.jQuery(container).html(html); return; }
    container.innerHTML = html;
    var scripts = $$('script', container);
    (function next(i) {
      if (i >= scripts.length) return;
      var old = scripts[i], s = document.createElement('script');
      Array.prototype.slice.call(old.attributes).forEach(function (a) { s.setAttribute(a.name, a.value); });
      if (old.src) { s.onload = s.onerror = function () { next(i + 1); }; }
      else { s.textContent = old.textContent; }
      old.parentNode.replaceChild(s, old);
      if (!old.src) next(i + 1);
    })(0);
  }

  /* ---------- Купить билет → попап с формой оплаты ---------- */
  var buyModal = $('#buyModal');
  var buyBox = $('#popupBuyForm');

  // table — выбранный стол из схемы (необязательно). В бэкенд уходит его ID из config.seating.tableIds
  // (ID стола в БД, как в старой схеме), а если соответствия нет — номер стола.
  function tableParam(table) {
    if (!table) return '';
    var ids = (CFG.seating && CFG.seating.tableIds) || {};
    return ids[table.n] != null ? ids[table.n] : table.n;
  }

  function openBuy(key, btn, table) {
    var t = (CFG.tickets || {})[key] || {};
    goal('buyClick', { package: key, table: table ? table.n : '' });
    openModal(buyModal);

    if (CFG.demo || !t.ticketId) {
      buyBox.innerHTML =
        '<div class="aa-demo-note">' +
          '<h3>Оформление билета</h3>' +
          (table ? '<p><b>Стол ' + table.n + '</b> · ' + ((window.SEATING.zones[table.zone] || {}).name || '') + '</p>' : '') +
          '<p>Здесь откроется форма оплаты с сайта (<code>payment.blade.php</code>).</p>' +
          '<p>Чтобы подключить: в <code>assets/js/config.js</code> укажите <code>tickets.' + key + '.ticketId</code> ' +
          'и выключите <code>demo</code>. Форма загрузится с адреса <code>' + (CFG.endpoints && CFG.endpoints.payForm || '/tickets/pay') + '?type=ID&amp;count=1&amp;table=' + (table ? tableParam(table) : '') + '</code>.</p>' +
        '</div>';
      return;
    }

    var url = (CFG.endpoints.payForm || '/tickets/pay') +
      '?type=' + encodeURIComponent(t.ticketId) + '&count=1&table=' + encodeURIComponent(tableParam(table));

    buyModal.classList.add('is-loading');
    if (btn) btn.classList.add('is-loading');
    fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (r) { if (!r.ok) throw new Error(r.status); return r.text(); })
      .then(function (html) { injectHTML(buyBox, html); })
      .catch(function () {
        buyBox.innerHTML = '<div class="aa-demo-note"><h3>Не удалось загрузить форму</h3>' +
          '<p>Попробуйте ещё раз или оставьте заявку — менеджер оформит билет вручную.</p></div>';
      })
      .then(function () {
        buyModal.classList.remove('is-loading');
        if (btn) btn.classList.remove('is-loading');
      });
  }

  /* ---------- Запрос (Стол / вопрос) ---------- */
  var reqModal = $('#requestModal');
  var reqForm = $('[data-request-form]');
  var reqDone = $('[data-request-done]');
  var reqErr = $('[data-request-error]');
  var REQ_TEXT = {
    table:    { eyebrow: '[ Стол ]',   title: 'Запрос на стол',     sub: 'Оставьте контакты — менеджер свяжется с вами и пришлёт условия, рассадку и стоимость.' },
    question: { eyebrow: '[ Вопрос ]', title: 'Остались вопросы?', sub: 'Оставьте контакты — менеджер ответит и поможет с оформлением.' }
  };

  function openRequest(key, table) {
    var txt = REQ_TEXT[key] || REQ_TEXT.question;
    $('[data-request-eyebrow]').textContent = table ? '[ Стол ' + table.n + ' ]' : txt.eyebrow;
    $('[data-request-title]').textContent = table ? 'Забронировать стол ' + table.n : txt.title;
    $('[data-request-sub]').textContent = table
      ? ((window.SEATING.zones[table.zone] || {}).name || '') + (table.cap ? ', до ' + table.cap + ' гостей' : '') + '. Оставьте контакты — менеджер подтвердит бронь и пришлёт условия.'
      : txt.sub;
    reqForm.elements['package'].value = key;
    reqForm.elements['table'].value = table ? table.n : '';
    var t = (CFG.tickets || {})[key];
    reqForm.elements['type_id'].value = t && t.ticketId ? t.ticketId : '';
    reqForm.hidden = false; reqDone.hidden = true; reqErr.hidden = true;
    goal('requestOpen', { package: key });
    openModal(reqModal);
  }

  // маска телефона +7 (___) ___-__-__
  $$('[data-phone]').forEach(function (inp) {
    inp.addEventListener('input', function () {
      var d = inp.value.replace(/\D/g, '');
      if (!d) { inp.value = ''; return; }
      if (d[0] === '8') d = '7' + d.slice(1);
      if (d[0] !== '7') d = '7' + d;
      d = d.slice(0, 11);
      var out = '+7';
      if (d.length > 1) out += ' (' + d.slice(1, 4);
      if (d.length >= 4) out += ')';
      if (d.length > 4) out += ' ' + d.slice(4, 7);
      if (d.length > 7) out += '-' + d.slice(7, 9);
      if (d.length > 9) out += '-' + d.slice(9, 11);
      inp.value = out;
    });
  });

  if (reqForm) {
    reqForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = true;
      $$('input[required]', reqForm).forEach(function (inp) {
        var bad = inp.type === 'checkbox' ? !inp.checked : !inp.checkValidity() || (inp.name === 'phone' && inp.value.replace(/\D/g, '').length < 11);
        var field = inp.closest('.aa-field');
        if (field) field.classList.toggle('is-invalid', bad);
        if (bad) ok = false;
      });
      if (!ok) { reqErr.textContent = 'Проверьте обязательные поля и согласие на обработку данных.'; reqErr.hidden = false; return; }
      if (reqForm.elements['website'].value) return; // бот

      reqErr.hidden = true;
      var submit = $('button[type=submit]', reqForm);
      submit.classList.add('is-loading');

      var done = function () {
        submit.classList.remove('is-loading');
        reqForm.hidden = true; reqDone.hidden = false;
        goal('requestSubmit', { package: reqForm.elements['package'].value });
        reqForm.reset();
      };
      var fail = function () {
        submit.classList.remove('is-loading');
        reqErr.textContent = 'Не удалось отправить заявку. Попробуйте ещё раз.';
        reqErr.hidden = false;
      };

      var endpoint = CFG.endpoints && CFG.endpoints.request;
      if (!endpoint) { // демо-режим: бэкенд не подключён
        console.info('[landing] endpoints.request не задан — заявка не отправлена (демо).', Object.fromEntries(new FormData(reqForm)));
        setTimeout(done, 700);
        return;
      }
      var fd = new FormData(reqForm);
      var token = ($('meta[name="csrf-token"]') || {}).content || '';
      if (token) fd.append('_token', token);
      fetch(endpoint, {
        method: 'POST', body: fd, credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
      }).then(function (r) { if (!r.ok) throw new Error(r.status); done(); }).catch(fail);
    });
    $$('input', reqForm).forEach(function (inp) {
      inp.addEventListener('input', function () { var f = inp.closest('.aa-field'); if (f) f.classList.remove('is-invalid'); });
    });
  }

  /* ------------------------------------------------------------------
     Схема зала: отрисовка столов из seating.js и выбор стола
     ------------------------------------------------------------------ */
  var SEAT = window.SEATING || { zones: {}, tables: [], labels: [] };
  var SCFG = CFG.seating || {};
  var plan = $('[data-plan]');
  var picked = null;           // выбранный стол (объект из SEAT.tables)
  var seatFilter = 'all';
  var fmt = function (n) { return new Intl.NumberFormat('ru-RU').format(n) + ' ₽'; };
  var capText = function (t) { return t.cap ? 'до ' + t.cap + ' чел' : 'уточняйте у менеджера'; };

  function svgEl(tag, attrs, text) {
    var el = document.createElementNS('http://www.w3.org/2000/svg', tag);
    Object.keys(attrs || {}).forEach(function (k) { el.setAttribute(k, attrs[k]); });
    if (text != null) el.textContent = text;
    return el;
  }

  function drawPlan() {
    if (!plan) return;
    var gl = $('[data-plan-labels]', plan), gt = $('[data-plan-tables]', plan);
    gl.textContent = ''; gt.textContent = '';

    SEAT.labels.forEach(function (l) {
      var g = svgEl('g', { 'class': 'aa-plan__cap' });
      (l[3] || []).forEach(function (ln) { g.appendChild(svgEl('line', { x1: ln[0], y1: ln[1], x2: ln[2], y2: ln[3] })); });
      g.appendChild(svgEl('text', { x: l[1], y: l[2] }, l[0]));
      gl.appendChild(g);
    });

    SEAT.tables.forEach(function (t) {
      var zone = SEAT.zones[t.zone];
      var fill = t.booked || !zone ? '#F4F4F4' : zone.color;
      var g = svgEl('g', {
        'class': 'aa-t' + (t.booked ? ' is-booked' : ''),
        'data-table': t.n, tabindex: t.booked ? -1 : 0, role: 'button',
        'aria-label': 'Стол ' + t.n + (t.booked ? ', забронирован' : ', ' + (zone ? zone.name : '') + ', ' + capText(t))
      });
      var cx, cy, shape, ring;
      if (t.c) {
        cx = t.c[0]; cy = t.c[1];
        shape = svgEl('circle', { cx: cx, cy: cy, r: 12.5 });
        ring = svgEl('circle', { cx: cx, cy: cy, r: 16 });
      } else {
        var r = t.r || t.rot;
        var tr = t.rot ? 'rotate(' + t.rot[4] + ' ' + r[0] + ' ' + r[1] + ')' : null;
        shape = svgEl('rect', { x: r[0], y: r[1], width: r[2], height: r[3] });
        ring = svgEl('rect', { x: r[0] - 3.5, y: r[1] - 3.5, width: r[2] + 7, height: r[3] + 7, rx: 3 });
        if (tr) { shape.setAttribute('transform', tr); ring.setAttribute('transform', tr); }
        // центр (для подписи и подсказки)
        var a = (t.rot ? t.rot[4] : 0) * Math.PI / 180, hx = r[2] / 2, hy = r[3] / 2;
        cx = r[0] + hx * Math.cos(a) - hy * Math.sin(a);
        cy = r[1] + hx * Math.sin(a) + hy * Math.cos(a);
      }
      shape.setAttribute('class', 'aa-t__shape');
      shape.setAttribute('fill', fill);
      ring.setAttribute('class', 'aa-t__ring');
      var num = svgEl('text', { 'class': 'aa-t__num', x: cx, y: cy + 0.5 }, t.n);
      if (t.rot) num.setAttribute('transform', 'rotate(' + t.rot[4] + ' ' + cx + ' ' + cy + ')');
      g.appendChild(shape); g.appendChild(ring); g.appendChild(num);
      t._el = g; t._c = [cx, cy];
      gt.appendChild(g);
    });

    // свободные столы по зонам — в фильтрах
    Object.keys(SEAT.zones).forEach(function (z) {
      var free = SEAT.tables.filter(function (t) { return t.zone === z && !t.booked; }).length;
      var el = $('[data-free="' + z + '"]');
      if (el) el.textContent = free;
    });
    applyFilter(seatFilter);
  }

  function byNum(n) { for (var i = 0; i < SEAT.tables.length; i++) if (SEAT.tables[i].n === String(n)) return SEAT.tables[i]; return null; }

  function applyFilter(f) {
    seatFilter = f;
    if (!plan) return;
    plan.classList.toggle('has-filter', f !== 'all');
    SEAT.tables.forEach(function (t) { t._el && t._el.classList.toggle('is-match', t.zone === f && !t.booked); });
    $$('[data-filter]').forEach(function (b) { b.classList.toggle('is-on', b.getAttribute('data-filter') === f); });
  }

  // подсказка при наведении
  var tip = $('[data-plan-tip]');
  var planBox = $('.aa-seat__plan');
  function showTip(t) {
    if (!tip || !t) return;
    var zone = SEAT.zones[t.zone];
    tip.innerHTML = '<b>Стол ' + t.n + '</b>' +
      '<small>' + (t.booked ? 'Забронирован' : (zone ? zone.name : '') + ' · ' + capText(t)) + '</small>';
    var r = t._el.getBoundingClientRect(), pb = planBox.getBoundingClientRect();
    tip.style.left = (r.left + r.width / 2 - pb.left) + 'px';
    tip.style.top = (r.top - pb.top) + 'px';
    tip.hidden = false;
  }
  function hideTip() { if (tip) tip.hidden = true; }

  // выбор стола
  var seatbar = $('[data-seatbar]');
  function pick(t) {
    if (!t || t.booked) return;
    picked = t;
    SEAT.tables.forEach(function (x) { x._el && x._el.classList.toggle('is-picked', x === t); });
    var zone = SEAT.zones[t.zone] || {};
    var ticket = (CFG.tickets || {})[zone.ticket] || {};
    $('[data-pick-empty]').hidden = true;
    var full = $('[data-pick-full]');
    full.hidden = false;
    full.style.animation = 'none'; void full.offsetWidth; full.style.animation = '';
    $('[data-pick-dot]').style.setProperty('--c', zone.color || '#fff');
    $('[data-pick-zone]').textContent = zone.name || '';
    $('[data-pick-num]').textContent = t.n;
    $('[data-pick-cap]').textContent = capText(t);
    $('[data-pick-pack]').textContent = zone.ticket === 'vip' ? 'VIP билет' : zone.ticket === 'business' ? 'Бизнес' : 'Персональный билет';
    $('[data-pick-price]').textContent = ticket.price ? fmt(ticket.price) + ' / гость' : 'по запросу';
    if (seatbar) {
      $('[data-seatbar-title]', seatbar).textContent = 'Стол ' + t.n;
      $('[data-seatbar-meta]', seatbar).textContent = (zone.name || '') + ' · ' + capText(t) + (ticket.price ? ' · ' + fmt(ticket.price) : '');
    }
    syncSeatbar();
  }
  function unpick() {
    picked = null;
    SEAT.tables.forEach(function (x) { x._el && x._el.classList.remove('is-picked'); });
    $('[data-pick-empty]').hidden = false;
    $('[data-pick-full]').hidden = true;
    syncSeatbar();
  }
  function syncSeatbar() {
    if (!seatbar) return;
    var sec = $('#scheme');
    var r = sec ? sec.getBoundingClientRect() : { top: 1, bottom: 0 };
    var inView = r.top < window.innerHeight * 0.7 && r.bottom > window.innerHeight * 0.4;
    seatbar.classList.toggle('is-visible', !!picked && inView);
    if (sticky && picked && inView) sticky.classList.remove('is-visible');
  }
  window.addEventListener('scroll', function () { requestAnimationFrame(syncSeatbar); }, { passive: true });

  if (plan) {
    drawPlan();

    // статусы столов с бэкенда (необязательно): GET → { "booked": ["702", "703", …] }
    if (SCFG.statusUrl) {
      fetch(SCFG.statusUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (d) {
          if (!d || !d.booked) return;
          var set = d.booked.map(String);
          SEAT.tables.forEach(function (t) { t.booked = set.indexOf(t.n) !== -1; });
          if (picked && picked.booked) unpick();
          drawPlan();
          if (picked) pick(picked);
        }).catch(function () {});
    }

    plan.addEventListener('click', function (e) {
      var g = e.target.closest('.aa-t');
      if (!g || dragMoved) return;
      pick(byNum(g.getAttribute('data-table')));
    });
    plan.addEventListener('keydown', function (e) {
      var g = e.target.closest && e.target.closest('.aa-t');
      if (g && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); pick(byNum(g.getAttribute('data-table'))); }
    });
    plan.addEventListener('pointerover', function (e) {
      var g = e.target.closest('.aa-t');
      if (g && e.pointerType !== 'touch') showTip(byNum(g.getAttribute('data-table')));
    });
    plan.addEventListener('pointerout', function (e) { if (e.target.closest('.aa-t')) hideTip(); });
    var lastPointer = 'mouse';
    plan.addEventListener('pointerdown', function (e) { lastPointer = e.pointerType; });
    plan.addEventListener('focusin', function (e) {
      var g = e.target.closest('.aa-t');
      if (g && lastPointer !== 'touch') showTip(byNum(g.getAttribute('data-table')));
    });
    plan.addEventListener('focusout', hideTip);

    $$('[data-filter]').forEach(function (b) {
      b.addEventListener('click', function () { applyFilter(b.getAttribute('data-filter')); });
    });
    $$('[data-seat-clear]').forEach(function (b) { b.addEventListener('click', unpick); });
    $$('[data-seat-buy]').forEach(function (b) {
      b.addEventListener('click', function () {
        if (!picked) return;
        var zone = SEAT.zones[picked.zone] || {};
        openBuy(zone.ticket, b, picked);
      });
    });
    $$('[data-seat-request]').forEach(function (b) {
      b.addEventListener('click', function () { if (picked) openRequest('table', picked); });
    });
  }

  // масштаб и перетаскивание схемы
  var vp = $('[data-plan-viewport]');
  var dragMoved = false;
  if (vp && plan) {
    var Z = [1, 1.5, 2.2, 3], zi = 0;
    var zoomBtns = $$('[data-zoom]');
    var setZoom = function (next) {
      var cx = (vp.scrollLeft + vp.clientWidth / 2) / vp.scrollWidth;
      var cy = (vp.scrollTop + vp.clientHeight / 2) / vp.scrollHeight;
      zi = Math.max(0, Math.min(Z.length - 1, next));
      vp.style.setProperty('--z', Z[zi]);
      vp.classList.toggle('is-zoomed', zi > 0);
      zoomBtns[0].disabled = zi === 0; zoomBtns[1].disabled = zi === Z.length - 1;
      plan.style.transition = 'none';   // пересчёт скролла сразу, без анимации высоты
      vp.scrollLeft = cx * vp.scrollWidth - vp.clientWidth / 2;
      vp.scrollTop = cy * vp.scrollHeight - vp.clientHeight / 2;
      requestAnimationFrame(function () { plan.style.transition = ''; });
      hideTip();
    };
    zoomBtns.forEach(function (b) { b.addEventListener('click', function () { setZoom(zi + parseInt(b.getAttribute('data-zoom'), 10)); }); });
    setZoom(0);

    // перетаскивание мышью (на телефоне — нативная прокрутка пальцем)
    var sx, sy, sl, st, down = false;
    vp.addEventListener('pointerdown', function (e) {
      if (e.pointerType !== 'mouse' || zi === 0) return;
      down = true; dragMoved = false; sx = e.clientX; sy = e.clientY; sl = vp.scrollLeft; st = vp.scrollTop;
    });
    window.addEventListener('pointermove', function (e) {
      if (!down) return;
      var dx = e.clientX - sx, dy = e.clientY - sy;
      if (Math.abs(dx) + Math.abs(dy) > 4) { dragMoved = true; vp.classList.add('is-dragging'); hideTip(); }
      vp.scrollLeft = sl - dx; vp.scrollTop = st - dy;
    });
    window.addEventListener('pointerup', function () {
      down = false; vp.classList.remove('is-dragging');
      setTimeout(function () { dragMoved = false; }, 0);
    });
  }

  /* ---------- делегирование кликов ---------- */
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-buy]');
    if (b) { e.preventDefault(); openBuy(b.getAttribute('data-buy'), b); return; }
    var r = e.target.closest('[data-request]');
    if (r) { e.preventDefault(); openRequest(r.getAttribute('data-request')); }
  });

  // Прямая ссылка на пакет: /tickets#buy-vip, #request-table
  var h = location.hash.match(/^#(buy|request)-(\w+)/);
  if (h) setTimeout(function () { h[1] === 'buy' ? openBuy(h[2]) : openRequest(h[2]); }, 400);
})();
