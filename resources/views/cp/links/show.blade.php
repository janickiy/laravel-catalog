@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .link-show-list {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(13rem, 1fr));
        }

        .link-show-item {
            border-bottom: 1px solid var(--bs-border-color);
            padding-bottom: .85rem;
        }

        .link-show-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .link-show-label {
            color: var(--bs-secondary-color);
            font-size: .8125rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .link-show-text {
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .link-show-url {
            overflow-wrap: anywhere;
        }
    </style>
@endsection

@section('content')
    @php
        $rawStatus = $link->getRawOriginal('status');
        $statusLabel = \App\Enums\LinkStatus::labelFor($rawStatus);
        $statusCode = \App\Enums\LinkStatus::codeFor($rawStatus);
        $statusColor = \App\Enums\LinkStatus::cssColorFor($rawStatus);
        $externalUrl = \Illuminate\Support\Str::startsWith($link->url, ['http://', 'https://'])
            ? $link->url
            : 'http://' . $link->url;
        $empty = '-';
    @endphp

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex flex-column flex-md-row gap-2 justify-content-between align-items-md-center">
                    <h3 class="card-title mb-0">{{ $link->name }}</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ URL::route('cp.links.index') }}">
                            <i class="bi bi-arrow-left"></i>
                            {{ __('interface.common.back') }}
                        </a>
                        <a class="btn btn-sm btn-primary" href="{{ URL::route('cp.links.edit', ['id' => $link->id]) }}">
                            <i class="bi bi-pencil-square"></i>
                            {{ __('interface.common.edit') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="link-show-list">
                        <div class="link-show-item">
                            <div class="link-show-label">ID</div>
                            <div class="fw-semibold">{{ $link->id }}</div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">{{ __('interface.common.category') }}</div>
                            <div class="fw-semibold">{{ $link->catalog->name ?? __('interface.common.misc') }}</div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">{{ __('interface.common.status') }}</div>
                            <div>
                                <span class="badge {{ $statusColor }}">{{ $statusLabel }}</span>
                                <span class="text-secondary small ms-1">{{ $statusCode }}</span>
                            </div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">{{ __('interface.common.views') }}</div>
                            <div class="fw-semibold">{{ number_format((int) $link->views, 0, '.', ' ') }}</div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">{{ __('interface.common.created_at') }}</div>
                            <div>{{ optional($link->created_at)->format('Y-m-d H:i:s') ?? $empty }}</div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">{{ __('interface.common.updated_at') }}</div>
                            <div>{{ optional($link->updated_at)->format('Y-m-d H:i:s') ?? $empty }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <div class="link-show-label">URL</div>
                        <a class="link-show-url" href="{{ $externalUrl }}" target="_blank" rel="noopener noreferrer">
                            {{ $link->url }}
                        </a>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <div class="link-show-label">{{ __('interface.common.phone') }}</div>
                            <div>{{ $link->phone ?: $empty }}</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="link-show-label">Email</div>
                            <div>{{ $link->email ?: $empty }}</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="link-show-label">{{ __('interface.common.city') }}</div>
                            <div>{{ $link->city ?: $empty }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="link-show-label mb-2">{{ __('interface.common.description') }}</div>
                        <div class="link-show-text">{{ $link->description ?: $empty }}</div>
                    </div>

                    <div class="mb-4">
                        <div class="link-show-label mb-2">{{ __('interface.common.full_description') }}</div>
                        <div class="link-show-text">{{ $link->full_description ?: $empty }}</div>
                    </div>

                    @php
                        $storedCustomValues = $link->custom_fields ?? [];
                        $shownCustomFields = collect($customFields ?? [])->filter(fn ($field) => array_key_exists($field->id, $storedCustomValues) && $storedCustomValues[$field->id] !== null && $storedCustomValues[$field->id] !== '' && is_scalar($storedCustomValues[$field->id]));
                    @endphp
                    @if($shownCustomFields->isNotEmpty())
                        <section class="mb-4" aria-labelledby="admin-custom-details-heading">
                            <h4 class="fs-6 fw-semibold" id="admin-custom-details-heading">{{ __('submission.additional_details') }}</h4>
                            <dl class="link-show-list mb-0">
                                @foreach($shownCustomFields as $field)
                                    <div class="link-show-item">
                                        <dt class="link-show-label">{{ $field->label }}</dt>
                                        <dd class="link-show-text mb-0">{{ $field->type === 'checkbox' ? (in_array($storedCustomValues[$field->id], [true, 1, '1', 'true', 'on'], true) ? __('submission.yes') : __('submission.no')) : $storedCustomValues[$field->id] }}</dd>
                                        @if(!$field->is_active)<p class="small text-secondary mb-0 mt-1">{{ __('submission.field_inactive') }}</p>@endif
                                    </div>
                                @endforeach
                            </dl>
                        </section>
                    @endif

                    <div>
                        <div class="link-show-label mb-2">{{ __('interface.common.keywords') }}</div>
                        <div class="link-show-text">{{ $link->keywords ?: $empty }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.links.screenshot') }}</h3>
                </div>
                <div class="card-body">
                    @if ($link->image && is_file(public_path('uploads/url/' . $link->image)))
                        <img class="img-fluid rounded border" src="{{ url('uploads/url/' . $link->image) }}" alt="{{ __('submission.screenshot_of', ['name' => $link->name]) }}" loading="lazy" decoding="async">
                        <div class="small text-secondary mt-2 link-show-url">{{ $link->image }}</div>
                    @else
                        <div class="text-secondary">{{ __('interface.admin.links.screenshot_missing') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
