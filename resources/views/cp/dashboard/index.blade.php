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
                    <div class="row">
                        <div class="text">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="well text-center connect">
                                    <i class="fa fa-link fa-3x"></i>
                                    <h5><strong>{{ $new }}</strong></h5>
                                    <span class="font-md">Ссылки ожидающие проверку</span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="well text-center connect">
                                    <i class="fa fa-link fa-3x"></i>
                                    <h5><strong>{{ $publish }}</strong></h5>
                                    <span class="font-md">Опубликовано ссылок</span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="well text-center connect">
                                    <i class="fa fa-link fa-3x"></i>
                                    <h5><strong>{{ $black }}</strong></h5>
                                    <span class="font-md">В черном списке</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->

    </div>

@endsection

@section('js')


@endsection
