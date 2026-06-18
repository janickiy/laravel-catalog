@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .catalog-form-section + .catalog-form-section {
            border-top: 1px solid var(--bs-border-color);
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }

        .catalog-state-icon {
            height: 3rem;
            width: 3rem;
        }

        .catalog-upload {
            border: 1px dashed var(--bs-border-color);
            border-radius: .5rem;
            padding: 1rem;
        }
    </style>
@endsection

@section('content')
    @php
        $selectedParentId = old('parent_id', isset($row) ? ($row->parent_id ?? 0) : ($parent_id ?? 0));
        $showParentSelect = (($parent_id ?? 0) == 0 && !isset($row)) || isset($row);
        $parentLabel = !$showParentSelect && isset($parent_id)
            ? ($options[$parent_id] ?? 'ID: ' . $parent_id)
            : null;
        $currentImage = isset($row) && !empty($row->image) ? $row->image : null;
    @endphp

    {!! Form::open([
        'url' => isset($row) ? URL::route('cp.catalog.update') : URL::route('cp.catalog.store'),
        'method' => isset($row) ? 'put' : 'post',
        'files' => true,
    ]) !!}

        {!! isset($row) ? Form::hidden('id', $row->id) : '' !!}
        {!! ! $showParentSelect && isset($parent_id) ? Form::hidden('parent_id', $parent_id) : '' !!}
        {!! Form::hidden('pic', $currentImage ?: 'NULL') !!}

        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">{{ isset($row) ? 'Редактирование категории' : 'Добавление категории' }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="catalog-form-section">
                            <h4 class="fs-6 fw-semibold mb-3">Основные данные</h4>

                            <div class="row g-3">
                                <div class="col-12">
                                    {!! Form::label('name', 'Имя*', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::text('name', old('name', isset($row) ? $row->name : ''), [
                                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                @if ($showParentSelect)
                                    <div class="col-12">
                                        {!! Form::label('parent_id', 'Раздел', ['class' => 'form-label fw-semibold']) !!}
                                        {!! Form::select('parent_id', $options, $selectedParentId, [
                                            'id' => 'parent_id',
                                            'class' => 'form-select' . ($errors->has('parent_id') ? ' is-invalid' : ''),
                                        ]) !!}

                                        @if ($errors->has('parent_id'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('parent_id') }}</div>
                                        @endif
                                    </div>
                                @elseif ($parentLabel)
                                    <div class="col-12">
                                        <div class="form-label fw-semibold mb-1">Родительский раздел</div>
                                        <div class="form-control-plaintext">{{ $parentLabel }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="catalog-form-section">
                            <h4 class="fs-6 fw-semibold mb-3">Описание</h4>

                            <div class="row g-3">
                                <div class="col-12">
                                    {!! Form::label('description', 'Описание', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::textarea('description', old('description', isset($row) ? $row->description : null), [
                                        'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                                        'placeholder' => 'Описание',
                                        'rows' => 4,
                                    ]) !!}

                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    {!! Form::label('keywords', 'Ключевые слова', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::textarea('keywords', old('keywords', isset($row) ? $row->keywords : null), [
                                        'class' => 'form-control' . ($errors->has('keywords') ? ' is-invalid' : ''),
                                        'rows' => 3,
                                    ]) !!}

                                    @if ($errors->has('keywords'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('keywords') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="catalog-form-section">
                            <h4 class="fs-6 fw-semibold mb-3">Иконка</h4>

                            <div class="catalog-upload bg-body-tertiary">
                                <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center">
                                    <span class="catalog-state-icon d-inline-flex align-items-center justify-content-center rounded text-bg-primary">
                                        <i class="bi bi-image fs-4"></i>
                                    </span>

                                    <div class="flex-fill">
                                        {!! Form::label('image', 'Файл иконки', ['class' => 'form-label fw-semibold']) !!}
                                        {!! Form::file('image', [
                                            'id' => 'image',
                                            'class' => 'form-control' . ($errors->has('image') ? ' is-invalid' : ''),
                                            'accept' => '.jpg,.jpeg,.gif,.png,image/jpeg,image/gif,image/png',
                                        ]) !!}

                                        @if ($errors->has('image'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('image') }}</div>
                                        @endif

                                        <div class="form-text">JPG, GIF или PNG. Максимальный размер: {{ $maxUploadFileSize }}.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex flex-column flex-sm-row gap-2 justify-content-between">
                        <a class="btn btn-outline-secondary" href="{{ URL::route('cp.catalog.index') }}">
                            <i class="bi bi-arrow-left"></i>
                            Назад
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            {{ isset($row) ? 'Изменить' : 'Добавить' }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Состояние</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="catalog-state-icon d-inline-flex align-items-center justify-content-center rounded text-bg-primary">
                                <i class="bi bi-folder fs-4"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">{{ isset($row) ? 'Категория существует' : 'Новая категория' }}</div>
                                <div class="text-secondary small">
                                    {{ isset($row) ? 'ID: ' . $row->id : 'Будет создана после сохранения' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-secondary small mb-2">Родительский раздел</div>
                            <div class="fw-semibold">
                                @if ($showParentSelect)
                                    {{ $options[$selectedParentId] ?? 'Выберите' }}
                                @else
                                    {{ $parentLabel ?? 'Выберите' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Текущая иконка</h3>
                    </div>
                    <div class="card-body">
                        @if ($currentImage)
                            <img class="img-fluid rounded border" src="{{ url('uploads/catalog/' . $currentImage) }}" alt="">
                            <div class="small text-secondary mt-2 text-break">{{ $currentImage }}</div>
                        @else
                            <div class="text-secondary">Иконка не загружена</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    {!! Form::close() !!}
@endsection

@section('js')
@endsection
