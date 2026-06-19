<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
</head>
<body>
<h1>
    {{ __('mail.new_link_title') }} {{ $data->status === \App\Enums\LinkStatus::Pending->code() ? __('mail.new_link_pending') : '' }}
</h1>

<p>{{ __('mail.site_name') }}: {{ $data->name }}</p>
<p>URL: {{ $data->url }}</p>
<p>{{ __('mail.category') }}: {{ $data->catalog->name }}</p>
<p>{{ __('mail.description') }}: {{ $data->description }}</p>
<p>{{ __('mail.full_description') }}: {{ $data->full_description }}</p>
@if(isset($data->email) && $data->email)<p>Email: {{ $data->email }}</p>@endif
@if(isset($data->phone) && $data->phone)<p>{{ __('mail.phone') }}: {{ $data->phone }}</p>@endif

</p>
</body>
</html>
