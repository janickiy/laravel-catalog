@extends('layouts.frontend')

@section('title', $title)

@section('description', '')

@section('keywords', '')

@section('content')
    <section class="content-card form-card">
        <div class="section-heading">
            <div>
                <span class="eyebrow">{{ __('interface.frontend.contact_eyebrow') }}</span>
                <h1>{{ $title }}</h1>
                <p>{{ __('interface.frontend.contact_intro') }}</p>
            </div>
        </div>

        @include('layouts.notifications')

        @if (!session('success'))
            <p class="form-help">{{ __('interface.common.required_fields_frontend') }}</p>

            {!! Form::open(['url' =>  URL::route('sendmsg'), 'method' => 'post', 'class' => 'frontend-form']) !!}
                <div class="form-group">
                    {!! Form::label('name', __('interface.frontend.your_name'), ['class'=> 'form-label']) !!}
                    {!! Form::text('name', old('name', null), [
                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                        'placeholder' => __('interface.frontend.your_name_placeholder'),
                    ]) !!}
                    @if ($errors->has('name'))
                        <span class="form-error">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('email', __('interface.common.email') . '*', ['class'=> 'form-label']) !!}
                    {!! Form::text('email', old('email', null), [
                        'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                        'placeholder' => 'mail@example.com',
                    ]) !!}
                    @if ($errors->has('email'))
                        <span class="form-error">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('message', __('interface.frontend.message'), ['class'=> 'form-label']) !!}
                    {!! Form::textarea('message', old('message', null), [
                        'placeholder' => __('interface.frontend.message_placeholder'),
                        'class' => 'form-control' . ($errors->has('message') ? ' is-invalid' : ''),
                        'rows' => 4,
                    ]) !!}
                    @if ($errors->has('message'))
                        <span class="form-error">{{ $errors->first('message') }}</span>
                    @endif
                </div>

                <div class="form-group captcha-block">
                    {!! Form::label('captcha', __('interface.frontend.captcha'), ['class'=> 'form-label']) !!}
                    {!! Form::text('captcha', null, [
                        'class' => 'form-control' . ($errors->has('captcha') ? ' is-invalid' : ''),
                        'placeholder' => __('interface.frontend.captcha_placeholder'),
                        'id' => 'captcha',
                    ]) !!}
                    <div>@captcha</div>
                    @if ($errors->has('captcha'))
                        <span class="form-error">{{ $errors->first('captcha') }}</span>
                    @endif
                </div>

                <div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-send"></i>
                        {{ __('interface.common.submit') }}
                    </button>
                </div>
            {!! Form::close() !!}
        @endif
    </section>
@endsection
