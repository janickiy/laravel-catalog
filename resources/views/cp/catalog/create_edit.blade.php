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

                        {!! Form::open(['url' => isset($row) ? URL::route('cp.catalog.update') : URL::route('cp.catalog.store'), 'method' => isset($row) ? 'put' : 'post', 'files' => true, 'class' => "smart-form"]) !!}

                        {!! isset($row) ? Form::hidden('id', $row->id) : '' !!}

                        {!! isset($parent_id) ? Form::hidden('parent_id', $parent_id) : '' !!}

                        {!! Form::hidden('pic', isset($row) && $row->image ? $row->image : 'NULL') !!}

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

                                {!! Form::label('description', 'Описание', ['class' => 'label']) !!}

                                <label class="textarea textarea-resizable">

                                    {!! Form::textarea('description', old('description', isset($row) ? $row->description : null), ['placeholder' => 'Описание', 'class' => 'custom-scroll', 'rows' => 3]) !!}

                                </label>

                                @if ($errors->has('description'))
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('keywords', 'Ключевые слова', ['class' => 'label']) !!}

                                <label class="textarea textarea-resizable">

                                    {!! Form::textarea('keywords', old('keywords', isset($row) ? $row->keywords : null), ['class' => 'custom-scroll', 'rows' => 2]) !!}

                                </label>

                                @if ($errors->has('keywords'))
                                    <p class="text-danger">{{ $errors->first('keywords') }}</p>
                                @endif

                            </section>

                            <section>

                                {!! Form::label('image', 'Иконка (jpg,gif,png)', ['class' => 'label']) !!}

                                <div class="input input-file">
                                    <span class="button">

                                        {!! Form::file('image',  ['id' => 'image', 'onchange' => "this.parentNode.nextSibling.value = this.value"]) !!} Обзор...

                                    </span><input type="text" placeholder="выберите файл" readonly="">

                                    <br>
                                    @if (isset($row) && !empty($row->image))
                                        <img src='{{ url("uploads/catalog/$row->image") }}' width="80"
                                             height="80">
                                    @endif

                                </div>

                                @if ($errors->has('image'))
                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                @endif

                                <div class="note">
                                    Максимальный размер: <strong>{{ $maxUploadFileSize }}</strong>
                                </div>

                            </section>

                            @if (($parent_id == 0 && !isset($row)) || isset($row))

                            <section>

                                {!! Form::label('parent_id',  "Раздел", ['class' => 'label']) !!}

                                <label class="input">

                                    {!! Form::select('parent_id', $options, old('parent_id', isset($row) ? $row->parent_id : 0), ['class' => 'form-control custom-scroll']) !!}

                                </label>

                                @if ($errors->has('parent_id'))
                                    <p class="text-danger">{{ $errors->first('parent_id') }}</p>
                                @endif

                            </section>

                            @endif

                        </fieldset>

                        <footer>
                            <button type="submit" class="btn btn-primary button-apply">
                                {{ isset($row) ? 'Изменить' : 'Добавить' }}
                            </button>
                            <a class="btn btn-default" href="{{ URL::route('cp.catalog.index') }}">
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
