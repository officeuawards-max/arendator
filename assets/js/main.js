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
     Схема зала: подсветка сектора ⇄ легенда, клик → к пакету
     ------------------------------------------------------------------ */
  var hall = $('.aa-hall');
  function highlight(sector) {
    if (!hall) return;
    hall.classList.toggle('has-focus', !!sector);
    $$('.aa-hall__sector, .aa-legend__item[data-sector]').forEach(function (el) {
      el.classList.toggle('is-hl', !!sector && el.getAttribute('data-sector') === sector);
    });
  }
  function goToPackage(sector) {
    var card = $('.aa-card[data-package="' + sector + '"]');
    if (!card) return;
    card.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center', inline: 'center' });
    card.classList.remove('is-flash');
    void card.offsetWidth;
    card.classList.add('is-flash');
  }
  $$('.aa-hall__sector, .aa-legend__item[data-sector]').forEach(function (el) {
    var s = el.getAttribute('data-sector');
    el.addEventListener('mouseenter', function () { highlight(s); });
    el.addEventListener('mouseleave', function () { highlight(null); });
    el.addEventListener('focus', function () { highlight(s); });
    el.addEventListener('blur', function () { highlight(null); });
    el.addEventListener('click', function () { goToPackage(s); });
    el.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); goToPackage(s); } });
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

  function openBuy(key, btn) {
    var t = (CFG.tickets || {})[key] || {};
    goal('buyClick', { package: key });
    openModal(buyModal);

    if (CFG.demo || !t.ticketId) {
      buyBox.innerHTML =
        '<div class="aa-demo-note">' +
          '<h3>Оформление билета</h3>' +
          '<p>Здесь откроется форма оплаты с сайта (<code>payment.blade.php</code>).</p>' +
          '<p>Чтобы подключить: в <code>assets/js/config.js</code> укажите <code>tickets.' + key + '.ticketId</code> ' +
          'и выключите <code>demo</code>. Форма загрузится с адреса <code>' + (CFG.endpoints && CFG.endpoints.payForm || '/tickets/pay') + '?type=ID&amp;count=1</code>.</p>' +
        '</div>';
      return;
    }

    var url = (CFG.endpoints.payForm || '/tickets/pay') +
      '?type=' + encodeURIComponent(t.ticketId) + '&count=1&table=';

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

  function openRequest(key) {
    var txt = REQ_TEXT[key] || REQ_TEXT.question;
    $('[data-request-eyebrow]').textContent = txt.eyebrow;
    $('[data-request-title]').textContent = txt.title;
    $('[data-request-sub]').textContent = txt.sub;
    reqForm.elements['package'].value = key;
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
