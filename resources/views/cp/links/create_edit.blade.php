@extends('cp.app')

@section('title', $title)

@section('css')


@endsection

@section('content')

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                <!-- widget options:
                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                data-widget-colorbutton="false"
                data-widget-editbutton="false"
                data-widget-togglebutton="false"
                data-widget-deletebutton="false"
                data-widget-fullscreenbutton="false"
                data-widget-custombutton="false"
                data-widget-collapsed="true"
                data-widget-sortable="false"

                -->

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body">

                        {!! Form::open(['url' => isset($row) ? URL::route('cp.links.update') : URL::route('cp.links.store'), 'method' => isset($row) ? 'put' : 'post', 'class' => "smart-form"]) !!}

                        {!! isset($row) ? Form::hidden('id', $row->id) : '' !!}

                        <header>
                            *-обязательные поля
                        </header>

                        <fieldset>

                            <section>

                                {!! Form::label('name', 'Имя*', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::text('name', old('name', isset($row) ? $row->name : ''), ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                                </label>

                                @if ($errors->has('name'))
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('url', 'URL*', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::text('url', old('url', isset($row) ? $row->url : ''), ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                                </label>

                                @if ($errors->has('url'))
                                    <p class="text-danger">{{ $errors->first('url') }}</p>
                                @endif

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

                            <section>

                                {!! Form::label('url', 'URL*', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::text('url', old('url', isset($row) ? $row->url : ''), ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                                </label>

                                @if ($errors->has('url'))
                                    <p class="text-danger">{{ $errors->first('url') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('phone', 'Телефон', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::text('phone', old('phone', isset($row) ? $row->phone : ''), ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                                </label>

                                @if ($errors->has('phone'))
                                    <p class="text-danger">{{ $errors->first('phone') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('email', 'Email', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::text('email', old('email', isset($row) ? $row->email : ''), ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                                </label>

                                @if ($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('city', 'Город', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::text('city', old('city', isset($row) ? $row->city : ''), ['class' => 'form-control', 'autocomplete' => 'off']) !!}

                                </label>

                                @if ($errors->has('city'))
                                    <p class="text-danger">{{ $errors->first('city') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('description', 'Описание*', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::textarea('description', old('description', isset($row) ? $row->description : null), ['placeholder' =>'Описание','class' => 'form-control', 'rows' => 2]) !!}

                                </label>

                                @if ($errors->has('description'))
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('keywords', 'Ключевые слова', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::textarea('keywords', old('keywords', isset($row) ? $row->keywords : null), ['class' => 'form-control', 'rows' => 2]) !!}

                                </label>

                                @if ($errors->has('keywords'))
                                    <p class="text-danger">{{ $errors->first('keywords') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('full_description', 'Полное описание*', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::textarea('full_description', old('full_description', isset($row) ? $row->full_description : null), ['class' => 'form-control', 'rows' => 2]) !!}

                                </label>

                                @if ($errors->has('full_description'))
                                    <p class="text-danger">{{ $errors->first('full_description') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('htmlcode_banner', 'HTML кода банера', ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::textarea('htmlcode_banner', old('htmlcode_banner', isset($row) ? $row->htmlcode_banner : null), ['class' => 'form-control', 'rows' => 2]) !!}

                                </label>

                                @if ($errors->has('htmlcode_banner'))
                                    <p class="text-danger">{{ $errors->first('htmlcode_banner') }}</p>
                                @endif

                            </section>


                        </fieldset>

                        <footer>
                            <button type="submit" class="btn btn-primary button-apply">
                                {{ isset($row) ? 'Изменить' : 'Добавить' }}
                            </button>
                            <a class="btn btn-default" href="{{ URL::route('cp.links.index') }}">
                                Назад
                            </a>
                        </footer>

                        {!! Form::close() !!}

                    </div>

                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </div>
        </article>
    </div>

@endsection

@section('js')


@endsection
