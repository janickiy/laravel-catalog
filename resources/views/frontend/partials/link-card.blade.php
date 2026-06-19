@php
    $hasImage = $link->image && file_exists(public_path('/uploads/url/') . '/' . $link->image);
    $imageUrl = $hasImage ? url('/uploads/url/' . $link->image) : url('/img/site-placeholder.svg');
@endphp

<article class="link-card">
    <a class="link-card__media" href="{{ \App\Helpers\StringHelper::urlWithPrefix($link->url) }}" target="_blank" rel="noopener nofollow">
        <img src="{{ $imageUrl }}" alt="{{ $hasImage ? $link->name : __('interface.frontend.no_site_image') }}" loading="lazy" decoding="async">
    </a>

    <div class="link-card__body">
        <h3 class="link-card__title">
            <a href="{{ URL::route('info', ['id' => $link->id]) }}">{{ $link->name }}</a>
        </h3>

        @if ($link->description)
            <div class="link-card__description">{{ $link->description }}</div>
        @endif

        <div class="link-card__footer">
            <span class="link-card__date">
                <i class="bi bi-calendar3"></i>
                {{ \App\Helpers\StringHelper::mysql_localized_date($link->created_at) }}
            </span>

            <a class="link-card__more" href="{{ URL::route('info', ['id' => $link->id]) }}">
                {{ __('interface.frontend.more') }}
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</article>
