@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .links-actions {
            gap: .5rem;
        }

        .links-table-card .card-body {
            overflow: hidden;
        }

        .links-table-card table.dataTable {
            margin: 0 !important;
        }

        .links-table-card th,
        .links-table-card td {
            vertical-align: middle;
        }

        .links-bulk-panel {
            max-width: 38rem;
        }
    </style>
@endsection

@section('content')
    <div class="card links-table-card shadow-sm">
        <div class="card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h3 class="card-title mb-0">{{ __('interface.admin.links.title') }}</h3>
                <div class="text-secondary small">{{ __('interface.admin.links.subtitle') }}</div>
            </div>

            <div class="links-actions d-flex flex-wrap">
                <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"
                   href="{{ URL::route('cp.links.create') }}">
                    <i class="bi bi-plus-lg"></i>
                    {{ __('interface.common.add') }}
                </a>
                <a class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1"
                   href="{{ URL::route('cp.links.import') }}">
                    <i class="bi bi-cloud-arrow-up"></i>
                    {{ __('interface.common.import') }}
                </a>
                <a class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1"
                   href="{{ URL::route('cp.links.export') }}">
                    <i class="bi bi-download"></i>
                    {{ __('interface.common.export') }}
                </a>
            </div>
        </div>

        {!! Form::open(['url' => URL::route('cp.statuslinks.update'), 'method' => 'put']) !!}
            <div class="card-body">
                <div class="table-responsive">
                    <table id="itemList" class="table table-striped table-hover align-middle w-100">
                        <thead>
                        <tr>
                            <th class="text-center">
                                <input type="checkbox" class="form-check-input" title="{{ __('interface.admin.links.bulk_status') }}" id="checkAll">
                            </th>
                            <th>{{ __('interface.common.name') }}</th>
                            <th>{{ __('interface.common.description') }}</th>
                            <th>{{ __('interface.common.site') }}</th>
                            <th>{{ __('interface.common.phone') }}</th>
                            <th>{{ __('interface.common.catalog') }}</th>
                            <th>{{ __('interface.common.status') }}</th>
                            <th>{{ __('interface.common.views') }}</th>
                            <th>{{ __('interface.common.date') }}</th>
                            <th>{{ __('interface.common.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-body-tertiary">
                <div class="links-bulk-panel">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-7">
                            {!! Form::label('select_action', __('interface.admin.links.bulk_status'), ['class' => 'form-label fw-semibold']) !!}
                            {!! Form::select('action', $status_list, null, [
                                'class' => 'form-select',
                                'placeholder' => __('interface.admin.links.choose_status'),
                                'id' => 'select_action',
                            ]) !!}
                        </div>
                        <div class="col-12 col-md-auto">
                            <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1">
                                <i class="bi bi-check2-circle"></i>
                                {{ __('interface.admin.links.apply') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            var table = $('#itemList').DataTable({
                dom: "<'row g-2 mb-3'<'col-12 col-md-6'l><'col-12 col-md-6'f>>rt<'row g-2 mt-3'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
                autoWidth: false,
                language: window.AdminI18n.datatable,
                order: [[1, 'asc']],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::route('cp.datatable.links') }}',
                },
                createdRow: function (row, data) {
                    $(row).attr('id', 'rowid_' + data.id);
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description', orderable: false, searchable: false},
                    {data: 'url', name: 'url'},
                    {data: 'phone', name: 'phone'},
                    {data: 'catalog', name: 'catalog.name'},
                    {data: 'status', name: 'status', searchable: false, className: 'text-nowrap'},
                    {data: 'views', name: 'views', searchable: false, className: 'text-end'},
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

            $('#checkAll').on('change', function () {
                $('#itemList input:checkbox').not(this).prop('checked', this.checked);
            });
        });
    </script>
@endsection
