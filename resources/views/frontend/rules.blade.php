@extends('layouts.frontend')

@section('title', $title)

@section('description', '')

@section('keywords', '')


@section('css')

@endsection

@section('content')
    <article class="content-card article-card rich-text">
        <div class="section-heading">
            <div>
                <span class="eyebrow">{{ __('interface.frontend.rules_eyebrow') }}</span>
                <h1>{{ $title }}</h1>
            </div>
        </div>

        {!! __('interface.frontend.rules_html') !!}
    </article>

@endsection

@section('js')



@endsection
