@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .import-upload {
            border: 1px dashed var(--bs-border-color);
            border-radius: .5rem;
            padding: 1.25rem;
        }

        .import-upload-icon {
            align-items: center;
            border-radius: .5rem;
            display: inline-flex;
            height: 3rem;
            justify-content: center;
            width: 3rem;
        }

        .import-meta {
            display: grid;
            gap: .75rem;
        }
    </style>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.links.import_heading') }}</h3>
                </div>

                {!! Form::open(['url' => URL::route('cp.links.importlink'), 'files' => true, 'method' => 'post']) !!}
                    <div class="card-body">
                        <div class="import-upload bg-body-tertiary">
                            <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center">
                                <span class="import-upload-icon text-bg-primary">
                                    <i class="bi bi-file-earmark-arrow-up fs-4"></i>
                                </span>

                                <div class="flex-fill">
                                    {!! Form::label('file', __('interface.admin.links.import_file'), ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::file('file', [
                                        'id' => 'file',
                                        'class' => 'form-control' . ($errors->has('file') ? ' is-invalid' : ''),
                                        'accept' => '.csv,.txt,.xls,.xlsx',
                                    ]) !!}

                                    @if ($errors->has('file'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('file') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex flex-column flex-sm-row gap-2 justify-content-between">
                        <a class="btn btn-outline-secondary" href="{{ URL::route('cp.links.index') }}">
                            <i class="bi bi-arrow-left"></i>
                            {{ __('interface.common.back') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up"></i>
                            {{ __('interface.admin.links.import_submit') }}
                        </button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ __('interface.admin.links.file_parameters') }}</h3>
                </div>
                <div class="card-body import-meta">
                    <div class="d-flex justify-content-between gap-3">
                        <span class="text-secondary">{{ __('interface.common.file_formats') }}</span>
                        <span class="fw-semibold text-end">CSV, TXT, XLS, XLSX</span>
                    </div>
                    <div class="d-flex justify-content-between gap-3">
                        <span class="text-secondary">{{ __('interface.common.max_size') }}</span>
                        <span class="fw-semibold text-end">{{ $maxUploadFileSize }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
