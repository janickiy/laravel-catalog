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

                    {!! Form::open(['url' => URL::route('cp.links.importlink'), 'files' => true, 'method' => 'post', 'class' => "smart-form"]) !!}

                        <header>
                            *-обязательные поля
                        </header>

                        <fieldset>

                            <section>

                                {!! Form::label('import', 'Файл*', ['class' => 'label']) !!}

                                <div class="input input-file">
                                    <span class="button">

                                        {!! Form::file('file') !!} Выберите

                                    </span><input type="text" placeholder="Выберите файл" readonly="">

                                </div>

                                @if ($errors->has('file'))
                                    <span class="text-danger">{{ $errors->first('file') }}</span>
                                @endif

                            </section>

                            <footer>
                                <button type="submit" class="btn btn-primary">
                                    Импортировать
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
