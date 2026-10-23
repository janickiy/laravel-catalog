<div class="form-group captcha-block" data-captcha
     data-loading="{{ __('captcha_ui.loading') }}" data-updated="{{ __('captcha_ui.updated') }}"
     data-error="{{ __('captcha_ui.error') }}">
    {!! Form::label('captcha', __('interface.frontend.captcha'), ['class' => 'form-label']) !!}
    <div class="captcha-block__controls">
        <div class="captcha-block__challenge">
            <div class="captcha-block__image">{!! captcha_img('default', ['alt' => e(__('captcha_ui.image_alt'))]) !!}</div>
            <button type="button" class="captcha-block__refresh" data-captcha-refresh
                    title="{{ __('captcha_ui.refresh') }}" aria-label="{{ __('captcha_ui.refresh') }}" hidden>
                <svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 7v5h-5M4 17v-5h5"/>
                    <path d="M6.1 7a7 7 0 0 1 11.5-1L20 9M4 15l2.4 3A7 7 0 0 0 17.9 17"/>
                </svg>
            </button>
        </div>
        {!! Form::text('captcha', null, [
            'class' => 'form-control' . ($errors->has('captcha') ? ' is-invalid' : ''),
            'aria-invalid' => $errors->has('captcha') ? 'true' : 'false',
            'aria-describedby' => 'captcha-status' . ($errors->has('captcha') ? ' captcha-error' : ''),
            'aria-required' => 'true',
            'autocomplete' => 'off',
            'spellcheck' => 'false',
            'placeholder' => __('captcha_ui.placeholder'),
            'id' => 'captcha',
        ]) !!}
    </div>
    <span class="captcha-block__status" id="captcha-status" data-captcha-status role="status" aria-live="polite" hidden></span>
    @if ($errors->has('captcha'))
        <span class="form-error" id="captcha-error">{{ $errors->first('captcha') }}</span>
    @endif
</div>

@once
    @push('scripts')
        <script src="{{ asset('js/captcha.js') }}?v={{ filemtime(public_path('js/captcha.js')) }}" defer></script>
    @endpush
@endonce
