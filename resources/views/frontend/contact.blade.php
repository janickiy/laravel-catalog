@extends('layouts.frontend')

@section('title', $title)
@section('description', '')
@section('keywords', '')


@section('css')

@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 bg-white rounded box-shadow" style="margin:10px">

            <h1>{{ $title }}</h1>

            <p>*-обязательные поля</p>

            {!! Form::open(['url' =>  URL::route('sendmsg'), 'method' => 'post', 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                {!! Form::label('name', 'Ваше имя*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::text('name', old('name', null), ['class' => 'form-control', 'placeholder'=>'Ваше имя']) !!}

                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('email', 'Email*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::text('email', old('email', null), ['class' => 'form-control', 'placeholder'=>'Email']) !!}

                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>

            </div>

            <div class="form-group">
                {!! Form::label('message', 'Сообщение*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('message', old('message', null), ['placeholder' =>'Ваше сообщение','class' => 'form-control', 'rows' => 3]) !!}

                    @if ($errors->has('message'))
                        <span class="text-danger">{{ $errors->first('message') }}</span>
                    @endif

                </div>
            </div>

            <div class="form-group">
                {!! Form::label('captcha', 'Защитный код*', ['class'=> 'control-label col-sm-2']) !!}
                <div class="col-sm-10">

                    {!! Form::text('captcha', null, ['class' => 'form-control', 'placeholder'=>'Название', 'id' => 'captcha']) !!}

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
                    {!! Form::submit( 'Отправить', ['class'=>'btn btn-success']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('js')

@endsection
