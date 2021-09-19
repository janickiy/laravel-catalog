<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @for($i=1; $i < $l + 1; $i++)
        <sitemap>
            <loc>{{ URL::route('maplinks', ['page' => $i]) }}</loc>
            <lastmod>{{ date('Y-m-d\TH:i:s') }}</lastmod>
        </sitemap>
    @endfor
    @for($i=1; $i < $c + 1; $i++)
        <sitemap>
            <loc>{{ URL::route('mapcatalogs', ['page' => $i]) }}</loc>
            <lastmod>{{ date('Y-m-d\TH:i:s') }}</lastmod>
        </sitemap>
    @endfor
</sitemapindex>
