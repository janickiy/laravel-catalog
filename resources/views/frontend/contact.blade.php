@extends('layouts.frontend')

@section('title', $title)

@section('description', '')

@section('keywords', '')

@section('content')
    <section class="content-card form-card">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Связь</span>
                <h1>{{ $title }}</h1>
                <p>Отправьте сообщение администрации каталога через форму обратной связи.</p>
            </div>
        </div>

        @include('layouts.notifications')

        @if (!session('success'))
            <p class="form-help">Поля со звездочкой обязательны для заполнения.</p>

            {!! Form::open(['url' =>  URL::route('sendmsg'), 'method' => 'post', 'class' => 'frontend-form']) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Ваше имя*', ['class'=> 'form-label']) !!}
                    {!! Form::text('name', old('name', null), [
                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                        'placeholder' => 'Ваше имя',
                    ]) !!}
                    @if ($errors->has('name'))
                        <span class="form-error">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email*', ['class'=> 'form-label']) !!}
                    {!! Form::text('email', old('email', null), [
                        'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                        'placeholder' => 'mail@example.com',
                    ]) !!}
                    @if ($errors->has('email'))
                        <span class="form-error">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('message', 'Сообщение*', ['class'=> 'form-label']) !!}
                    {!! Form::textarea('message', old('message', null), [
                        'placeholder' => 'Ваше сообщение',
                        'class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : ''),
                        'rows' => 4,
                    ]) !!}
                    @if ($errors->has('message'))
                        <span class="form-error">{{ $errors->first('message') }}</span>
                    @endif
                </div>

                <div class="form-group captcha-block">
                    {!! Form::label('captcha', 'Защитный код*', ['class'=> 'form-label']) !!}
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

                <div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-send"></i>
                        Отправить
                    </button>
                </div>
            {!! Form::close() !!}
        @endif
    </section>
@endsection
