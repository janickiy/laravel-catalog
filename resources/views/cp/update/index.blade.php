@extends('cp.app')

@section('title', __('interface.admin.updates.title'))

@section('css')
    <style>
        .update-status-card {
            border: 1px solid var(--bs-border-color);
            border-radius: .5rem;
            padding: 1rem;
        }

        .update-status-card__label {
            color: var(--bs-secondary-color);
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .03em;
            margin-bottom: .35rem;
            text-transform: uppercase;
        }

        .update-status-card__value {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .update-notes {
            color: var(--bs-body-color);
            line-height: 1.6;
        }

        .update-notes :where(ul, ol) {
            margin-bottom: 0;
            padding-left: 1.25rem;
        }

        .update-notes :where(a) {
            font-weight: 700;
        }

        .update-progress-status {
            min-height: 1.5rem;
        }
    </style>
@endsection

@section('content')
    @php
        $statusClass = match (true) {
            ! $update['success'] => 'text-bg-danger',
            $update['available'] => 'text-bg-warning',
            default => 'text-bg-success',
        };

        $statusText = match (true) {
            ! $update['success'] => __('interface.admin.updates.check_failed'),
            $update['available'] => __('interface.admin.updates.available'),
            default => __('interface.admin.updates.not_available'),
        };

        $targetVersion = $update['upgrade_version'] ?: $update['latest_version'] ?: $update['current_version'];
        $canUpdate = $update['success'] && $update['available'] && $update['update_url'];
    @endphp

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h3 class="card-title mb-1">{{ __('interface.admin.updates.title') }}</h3>
                        <p class="text-secondary mb-0">{{ __('interface.admin.updates.subtitle') }}</p>
                    </div>
                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                </div>

                <div class="card-body">
                    @if (! $update['success'])
                        <div class="alert alert-danger">
                            {{ __('interface.admin.updates.cannot_connect') }}
                            @if ($update['error'])
                                <span class="d-block small mt-1">{{ $update['error'] }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="update-status-card h-100">
                                <div class="update-status-card__label">{{ __('interface.admin.updates.current_version') }}</div>
                                <div class="update-status-card__value">{{ $update['current_version'] }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="update-status-card h-100">
                                <div class="update-status-card__label">{{ __('interface.admin.updates.latest_version') }}</div>
                                <div class="update-status-card__value">{{ $targetVersion ?: __('interface.admin.updates.unknown') }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="update-status-card h-100">
                                <div class="update-status-card__label">{{ __('interface.admin.updates.released_at') }}</div>
                                <div class="update-status-card__value">{{ $update['created'] ?: __('interface.admin.updates.unknown') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ __('interface.admin.updates.info_text') }}
                    </div>

                    @if ($update['message'])
                        <div class="mt-4">
                            <h4 class="h5 mb-3">{{ __('interface.admin.updates.release_notes') }}</h4>
                            <div class="update-notes border rounded p-3">
                                {!! $update['message'] !!}
                            </div>
                        </div>
                    @endif
                </div>

                <div id="update-progress" class="card-body border-top d-none">
                    <div class="progress" role="progressbar" aria-label="{{ __('interface.admin.updates.processing') }}" aria-valuemin="0" aria-valuemax="100">
                        <div id="update-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">0%</div>
                    </div>
                    <div id="update-progress-status" class="update-progress-status text-secondary fw-semibold mt-2">
                        {{ __('interface.admin.updates.processing') }}
                    </div>
                </div>

                <div class="card-footer d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <a href="{{ URL::route('cp.dashbaord.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        {{ __('interface.common.back') }}
                    </a>

                    @if ($canUpdate)
                        <button id="update-start" type="button" class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-down me-1"></i>
                            {{ __('interface.admin.updates.run_to', ['version' => $targetVersion]) }}
                        </button>
                    @else
                        <button type="button" class="btn btn-outline-secondary" disabled>
                            <i class="bi bi-check2-circle me-1"></i>
                            {{ $update['success'] ? __('interface.admin.updates.up_to_date') : __('interface.admin.updates.check_failed') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.updates.source') }}</h3>
                </div>
                <div class="card-body">
                    <p class="text-secondary">{{ __('interface.admin.updates.source_description') }}</p>
                    <a href="{{ $update['source_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-up-right me-1"></i>
                        {{ __('interface.admin.updates.open_source') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if ($canUpdate)
        <script>
            const updateButton = document.getElementById('update-start');
            const updateProgress = document.getElementById('update-progress');
            const updateProgressBar = document.getElementById('update-progress-bar');
            const updateProgressStatus = document.getElementById('update-progress-status');
            const updateSteps = @json(array_keys($steps));
            const updateStepLabels = @json(array_map(static fn ($step) => $step['label'], $steps));
            const updateUrl = @json(URL::route('cp.update.run'));
            const csrfToken = @json(csrf_token());

            function setProgress(percent, text) {
                updateProgressBar.style.width = `${percent}%`;
                updateProgressBar.textContent = `${percent}%`;
                updateProgressBar.setAttribute('aria-valuenow', percent);
                updateProgressStatus.textContent = text;
            }

            async function runUpdateStep(step, fallbackLabel) {
                setProgress(updateProgressBar.getAttribute('aria-valuenow') || 0, fallbackLabel);

                const body = new URLSearchParams();
                body.append('step', step);

                const response = await fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body
                });

                const data = await response.json();

                if (!data.result) {
                    throw new Error(data.status || @json(__('interface.admin.updates.failed', ['message' => ''])));
                }

                setProgress(data.progress, data.status);
            }

            updateButton.addEventListener('click', async () => {
                updateButton.disabled = true;
                updateProgress.classList.remove('d-none');
                setProgress(0, @json(__('interface.admin.updates.processing')));

                try {
                    for (const step of updateSteps) {
                        await runUpdateStep(step, updateStepLabels[step]);
                    }

                    await Swal.fire({
                        title: @json(__('interface.admin.updates.title')),
                        text: @json(__('interface.admin.updates.completed', ['version' => $targetVersion])),
                        icon: 'success'
                    });

                    window.location.reload();
                } catch (error) {
                    updateButton.disabled = false;

                    Swal.fire({
                        title: @json(__('interface.admin.updates.title')),
                        text: error.message,
                        icon: 'error'
                    });
                }
            });
        </script>
    @endif
@endsection
