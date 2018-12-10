@php echo '<?xml version="1.0" encoding="UTF-8"?>';  @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($items as $item)
        @if(($item->type == 'mc' && $bot) || $item->type == 'c')
            <url>
                <loc>{{ url($item->slug) }}</loc>
                <lastmod>{{ date('Y-m-d') }}</lastmod>
                <changefreq>daily</changefreq>
                <priority>0.8</priority>
            </url>
        @endif
    @endforeach
</urlset>