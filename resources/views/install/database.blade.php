@extends('layouts.install')

@section('content')
    <div class="install-heading">
        <p class="install-eyebrow">{{ __('install.str.database_info') }}</p>
        <h2>{{ __('install.str.database_information') }}</h2>
    </div>

    <form method="post" action="{{ route('install.installation') }}" class="install-form">
        @csrf

        <div class="install-grid install-grid--two">
            <label class="install-field">
                <span>{{ __('install.str.database_host') }}</span>
                <input type="text" name="host" value="{{ old('host', config('database.connections.mysql.host', 'mysql')) }}" required>
                <small>{{ __('install.hint.database_host') }}</small>
            </label>

            <label class="install-field">
                <span>{{ __('install.str.database_port') }}</span>
                <input type="number" name="port" value="{{ old('port', config('database.connections.mysql.port', 3306)) }}" min="1" max="65535" required>
                <small>{{ __('install.hint.database_port') }}</small>
            </label>
        </div>

        <label class="install-field">
            <span>{{ __('install.str.database_name') }}</span>
            <input type="text" name="database" value="{{ old('database', config('database.connections.mysql.database', 'laravel_catalog')) }}" required>
            <small>{{ __('install.hint.database_name') }}</small>
        </label>

        <div class="install-grid install-grid--two">
            <label class="install-field">
                <span>{{ __('install.str.database_username') }}</span>
                <input type="text" name="username" value="{{ old('username', config('database.connections.mysql.username', 'laravel')) }}" required>
                <small>{{ __('install.hint.database_username') }}</small>
            </label>

            <label class="install-field">
                <span>{{ __('install.str.password') }}</span>
                <input type="password" name="password" value="{{ old('password', config('database.connections.mysql.password', '')) }}">
                <small>{{ __('install.hint.database_password') }}</small>
            </label>
        </div>

        <div class="install-actions">
            <button type="submit" class="install-button">{{ __('install.button.next') }}</button>
        </div>
    </form>
@endsection
