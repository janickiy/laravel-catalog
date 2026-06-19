@if (Session::has('message'))
    <div class="alert alert-warning fade in">
        <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="{{ __('interface.common.close') }}">
            ×
        </button>
        <i class="bi bi-exclamation-triangle"></i>
        {{ Session::get('message') }}.
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success fade in">
        <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="{{ __('interface.common.close') }}">
            ×
        </button>
        <i class="bi bi-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger fade in">
        <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="{{ __('interface.common.close') }}">
            ×
        </button>
        <i class="bi bi-x-circle"></i>
        {{ session('error') }}
    </div>
@endif
