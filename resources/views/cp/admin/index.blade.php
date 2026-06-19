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
                                <a href="{{ URL::route('cp.admin.create') }}"
                                   class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-plus-lg"></i>
                                    {{ __('interface.common.add') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="itemList" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>{{ __('interface.common.name') }}</th>
                                <th>{{ __('interface.common.login') }}</th>
                                <th>{{ __('interface.common.created_at') }}</th>
                                <th width="20px">{{ __('interface.common.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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

    <script>

        $(document).ready(function () {

            pageSetUp();

            /* // DOM Position key index //

            l - Length changing (dropdown)
            f - Filtering input (search)
            t - The Table! (datatable)
            i - Information (records)
            p - Pagination (paging)
            r - pRocessing
            < and > - div elements
            <"#id" and > - div with an id
            <"class" and > - div with a class
            <"#id.class" and > - div with an id and class

            Also see: http://legacy.datatables.net/usage/features
            */

            /* BASIC ;*/
            var responsiveHelper_dt_basic = undefined;

            var breakpointDefinition = {
                tablet: 1024,
            };

            $('#itemList').dataTable({
                "sDom": "flrtip",
                "autoWidth": true,
                "oLanguage": window.AdminI18n.datatableLegacy,
                "preDrawCallback": function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_dt_basic) {
                        responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#itemList'), breakpointDefinition);
                    }
                },
                "rowCallback": function (nRow) {
                    responsiveHelper_dt_basic.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_dt_basic.respond();
                },
                'createdRow': function (row, data, dataIndex) {
                    $(row).attr('id', 'rowid_' + data['id']);
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::route('cp.datatable.admin') }}'
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'login', name: 'login'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

            $('#itemList').on('click', 'a.deleteRow', function () {

                var rowid = $(this).attr('id');
                var deleteUrl = $(this).data('delete-url');
                swal({
                        title: window.AdminI18n.confirm.title,
                        text: window.AdminI18n.confirm.text,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: window.AdminI18n.confirm.confirm,
                        cancelButtonText: window.AdminI18n.confirm.cancel,
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (!isConfirm) return;
                        $.ajax({
                            url: deleteUrl,
                            type: "DELETE",
                            dataType: "html",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function () {
                                $("#rowid_" + rowid).remove();
                                swal(window.AdminI18n.confirm.done_title, window.AdminI18n.confirm.delete_success, "success");
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                swal(window.AdminI18n.confirm.delete_error_title, window.AdminI18n.confirm.delete_error_text, "error");
                            }
                        });
                    });
            });

        })

    </script>

@endsection
