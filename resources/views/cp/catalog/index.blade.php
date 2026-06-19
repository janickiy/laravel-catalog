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

                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ URL::route('cp.catalog.create') }}"
                                   class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-plus-lg"></i>
                                    {{ __('interface.common.add') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <br>

                   {!! $catalogTree !!}

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
