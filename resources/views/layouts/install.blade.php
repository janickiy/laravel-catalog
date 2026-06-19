<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('install.str.installation') }} | My Links Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('img/my-links-manager-icon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/install.css') }}">
</head>
<body>
    <div class="install-shell">
        <aside class="install-sidebar">
            <a class="install-logo" href="{{ route('install.start') }}" aria-label="My Links Manager">
                <img src="{{ asset('img/my-links-manager-logo.svg') }}" alt="My Links Manager">
            </a>

            <div class="install-sidebar__intro">
                <p class="install-eyebrow">{{ __('install.str.installation') }}</p>
                <h1>My Links Manager</h1>
                <p>{{ __('install.str.welcome') }}</p>
            </div>

            @include('install.steps')
        </aside>

        <main class="install-content">
            <div class="install-card">
                <div class="install-toolbar">
                    <label for="install_locale">{{ __('install.str.select_language') }}</label>
                    <select id="install_locale" class="install-language">
                        @foreach(config('app.languages', []) as $code => $languageName)
                            <option value="{{ $code }}" @selected(app()->getLocale() === $code)>{{ $languageName }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($errors->any())
                    <div class="install-alert install-alert--danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.getElementById('install_locale')?.addEventListener('change', function () {
            fetch('{{ route('install.ajax.action') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    action: 'change_lng',
                    locale: this.value,
                }),
            }).then(function () {
                window.location.reload();
            });
        });
    </script>
</body>
</html>
