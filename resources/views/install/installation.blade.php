@extends('layouts.install')

@section('content')
    <div class="install-heading">
        <p class="install-eyebrow">{{ __('install.str.administration') }}</p>
        <h2>{{ __('install.str.ready_to_install') }}</h2>
    </div>

    <form method="post" action="{{ route('install.install') }}" class="install-form install-form--compact">
        @csrf

        <label class="install-field">
            <span>{{ __('install.str.login') }}</span>
            <input type="text" name="login" value="{{ old('login', 'admin') }}" autocomplete="username" required>
        </label>

        <div class="install-grid install-grid--two">
            <label class="install-field">
                <span>{{ __('install.str.password') }}</span>
                <input type="password" name="password" autocomplete="new-password" required>
            </label>

            <label class="install-field">
                <span>{{ __('install.str.confirm_password') }}</span>
                <input type="password" name="confirm_password" autocomplete="new-password" required>
            </label>
        </div>

        <div class="install-alert install-alert--info">
            <strong>{{ __('install.str.important') }}</strong>
            <span>{{ __('install.license') }}</span>
        </div>

        <div class="install-actions">
            <a href="{{ route('install.database') }}" class="install-button install-button--secondary">
                {{ __('install.button.back') }}
            </a>
            <button type="submit" class="install-button">{{ __('install.button.install') }}</button>
        </div>
    </form>
@endsection
