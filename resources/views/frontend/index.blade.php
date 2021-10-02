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

    <div class="row">
        <div class="col-sm-12 bg-white rounded box-shadow" style="margin:10px">
            <table class="table table-responsive borderless">
                @for ($i = 0; $i < $number; $i++)
                    <tr>
                        @for ($j = 0; $j < \App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER'); $j++)
                            <td style="vertical-align: top; width: {{ 100/\App\Helpers\SettingsHelpers::getSetting('COLUMNS_NUMBER') }}%">
                                @if(isset($arr[$i][$j][1]) && isset($arr[$i][$j][0]) && isset($arr[$i][$j][3]))
                                    <table class="table table-responsive borderless">
                                        <tr>
                                            <td style="padding:6px; vertical-align: top;">
                                                <img style="border: 0; border-width: 0;" width="50px"
                                                     src="{{ isset($arr[$i][$j][2]) && $arr[$i][$j][2] ? url('uploads/catalog/' . $arr[$i][$j][2]) : url('/img/folder.gif') }}">
                                            </td>
                                            <td style="padding:6px">
                                                <strong><a href="{{ URL::route('index', ['id' => $arr[$i][$j][1]]) }}">{{ $arr[$i][$j][0] }}</a></strong>
                                                <span>({{ $arr[$i][$j][3] }})</span><br>
                                                <div class="subcat">

                                                    {!! \App\Models\Catalog::ShowSubCat($arr[$i][$j][1]) !!}

                                                </div>
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

    {!! isset($pathway) ? $pathway : '' !!}

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

        <div style="margin:10px" class="col-sm-12 col-md-8 col-lg-8 bg-white rounded box-shadow">

            <div id="logo">
                <div id="logopos" syle="float: right !important;">

                </div>
            </div>

            <h2 style="padding-bottom: 20px">@if(isset($catalog_name) && $catalog_name) {{ $catalog_name }} @else Новые
                сайты@endif</h2>

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
                                            <a style="margin-bottom: 20px" href="{{ URL::route('info',['id' => $link->id]) }}">подробно...</a>
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

        <div style="margin:10px" class="col-sm-12 col-md-8 col-lg-8">

            {!! isset($id) && $id ? $links->links() : '' !!}

        </div>

    </div>

@endsection

@section('js')


@endsection
