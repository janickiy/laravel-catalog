@extends('layouts.install')

@section('content')
    <div class="install-heading">
        <p class="install-eyebrow">{{ __('install.str.license_agreement') }}</p>
        <h2>{{ __('install.str.installation') }}</h2>
        <p>{{ __('install.str.welcome') }}</p>
    </div>

    <textarea class="install-license" readonly>{{ __('license.agreement') }}</textarea>

    <div class="install-actions">
        <button type="button" class="install-button install-button--secondary" onclick="window.history.back()">
            {{ __('install.button.back') }}
        </button>
        <a href="{{ route('install.requirements') }}" class="install-button">
            {{ __('install.button.next') }}
        </a>
    </div>
@endsection
