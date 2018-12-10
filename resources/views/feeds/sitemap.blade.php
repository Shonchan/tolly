@php echo '<?xml version="1.0" encoding="UTF-8"?>';  @endphp
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('sitemap_p00.xml') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
    </sitemap>
    @if ($pages > 0)
        @for ($p = 1; $p <= $pages ; $p++)
            <sitemap>
                <loc>{{ url('sitemap_p0'.$p.'.xml') }}</loc>
                <lastmod>{{ date('Y-m-d') }}</lastmod>
            </sitemap>
        @endfor
    @endif
</sitemapindex>
