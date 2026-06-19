@extends('layouts.install')

@section('content')
    <div class="install-heading">
        <p class="install-eyebrow">{{ __('install.str.license_agreement') }}</p>
        <h2>{{ __('install.str.installation') }}</h2>
        <p>{{ __('install.str.welcome') }}</p>
    </div>

    <textarea class="install-license" readonly>{{ __('install.license') }}</textarea>

    <div class="install-actions">
        <a href="{{ route('install.requirements') }}" class="install-button">
            {{ __('install.button.next') }}
        </a>
    </div>
@endsection
