<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('interface.auth.page_title') }} | My Links Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('img/my-links-manager-icon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ filemtime(public_path('css/login.css')) }}">
</head>

<body>
    <main class="auth-shell">
        <section class="auth-card" aria-labelledby="auth-title">
            <div class="auth-brand">
                <a class="auth-brand__link" href="{{ URL::route('index') }}" aria-label="My Links Manager">
                    <span class="auth-brand__mark"><x-directory-icon name="folder" /></span>
                    <span class="auth-brand__text">
                        <strong>My Links <span>Manager</span></strong>
                        <small>{{ __('interface.admin.panel') }}</small>
                    </span>
                </a>
            </div>

            <div class="auth-body">
                <h1 id="auth-title" class="auth-title">{{ __('interface.auth.title') }}</h1>

                {!! Form::open(['url' => URL::route('singin'), 'method' => 'post', 'novalidate' => true, 'class' => 'auth-form']) !!}
                    @if ($errors->has('message'))
                        <div class="auth-alert" role="alert">
                            <x-directory-icon name="alert" />
                            <span>{{ $errors->first('message') }}</span>
                        </div>
                    @endif

                    <div class="auth-field">
                        {!! Form::label('login', __('interface.common.login'), ['class' => 'auth-label']) !!}
                        <div class="auth-input">
                            <x-directory-icon name="user" />
                            {!! Form::text('login', old('login'), [
                                'id' => 'login',
                                'class' => 'auth-control' . ($errors->has('login') ? ' is-invalid' : ''),
                                'placeholder' => __('interface.auth.login_placeholder'),
                                'autocomplete' => 'username',
                                'autocapitalize' => 'none',
                                'spellcheck' => 'false',
                                'autofocus' => true,
                                'aria-required' => 'true',
                                'aria-invalid' => $errors->has('login') ? 'true' : 'false',
                                'aria-describedby' => $errors->has('login') ? 'login-error' : null,
                            ]) !!}
                        </div>
                        @if ($errors->has('login'))
                            <p class="auth-error" id="login-error">{{ $errors->first('login') }}</p>
                        @endif
                    </div>

                    <div class="auth-field">
                        {!! Form::label('password', __('interface.common.password'), ['class' => 'auth-label']) !!}
                        <div class="auth-input">
                            <x-directory-icon name="lock" />
                            {!! Form::password('password', [
                                'id' => 'password',
                                'class' => 'auth-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                'placeholder' => __('interface.auth.password_placeholder'),
                                'autocomplete' => 'current-password',
                                'aria-required' => 'true',
                                'aria-invalid' => $errors->has('password') ? 'true' : 'false',
                                'aria-describedby' => $errors->has('password') ? 'password-error' : null,
                            ]) !!}
                        </div>
                        @if ($errors->has('password'))
                            <p class="auth-error" id="password-error">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="auth-remember">
                        {!! Form::checkbox('remember', 1, old('remember') ? true : false, [
                            'id' => 'remember',
                        ]) !!}
                        {!! Form::label('remember', __('interface.auth.remember')) !!}
                    </div>

                    <button type="submit" class="auth-submit">
                        <x-directory-icon name="sign-in" />
                        <span>{{ __('interface.auth.submit') }}</span>
                    </button>
                {!! Form::close() !!}
            </div>
        </section>
    </main>
</body>
</html>
