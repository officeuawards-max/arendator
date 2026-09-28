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

  /* ---------- баннер ---------- */
  var banner;
  function buildBanner() {
    banner = document.createElement('div');
    banner.className = 'aa-cookie';
    banner.setAttribute('role', 'dialog');
    banner.setAttribute('aria-live', 'polite');
    banner.setAttribute('aria-label', 'Настройки cookie');
    banner.innerHTML =
      '<p class="aa-cookie__title">Мы используем cookie</p>' +
      '<p class="aa-cookie__text">Необходимые — чтобы сайт и формы работали. Аналитические (Яндекс&nbsp;Метрика) — чтобы понимать, ' +
      'какие разделы полезны. Аналитику включим только с&nbsp;вашего согласия. Подробнее — в&nbsp;' +
      '<a href="' + legalBase + 'cookies.html">Политике cookie</a>.</p>' +
      '<div class="aa-cookie__actions">' +
        '<button type="button" class="aa-btn aa-btn--primary aa-btn--sm" data-cookie="all">Принять все</button>' +
        '<button type="button" class="aa-btn aa-btn--ghost aa-btn--sm" data-cookie="necessary">Только необходимые</button>' +
      '</div>';
    (document.querySelector('.aa') || document.body).appendChild(banner);
    banner.addEventListener('click', function (e) {
      var b = e.target.closest('[data-cookie]');
      if (!b) return;
      save(b.getAttribute('data-cookie') === 'all');
      hideBanner();
    });
  }
  function showBanner() {
    if (!banner) buildBanner();
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
