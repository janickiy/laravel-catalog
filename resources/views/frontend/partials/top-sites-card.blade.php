<section class="top-sites-card" aria-labelledby="top-sites-title">
    <div class="top-sites-card__header">
        <span class="eyebrow">{{ __('interface.frontend.top_sites_eyebrow') }}</span>
        <h2 id="top-sites-title">{{ __('interface.frontend.top_sites') }}</h2>
    </div>

    @if(isset($topLinks) && count($topLinks))
        <ol class="top-sites-list">
            @foreach($topLinks as $topLink)
                <li class="top-sites-list__item">
                    <a class="top-sites-list__link" href="{{ URL::route('info', ['id' => $topLink->id]) }}">
                        {{ $topLink->name }}
                    </a>
                    <span class="top-sites-list__views">
                        <i class="bi bi-eye"></i>
                        {{ number_format((int) $topLink->views, 0, '.', ' ') }}
                    </span>
                </li>
            @endforeach
        </ol>
    @else
        <p class="top-sites-card__empty">{{ __('interface.common.no_links') }}</p>
    @endif
</section>
