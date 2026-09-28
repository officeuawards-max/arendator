/* ==========================================================================
   Согласия и cookie (152-ФЗ) + реквизиты оператора
   Общий файл для лендинга и страниц legal/*.html. Подключается после config.js.

   - Баннер cookie: «Принять все» / «Только необходимые». Выбор хранится в
     localStorage (aa_cookie_consent) и привязан к версии документов — при новой
     редакции (operator.docsVersion) баннер покажется снова.
   - Яндекс Метрика и цели включаются ТОЛЬКО при согласии на аналитику.
     Текущее решение: window.aaConsent = { analytics: true|false, v, at }.
     Событие при изменении: document 'aa:consent' (detail — то же объект).
   - [data-op="name|inn|…"] — подставляет реквизиты из LANDING_CONFIG.operator.
   - [data-cookie-settings] — любая кнопка/ссылка снова открывает баннер.
   ========================================================================== */
(function () {
  'use strict';

  var CFG = window.LANDING_CONFIG || {};
  var OP = CFG.operator || {};
  var AN = CFG.analytics || {};
  var KEY = 'aa_cookie_consent';
  var VER = OP.docsVersion || '1';
  var root = document.documentElement;
  var legalBase = root.getAttribute('data-legal-base');
  if (legalBase == null) legalBase = 'legal/';

  /* ---------- реквизиты оператора ---------- */
  function fillOperator() {
    Array.prototype.forEach.call(document.querySelectorAll('[data-op]'), function (el) {
      var v = OP[el.getAttribute('data-op')];
      if (v) { el.textContent = v; el.classList.add('is-filled'); }
    });
  }

  /* ---------- хранение выбора ---------- */
  function read() {
    try {
      var v = JSON.parse(localStorage.getItem(KEY) || 'null');
      return v && v.v === VER ? v : null;
    } catch (e) { return null; }
  }
  function save(analytics) {
    var v = { analytics: !!analytics, v: VER, at: new Date().toISOString() };
    try { localStorage.setItem(KEY, JSON.stringify(v)); } catch (e) {}
    apply(v);
  }
  function apply(v) {
    window.aaConsent = v;
    root.classList.toggle('aa-consent-analytics', !!(v && v.analytics));
    try { document.dispatchEvent(new CustomEvent('aa:consent', { detail: v })); } catch (e) {}
    if (v && v.analytics) loadMetrika();
  }

  /* ---------- Яндекс Метрика: только после согласия ---------- */
  function loadMetrika() {
    if (!AN.loadMetrika || !AN.metrikaId || window.ym) return;
    (function (m, e, t, r, i, k, a) {
      m[i] = m[i] || function () { (m[i].a = m[i].a || []).push(arguments); };
      m[i].l = 1 * new Date();
      k = e.createElement(t); a = e.getElementsByTagName(t)[0];
      k.async = 1; k.src = r; a.parentNode.insertBefore(k, a);
    })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js', 'ym');
    window.ym(AN.metrikaId, 'init', { clickmap: true, trackLinks: true, accurateTrackBounce: true, webvisor: false });
  }

  /* ---------- баннер ----------
   * Шутки про коммерческую недвижимость. Каждый раз — случайный вариант.
   * Смысл под шуткой всегда один и тот же, простыми словами (строка «По делу»),
   * а отказ — такая же заметная кнопка «Только необходимые»: так требует честное согласие.
   */
  var JOKES = [
    { t: 'Файлы cookie ищут арендатора',
      x: 'Небольшие файлы, класс&nbsp;А, отделка «под ключ». Ставка — 0&nbsp;₽ за&nbsp;м², без индексации и&nbsp;арендных каникул.',
      ok: 'подписать договор аренды' },
    { t: 'Cookie: 100% заполняемость? Почти',
      x: 'Необходимые cookie уже заехали. Аналитические ждут одобрения — как арендатор ждёт согласования с&nbsp;управляющей компанией.',
      ok: 'одобрить заезд' },
    { t: 'Cookie — лучший cap rate на&nbsp;рынке',
      x: 'Вложений — ноль, доходность — сайт становится удобнее. Такой объект мы&nbsp;бы и&nbsp;сами взяли в&nbsp;портфель.',
      ok: 'инвестировать' },
    { t: 'Cookie — якорный арендатор этого сайта',
      x: 'Каждому ТЦ нужен якорь. Наш — файлы cookie: без них не&nbsp;работают формы, а&nbsp;с&nbsp;аналитикой мы понимаем, что вам интересно.',
      ok: 'пустить якоря' },
    { t: 'Cookie: сделка без брокера',
      x: 'Никаких комиссий и&nbsp;LOI на&nbsp;трёх страницах. Одна кнопка — и&nbsp;файлы cookie заезжают. Due diligence уже пройден.',
      ok: 'закрыть сделку' },
    { t: 'Сдаётся под cookie: ваш браузер, 0,001&nbsp;м²',
      x: 'Арендатор надёжный — файлы cookie платят пользой: сайт работает лучше, а&nbsp;мы видим, что нравится гостям премии.',
      ok: 'сдать в&nbsp;аренду' }
  ];

  // печенька-бизнес-центр: шоколадные «окна» и укус при согласии
  var COOKIE_SVG =
    '<svg class="aa-cookie__art" viewBox="0 0 64 64" aria-hidden="true">' +
      '<defs><mask id="aaBite"><rect width="64" height="64" fill="#fff"/>' +
        '<g class="aa-cookie__bite"><circle cx="56" cy="12" r="9" fill="#000"/><circle cx="50" cy="4" r="6" fill="#000"/><circle cx="61" cy="22" r="6" fill="#000"/></g>' +
      '</mask></defs>' +
      '<g mask="url(#aaBite)">' +
        '<circle cx="32" cy="32" r="28" fill="#C98A45"/><circle cx="32" cy="32" r="24.5" fill="#E0A862"/>' +
        '<g fill="#5A3217">' +
          '<rect x="19" y="18" width="6" height="7" rx="1.2"/><rect x="29" y="18" width="6" height="7" rx="1.2"/><rect x="39" y="18" width="6" height="7" rx="1.2"/>' +
          '<rect x="19" y="29" width="6" height="7" rx="1.2"/><rect x="29" y="29" width="6" height="7" rx="1.2"/><rect x="39" y="29" width="6" height="7" rx="1.2"/>' +
          '<rect x="19" y="40" width="6" height="7" rx="1.2"/><rect x="39" y="40" width="6" height="7" rx="1.2"/>' +
          '<path d="M29 47v-7h6v7z"/>' +
        '</g>' +
        '<rect class="aa-cookie__lit" x="39" y="18" width="6" height="7" rx="1.2" fill="#FFE39A"/>' +
      '</g>' +
      '<g class="aa-cookie__crumbs" fill="#C98A45"><circle cx="58" cy="30" r="1.6"/><circle cx="52" cy="24" r="1.1"/><circle cx="60" cy="36" r="1"/></g>' +
    '</svg>';

  var banner;
  function buildBanner() {
    banner = document.createElement('div');
    banner.className = 'aa-cookie';
    banner.setAttribute('role', 'dialog');
    banner.setAttribute('aria-live', 'polite');
    banner.setAttribute('aria-label', 'Уведомление об использовании файлов cookie');
    (document.querySelector('.aa') || document.body).appendChild(banner);
    banner.addEventListener('click', function (e) {
      var b = e.target.closest('[data-cookie]');
      if (!b || banner.classList.contains('is-done')) return;
      var all = b.getAttribute('data-cookie') === 'all';
      save(all);
      // короткая «развязка» шутки, потом баннер уходит
      banner.classList.add('is-done', all ? 'is-accepted' : 'is-declined');
      $('.aa-cookie__title', banner).innerHTML = all ? 'Договор аренды cookie подписан' : 'Понимаем, сделка не&nbsp;для всех';
      $('.aa-cookie__text', banner).innerHTML = all
        ? 'Все файлы cookie приняты: ключи переданы, аналитика включена.'
        : 'Оставили только необходимые файлы cookie. Аналитика выключена.';
      setTimeout(hideBanner, 1600);
    });
  }
  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function fillBanner() {
    var j = JOKES[Math.floor(Math.random() * JOKES.length)];
    banner.classList.remove('is-done', 'is-accepted', 'is-declined');
    banner.innerHTML =
      '<div class="aa-cookie__head">' + COOKIE_SVG +
        '<div><p class="aa-cookie__eyebrow">Сайт использует файлы cookie</p><p class="aa-cookie__title">' + j.t + '</p></div>' +
      '</div>' +
      '<p class="aa-cookie__text">' + j.x + '</p>' +
      '<p class="aa-cookie__plain"><b>По делу:</b> необходимые cookie нужны для работы сайта и&nbsp;форм. ' +
        'Аналитические (Яндекс&nbsp;Метрика) включим, только если нажмёте первую кнопку. ' +
        'Подробнее — в&nbsp;<a href="' + legalBase + 'cookies.html">Политике cookie</a>.</p>' +
      '<div class="aa-cookie__actions">' +
        // кнопки называют действие прямо («Принять все cookie»), шутка — мелкой подписью
        '<button type="button" class="aa-btn aa-btn--primary aa-cookie__yes" data-cookie="all">' +
          '<span class="aa-cookie__yes-main"><span class="aa-cookie__key" aria-hidden="true">🔑</span>Принять все cookie</span>' +
          '<span class="aa-cookie__yes-sub">и&nbsp;' + j.ok + '</span></button>' +
        '<button type="button" class="aa-btn aa-btn--ghost aa-cookie__no" data-cookie="necessary">' +
          '<span class="aa-cookie__yes-main">Только необходимые cookie</span>' +
          '<span class="aa-cookie__yes-sub">без аналитики</span></button>' +
      '</div>';
  }
  function showBanner() {
    if (!banner) buildBanner();
    fillBanner();
    banner.hidden = false;
    requestAnimationFrame(function () { banner.classList.add('is-visible'); root.classList.add('aa-cookie-open'); });
  }
  function hideBanner() {
    if (!banner) return;
    banner.classList.remove('is-visible');
    root.classList.remove('aa-cookie-open');
    setTimeout(function () { banner.hidden = true; }, 500);
  }

  // выбор читаем сразу (синхронно), чтобы main.js уже знал о согласии
  var saved = read();
  window.aaConsent = saved;
  if (saved) { root.classList.toggle('aa-consent-analytics', !!saved.analytics); if (saved.analytics) loadMetrika(); }

  function init() {
    fillOperator();
    if (!saved) setTimeout(showBanner, 900);
    document.addEventListener('click', function (e) {
      if (e.target.closest('[data-cookie-settings]')) { e.preventDefault(); showBanner(); }
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
