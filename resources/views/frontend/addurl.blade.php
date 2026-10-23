@extends('layouts.frontend')

@section('title', $title)

@section('description', __('interface.frontend.add_site_description'))

@section('keywords', __('interface.frontend.add_site_keywords'))

@section('content')
    <section class="content-card form-card add-site-card">
        <div class="section-heading">
            <div>
                <span class="eyebrow">{{ __('interface.frontend.new_record') }}</span>
                <h1>{{ $title }}</h1>
                <p>{{ __('interface.frontend.add_site_intro') }}</p>
            </div>
        </div>

        @include('layouts.notifications')

        @if(\App\Helpers\SettingsHelpers::getSetting('NOTE') != '')
            <div class="alert alert-info" role="alert">
                {!! \App\Helpers\SettingsHelpers::getSetting('NOTE') !!}
            </div>
        @endif

        <p class="form-help">{{ __('interface.common.required_fields_frontend') }}</p>

        {!! Form::open(['url' =>  URL::route('add'), 'method' => 'post', 'class' => 'frontend-form add-site-form', 'files' => true]) !!}
            <div class="form-group">
                {!! Form::label('name', __('interface.frontend.site_name'), ['class' => 'form-label']) !!}
                {!! Form::text('name', old('name', null), [
                    'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                    'aria-invalid' => $errors->has('name') ? 'true' : 'false',
                    'aria-describedby' => $errors->has('name') ? 'name-error' : null,
                    'aria-required' => 'true',
                    'placeholder' => __('interface.frontend.site_name_placeholder'),
                ]) !!}
                @if ($errors->has('name'))
                    <span class="form-error" id="name-error">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('catalog_id', __('interface.frontend.section'), ['class' => 'form-label']) !!}
                {!! Form::select('catalog_id', $options, old('catalog_id', null), [
                    'placeholder' => __('interface.frontend.choose_section'),
                    'class' => 'form-control' . ($errors->has('catalog_id') ? ' is-invalid' : ''),
                    'aria-invalid' => $errors->has('catalog_id') ? 'true' : 'false',
                    'aria-describedby' => $errors->has('catalog_id') ? 'catalog_id-error' : null,
                    'aria-required' => 'true',
                ]) !!}
                @if ($errors->has('catalog_id'))
                    <span class="form-error" id="catalog_id-error">{{ $errors->first('catalog_id') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('url', __('interface.frontend.url_address'), ['class' => 'form-label']) !!}
                {!! Form::text('url', old('url', null), [
                    'class' => 'form-control' . ($errors->has('url') ? ' is-invalid' : ''),
                    'aria-invalid' => $errors->has('url') ? 'true' : 'false',
                    'aria-describedby' => $errors->has('url') ? 'url-error' : null,
                    'aria-required' => 'true',
                    'inputmode' => 'url',
                    'placeholder' => 'https://example.com',
                ]) !!}
                @if ($errors->has('url'))
                    <span class="form-error" id="url-error">{{ $errors->first('url') }}</span>
                @endif
            </div>

            <div class="add-site-form__contacts">
                <div class="form-group">
                    {!! Form::label('phone', __('interface.common.phone'), ['class' => 'form-label']) !!}
                    {!! Form::text('phone', old('phone', null), [
                        'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''),
                        'aria-invalid' => $errors->has('phone') ? 'true' : 'false',
                        'aria-describedby' => $errors->has('phone') ? 'phone-error' : null,
                        'inputmode' => 'tel',
                        'autocomplete' => 'tel',
                        'placeholder' => '+7 999 000-00-00',
                    ]) !!}
                    @if ($errors->has('phone'))
                        <span class="form-error" id="phone-error">{{ $errors->first('phone') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('email', __('interface.common.email'), ['class' => 'form-label']) !!}
                    {!! Form::text('email', old('email', null), [
                        'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                        'aria-invalid' => $errors->has('email') ? 'true' : 'false',
                        'aria-describedby' => $errors->has('email') ? 'email-error' : null,
                        'inputmode' => 'email',
                        'autocomplete' => 'email',
                        'placeholder' => 'mail@example.com',
                    ]) !!}
                    @if ($errors->has('email'))
                        <span class="form-error" id="email-error">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('description', __('interface.frontend.short_description'), ['class' => 'form-label']) !!}
                {!! Form::textarea('description', old('description', null), [
                    'placeholder' => __('interface.frontend.short_description_placeholder'),
                    'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                    'aria-invalid' => $errors->has('description') ? 'true' : 'false',
                    'aria-describedby' => $errors->has('description') ? 'description-error' : null,
                    'aria-required' => 'true',
                    'rows' => 3,
                ]) !!}
                @if ($errors->has('description'))
                    <span class="form-error" id="description-error">{{ $errors->first('description') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('full_description', __('interface.frontend.full_description'), ['class' => 'form-label']) !!}
                {!! Form::textarea('full_description', old('full_description', null), [
                    'placeholder' => __('interface.frontend.full_description_placeholder'),
                    'class' => 'form-control' . ($errors->has('full_description') ? ' is-invalid' : ''),
                    'aria-invalid' => $errors->has('full_description') ? 'true' : 'false',
                    'aria-describedby' => $errors->has('full_description') ? 'full_description-error' : null,
                    'aria-required' => 'true',
                    'rows' => 4,
                ]) !!}
                @if ($errors->has('full_description'))
                    <span class="form-error" id="full_description-error">{{ $errors->first('full_description') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('keywords', __('interface.common.keywords'), ['class' => 'form-label']) !!}
                {!! Form::textarea('keywords', old('keywords', null), [
                    'placeholder' => __('interface.frontend.keywords_placeholder'),
                    'class' => 'form-control' . ($errors->has('keywords') ? ' is-invalid' : ''),
                    'aria-invalid' => $errors->has('keywords') ? 'true' : 'false',
                    'aria-describedby' => $errors->has('keywords') ? 'keywords-error' : null,
                    'rows' => 2,
                ]) !!}
                @if ($errors->has('keywords'))
                    <span class="form-error" id="keywords-error">{{ $errors->first('keywords') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('htmlcode_banner', __('interface.common.html_banner'), ['class' => 'form-label']) !!}
                {!! Form::textarea('htmlcode_banner', old('htmlcode_banner', null), [
                    'placeholder' => __('interface.frontend.html_banner_placeholder'),
                    'class' => 'form-control' . ($errors->has('htmlcode_banner') ? ' is-invalid' : ''),
                    'aria-invalid' => $errors->has('htmlcode_banner') ? 'true' : 'false',
                    'aria-describedby' => $errors->has('htmlcode_banner') ? 'htmlcode_banner-error' : null,
                    'rows' => 3,
                ]) !!}
                @if ($errors->has('htmlcode_banner'))
                    <span class="form-error" id="htmlcode_banner-error">{{ $errors->first('htmlcode_banner') }}</span>
                @endif
            </div>

            @include('shared.link-screenshot-upload', ['admin' => false])

            @include('shared.link-custom-fields', ['admin' => false, 'values' => []])

            @include('frontend.partials.captcha')

            <div class="form-group add-site-form__agreement">
                <label class="checkbox-line">
                    {!! Form::checkbox('agree', 1, old('agree') ? true : false, [
                        'aria-required' => 'true',
                        'aria-invalid' => $errors->has('agree') ? 'true' : 'false',
                        'aria-describedby' => $errors->has('agree') ? 'agree-error' : null,
                    ]) !!}
                    <span>{{ __('interface.frontend.agree_prefix') }} <a href="{{ URL::route('rules') }}" target="_blank" rel="noopener">{{ __('interface.frontend.agree_link') }}</a> {{ __('interface.frontend.agree_suffix') }}</span>
                </label>
                @if ($errors->has('agree'))
                    <span class="form-error" id="agree-error">{{ $errors->first('agree') }}</span>
                @endif
            </div>

            <div>
                <button type="submit" class="btn btn-primary btn-lg add-site-form__submit">
                    <x-directory-icon name="plus" />
                    {{ __('interface.frontend.add_submit') }}
                </button>
            </div>
        {!! Form::close() !!}
    </section>
@endsection

@section('js')
    <script src="{{ asset('js/submission.js') }}?v={{ filemtime(public_path('js/submission.js')) }}" defer></script>
@endsection
