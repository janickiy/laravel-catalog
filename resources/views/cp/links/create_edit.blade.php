@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .link-form-section + .link-form-section {
            border-top: 1px solid var(--bs-border-color);
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }

        .link-state-icon {
            height: 3rem;
            width: 3rem;
        }
    </style>
@endsection

@section('content')
    {!! Form::open([
        'url' => isset($row) ? URL::route('cp.links.update') : URL::route('cp.links.store'),
        'method' => isset($row) ? 'put' : 'post',
    ]) !!}

        {!! isset($row) ? Form::hidden('id', $row->id) : '' !!}

        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title mb-0">{{ isset($row) ? 'Редактирование ссылки' : 'Добавление ссылки' }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="link-form-section">
                            <h4 class="fs-6 fw-semibold mb-3">Основные данные</h4>

                            <div class="row g-3">
                                <div class="col-12 col-lg-7">
                                    {!! Form::label('name', 'Имя*', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::text('name', old('name', isset($row) ? $row->name : ''), [
                                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                <div class="col-12 col-lg-5">
                                    {!! Form::label('catalog_id', 'Категория*', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::select('catalog_id', $options, old('catalog_id', isset($row) ? $row->catalog_id : null), [
                                        'placeholder' => 'Выберите',
                                        'class' => 'form-select' . ($errors->has('catalog_id') ? ' is-invalid' : ''),
                                    ]) !!}

                                    @if ($errors->has('catalog_id'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('catalog_id') }}</div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    {!! Form::label('url', 'URL*', ['class' => 'form-label fw-semibold']) !!}
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                        {!! Form::text('url', old('url', isset($row) ? $row->url : ''), [
                                            'class' => 'form-control' . ($errors->has('url') ? ' is-invalid' : ''),
                                            'autocomplete' => 'off',
                                        ]) !!}
                                    </div>

                                    @if ($errors->has('url'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('url') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="link-form-section">
                            <h4 class="fs-6 fw-semibold mb-3">Контакты</h4>

                            <div class="row g-3">
                                <div class="col-12 col-lg-4">
                                    {!! Form::label('phone', 'Телефон', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::text('phone', old('phone', isset($row) ? $row->phone : ''), [
                                        'class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''),
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    @if ($errors->has('phone'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                                    @endif
                                </div>

                                <div class="col-12 col-lg-4">
                                    {!! Form::label('email', 'Email', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::text('email', old('email', isset($row) ? $row->email : ''), [
                                        'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                                <div class="col-12 col-lg-4">
                                    {!! Form::label('city', 'Город', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::text('city', old('city', isset($row) ? $row->city : ''), [
                                        'class' => 'form-control' . ($errors->has('city') ? ' is-invalid' : ''),
                                        'autocomplete' => 'off',
                                    ]) !!}

                                    @if ($errors->has('city'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="link-form-section">
                            <h4 class="fs-6 fw-semibold mb-3">Описание</h4>

                            <div class="row g-3">
                                <div class="col-12">
                                    {!! Form::label('description', 'Описание*', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::textarea('description', old('description', isset($row) ? $row->description : null), [
                                        'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                                        'rows' => 3,
                                    ]) !!}

                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    {!! Form::label('full_description', 'Полное описание*', ['class' => 'form-label fw-semibold']) !!}
                                    {!! Form::textarea('full_description', old('full_description', isset($row) ? $row->full_description : null), [
                                        'class' => 'form-control' . ($errors->has('full_description') ? ' is-invalid' : ''),
                                        'rows' => 5,
                                    ]) !!}

                                    @if ($errors->has('full_description'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('full_description') }}</div>
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
                    </div>

                    <div class="card-footer d-flex flex-column flex-sm-row gap-2 justify-content-between">
                        <a class="btn btn-outline-secondary" href="{{ URL::route('cp.links.index') }}">
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
                            <span class="link-state-icon d-inline-flex align-items-center justify-content-center rounded text-bg-primary">
                                <i class="bi bi-link-45deg fs-4"></i>
                            </span>
                            <div>
                                <div class="fw-semibold">{{ isset($row) ? 'Запись существует' : 'Новая запись' }}</div>
                                <div class="text-secondary small">
                                    {{ isset($row) ? 'ID: ' . $row->id : 'Будет опубликована после сохранения' }}
                                </div>
                            </div>
                        </div>

                        @if (isset($row) && !empty($row->image))
                            <div class="mt-4">
                                <div class="text-secondary small mb-2">Снимок сайта</div>
                                <img class="img-fluid rounded border" src="{{ url('uploads/url/' . $row->image) }}" alt="">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    {!! Form::close() !!}
@endsection

@section('js')
@endsection
