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
                            Назад
                        </a>
                        <a class="btn btn-sm btn-primary" href="{{ URL::route('cp.links.edit', ['id' => $link->id]) }}">
                            <i class="bi bi-pencil-square"></i>
                            Редактировать
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
                            <div class="link-show-label">Категория</div>
                            <div class="fw-semibold">{{ $link->catalog->name ?? 'Разное' }}</div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">Статус</div>
                            <div>
                                <span class="badge {{ $statusColor }}">{{ $statusLabel }}</span>
                                <span class="text-secondary small ms-1">{{ $statusCode }}</span>
                            </div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">Просмотры</div>
                            <div class="fw-semibold">{{ number_format((int) $link->views, 0, '.', ' ') }}</div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">Дата создания</div>
                            <div>{{ optional($link->created_at)->format('Y-m-d H:i:s') ?? $empty }}</div>
                        </div>

                        <div class="link-show-item">
                            <div class="link-show-label">Дата обновления</div>
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
                            <div class="link-show-label">Телефон</div>
                            <div>{{ $link->phone ?: $empty }}</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="link-show-label">Email</div>
                            <div>{{ $link->email ?: $empty }}</div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="link-show-label">Город</div>
                            <div>{{ $link->city ?: $empty }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="link-show-label mb-2">Описание</div>
                        <div class="link-show-text">{{ $link->description ?: $empty }}</div>
                    </div>

                    <div class="mb-4">
                        <div class="link-show-label mb-2">Полное описание</div>
                        <div class="link-show-text">{{ $link->full_description ?: $empty }}</div>
                    </div>

                    <div>
                        <div class="link-show-label mb-2">Ключевые слова</div>
                        <div class="link-show-text">{{ $link->keywords ?: $empty }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">Снимок сайта</h3>
                </div>
                <div class="card-body">
                    @if (!empty($link->image))
                        <img class="img-fluid rounded border" src="{{ url('uploads/url/' . $link->image) }}" alt="">
                        <div class="small text-secondary mt-2 link-show-url">{{ $link->image }}</div>
                    @else
                        <div class="text-secondary">Снимок не загружен</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
