<div class="form-group captcha-block">
    {!! Form::label('captcha', __('interface.frontend.captcha'), ['class' => 'form-label']) !!}
    <div class="captcha-block__controls">
        <div class="captcha-block__image">@captcha</div>
        {!! Form::text('captcha', null, [
            'class' => 'form-control' . ($errors->has('captcha') ? ' is-invalid' : ''),
            'aria-invalid' => $errors->has('captcha') ? 'true' : 'false',
            'aria-describedby' => $errors->has('captcha') ? 'captcha-error' : null,
            'aria-required' => 'true',
            'autocomplete' => 'off',
            'spellcheck' => 'false',
            'placeholder' => __('interface.frontend.captcha_placeholder'),
            'id' => 'captcha',
        ]) !!}
    </div>
    @if ($errors->has('captcha'))
        <span class="form-error" id="captcha-error">{{ $errors->first('captcha') }}</span>
    @endif
</div>
