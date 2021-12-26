@extends('layouts.frontend')

@section('title', $title)

@section('description', $description)

@section('keywords', $keywords)

@section('css')

    <style>
        .borderless tr, .borderless td, .borderless th {
            border: none !important;
        }
    </style>

@endsection

@section('content')

    @if(isset($arr) && $arr)
        <div class="row">

            <div class="col-sm-12 bg-white rounded box-shadow">

                <table class="table table-responsive borderless">
                    @for ($i = 0; $i < $number; $i++)
                        <tr>
                            @for ($j = 0; $j < \App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER'); $j++)
                                <td style="vertical-align: top; width: {{ 100/\App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER') }}%">
                                    @if(isset($arr[$i][$j][1]) && isset($arr[$i][$j][0]) && isset($arr[$i][$j][3]))
                                        <table class="table table-responsive borderless">
                                            <tr>
                                                <td style="width: 80px; padding:6px; vertical-align: top;">
                                                    <img style="border: 0; border-width: 0;" width="50px"
                                                         src="{{ isset($arr[$i][$j][2]) && $arr[$i][$j][2] ? url('uploads/catalog/' . $arr[$i][$j][2]) : url('/img/folder.jpg') }}">
                                                </td>
                                                <td style="padding:6px">
                                                    <strong><a href="{{ URL::route('catalog', ['id' => $arr[$i][$j][1] > 0 ? $arr[$i][$j][1] : '']) }}">{{ $arr[$i][$j][0] }}</a></strong>
                                                    @if($arr[$i][$j][1] > 0)
                                                        <br>
                                                        <div class="subcat">

                                                            {!! \App\Models\Catalog::ShowSubCat($arr[$i][$j][1]) !!}

                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </table>

            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12" style="margin-top:10px">{!! isset($pathway) ? $pathway : '' !!} </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- top2 -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-2243538192217050"
                 data-ad-slot="8369734756"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>

        @if(isset($paginator) && $paginator)
            <div class="col-sm-12 col-md-8 col-lg-8">
                {!! $paginator !!}
            </div>
        @endif

        <div style="margin:10px" class="col-sm-12 col-md-8 col-lg-8 bg-white rounded box-shadow">

            <h2 style="padding-bottom: 20px">@if(isset($catalog_name) && $catalog_name) {{ $catalog_name }} @elseНедавно
                добавленные сайты@endif</h2>

            @if($links)

                <table class="table table-responsive table-borderless">

                    @foreach($links as $link)

                        <tr>
                            <td>
                                <table class="table-borderless">
                                    <tr>
                                        <td style="width: 100px" class="margin-15">
                                            <a href="http://{{ $link->url }}" target="_blank">
                                                {!! isset($link->htmlcode_banner) && $link->htmlcode_banner ? $link->htmlcode_banner : '<img border="0" src="'.url('/img/noimage.gif').'">'; !!}
                                            </a>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <h5><strong class="text-info">{{ $link->name }}</strong></h5>

                                            {{ $link->description }}

                                            <p class="text-right">
                                                <a style="margin-bottom: 20px"
                                                   href="{{ URL::route('info',['id' => $link->id]) }}">подробно...</a>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                       <span class="text-muted">
                                            Дата публикации: {{ \App\Helpers\StringHelper::mysql_russian_date($link->created_at) }}
                                        </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    @endforeach
                </table>
            @else
                <p>Нет ссылок</p>
            @endif
        </div>

        <div style="margin:10px" class="col-sm-12 col-md-3 col-lg-3">

            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- 180x150, создано 13.01.09 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:200px;height:200px"
                 data-ad-client="ca-pub-2243538192217050"
                 data-ad-slot="0787053397"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- 180x150, создано 13.01.09 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:200px;height:200px"
                 data-ad-client="ca-pub-2243538192217050"
                 data-ad-slot="0787053397"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- 180x150, создано 13.01.09 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:200px;height:200px"
                 data-ad-client="ca-pub-2243538192217050"
                 data-ad-slot="0787053397"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

        </div>

        @if(isset($paginator) && $paginator)
            <div class="col-sm-12 col-md-8 col-lg-8">
                {!! $paginator !!}
            </div>
        @endif

    </div>

@endsection

@section('js')


@endsection
