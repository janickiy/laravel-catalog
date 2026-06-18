@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .export-choice-grid {
            display: grid;
            gap: .75rem;
            grid-template-columns: repeat(auto-fit, minmax(11rem, 1fr));
        }

        .export-choice {
            border: 1px solid var(--bs-border-color);
            border-radius: .5rem;
            cursor: pointer;
            display: flex;
            gap: .75rem;
            min-height: 4.5rem;
            padding: 1rem;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .btn-check:checked + .export-choice {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 .2rem rgba(var(--bs-primary-rgb), .15);
        }

        .export-choice-icon {
            align-items: center;
            border-radius: .5rem;
            display: inline-flex;
            flex: 0 0 2.5rem;
            height: 2.5rem;
            justify-content: center;
            width: 2.5rem;
        }

        .export-meta {
            display: grid;
            gap: .75rem;
        }
    </style>
@endsection

@section('content')
    @php
        $exportType = old('export_type', 'text');
        $compress = old('compress', 'none');
    @endphp

    <div class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">Экспорт ссылок</h3>
                </div>

                {!! Form::open(['url' => URL::route('cp.links.export_link'), 'method' => 'post']) !!}
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-file-earmark-arrow-down text-primary"></i>
                                {!! Form::label('export_type_text', 'Формат файла', ['class' => 'form-label fw-semibold mb-0']) !!}
                            </div>

                            <div class="export-choice-grid">
                                <div>
                                    {{ Form::radio('export_type', 'text', $exportType === 'text', [
                                        'class' => 'btn-check',
                                        'id' => 'export_type_text',
                                    ]) }}
                                    <label class="export-choice" for="export_type_text">
                                        <span class="export-choice-icon text-bg-primary">
                                            <i class="bi bi-filetype-txt fs-5"></i>
                                        </span>
                                        <span>
                                            <span class="d-block fw-semibold">Текст</span>
                                            <span class="text-secondary small">TXT</span>
                                        </span>
                                    </label>
                                </div>

                                <div>
                                    {{ Form::radio('export_type', 'excel', $exportType === 'excel', [
                                        'class' => 'btn-check',
                                        'id' => 'export_type_excel',
                                    ]) }}
                                    <label class="export-choice" for="export_type_excel">
                                        <span class="export-choice-icon text-bg-success">
                                            <i class="bi bi-file-earmark-spreadsheet fs-5"></i>
                                        </span>
                                        <span>
                                            <span class="d-block fw-semibold">MS Excel</span>
                                            <span class="text-secondary small">XLSX</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            @if ($errors->has('export_type'))
                                <div class="text-danger small mt-2">{{ $errors->first('export_type') }}</div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-file-zip text-primary"></i>
                                {!! Form::label('compress_none', 'Архивирование', ['class' => 'form-label fw-semibold mb-0']) !!}
                            </div>

                            <div class="export-choice-grid">
                                <div>
                                    {{ Form::radio('compress', 'none', $compress === 'none', [
                                        'class' => 'btn-check',
                                        'id' => 'compress_none',
                                    ]) }}
                                    <label class="export-choice" for="compress_none">
                                        <span class="export-choice-icon text-bg-secondary">
                                            <i class="bi bi-file-earmark fs-5"></i>
                                        </span>
                                        <span>
                                            <span class="d-block fw-semibold">Без архива</span>
                                            <span class="text-secondary small">Обычный файл</span>
                                        </span>
                                    </label>
                                </div>

                                <div>
                                    {{ Form::radio('compress', 'zip', $compress === 'zip', [
                                        'class' => 'btn-check',
                                        'id' => 'compress_zip',
                                    ]) }}
                                    <label class="export-choice" for="compress_zip">
                                        <span class="export-choice-icon text-bg-warning">
                                            <i class="bi bi-file-zip fs-5"></i>
                                        </span>
                                        <span>
                                            <span class="d-block fw-semibold">ZIP</span>
                                            <span class="text-secondary small">Архив</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            @if ($errors->has('compress'))
                                <div class="text-danger small mt-2">{{ $errors->first('compress') }}</div>
                            @endif
                        </div>

                        <div>
                            {!! Form::label('catalog_id', 'Категория', ['class' => 'form-label fw-semibold']) !!}
                            {!! Form::select('catalog_id', $options, old('catalog_id'), [
                                'id' => 'catalog_id',
                                'placeholder' => 'Все категории',
                                'class' => 'form-select' . ($errors->has('catalog_id') ? ' is-invalid' : ''),
                            ]) !!}

                            @if ($errors->has('catalog_id'))
                                <div class="invalid-feedback d-block">{{ $errors->first('catalog_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer d-flex flex-column flex-sm-row gap-2 justify-content-between">
                        <a class="btn btn-outline-secondary" href="{{ URL::route('cp.links.index') }}">
                            <i class="bi bi-arrow-left"></i>
                            Назад
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-download"></i>
                            Экспортировать
                        </button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">Параметры экспорта</h3>
                </div>
                <div class="card-body export-meta">
                    <div class="d-flex justify-content-between gap-3">
                        <span class="text-secondary">Форматы</span>
                        <span class="fw-semibold text-end">TXT, XLSX</span>
                    </div>
                    <div class="d-flex justify-content-between gap-3">
                        <span class="text-secondary">Архив</span>
                        <span class="fw-semibold text-end">ZIP</span>
                    </div>
                    <div class="d-flex justify-content-between gap-3">
                        <span class="text-secondary">Категория</span>
                        <span class="fw-semibold text-end">Опционально</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
