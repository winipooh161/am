@php
// Подготавливаем данные для структурированной разметки видео
$title = $news->video_title ?? $news->title;
$description = $news->video_description ?? $news->short_description ?? '';
$embedUrl = "";
$thumbnailUrl = $news->image_url 
    ? asset('uploads/' . $news->image_url) 
    : asset('images/news-placeholder.jpg');

// Извлекаем URL видео из iframe
if ($news->video_iframe) {
    preg_match('/src="([^"]+)"/', $news->video_iframe, $matches);
    $embedUrl = $matches[1] ?? '';
}

// Определяем платформу видео
$contentUrl = "";
$platform = "";
if ($embedUrl) {
    if (strpos($embedUrl, 'vk.com') !== false) {
        $platform = 'ВКонтакте';
        
        // Получаем видео ID для VK
        if (preg_match('/oid=(-?\d+)&id=(\d+)/', $embedUrl, $idMatches)) {
            $ownerId = $idMatches[1];
            $videoId = $idMatches[2];
            $contentUrl = "https://vk.com/video{$ownerId}_{$videoId}";
        }
    } elseif (strpos($embedUrl, 'rutube.ru') !== false) {
        $platform = 'Rutube';
        
        // Получаем видео ID для Rutube
        if (preg_match('/embed\/([^\/\?]+)/', $embedUrl, $idMatches)) {
            $videoId = $idMatches[1];
            $contentUrl = "https://rutube.ru/video/{$videoId}/";
        }
    }
}

// Формируем данные об авторе
$authorName = $news->video_author_name ?? ($news->user ? $news->user->name : config('app.name'));
$authorUrl = $news->video_author_link ?? url('/');

// Канонический URL
$pageUrl = route('news.show', $news->slug);

// Даты для разметки
$uploadDate = $news->created_at->toIso8601String();

// Преобразуем хэштеги в массив тегов
$keywords = [];
if ($news->video_tags) {
    $keywords = array_map('trim', explode(',', $news->video_tags));
}
@endphp

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ $pageUrl }}"
    },
    "headline": "{{ $title }}",
    "description": "{{ Str::limit(strip_tags($description), 160) }}",
    "image": [
        "{{ $thumbnailUrl }}"
    ],
    "datePublished": "{{ $uploadDate }}",
    "dateModified": "{{ $news->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $news->user ? $news->user->name : config('app.name') }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    },
    "video": {
        "@type": "VideoObject",
        "name": "{{ $title }}",
        "description": "{{ Str::limit(strip_tags($description), 160) }}",
        "thumbnailUrl": "{{ $thumbnailUrl }}",
        "uploadDate": "{{ $uploadDate }}",
        @if($embedUrl)
        "embedUrl": "{{ $embedUrl }}",
        @endif
        @if($contentUrl)
        "contentUrl": "{{ $contentUrl }}",
        @endif
        "publisher": {
            "@type": "Organization",
            "name": "{{ $platform ?: config('app.name') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/logo.png') }}"
            }
        }
    }
}
</script>

@if($embedUrl && $news->video_author_name)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "VideoObject",
    "name": "{{ $title }}",
    "description": "{{ Str::limit(strip_tags($description), 160) }}",
    "thumbnailUrl": "{{ $thumbnailUrl }}",
    "uploadDate": "{{ $uploadDate }}",
    "embedUrl": "{{ $embedUrl }}",
    @if($contentUrl)
    "contentUrl": "{{ $contentUrl }}",
    @endif
    @if(count($keywords) > 0)
    "keywords": "{{ implode(', ', $keywords) }}",
    @endif
    "author": {
        "@type": "Person",
        "name": "{{ $authorName }}",
        @if($news->video_author_link)
        "url": "{{ $authorUrl }}"
        @endif
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ $platform ?: config('app.name') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    }
}
</script>
@endif
