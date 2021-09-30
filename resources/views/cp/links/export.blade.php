@extends('cp.app')

@section('title', $title)

@section('css')

@endsection

@section('content')

    <div class="row-fluid">
        <div class="col">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">

                <!-- widget div-->
                <div>

                    {!! Form::open(['url' =>  URL::route('cp.links.export_link'), 'method' => 'post', 'class' => "smart-form"]) !!}

                        <fieldset>

                            <section>

                                {!! Form::label('export_type', 'Формат', ['class' => 'label']) !!}

                                <div class="inline-group">
                                    <label class="radio">

                                        {{ Form::radio('export_type', 'text', true) }}

                                        <i></i>текст
                                    </label>
                                    <label class="radio">

                                        {{ Form::radio('export_type', 'excel', false) }}

                                        <i></i>MS Excel
                                    </label>
                                </div>
                            </section>

                            <section>

                                {!! Form::label('compress', 'Архивировать', ['class' => 'label']) !!}

                                <div class="inline-group">
                                    <label class="radio">

                                        {{ Form::radio('compress', 'none', true) }}

                                        <i></i>нет
                                    </label>
                                    <label class="radio">

                                        {{ Form::radio('compress', 'zip', true) }}

                                        <i></i>zip
                                    </label>
                                </div>
                            </section>

                            <section>

                                {!! Form::label('catalog_id',  "Категория*", ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::select('catalog_id', $options, old('catalog_id', isset($row) ? $row->catalog_id : null), ['placeholder' => 'Выберите', 'class' => 'form-control custom-scroll']) !!}

                                </label>

                                @if ($errors->has('catalog_id'))
                                    <p class="text-danger">{{ $errors->first('catalog_id') }}</p>
                                @endif

                            </section>

                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    Экспортировать
                                </button>
                                <a class="btn btn-default" href="{{ URL::route('cp.links.index') }}">
                                    Назад
                                </a>
                            </footer>

                        </fieldset>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')


@endsection
