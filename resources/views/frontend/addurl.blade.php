@extends('layouts.frontend')

@section('title', $title)

@section('description', 'Добавление сайта')

@section('keywords', 'белый каталог сайтов, добавить url, добавить сайт')

@section('content')
    <section class="content-card form-card">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Новая запись</span>
                <h1>{{ $title }}</h1>
                <p>Заполните данные сайта, выберите раздел и отправьте запись на модерацию.</p>
            </div>
        </div>

        @include('layouts.notifications')

        @if(\App\Helpers\SettingsHelpers::getSetting('NOTE') != '')
            <div class="alert alert-info" role="alert">
                {!! \App\Helpers\SettingsHelpers::getSetting('NOTE') !!}
            </div>
        @endif

        <p class="form-help">Поля со звездочкой обязательны для заполнения.</p>

        {!! Form::open(['url' =>  URL::route('add'), 'method' => 'post', 'class' => 'frontend-form']) !!}
            <div class="form-group">
                {!! Form::label('name', 'Название*', ['class' => 'form-label']) !!}
                {!! Form::text('name', old('name', null), [
                    'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                    'placeholder' => 'Название сайта',
                ]) !!}
                @if ($errors->has('name'))
                    <span class="form-error">{{ $errors->first('name') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('catalog_id', 'Раздел*', ['class' => 'form-label']) !!}
                {!! Form::select('catalog_id', $options, old('catalog_id', null), [
                    'placeholder' => 'Выберите раздел',
                    'class' => 'form-control' . ($errors->has('catalog_id') ? ' is-invalid' : ''),
                ]) !!}
                @if ($errors->has('catalog_id'))
                    <span class="form-error">{{ $errors->first('catalog_id') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('url', 'URL адрес сайта*', ['class' => 'form-label']) !!}
                {!! Form::text('url', old('url', null), [
                    'class' => 'form-control' . ($errors->has('url') ? ' is-invalid' : ''),
                    'placeholder' => 'https://example.com',
                ]) !!}
                @if ($errors->has('url'))
                    <span class="form-error">{{ $errors->first('url') }}</span>
                @endif
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('phone', 'Телефон', ['class' => 'form-label']) !!}
                        {!! Form::text('phone', old('phone', null), [
                            'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''),
                            'placeholder' => '+7 999 000-00-00',
                        ]) !!}
                        @if ($errors->has('phone'))
                            <span class="form-error">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('email', 'Email', ['class' => 'form-label']) !!}
                        {!! Form::text('email', old('email', null), [
                            'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                            'placeholder' => 'mail@example.com',
                        ]) !!}
                        @if ($errors->has('email'))
                            <span class="form-error">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Описание*', ['class' => 'form-label']) !!}
                {!! Form::textarea('description', old('description', null), [
                    'placeholder' => 'Краткое описание сайта',
                    'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                    'rows' => 3,
                ]) !!}
                @if ($errors->has('description'))
                    <span class="form-error">{{ $errors->first('description') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('full_description', 'Полное описание*', ['class' => 'form-label']) !!}
                {!! Form::textarea('full_description', old('full_description', null), [
                    'placeholder' => 'Подробное описание сайта',
                    'class' => 'form-control' . ($errors->has('full_description') ? ' is-invalid' : ''),
                    'rows' => 4,
                ]) !!}
                @if ($errors->has('full_description'))
                    <span class="form-error">{{ $errors->first('full_description') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('keywords', 'Ключевые слова', ['class' => 'form-label']) !!}
                {!! Form::textarea('keywords', old('keywords', null), [
                    'placeholder' => 'Ключевые слова через запятую',
                    'class' => 'form-control' . ($errors->has('keywords') ? ' is-invalid' : ''),
                    'rows' => 2,
                ]) !!}
                @if ($errors->has('keywords'))
                    <span class="form-error">{{ $errors->first('keywords') }}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('htmlcode_banner', 'HTML код баннера', ['class' => 'form-label']) !!}
                {!! Form::textarea('htmlcode_banner', old('htmlcode_banner', null), [
                    'placeholder' => 'HTML код баннера',
                    'class' => 'form-control' . ($errors->has('htmlcode_banner') ? ' is-invalid' : ''),
                    'rows' => 3,
                ]) !!}
                @if ($errors->has('htmlcode_banner'))
                    <span class="form-error">{{ $errors->first('htmlcode_banner') }}</span>
                @endif
            </div>

            <div class="form-group captcha-block">
                {!! Form::label('captcha', 'Защитный код*', ['class' => 'form-label']) !!}
                {!! Form::text('captcha', null, [
                    'class' => 'form-control' . ($errors->has('captcha') ? ' is-invalid' : ''),
                    'placeholder' => 'Введите код с картинки',
                    'id' => 'captcha',
                ]) !!}
                <div>@captcha</div>
                @if ($errors->has('captcha'))
                    <span class="form-error">{{ $errors->first('captcha') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="checkbox-line">
                    {!! Form::checkbox('agree', 1, old('agree') ? true : false) !!}
                    <span>С <a href="{{ URL::route('rules') }}" target="_blank" rel="noopener">правилами участия</a> согласен(на)</span>
                </label>
                @if ($errors->has('agree'))
                    <span class="form-error">{{ $errors->first('agree') }}</span>
                @endif
            </div>

            <div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg"></i>
                    Добавить
                </button>
            </div>
        {!! Form::close() !!}
    </section>
@endsection
