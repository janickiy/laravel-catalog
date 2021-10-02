@extends('layouts.frontend')

@section('title', $title)

@section('description', 'Добавлние сайта')

@section('keywords', 'белый каталог сайтов, добавиить url, обавить сайт')

@section('css')

@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12 bg-white rounded box-shadow" style="margin:10px">

            <h1>{{ $title }}</h1>

            @include('layouts.notifications')

            @if(\App\Helpers\SettingsHelpers::getSetting('NOTE') != '')
                <div class="alert alert-info" role="alert">
                    {!! \App\Helpers\SettingsHelpers::getSetting('NOTE') !!}
                </div>
            @endif

            <p>*-обязательные поля</p>

            {!! Form::open(['url' =>  URL::route('add'), 'method' => 'post', 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('name', 'Название*', ['class' => 'control-label col-sm-2']) !!}

                <div class="col-sm-10">

                    {!! Form::text('name', old('name', null), ['class' => 'form-control', 'placeholder' => 'Название']) !!}

                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>

            </div>

            <div class="form-group">
                {!! Form::label('catalog_id', 'Раздел*', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::select('catalog_id', $options, old('catalog_id', null), ['placeholder' => 'Выберите', 'class' => 'form-control']) !!}

                    @if ($errors->has('catalog_id'))
                        <span class="text-danger">{{ $errors->first('catalog_id') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">

                {!! Form::label('url', 'Url адрес сайта*', ['class' => 'control-label col-sm-2']) !!}

                <div class="col-sm-10">
                    {!! Form::text('url', old('url', null), ['class' => 'form-control', 'placeholder' => 'Url адрес сайта']) !!}

                    @if ($errors->has('url'))
                        <span class="text-danger">{{ $errors->first('url') }}</span>
                    @endif
                </div>

            </div>

            <div class="form-group">
                {!! Form::label('phone', 'Телефон', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::text('phone', old('phone', null), ['class' => 'form-control', 'placeholder'=>'Телефон']) !!}

                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('email', 'Email', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::text('email', old('email', null), ['class' => 'form-control', 'placeholder' => 'Email']) !!}

                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Описание (не HTML код)*', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('description', old('description', null), ['placeholder' =>'Описание','class' => 'form-control', 'rows' => 2]) !!}

                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('full_description', 'Полное описание (не HTML код)*', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('full_description', old('full_description', null), ['placeholder' => 'Полное описание','class' => 'form-control', 'rows' => 3]) !!}
                    @if ($errors->has('full_description'))
                        <span class="text-danger">{{ $errors->first('full_description') }}</span>
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('keywords', 'Ключевые слова', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('keywords', old('keywords', null), ['placeholder' =>'Ключевые слова', 'class' => 'form-control', 'rows' => 2]) !!}

                    @if ($errors->has('keywords'))
                        <span class="text-danger">{{ $errors->first('keywords') }}</span>
                    @endif

                </div>
            </div>


            <div class="form-group">
                {!! Form::label('htmlcode_banner', 'HTML код баннера', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">

                    {!! Form::textarea('htmlcode_banner', old('htmlcode_banner', null), ['placeholder' => 'HTML код баннера','class' => 'form-control', 'rows' => 3]) !!}
                    @if ($errors->has('htmlcode_banner'))
                        <span class="text-danger">{{ $errors->first('htmlcode_banner') }}</span>
                    @endif
                </div>

            </div>

            <div class="form-group">
                {!! Form::label('captcha', 'Защитный код*', ['class' => 'control-label col-sm-2']) !!}
                <div class="col-sm-10">

                    {!! Form::text('captcha', null, ['class' => 'form-control', 'placeholder' => 'код', 'id' => 'captcha']) !!}

                    <br>
                    @captcha
                    <br>

                    @if ($errors->has('captcha'))
                        <span class="text-danger">{{ $errors->first('captcha') }}</span>
                    @endif
                </div>

            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label>{!! Form::checkbox('agree', null, null, ['class' => 'checkbox]']) !!} <a
                                    href="{{URL::route('rules')}}" target="_blank">С правилами участия
                                согласен(на)</a></label>
                    </div>

                    @if ($errors->has('agree'))
                        <span class="text-danger">{{ $errors->first('agree') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::submit( 'Добавить', ['class'=>'btn btn-success']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@section('js')


@endsection
