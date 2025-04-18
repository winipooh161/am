<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @foreach ($news as $newsItem)
    <url>
        <loc>{{ route('news.show', $newsItem->slug) }}</loc>
        <lastmod>{{ $newsItem->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($newsItem->image_url)
        <image:image>
            <image:loc>{{ asset('uploads/' . $newsItem->image_url) }}</image:loc>
            <image:title>{{ htmlspecialchars($newsItem->title) }}</image:title>
        </image:image>
        @endif
        <news:news>
            <news:publication>
                <news:name>{{ config('app.name') }}</news:name>
                <news:language>ru</news:language>
            </news:publication>
            <news:publication_date>{{ $newsItem->created_at->toAtomString() }}</news:publication_date>
            <news:title>{{ htmlspecialchars($newsItem->title) }}</news:title>
            @if($newsItem->short_description)
            <news:keywords>кулинария, рецепты, новости</news:keywords>
            @endif
        </news:news>
    </url>
    @endforeach
</urlset>
