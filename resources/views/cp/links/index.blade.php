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
                <h3 class="card-title mb-0">Ссылки</h3>
                <div class="text-secondary small">Управление ссылками каталога</div>
            </div>

            <div class="links-actions d-flex flex-wrap">
                <a class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"
                   href="{{ URL::route('cp.links.create') }}">
                    <i class="bi bi-plus-lg"></i>
                    Добавить
                </a>
                <a class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1"
                   href="{{ URL::route('cp.links.import') }}">
                    <i class="bi bi-cloud-arrow-up"></i>
                    Импорт
                </a>
                <a class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1"
                   href="{{ URL::route('cp.links.export') }}">
                    <i class="bi bi-download"></i>
                    Экспорт
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
                                <input type="checkbox" class="form-check-input" title="Отметить все/Снять отметку у всех" id="checkAll">
                            </th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Сайт</th>
                            <th>Телефон</th>
                            <th>Каталог</th>
                            <th>Статус</th>
                            <th>Просмотры</th>
                            <th>Дата</th>
                            <th>Действия</th>
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
                            {!! Form::label('select_action', 'Массовое изменение статуса', ['class' => 'form-label fw-semibold']) !!}
                            {!! Form::select('action', $status_list, null, [
                                'class' => 'form-select',
                                'placeholder' => 'Выберите статус',
                                'id' => 'select_action',
                            ]) !!}
                        </div>
                        <div class="col-12 col-md-auto">
                            <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1">
                                <i class="bi bi-check2-circle"></i>
                                Применить
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
                language: {
                    lengthMenu: 'Показывать _MENU_ записей',
                    zeroRecords: 'Ничего не найдено',
                    info: 'Показано с _START_ по _END_ из _TOTAL_ записей',
                    infoEmpty: 'Показано с 0 по 0 из 0 записей',
                    infoFiltered: '(отфильтровано из _MAX_ записей)',
                    search: 'Поиск',
                    processing: 'Загрузка...',
                    paginate: {
                        first: 'Первая',
                        last: 'Последняя',
                        next: 'След.',
                        previous: 'Пред.',
                    },
                },
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
                        title: 'Вы уверены?',
                        text: 'Вы не сможете восстановить эту информацию!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Да',
                        cancelButtonText: 'Отмена',
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
                                swal('Сделано!', 'Данные успешно удалены!', 'success');
                            },
                            error: function () {
                                swal('Ошибка при удалении!', 'Попробуйте еще раз', 'error');
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
