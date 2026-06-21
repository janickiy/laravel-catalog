@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .admin-actions {
            gap: .5rem;
        }

        .admin-table-card .card-body {
            overflow: hidden;
        }

        .admin-table-card table.dataTable {
            margin: 0 !important;
        }

        .admin-table-card th,
        .admin-table-card td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="card admin-table-card shadow-sm">
        <div class="card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-center gap-3">
            <div class="admin-actions d-flex flex-wrap justify-content-center">
                <a href="{{ URL::route('cp.admin.create') }}"
                   class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-plus-lg"></i>
                    {{ __('interface.common.add') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="itemList" class="table table-striped table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th>{{ __('interface.common.name') }}</th>
                        <th>{{ __('interface.common.login') }}</th>
                        <th>{{ __('interface.common.created_at') }}</th>
                        <th>{{ __('interface.common.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            var table = $('#itemList').DataTable({
                dom: "<'row g-2 mb-3'<'col-12 col-md-6'l><'col-12 col-md-6'f>>rt<'row g-2 mt-3'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
                autoWidth: false,
                language: window.AdminI18n.datatable,
                order: [[0, 'asc']],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::route('cp.datatable.admin') }}',
                },
                createdRow: function (row, data) {
                    $(row).attr('id', 'rowid_' + data.id);
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'login', name: 'login'},
                    {data: 'created_at', name: 'created_at', className: 'text-nowrap'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-nowrap'},
                ],
            });

            $('#itemList').on('click', 'a.deleteRow', function () {
                var rowid = $(this).attr('id');
                var deleteUrl = $(this).data('delete-url');

                swal({
                        title: window.AdminI18n.confirm.title,
                        text: window.AdminI18n.confirm.text,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: window.AdminI18n.confirm.confirm,
                        cancelButtonText: window.AdminI18n.confirm.cancel,
                        closeOnConfirm: false,
                    },
                    function (isConfirm) {
                        if (!isConfirm) return;

                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            dataType: 'html',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function () {
                                table.row('#rowid_' + rowid).remove().draw(false);
                                swal(window.AdminI18n.confirm.done_title, window.AdminI18n.confirm.delete_success, 'success');
                            },
                            error: function () {
                                swal(window.AdminI18n.confirm.delete_error_title, window.AdminI18n.confirm.delete_error_text, 'error');
                            },
                        });
                    });
            });
        });
    </script>
@endsection
