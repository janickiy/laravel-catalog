<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
</head>
<body>
<h1>
    В Вашем каталоге добевлен новый сайт {{ $data->status === \App\Enums\LinkStatus::Pending->code() ? 'и ожидает проверку':'' }}
</h1>

<p>Название: {{ $data->name }}</p>
<p>URL: {{ $data->url }}</p>
<p>Категория: {{ $data->catalog->name }}</p>
<p>Описание: {{ $data->description }}</p>
<p>Полное описание: {{ $data->full_description }}</p>
@if(isset($data->email) && $data->email)<p>Email: {{ $data->email }}</p>@endif
@if(isset($data->phone) && $data->phone)<p>Тел: {{ $data->phone }}</p>@endif

</p>
</body>
</html>
