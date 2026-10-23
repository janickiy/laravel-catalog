@extends('cp.app')

@section('title', $title)

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
                <p class="text-secondary mb-0" style="max-width: 65ch">{{ __('custom_fields.intro') }}</p>
                <a class="btn btn-primary d-inline-flex align-items-center gap-2" href="{{ route('cp.custom-fields.create') }}">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>{{ __('custom_fields.add') }}
                </a>
            </div>
            @if($fields->isEmpty())
                <p class="text-secondary mb-0">{{ __('custom_fields.empty') }}</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead><tr>
                            <th scope="col">{{ __('custom_fields.label') }}</th>
                            <th scope="col">{{ __('custom_fields.type') }}</th>
                            <th scope="col">{{ __('custom_fields.state') }}</th>
                            <th scope="col">{{ __('custom_fields.visibility') }}</th>
                            <th scope="col">{{ __('custom_fields.sort_order') }}</th>
                            <th scope="col" class="text-end">{{ __('custom_fields.actions') }}</th>
                        </tr></thead>
                        <tbody>
                            @foreach($fields as $field)
                                <tr>
                                    <th scope="row">
                                        <a href="{{ route('cp.custom-fields.edit', $field) }}">{{ $field->label }}</a>
                                        @if($field->is_required)<span class="text-secondary small d-block">{{ __('custom_fields.required') }}</span>@endif
                                    </th>
                                    <td>{{ __('custom_fields.types.' . $field->type) }}</td>
                                    <td><span class="badge {{ $field->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ __($field->is_active ? 'custom_fields.active' : 'custom_fields.hidden') }}</span></td>
                                    <td>{{ __($field->is_public ? 'custom_fields.public' : 'custom_fields.private') }}</td>
                                    <td>{{ $field->sort_order }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('cp.custom-fields.edit', $field) }}">{{ __('custom_fields.edit') }}</a>
                                            <form method="post" action="{{ route('cp.custom-fields.destroy', $field) }}" data-delete-custom-field>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('custom_fields.delete') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.querySelectorAll('[data-delete-custom-field]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const message = @json(__('custom_fields.delete_confirmation'));
                if (typeof window.swal !== 'function' || typeof window.Swal === 'undefined') {
                    if (window.confirm(message)) form.submit();
                    return;
                }
                window.swal({
                    title: @json(__('interface.confirm.title')),
                    text: message,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: @json(__('custom_fields.delete')),
                    cancelButtonText: @json(__('interface.confirm.cancel'))
                }, function (confirmed) {
                    if (confirmed) form.submit();
                });
            });
        });
    </script>
@endsection
