<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
</head>
<body>
<h1>
    {{ __('mail.feedback_title') }}
</h1>

<p>{{ __('mail.name') }}: {{ $data->name }}</p>
<p>Email: {{ $data->email }}</p>
<p>{{ __('mail.message') }}: {{ $data->message }}</p>

</p>
</body>
</html>
