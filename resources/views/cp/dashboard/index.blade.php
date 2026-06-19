@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .dashboard-stat {
            min-height: 8.75rem;
        }

        .dashboard-stat .stat-icon {
            align-items: center;
            border-radius: .5rem;
            display: inline-flex;
            height: 2.75rem;
            justify-content: center;
            width: 2.75rem;
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
            font-size: 1.25rem;
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
            <div class="col-12 col-sm-6 col-xl-4">
                <a class="card dashboard-stat text-decoration-none h-100 border-0 shadow-sm"
                   href="{{ $card['url'] }}">
                    <div class="card-body d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="text-secondary small text-uppercase fw-semibold">{{ $card['label'] }}</div>
                            <div class="fs-2 fw-semibold text-body mt-2">{{ number_format($card['value'], 0, '.', ' ') }}</div>
                        </div>
                        <span class="stat-icon {{ $card['colorClass'] ?? 'text-bg-' . $card['variant'] }}">
                            <i class="bi {{ $card['icon'] }}"></i>
                        </span>
                    </div>
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

            <div class="card shadow-sm mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.dashboard_sections.administrators') }}</h3>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($latestAdmins as $admin)
                        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                           href="{{ URL::route('cp.admin.edit', ['id' => $admin->id]) }}">
                            <span>{{ $admin->name ?? $admin->login }}</span>
                            <span class="small text-secondary">{{ $admin->login }}</span>
                        </a>
                    @empty
                        <div class="list-group-item text-secondary text-center py-4">{{ __('interface.common.no_admins') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
