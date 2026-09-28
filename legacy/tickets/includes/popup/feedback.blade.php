<div class="n-popup" data-feedback-form-{{ $type->id }}>
    <div class="n-popup__container">
        <div class="n-popup__loader">
            <div class="cssload-spinner"></div>
        </div>
        <button type="button" class="n-popup__close">
            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.83984 1.83887L10.6787 10.6777M10.6787 10.6777L19.5175 19.5165M10.6787 10.6777L19.5175 1.83887M10.6787 10.6777L1.83984 19.5165" stroke="#777777" stroke-width="3" />
            </svg>
        </button>
        <div class="n-popup__content">
            {{--<div class="n-popup__top">
                <div class="n-popup__title">Заявка на билет {{ $type->name }}</div>
            </div>--}}

            <div class="modal-body text-danger" id="feedback_ajax_{{ $type->id }}"></div>
        </div>
    </div>
</div>
