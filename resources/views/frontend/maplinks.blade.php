<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($links as $link)
        <url>
            <loc>{{ URL::route('info', ['id' => $link->id]) }}</loc>
            <lastmod>{{ date_format($link->updated_at, 'Y-m-d\TH:i:s') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>1</priority>
        </url>
    @endforeach
</urlset>
