/* ==========================================================================
   ARENDATOR AWARDS — лендинг билетов
   Без зависимостей. Оплаты на сайте нет — только бронирование (лид в Битрикс24).
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
    if (!window.aaConsent || !window.aaConsent.analytics) return;   // без согласия на аналитику — никаких целей (152-ФЗ)
    try { if (typeof window.ym === 'function') window.ym(a.metrikaId, 'reachGoal', name, params || {}); } catch (e) {}
  }

  var fmtRub = function (n) { return new Intl.NumberFormat('ru-RU').format(n) + ' ₽'; };
  function packPrice(key) { var t = (CFG.tickets || {})[key]; return t && t.price ? t.price : null; }

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
     Навигация, таймлайн, закреплённая панель внизу
     ------------------------------------------------------------------ */
  var nav = $('[data-nav]');
  var hero = $('.aa-hero');
  var timeline = $('[data-timeline]');
  var tlItems = $$('.aa-tl__item');
  var sticky = $('[data-sticky]');
  var orderSec = $('#order');
  var footer = $('.aa-footer');
  var lastY = window.scrollY;
  var ticking = false;

  function onScroll() {
    var y = window.scrollY;
    var vh = window.innerHeight;

    if (nav) {
      nav.classList.toggle('is-scrolled', y > 30);
      var goingDown = y > lastY && y > (hero ? hero.offsetHeight * 0.6 : 400);
      if (!nav.classList.contains('is-open')) nav.classList.toggle('is-hidden', goingDown);
    }

    if (timeline) {
      // золотая линия дорисовывается слева направо по мере прокрутки
      var r = timeline.getBoundingClientRect();
      var p = Math.max(0, Math.min(1, (vh * 0.85 - r.top) / (vh * 0.45)));
      timeline.style.setProperty('--tl', p.toFixed(4));
      var n = tlItems.length;
      tlItems.forEach(function (it, i) { it.classList.toggle('is-active', n > 1 ? p >= i / (n - 1) - 0.001 : p > 0); });
    }

    if (sticky) {
      var past = hero ? y > hero.offsetHeight * 0.8 : y > 600;
      var inOrder = false, nearEnd = false;
      if (orderSec) { var orr = orderSec.getBoundingClientRect(); inOrder = orr.top < vh * 0.85 && orr.bottom > 0; }
      if (footer) nearEnd = footer.getBoundingClientRect().top < vh;
      sticky.classList.toggle('is-visible', past && !inOrder && !nearEnd && !activeZoneBar());
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
     Видео в галерее: грузится только по клику (не тратим трафик заранее)
     ------------------------------------------------------------------ */
  $$('[data-video]').forEach(function (box) {
    var v = $('video', box);
    box.addEventListener('click', function () {
      if (box.classList.contains('is-playing')) return;
      if (!v.getAttribute('src')) v.src = v.getAttribute('data-src');
      v.controls = true;
      box.classList.add('is-playing');
      var pr = v.play(); if (pr && pr.catch) pr.catch(function () {});
    });
  });

  /* ------------------------------------------------------------------
     Слайдер отзывов (нативный scroll-snap + стрелки)
     ------------------------------------------------------------------ */
  // каждый [data-slider] листается кнопками [data-slide] из своей секции
  $$('[data-slider]').forEach(function (slider) {
    var sec = slider.closest('section') || document;
    var navBtns = $$('[data-slide]', sec);
    if (navBtns.length < 2) return;
    var step = function () {
      var c = slider.firstElementChild;
      var gap = parseFloat(getComputedStyle(slider).columnGap) || 18;
      return c ? c.getBoundingClientRect().width + gap : 400;
    };
    navBtns.forEach(function (b) {
      b.addEventListener('click', function () {
        slider.scrollBy({ left: step() * parseInt(b.getAttribute('data-slide'), 10), behavior: reduceMotion ? 'auto' : 'smooth' });
      });
    });
    var sync = function () {
      var max = slider.scrollWidth - slider.clientWidth - 2;
      navBtns[0].disabled = slider.scrollLeft <= 2;
      navBtns[1].disabled = slider.scrollLeft >= max;
      navBtns[0].parentNode.hidden = max <= 0;
    };
    slider.addEventListener('scroll', function () { requestAnimationFrame(sync); }, { passive: true });
    window.addEventListener('resize', sync);
    sync();
  });

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
     Цены и остаток мест — из config.js (правятся в одном месте)
     ------------------------------------------------------------------ */
  function fmtPrice(key) { var p = packPrice(key); return p ? fmtRub(p) : null; }
  $$('[data-price]').forEach(function (el) { var v = fmtPrice(el.getAttribute('data-price')); if (v) el.textContent = v; });
  (function () {
    var left = CFG.seatsLeft || {};
    var nums = $('[data-seats-nums]');
    if (!nums || (left.vip == null && left.business == null)) return;
    $$('[data-seats-left]', nums).forEach(function (el) { var v = left[el.getAttribute('data-seats-left')]; el.textContent = v == null ? '—' : v; });
    nums.hidden = false;
    var t = $('[data-seats-text]'); if (t) t.hidden = true;
  })();

  /* ------------------------------------------------------------------
     Бронирование → лид в Битрикс24
     Любая кнопка с data-book="vip|business|personal|table|question"
     прокручивает к форме (блок «Оформление билета») и выставляет формат. Отправка — POST на endpoints.lead (наш бэкенд),
     бэкенд создаёт лид в Битрикс24 (см. backend/ и README).
     ------------------------------------------------------------------ */
  var bookForm = $('[data-book-form]');
  var bookDone = $('[data-book-done]');
  var bookErr = $('[data-book-error]');
  var PACK = {
    vip:      'VIP билет',
    business: 'Business',
    personal: 'Персональный билет',
    table:    'Стол'
  };

  // UTM-метки: запоминаем на время визита, чтобы менеджер в Битриксе видел источник
  var UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
  // Метки из адреса берём всегда (они уходят только вместе с заявкой),
  // а в sessionStorage сохраняем лишь при согласии на аналитические cookie.
  var utm = {};
  var hasAnalytics = function () { return !!(window.aaConsent && window.aaConsent.analytics); };
  if (hasAnalytics()) { try { utm = JSON.parse(sessionStorage.getItem('aa_utm') || '{}'); } catch (e) {} }
  var qs = new URLSearchParams(location.search);
  if (UTM_KEYS.some(function (k) { return qs.get(k); })) {
    utm = {};
    UTM_KEYS.forEach(function (k) { if (qs.get(k)) utm[k] = qs.get(k); });
  }
  function persistUtm() {
    try {
      if (hasAnalytics() && Object.keys(utm).length) sessionStorage.setItem('aa_utm', JSON.stringify(utm));
      if (!hasAnalytics()) sessionStorage.removeItem('aa_utm');
    } catch (e) {}
  }
  persistUtm();
  document.addEventListener('aa:consent', persistUtm);

  // ID посетителя в Метрике — чтобы связать лид с визитом (сквозная аналитика)
  // только при согласии на аналитические cookie
  var ymClientId = '';
  function readYmId() {
    try {
      var an = CFG.analytics || {};
      if (window.aaConsent && window.aaConsent.analytics && typeof window.ym === 'function' && an.metrikaId) {
        window.ym(an.metrikaId, 'getClientID', function (id) { ymClientId = id; });
      }
    } catch (e) {}
  }
  readYmId();
  document.addEventListener('aa:consent', function (e) { if (e.detail && e.detail.analytics) setTimeout(readYmId, 1500); else ymClientId = ''; });


  function syncTotal() {
    if (!bookForm) return;
    var key = (bookForm.querySelector('[name="package"]:checked') || {}).value;
    var guests = parseInt(bookForm.elements['guests'].value, 10) || 1;
    var price = packPrice(key);
    $('[data-book-total]').textContent = price ? fmtRub(price * guests) : 'по запросу';
    $('[data-book-total-note]').textContent = price ? guests + ' × ' + fmtRub(price) : 'менеджер пришлёт условия';
  }

  // режим формы: бронирование или «просто задать вопрос»
  function setMode(isQuestion) {
    if (!bookForm) return;
    bookForm.classList.toggle('is-question', isQuestion);
    bookForm.elements['intent'].value = isQuestion ? 'question' : 'booking';
    $('[data-book-title]').textContent = isQuestion ? 'Ваш вопрос' : 'Контактные данные';
    $('[data-book-mode]').textContent = isQuestion ? '← Вернуться к бронированию' : 'Просто задать вопрос';
    $('[data-book-submit]').textContent = isQuestion ? 'Отправить вопрос' : 'Забронировать';
  }
  function setStep(n) { $$('[data-steps] li').forEach(function (li, i) { li.classList.toggle('is-on', i < n); }); }

  // key: vip|business|personal|table — выбрать формат; question — режим вопроса; '' — просто к форме
  function openBook(key) {
    if (!bookForm) return;
    bookForm.hidden = false; bookDone.hidden = true; bookErr.hidden = true;
    setMode(key === 'question');
    if (PACK[key]) {
      var r = bookForm.querySelector('[name="package"][value="' + key + '"]');
      if (r) r.checked = true;
    }
    syncTotal();
    goal('bookOpen', { package: key || 'any' });
    var box = $('[data-book-box]');
    var target = PACK[key] ? $('.aa-order__choice') : box;
    (target || bookForm).scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
    if (box) { box.classList.remove('aa-box--flash'); void box.offsetWidth; box.classList.add('aa-box--flash'); }
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

  if (bookForm) {
    // количество гостей
    var gIn = bookForm.elements['guests'];
    $$('[data-guests]', bookForm).forEach(function (b) {
      b.addEventListener('click', function () {
        var v = (parseInt(gIn.value, 10) || 1) + parseInt(b.getAttribute('data-guests'), 10);
        gIn.value = Math.max(1, Math.min(50, v));
        syncTotal();
      });
    });
    gIn.addEventListener('input', function () {
      var v = parseInt(gIn.value.replace(/\D/g, ''), 10);
      gIn.value = isNaN(v) ? '' : Math.max(1, Math.min(50, v));
      syncTotal();
    });
    gIn.addEventListener('blur', function () { if (!gIn.value) { gIn.value = 1; syncTotal(); } });
    $$('[name="package"]', bookForm).forEach(function (r) { r.addEventListener('change', syncTotal); });

    bookForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = true;
      $$('input[required]', bookForm).forEach(function (inp) {
        var bad = inp.type === 'checkbox' ? !inp.checked : !inp.checkValidity() || (inp.name === 'phone' && inp.value.replace(/\D/g, '').length < 11);
        var field = inp.closest('.aa-field');
        if (field) field.classList.toggle('is-invalid', bad);
        if (bad) ok = false;
      });
      if (!ok) {
        var pdOk = bookForm.elements['consent_pd'].checked;
        bookErr.textContent = pdOk ? 'Заполните имя, телефон и email.' : 'Чтобы отправить заявку, отметьте согласие на обработку персональных данных.';
        bookErr.hidden = false;
        $('.aa-check--req', bookForm).classList.toggle('is-invalid', !pdOk);
        return;
      }
      if (bookForm.elements['website'].value) return; // бот

      bookErr.hidden = true;
      var submit = $('button[type=submit]', bookForm);
      submit.classList.add('is-loading');

      var fd = new FormData(bookForm);
      if (bookForm.elements['intent'].value === 'question') { fd.delete('package'); fd.delete('guests'); }
      // доказательство согласия: версия документов и время (сервер добавит IP и браузер)
      fd.set('consent_ads', bookForm.elements['consent_ads'].checked ? '1' : '0');
      fd.append('consent_version', ((CFG.operator || {}).docsVersion) || '');
      fd.append('consent_at', new Date().toISOString());
      Object.keys(utm).forEach(function (k) { fd.append(k, utm[k]); });
      fd.append('page', location.href.split('#')[0]);
      fd.append('referrer', document.referrer || '');
      if (ymClientId) fd.append('ym_client_id', ymClientId);

      var done = function () {
        submit.classList.remove('is-loading');
        bookForm.hidden = true; bookDone.hidden = false; setStep(3);
        goal('bookSubmit', { package: fd.get('package') || 'question' });
        bookForm.reset(); gIn.value = 1; setMode(false); syncTotal();
        $('.aa-check--req', bookForm).classList.remove('is-invalid');
        bookDone.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
      };
      var fail = function () {
        submit.classList.remove('is-loading');
        bookErr.textContent = 'Не удалось отправить заявку. Проверьте интернет и попробуйте ещё раз.';
        bookErr.hidden = false;
      };

      var endpoint = CFG.endpoints && CFG.endpoints.lead;
      if (!endpoint) { // демо-режим: бэкенд не подключён
        console.info('[landing] endpoints.lead не задан — заявка не отправлена (демо).', Object.fromEntries(fd));
        setTimeout(done, 700);
        return;
      }
      var token = ($('meta[name="csrf-token"]') || {}).content || '';
      if (token) fd.append('_token', token);
      fetch(endpoint, {
        method: 'POST', body: fd, credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
      }).then(function (r) { if (!r.ok) throw new Error(r.status); done(); }).catch(fail);
    });
    $$('input', bookForm).forEach(function (inp) {
      inp.addEventListener('input', function () { var f = inp.closest('.aa-field'); if (f) f.classList.remove('is-invalid'); });
    });
    $('[data-book-mode]').addEventListener('click', function () { setMode(!bookForm.classList.contains('is-question')); });
    bookForm.addEventListener('focusin', function (e) { if (e.target.closest('[data-book-box]')) setStep(2); });
    $('[data-book-again]').addEventListener('click', function () { bookDone.hidden = true; bookForm.hidden = false; setStep(1); });
    bookForm.elements['consent_pd'].addEventListener('change', function () {
      if (this.checked) { $('.aa-check--req', bookForm).classList.remove('is-invalid'); bookErr.hidden = true; }
    });
  }

  /* ------------------------------------------------------------------
     Схема зала — только просмотр, выбор ЗОНЫ (не стола).
     Клик по любому столу выделяет всю зону этого цвета и показывает
     общую информацию: формат, цена за гостя, что входит, какие столы в зоне.
     Номер стола нигде не показывается и в заявку не передаётся.
     ------------------------------------------------------------------ */
  var SEAT = window.SEATING || { zones: {}, tables: [], labels: [] };
  var SCFG = CFG.seating || {};
  var plan = $('[data-plan]');
  var activeZone = null;       // 'vip' | 'business' | 'personal' | 'soldout' | null
  var zoneOf = function (t) { return t.booked || !t.zone ? 'soldout' : t.zone; };
  var zoneInfo = function (z) { return z === 'soldout' ? (SEAT.soldout || { name: 'Места закончились', color: '#B9A3E3' }) : (SEAT.zones[z] || {}); };

  // «Столы на 2 и 4 гостя» — общая информация по зоне
  function zoneCaps(z) {
    var caps = [];
    SEAT.tables.forEach(function (t) { if (zoneOf(t) === z && t.cap && caps.indexOf(t.cap) === -1) caps.push(t.cap); });
    caps.sort(function (a, b) { return a - b; });
    if (!caps.length) return 'уточняйте у менеджера';
    var last = caps[caps.length - 1], d = last % 10, h = last % 100;
    var word = (d >= 1 && d <= 4 && (h < 11 || h > 14)) ? 'гостя' : 'гостей';   // на 2 и 4 гостя, на 6 гостей
    return 'на ' + (caps.length === 1 ? last : caps.slice(0, -1).join(', ') + ' и ' + last) + ' ' + word;
  }

  function svgEl(tag, attrs, text) {
    var el = document.createElementNS('http://www.w3.org/2000/svg', tag);
    Object.keys(attrs || {}).forEach(function (k) { el.setAttribute(k, attrs[k]); });
    if (text != null) el.textContent = text;
    return el;
  }

  /* Ориентация: на компьютере и планшете — горизонтально, сценой вверх (как на утверждённой
     картинке), на телефоне — вертикально. Координаты в seating.js — в «вертикальной» системе
     (картинка 714×1280); горизонталь — это поворот всей схемы на 90°, а надписи
     поворачиваются обратно, чтобы читались ровно. */
  var planMQ = window.matchMedia('(max-width: 720px)');
  var isH = function () { return !planMQ.matches; };
  var VIEW_V = '15 100 630 1090', VIEW_H = '95 58 1100 652';
  function orientPlan() {
    if (!plan) return;
    var h = isH();
    plan.setAttribute('viewBox', h ? VIEW_H : VIEW_V);
    plan.classList.toggle('is-h', h);
    var vpEl = $('[data-plan-viewport]'); if (vpEl) vpEl.classList.toggle('is-h', h);
    var rot = $('[data-plan-rot]', plan);
    if (h) rot.setAttribute('transform', 'translate(0 714) rotate(-90)'); else rot.removeAttribute('transform');
    $$('[data-upright]', plan).forEach(function (t) {
      if (h) t.setAttribute('transform', 'rotate(90 ' + t.getAttribute('x') + ' ' + t.getAttribute('y') + ')');
      else t.removeAttribute('transform');
    });
  }

  function drawPlan() {
    if (!plan) return;
    var h = isH();
    var gl = $('[data-plan-labels]', plan), gt = $('[data-plan-tables]', plan);
    gl.textContent = ''; gt.textContent = '';

    SEAT.labels.forEach(function (l) {
      var g = svgEl('g', { 'class': 'aa-plan__cap' });
      (l[3] || []).forEach(function (ln) { g.appendChild(svgEl('line', { x1: ln[0], y1: ln[1], x2: ln[2], y2: ln[3] })); });
      if (!h || !l[3] || !l[3].length) {
        g.appendChild(svgEl('text', { x: l[1], y: l[2] }, l[0]));
      } else {
        // горизонтально: подпись ровно, у свободного конца выноски
        var ln = l[3][0], tx = l[1] + 14;
        var far = Math.abs(ln[0] - tx) <= Math.abs(ln[2] - tx) ? [ln[0], ln[1], ln[2]] : [ln[2], ln[3], ln[0]];
        var px = far[0] + (far[0] <= far[2] ? -7 : 7), py = far[1];
        g.appendChild(svgEl('text', { x: px, y: py, 'text-anchor': 'middle', transform: 'rotate(90 ' + px + ' ' + py + ')' }, l[0]));
      }
      gl.appendChild(g);
    });

    SEAT.tables.forEach(function (t) {
      var z = zoneOf(t), info = zoneInfo(z);
      var g = svgEl('g', {
        'class': 'aa-t' + (z === 'soldout' ? ' is-booked' : ''),
        'data-zone': z, tabindex: 0, role: 'button',
        'aria-label': info.name + (z === 'soldout' ? ', бронирование недоступно' : '') + '. Показать зону'
      });
      var cx, cy, shape, ring;
      if (t.c) {
        cx = t.c[0]; cy = t.c[1];
        shape = svgEl('circle', { cx: cx, cy: cy, r: 12 });
        ring = svgEl('circle', { cx: cx, cy: cy, r: 19.5 });
        // стулья — пунктирное кольцо цвета зоны
        g.appendChild(svgEl('circle', { 'class': 'aa-t__chairs', cx: cx, cy: cy, r: 16, stroke: info.color }));
      } else {
        var r = t.r || t.rot;
        var tr = t.rot ? 'rotate(' + t.rot[4] + ' ' + r[0] + ' ' + r[1] + ')' : null;
        shape = svgEl('rect', { x: r[0], y: r[1], width: r[2], height: r[3] });
        ring = svgEl('rect', { x: r[0] - 3.5, y: r[1] - 3.5, width: r[2] + 7, height: r[3] + 7, rx: 3 });
        if (tr) { shape.setAttribute('transform', tr); ring.setAttribute('transform', tr); }
        var a = (t.rot ? t.rot[4] : 0) * Math.PI / 180, hx = r[2] / 2, hy = r[3] / 2;
        cx = r[0] + hx * Math.cos(a) - hy * Math.sin(a);
        cy = r[1] + hx * Math.sin(a) + hy * Math.cos(a);
      }
      shape.setAttribute('class', 'aa-t__shape');
      shape.setAttribute('fill', info.color);
      ring.setAttribute('class', 'aa-t__ring');
      var num = svgEl('text', { 'class': 'aa-t__num', x: cx, y: cy + 0.5 }, t.n);
      var na = (t.rot ? t.rot[4] : 0) + (h ? 90 : 0);
      if (na) num.setAttribute('transform', 'rotate(' + na + ' ' + cx + ' ' + cy + ')');
      g.appendChild(shape); g.appendChild(ring); g.appendChild(num);
      t._el = g;
      gt.appendChild(g);
    });
    paintZone();
  }

  // подсветка: выбранная зона (или зона под курсором) — яркая, остальные приглушены
  var hoverZone = null;
  function paintZone() {
    if (!plan) return;
    var z = activeZone;
    plan.classList.toggle('has-zone', !!z);
    SEAT.tables.forEach(function (t) {
      if (!t._el) return;
      var tz = zoneOf(t);
      t._el.classList.toggle('is-zone', tz === z);
      t._el.classList.toggle('is-hover', !!hoverZone && tz === hoverZone && tz !== z);
    });
    $$('[data-filter]').forEach(function (b) { b.classList.toggle('is-on', b.getAttribute('data-filter') === (z || 'all')); });
  }

  // подсказка при наведении — про зону, без номера стола
  var tip = $('[data-plan-tip]');
  var planBox = $('.aa-seat__plan');
  function showTip(g) {
    if (!tip || !g) return;
    var z = g.getAttribute('data-zone'), info = zoneInfo(z);
    var price = z === 'soldout' ? null : packPrice(info.ticket);
    tip.innerHTML = z === 'soldout'
      ? '<b>Места закончились</b><small>Бронирование недоступно</small>'
      : '<b>' + info.name + (price ? ' · ' + fmtRub(price) : '') + '</b><small>' + (price ? 'за гостя · ' : '') + 'нажмите, чтобы выделить зону</small>';
    var r = g.getBoundingClientRect(), pb = planBox.getBoundingClientRect();
    tip.style.left = (r.left + r.width / 2 - pb.left) + 'px';
    tip.style.top = (r.top - pb.top) + 'px';
    tip.hidden = false;
  }
  function hideTip() { if (tip) tip.hidden = true; }

  // выбор зоны → общая информация
  var seatbar = $('[data-seatbar]');
  function selectZone(z) {
    if (!z || z === 'all') { closeInspect(); return; }
    activeZone = z;
    paintZone();
    var info = zoneInfo(z);
    $('[data-pick-empty]').hidden = true;
    if (z === 'soldout') { showSold(); return; }
    $('[data-pick-sold]').hidden = true;
    var price = packPrice(info.ticket);
    var full = $('[data-pick-full]');
    full.hidden = false;
    full.style.animation = 'none'; void full.offsetWidth; full.style.animation = '';
    $('[data-pick-dot]').style.setProperty('--c', info.color || '#fff');
    $('[data-pick-zone]').textContent = info.name || '';
    $('[data-pick-price]').textContent = price ? fmtRub(price) : 'по запросу';
    $('[data-pick-price]').parentNode.lastElementChild.hidden = !price;
    $('[data-pick-cap]').textContent = zoneCaps(z);
    $('[data-pick-inc]').textContent = info.includes || '—';
    $$('[data-seat-book]').forEach(function (b) { b.setAttribute('data-book', info.ticket); });
    if (seatbar) {
      $('[data-seatbar-title]', seatbar).textContent = (info.name || '') + (price ? ' · ' + fmtRub(price) : '');
      $('[data-seatbar-meta]', seatbar).textContent = 'Столы ' + zoneCaps(z);
      $('[data-seatbar] [data-seat-book]').hidden = false;
      $('[data-seatbar-sold]').hidden = true;
    }
    syncSeatbar();
  }

  // зона, где места закончились
  function showSold() {
    $('[data-pick-full]').hidden = true;
    var sold = $('[data-pick-sold]');
    sold.hidden = false;
    sold.style.animation = 'none'; void sold.offsetWidth; sold.style.animation = '';
    if (seatbar) {
      $('[data-seatbar-title]', seatbar).textContent = 'Места закончились';
      $('[data-seatbar-meta]', seatbar).textContent = 'Аншлаг в этой зоне';
      $('[data-seatbar] [data-seat-book]').hidden = true;
      $('[data-seatbar-sold]').hidden = false;
    }
    syncSeatbar();
  }

  function closeInspect() {
    activeZone = null;
    paintZone();
    $('[data-pick-sold]').hidden = true;
    $('[data-pick-empty]').hidden = false;
    $('[data-pick-full]').hidden = true;
    syncSeatbar();
  }
  function syncSeatbar() {
    if (!seatbar) return;
    var sec = $('#scheme');
    var r = sec ? sec.getBoundingClientRect() : { top: 1, bottom: 0 };
    var inView = r.top < window.innerHeight * 0.7 && r.bottom > window.innerHeight * 0.4;
    seatbar.classList.toggle('is-visible', !!activeZone && inView);
    if (sticky && activeZone && inView) sticky.classList.remove('is-visible');
  }
  function activeZoneBar() { return !!(seatbar && seatbar.classList.contains('is-visible')); }
  window.addEventListener('scroll', function () { requestAnimationFrame(syncSeatbar); }, { passive: true });

  var dragMoved = false;
  if (plan) {
    orientPlan();
    drawPlan();
    var onOrient = function () {
      orientPlan(); drawPlan(); hideTip();
      if (activeZone) selectZone(activeZone);
      if (typeof setZoom === 'function') setZoom(0);
    };
    if (planMQ.addEventListener) planMQ.addEventListener('change', onOrient); else if (planMQ.addListener) planMQ.addListener(onOrient);

    // необязательно: столы, где места закончились, с бэкенда. GET → { "booked": ["702", "703", …] }
    if (SCFG.statusUrl) {
      fetch(SCFG.statusUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (d) {
          if (!d || !d.booked) return;
          var set = d.booked.map(String);
          SEAT.tables.forEach(function (t) { t.booked = set.indexOf(t.n) !== -1; });
          drawPlan();
          if (activeZone) selectZone(activeZone);
        }).catch(function () {});
    }

    plan.addEventListener('click', function (e) {
      var g = e.target.closest('.aa-t');
      if (!g || dragMoved) return;
      var z = g.getAttribute('data-zone');
      z === activeZone ? closeInspect() : selectZone(z);
    });
    plan.addEventListener('keydown', function (e) {
      var g = e.target.closest && e.target.closest('.aa-t');
      if (g && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); selectZone(g.getAttribute('data-zone')); }
    });
    plan.addEventListener('pointerover', function (e) {
      var g = e.target.closest('.aa-t');
      if (!g || e.pointerType === 'touch') return;
      hoverZone = g.getAttribute('data-zone'); paintZone(); showTip(g);
    });
    plan.addEventListener('pointerout', function (e) {
      if (!e.target.closest('.aa-t')) return;
      hoverZone = null; paintZone(); hideTip();
    });
    var lastPointer = 'mouse';
    plan.addEventListener('pointerdown', function (e) { lastPointer = e.pointerType; });
    plan.addEventListener('focusin', function (e) {
      var g = e.target.closest('.aa-t');
      if (g && lastPointer !== 'touch') showTip(g);
    });
    plan.addEventListener('focusout', hideTip);

    $$('[data-filter]').forEach(function (b) {
      b.addEventListener('click', function () {
        var z = b.getAttribute('data-filter');
        z === activeZone ? closeInspect() : selectZone(z);
      });
    });
    // «Посмотреть на схеме зала» в карточках форматов
    $$('[data-show-zone]').forEach(function (a) {
      a.addEventListener('click', function () { selectZone(a.getAttribute('data-show-zone')); });
    });
    $$('[data-seat-clear]').forEach(function (b) { b.addEventListener('click', closeInspect); });
  }

  // масштаб и перетаскивание схемы
  var vp = $('[data-plan-viewport]');
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
      plan.style.transition = 'none';
      vp.scrollLeft = cx * vp.scrollWidth - vp.clientWidth / 2;
      vp.scrollTop = cy * vp.scrollHeight - vp.clientHeight / 2;
      requestAnimationFrame(function () { plan.style.transition = ''; });
      hideTip();
    };
    zoomBtns.forEach(function (b) { b.addEventListener('click', function () { setZoom(zi + parseInt(b.getAttribute('data-zoom'), 10)); }); });
    setZoom(0);

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
    var b = e.target.closest('[data-book]');
    if (b && !b.disabled) {
      e.preventDefault();
      if (nav) { nav.classList.remove('is-open'); if (burger) burger.setAttribute('aria-expanded', 'false'); }
      openBook(b.getAttribute('data-book'));
    }
  });

  // Прямая ссылка: /tickets#book-vip, #book-business, #book-personal, #book-table, #book-question
  var h = location.hash.match(/^#book-(\w+)/);
  if (h) setTimeout(function () { openBook(h[1]); }, 400);
})();
