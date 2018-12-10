@php echo '<?xml version="1.0" encoding="UTF-8"?>';  @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($items as $item)
        <url>
            <loc>{{ url('product', $item->id) }}</loc>
            <lastmod>{{ date('Y-m-d', strtotime($item->updated_at)) }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>