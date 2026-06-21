@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .dashboard-stat {
            border: 0;
            border-radius: .375rem;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
            color: #fff !important;
            display: flex;
            flex-direction: column;
            min-height: 10.5rem;
            overflow: hidden;
            position: relative;
            text-decoration: none;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .dashboard-stat:hover,
        .dashboard-stat:focus {
            color: #fff !important;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
            transform: translateY(-1px);
        }

        .dashboard-stat.text-bg-warning {
            color: #1f2937 !important;
        }

        .dashboard-stat.text-bg-warning:hover,
        .dashboard-stat.text-bg-warning:focus {
            color: #1f2937 !important;
        }

        .dashboard-stat__body {
            flex: 1 1 auto;
            min-height: 8rem;
            padding: 1.5rem 6.75rem 1.25rem 1.5rem;
            position: relative;
            z-index: 1;
        }

        .dashboard-stat__value {
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1;
        }

        .dashboard-stat__label {
            font-size: 1.05rem;
            font-weight: 700;
            margin-top: 1.5rem;
        }

        .dashboard-stat__description {
            font-size: .875rem;
            font-weight: 600;
            margin-top: .5rem;
            opacity: .92;
        }

        .dashboard-stat__icon {
            font-size: 4.5rem;
            line-height: 1;
            opacity: .24;
            position: absolute;
            right: 1.25rem;
            top: 1.65rem;
            transition: transform .15s ease;
            z-index: 0;
        }

        .dashboard-stat:hover .dashboard-stat__icon,
        .dashboard-stat:focus .dashboard-stat__icon {
            transform: scale(1.05);
        }

        .dashboard-stat__footer {
            align-items: center;
            background: rgba(0, 0, 0, .14);
            display: flex;
            font-weight: 700;
            gap: .375rem;
            justify-content: center;
            min-height: 2.25rem;
            padding: .35rem .75rem;
            position: relative;
            z-index: 1;
        }

        .dashboard-stat__footer i {
            font-size: 1.05rem;
            line-height: 1;
        }

        .dashboard-action {
            align-items: center;
            display: flex;
            gap: .75rem;
            justify-content: space-between;
            min-height: 3.5rem;
            text-decoration: none;
        }

        .dashboard-action i {
            display: inline-flex;
            font-size: 1.5rem;
            justify-content: center;
            line-height: 1;
            width: 2rem;
        }

        .dashboard-table td,
        .dashboard-table th {
            vertical-align: middle;
        }

        .dashboard-message {
            max-width: 28rem;
        }
    </style>
@endsection

@section('content')
    <div class="row g-3">
        @foreach ($summaryCards as $card)
            @php($statColorClass = $card['colorClass'] ?? 'text-bg-' . ($card['variant'] ?? 'secondary'))
            <div class="col-12 col-sm-6 col-xl-4 col-xxl-3">
                <a class="dashboard-stat {{ $statColorClass }}" href="{{ $card['url'] }}">
                    <div class="dashboard-stat__body">
                        <div class="dashboard-stat__value">{{ number_format($card['value'], 0, '.', ' ') }}</div>
                        <div class="dashboard-stat__label">{{ $card['label'] }}</div>
                        <div class="dashboard-stat__description">{{ $card['description'] }}</div>
                        <i class="dashboard-stat__icon bi {{ $card['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <span class="dashboard-stat__footer">
                        {{ __('interface.admin.dashboard_sections.open_section') }}
                        <i class="bi bi-arrow-right-circle-fill" aria-hidden="true"></i>
                    </span>
                </a>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.dashboard_sections.link_statuses') }}</h3>
                </div>
                <div class="card-body">
                    @foreach ($linkStatuses as $status)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">{{ $status['label'] }}</span>
                                <span class="text-secondary">{{ number_format($status['count'], 0, '.', ' ') }}</span>
                            </div>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $status['percent'] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar {{ $status['barClass'] }}" style="width: {{ $status['percent'] }}%">
                                    {{ $status['percent'] }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.dashboard_sections.quick_actions') }}</h3>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($quickActions as $action)
                        <a class="list-group-item list-group-item-action dashboard-action" href="{{ $action['url'] }}">
                            <span>{{ $action['label'] }}</span>
                            <i class="bi {{ $action['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.dashboard_sections.top_views') }}</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm dashboard-table mb-0">
                            <thead>
                            <tr>
                                <th>{{ __('interface.common.site') }}</th>
                                <th class="text-end">{{ __('interface.common.views') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($topViewedLinks as $link)
                                <tr>
                                    <td>
                                        <a href="{{ URL::route('cp.links.edit', ['id' => $link->id]) }}">{{ $link->name }}</a>
                                        <div class="small text-secondary">{{ $link->catalog->name ?? __('interface.common.misc') }}</div>
                                    </td>
                                    <td class="text-end">{{ number_format((int) $link->views, 0, '.', ' ') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-secondary text-center py-4">{{ __('interface.common.no_data') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">{{ __('interface.admin.dashboard_sections.latest_links') }}</h3>
                    <a class="btn btn-sm btn-outline-primary" href="{{ URL::route('cp.links.index') }}">{{ __('interface.admin.dashboard_sections.all_links') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table dashboard-table mb-0">
                            <thead>
                            <tr>
                                <th>{{ __('interface.common.name') }}</th>
                                <th>{{ __('interface.common.category') }}</th>
                                <th>{{ __('interface.common.status') }}</th>
                                <th class="text-end">{{ __('interface.common.date') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($latestLinks as $link)
                                <tr>
                                    <td>
                                        <a href="{{ URL::route('cp.links.edit', ['id' => $link->id]) }}">{{ $link->name }}</a>
                                        <div class="small text-secondary">{{ $link->url }}</div>
                                    </td>
                                    <td>{{ $link->catalog->name ?? __('interface.common.misc') }}</td>
                                    <td>
                                        <span class="badge {{ \App\Enums\LinkStatus::cssColorFor($link->status) }}">{{ \App\Enums\LinkStatus::labelFor($link->status) }}</span>
                                    </td>
                                    <td class="text-end text-secondary">{{ optional($link->created_at)->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-secondary text-center py-4">{{ __('interface.common.no_links') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">{{ __('interface.admin.dashboard_sections.latest_messages') }}</h3>
                    <a class="btn btn-sm btn-outline-primary" href="{{ URL::route('cp.feedback.index') }}">{{ __('interface.common.all') }}</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($latestFeedback as $message)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between gap-3">
                                <span class="fw-semibold">{{ $message->name }}</span>
                                <span class="small text-secondary">{{ optional($message->created_at)->format('Y-m-d H:i:s') }}</span>
                            </div>
                            <div class="small text-secondary">{{ $message->email }}</div>
                            <div class="dashboard-message text-truncate mt-1">{{ $message->message }}</div>
                        </div>
                    @empty
                        <div class="list-group-item text-secondary text-center py-4">{{ __('interface.common.no_messages') }}</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
@endsection
