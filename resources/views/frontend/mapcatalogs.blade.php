<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($catalogs as $catalog)
        <url>
            <loc>{{ URL::route('index', ['id' => $catalog->id]) }}</loc>
            <lastmod>{{ date_format($catalog->updated_at, 'Y-m-d\TH:i:s') }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
