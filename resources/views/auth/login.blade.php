<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Авторизация | My Links Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('img/my-links-manager-icon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background:
                linear-gradient(135deg, rgba(13, 110, 253, .08), rgba(32, 201, 151, .08)),
                var(--bs-body-bg);
        }

        .auth-shell {
            align-items: center;
            display: flex;
            justify-content: center;
            flex: 1 1 auto;
            min-height: 100vh;
            padding: 2rem 1rem;
            width: 100%;
        }

        .auth-card {
            border: 1px solid var(--bs-border-color);
            border-radius: .75rem;
            box-shadow: 0 1rem 3rem rgba(15, 23, 42, .12);
            overflow: hidden;
            width: min(100%, 26rem);
        }

        .auth-brand {
            background: #2f363d;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            padding: 1.15rem 1.35rem;
        }

        .auth-brand a {
            display: inline-block;
        }

        .auth-brand img {
            display: block;
            height: auto;
            max-width: 100%;
            width: 14.25rem;
        }

        .auth-title {
            color: var(--bs-emphasis-color);
            font-size: 1.2rem;
            font-weight: 650;
            margin: 0;
        }

        .input-group-text {
            background: var(--bs-tertiary-bg);
            border-color: var(--bs-border-color);
            color: var(--bs-secondary-color);
            min-width: 2.85rem;
            justify-content: center;
        }

        .form-control {
            border-color: var(--bs-border-color);
        }

        .btn-login {
            align-items: center;
            display: inline-flex;
            gap: .45rem;
            justify-content: center;
        }
    </style>
</head>

<body class="login-page bg-body-tertiary">
    <main class="auth-shell">
        <div class="card auth-card mb-0">
            <div class="auth-brand">
                <a href="{{ URL::route('index') }}">
                    <img src="{{ asset('img/my-links-manager-admin-logo.svg') }}" alt="My Links Manager Admin Panel">
                </a>
            </div>

            <div class="card-body p-4">
                <div class="mb-4">
                    <p class="auth-title">Вход в панель управления</p>
                </div>

                {!! Form::open(['url' => URL::route('singin'), 'method' => 'post', 'novalidate' => true]) !!}
                    @if ($errors->has('message'))
                        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span>{{ $errors->first('message') }}</span>
                        </div>
                    @endif

                    <div class="mb-3">
                        {!! Form::label('login', 'Логин', ['class' => 'form-label fw-semibold']) !!}
                        <div class="input-group has-validation">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>
                            {!! Form::text('login', old('login'), [
                                'id' => 'login',
                                'class' => 'form-control' . ($errors->has('login') ? ' is-invalid' : ''),
                                'placeholder' => 'Введите логин',
                                'autocomplete' => 'username',
                                'autofocus' => true,
                            ]) !!}
                            @if ($errors->has('login'))
                                <div class="invalid-feedback">{{ $errors->first('login') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        {!! Form::label('password', 'Пароль', ['class' => 'form-label fw-semibold']) !!}
                        <div class="input-group has-validation">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            {!! Form::password('password', [
                                'id' => 'password',
                                'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                                'placeholder' => 'Введите пароль',
                                'autocomplete' => 'current-password',
                            ]) !!}
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        {!! Form::checkbox('remember', 1, old('remember') ? true : false, [
                            'id' => 'remember',
                            'class' => 'form-check-input',
                        ]) !!}
                        {!! Form::label('remember', 'Запомнить меня', ['class' => 'form-check-label']) !!}
                    </div>

                    <button type="submit" class="btn btn-primary btn-login w-100">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Войти</span>
                    </button>
                {!! Form::close() !!}
            </div>
        </div>
    </main>
</body>
</html>
