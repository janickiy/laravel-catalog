@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .feedback-show-list {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(13rem, 1fr));
        }

        .feedback-show-item {
            border-bottom: 1px solid var(--bs-border-color);
            padding-bottom: .85rem;
        }

        .feedback-show-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .feedback-show-label {
            color: var(--bs-secondary-color);
            font-size: .8125rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .feedback-show-text {
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: .5rem;
            overflow-wrap: anywhere;
            padding: 1rem;
            white-space: pre-line;
        }
    </style>
@endsection

@section('content')
    @php($empty = '-')

    <div class="card shadow-sm">
        <div class="card-header d-flex flex-column flex-md-row gap-2 justify-content-between align-items-md-center">
            <h3 class="card-title mb-0">{{ __('interface.admin.feedback_messages.show_title') }}</h3>
            <a class="btn btn-sm btn-outline-secondary" href="{{ URL::route('cp.feedback.index') }}">
                <i class="bi bi-arrow-left"></i>
                {{ __('interface.common.back') }}
            </a>
        </div>

        <div class="card-body">
            <div class="mb-4">
                <div class="feedback-show-label mb-2">{{ __('interface.admin.feedback_messages.message_text') }}</div>
                <div class="feedback-show-text">{{ $feedbackMessage->message ?: $empty }}</div>
            </div>

            <hr>

            <div class="feedback-show-list">
                <div class="feedback-show-item">
                    <div class="feedback-show-label">ID</div>
                    <div class="fw-semibold">{{ $feedbackMessage->id }}</div>
                </div>

                <div class="feedback-show-item">
                    <div class="feedback-show-label">{{ __('interface.common.name') }}</div>
                    <div class="fw-semibold">{{ $feedbackMessage->name ?: $empty }}</div>
                </div>

                <div class="feedback-show-item">
                    <div class="feedback-show-label">Email</div>
                    <div>{{ $feedbackMessage->email ?: $empty }}</div>
                </div>

                <div class="feedback-show-item">
                    <div class="feedback-show-label">IP</div>
                    <div>{{ $feedbackMessage->ip ?: $empty }}</div>
                </div>

                <div class="feedback-show-item">
                    <div class="feedback-show-label">{{ __('interface.common.created_at') }}</div>
                    <div>{{ optional($feedbackMessage->created_at)->format('Y-m-d H:i:s') ?? $empty }}</div>
                </div>

                <div class="feedback-show-item">
                    <div class="feedback-show-label">{{ __('interface.common.updated_at') }}</div>
                    <div>{{ optional($feedbackMessage->updated_at)->format('Y-m-d H:i:s') ?? $empty }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
