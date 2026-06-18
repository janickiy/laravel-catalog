<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Панель управления | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('img/my-links-manager-icon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/admin/js/plugin/jquery-treeview-master/jquery.treeview.css">
    <link rel="stylesheet" href="/admin/js/plugin/datetimepicker/jquery.datetimepicker.css">

    <style>
        :root {
            --lte-sidebar-width: 17rem;
        }

        .app-main {
            min-height: calc(100vh - 7rem);
        }

        .app-content-header h2,
        .app-content-header h3 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 600;
        }

        .sidebar-brand {
            min-height: 4.25rem;
            padding: .7rem .75rem;
        }

        .sidebar-brand .brand-link {
            align-items: center;
            display: flex;
            justify-content: flex-start;
            padding: 0;
            text-decoration: none;
        }

        .sidebar-brand .brand-logo {
            display: block;
            height: auto;
            max-width: 100%;
            width: 13.25rem;
        }

        .sidebar-menu .nav-link {
            gap: .55rem;
        }

        .admin-user-label {
            align-items: center;
            color: var(--bs-secondary-color);
            display: inline-flex;
            font-size: .95rem;
            gap: .45rem;
            max-width: 16rem;
        }

        .admin-user-label span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .jarviswidget {
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: .5rem;
            box-shadow: 0 .125rem .5rem rgba(0, 0, 0, .04);
            padding: 1rem;
        }

        .box-header {
            margin-bottom: .75rem;
        }

        .smart-form header {
            border-bottom: 1px solid var(--bs-border-color);
            color: var(--bs-secondary-color);
            font-size: .95rem;
            margin-bottom: 1rem;
            padding-bottom: .75rem;
        }

        .smart-form fieldset {
            border: 0;
            margin: 0;
            padding: 0;
        }

        .smart-form section {
            margin-bottom: 1rem;
        }

        .smart-form .label {
            display: block;
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .smart-form label.input,
        .smart-form label.textarea {
            display: block;
            margin: 0;
        }

        .smart-form textarea,
        .smart-form .custom-scroll {
            width: 100%;
        }

        .smart-form footer {
            border-top: 1px solid var(--bs-border-color);
            display: flex;
            gap: .5rem;
            margin-top: 1.25rem;
            padding-top: 1rem;
        }

        .pull-left {
            float: left !important;
        }

        .btn-default {
            --bs-btn-color: #212529;
            --bs-btn-bg: #f8f9fa;
            --bs-btn-border-color: #ced4da;
            --bs-btn-hover-bg: #e9ecef;
            --bs-btn-hover-border-color: #ced4da;
        }

        .btn-xs {
            --bs-btn-padding-y: .125rem;
            --bs-btn-padding-x: .35rem;
            --bs-btn-font-size: .75rem;
        }

        .nobr {
            white-space: nowrap;
        }

    </style>

    @yield('css')

    <script>
        window.SITE_URL = "{{ url('/') }}";
    </script>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @php($currentAdmin = auth('admin')->user())
                @if ($currentAdmin)
                    <li class="nav-item d-flex align-items-center">
                        <span class="navbar-text admin-user-label" title="{{ $currentAdmin->login ?: $currentAdmin->name }}">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ $currentAdmin->login ?: ($currentAdmin->name ?: 'Администратор') }}</span>
                        </span>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ URL::route('logout') }}" title="Выйти">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
            <a href="{{ URL::route('index') }}" class="brand-link">
                <img src="{{ asset('img/my-links-manager-admin-logo.svg') }}" class="brand-logo" alt="My Links Manager Admin Panel">
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ URL::route('cp.dashbaord.index') }}" class="nav-link {{ request()->routeIs('cp.dashbaord.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Главная</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('cp.links.index') }}" class="nav-link {{ request()->is('cp/links*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-link-45deg"></i>
                            <p>Ссылки</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('cp.catalog.index') }}" class="nav-link {{ request()->is('cp/catalog*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-list-ul"></i>
                            <p>Категории</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('cp.feedback.index') }}" class="nav-link {{ request()->is('cp/feedback*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-envelope"></i>
                            <p>Сообщения с сайта</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('cp.admin.index') }}" class="nav-link {{ request()->is('cp/admins*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Администраторы</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::route('cp.settings.index') }}" class="nav-link {{ request()->is('cp/settings*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-gear"></i>
                            <p>Настройки</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        @if (isset($title))
                            <h2>{!! $title !!}</h2>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end mb-0">
                            <li class="breadcrumb-item">Панель управления</li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('layouts.notifications')
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <strong>© {{ date('Y') }} My Links Manager</strong>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
<script src="/admin/js/plugin/datetimepicker/jquery.datetimepicker.full.js"></script>
<script src="/admin/js/plugin/jquery-treeview-master/jquery.treeview.js"></script>

<script>
    window.pageSetUp = window.pageSetUp || function () {};

    window.ResponsiveDatatablesHelper = window.ResponsiveDatatablesHelper || function () {
        return {
            createExpandIcon: function () {},
            respond: function () {}
        };
    };

    window.swal = function (options, callbackOrText, type) {
        if (typeof options === 'object') {
            return Swal.fire({
                title: options.title,
                text: options.text,
                icon: options.type || options.icon,
                showCancelButton: options.showCancelButton || false,
                confirmButtonText: options.confirmButtonText || 'OK',
                cancelButtonText: options.cancelButtonText || 'Cancel',
                confirmButtonColor: options.confirmButtonColor
            }).then(function (result) {
                if (typeof callbackOrText === 'function') {
                    callbackOrText(result.isConfirmed);
                }
            });
        }

        return Swal.fire({
            title: options,
            text: callbackOrText,
            icon: type
        });
    };
</script>

@yield('js')

</body>
</html>
