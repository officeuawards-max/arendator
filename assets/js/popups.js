/* ==========================================================================
   Всплывающие окна n-popup — как в вёрстке legacy (legacy/tickets/…)
   Нужен jQuery. На сайте он уже подключён в layouts.default (/aaw/js/jquery-3.4.1.min.js),
   поэтому в Blade этот файл подключают в @push('scripts') — ПОСЛЕ jQuery.

   Разметка окна (как legacy/tickets/includes/popup/*.blade.php):
     <div class="n-popup" data-ИМЯ>
       <div class="n-popup__container">
         <div class="n-popup__loader"><div class="cssload-spinner"></div></div>
         <button type="button" class="n-popup__close">…</button>
         <div class="n-popup__content">…</div>
       </div>
     </div>
   Кнопка открытия:  data-popup-btn="data-ИМЯ"  → окну добавляется класс .active
   Закрытие:         .n-popup__close (как в legacy) + клик по фону + Esc

   Окно бронирования лендинга — [data-popup-book]. Кнопка может передать:
     data-pack="vip|business|personal|table|question" — формат (question — вопрос);
     data-title="Заявка на билет VIP"                 — заголовок формы заявки сайта.
   Если в config.js задан popup.feedbackUrl (route('feedback.form')), форма заявки сайта
   подгружается в окно jQuery .load(), с теми же параметрами, что в legacy:
     { mod_name: 'feed_form', mod_tile: <заголовок>, mod_template: 'white', _token: <csrf> }
   Если не задан — в окне наша форма бронирования (main.js, лид в Битрикс24).

   Для совместимости оставлена legacy-кнопка покупки: класс .ticket-buy-button + data-id
   грузит /tickets/pay?type=ID&count=1 в #popupBuyForm (окно [data-buy-form] из legacy).
   ========================================================================== */
(function () {
  'use strict';

  // jQuery на сайте подключается в конце layouts.default. Если этот файл оказался выше —
  // ждём jQuery (до 15 секунд), а не падаем молча.
  var tries = 0;
  (function waitForJQuery() {
    if (window.jQuery) return init(window.jQuery);
    if (++tries > 150) { if (window.console) console.warn('[landing] popups.js: нет jQuery — окна не откроются'); return; }
    setTimeout(waitForJQuery, 100);
  })();

function init($) {

  var CFG = window.LANDING_CONFIG || {};
  var P = CFG.popup || {};
  var lastFocus = null;

  function csrf() { return $('meta[name="csrf-token"]').attr('content') || ''; }

  function openPopup($p, $btn) {
    lastFocus = document.activeElement;
    $p.addClass('active');
    $('body').addClass('_noscroll');
    $('html').addClass('aa-popup-open');
    setTimeout(function () { $p.find('.n-popup__close').trigger('focus'); }, 60);
    if ($p.is('[data-popup-book]')) prepareBook($p, $btn || $());
  }

  function closePopup($p) {
    $p.removeClass('active');
    if (!$('.n-popup.active').length) {
      $('body').removeClass('_noscroll');
      $('html').removeClass('aa-popup-open');
    }
    if (lastFocus && lastFocus.focus) lastFocus.focus({ preventScroll: true });
  }

  // окно бронирования: форма сайта (feedbackUrl) или наша форма
  function prepareBook($p, $btn) {
    var pack = $btn.attr('data-pack') || '';
    var title = $btn.attr('data-title') || (pack === 'question' ? 'Вопрос по билетам' : 'Бронирование билета');
    var $remote = $p.find('[data-popup-remote]');
    var $form = $p.find('[data-book-form]');
    var $done = $p.find('[data-book-done]');

    if (P.feedbackUrl) {
      // как в legacy: $('#feedback_ajax_…').load(route('feedback.form'), {...})
      $form.prop('hidden', true); $done.prop('hidden', true);
      $p.find('[data-popup-top]').prop('hidden', true);          // заголовок рисует сама форма сайта (mod_tile)
      $remote.prop('hidden', false).html('');
      var $loader = $p.find('.n-popup__loader').addClass('active');
      $remote.load(P.feedbackUrl, {
        mod_name: 'feed_form',
        mod_tile: title,
        mod_template: 'white',
        _token: csrf()
      }, function (resp, status) {
        $loader.removeClass('active');
        if (status === 'error') $remote.html('<p class="aa-rform__error">Не удалось загрузить форму. Обновите страницу или попробуйте позже.</p>');
      });
    } else {
      $remote.prop('hidden', true);
      $p.find('[data-popup-top]').prop('hidden', false);
      $p.find('[data-popup-title]').text(title);
      if (window.aaBooking) window.aaBooking.prepare(pack);
    }
  }

  // открыть окно (делегирование — работает и для кнопок, добавленных позже)
  $(document).on('click', '[data-popup-btn]', function (e) {
    var $btn = $(this);
    if ($btn.prop('disabled')) return;
    var $p = $('[' + $btn.attr('data-popup-btn') + ']').first();
    if (!$p.length) return;
    e.preventDefault();

    // legacy: кнопка покупки — форма оплаты с сервера сайта
    if ($btn.hasClass('ticket-buy-button') && $('#popupBuyForm').length) {
      $('#popupBuyForm').html('');
      $p.find('.n-popup__loader').addClass('active');
      $('#popupBuyForm').load('/tickets/pay?type=' + encodeURIComponent($btn.attr('data-id')) + '&count=1', function () {
        $p.find('.n-popup__loader').removeClass('active');
      });
    }
    openPopup($p, $btn);
  });

  // закрыть: крестик (как в legacy), кнопка «Закрыть», клик по фону, Esc
  $('body').on('click', '.n-popup__close, .n-popup__close-btn', function () { closePopup($(this).closest('.n-popup')); });
  $(document).on('mousedown', '.n-popup', function (e) { if (e.target === this) closePopup($(this)); });
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') $('.n-popup.active').each(function () { closePopup($(this)); });
  });

  // прямая ссылка …/tickets#book-question — сразу открыть окно вопроса
  function onQuestionHash(delay) {
    if (location.hash !== '#book-question') return;
    var $q = $('[data-popup-btn="data-popup-book"][data-pack="question"]').first();
    if ($q.length) setTimeout(function () { $q.trigger('click'); }, delay);
  }
  $(function () { onQuestionHash(400); });
  $(window).on('hashchange', function () { onQuestionHash(0); });

  window.aaPopup = { open: function (sel, $btn) { openPopup($(sel).first(), $btn); }, close: function (sel) { closePopup($(sel || '.n-popup.active')); } };
}
})();
