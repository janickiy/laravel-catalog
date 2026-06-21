@extends('cp.app')

@section('title', $title)

@section('css')
    <style>
        .feedback-table-card .card-body {
            overflow: hidden;
        }

        .feedback-table-card table.dataTable {
            margin: 0 !important;
        }

        .feedback-table-card th,
        .feedback-table-card td {
            vertical-align: middle;
        }

        .feedback-message-preview {
            min-width: 18rem;
            white-space: normal;
        }
    </style>
@endsection

@section('content')
    <div class="card feedback-table-card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ __('interface.admin.feedback') }}</h3>
            <div class="text-secondary small">{{ __('interface.admin.feedback_messages.subtitle') }}</div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="itemList" class="table table-striped table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th>{{ __('interface.common.name') }}</th>
                        <th>Email</th>
                        <th>{{ __('interface.common.message') }}</th>
                        <th>IP</th>
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
            $('#itemList').DataTable({
                dom: "<'row g-2 mb-3'<'col-12 col-md-6'l><'col-12 col-md-6'f>>rt<'row g-2 mt-3'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
                autoWidth: false,
                language: window.AdminI18n.datatable,
                order: [[4, 'desc']],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ URL::route('cp.datatable.feedback') }}',
                },
                createdRow: function (row, data) {
                    $(row).attr('id', 'rowid_' + data.id);
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'message', name: 'message', orderable: false, searchable: false, className: 'feedback-message-preview'},
                    {data: 'ip', name: 'ip', className: 'text-nowrap'},
                    {data: 'created_at', name: 'created_at', className: 'text-nowrap'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-nowrap'},
                ],
            });
        });
    </script>
@endsection
