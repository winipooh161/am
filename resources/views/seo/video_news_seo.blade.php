@php
// Получаем данные о видео из модели новости
$title = $news->video_title ?? $news->title;
$description = $news->video_description ?? $news->short_description ?? '';
$videoUrl = "";

// Определяем платформу и получаем ID видео из iframe (ВКонтакте или Rutube)
if ($news->video_iframe) {
    if (strpos($news->video_iframe, 'vk.com') !== false) {
        $platform = 'vk';
        // Извлекаем URL из iframe для VK
        preg_match('/src="([^"]+)"/', $news->video_iframe, $matches);
        $videoUrl = $matches[1] ?? '';
    } elseif (strpos($news->video_iframe, 'rutube.ru') !== false) {
        $platform = 'rutube';
        // Извлекаем URL из iframe для Rutube
        preg_match('/src="([^"]+)"/', $news->video_iframe, $matches);
        $videoUrl = $matches[1] ?? '';
    }
}

// Формируем изображение для превью
$imageUrl = $news->image_url 
    ? asset('uploads/' . $news->image_url) 
    : asset('images/news-placeholder.jpg');

// Преобразуем хэштеги в массив тегов
$tags = [];
if ($news->video_tags) {
    $tags = array_map('trim', explode(',', $news->video_tags));
}

// Формируем данные об авторе
$authorName = $news->video_author_name ?? ($news->user ? $news->user->name : config('app.name'));
$authorUrl = $news->video_author_link ?? url('/');

// Канонический URL
$canonicalUrl = route('news.show', $news->slug);

// Дата публикации в ISO формате
$publishDate = $news->created_at->toIso8601String();
@endphp

<!-- Основные мета-теги -->
<title>{{ $title }} | {{ config('app.name') }}</title>
<meta name="description" content="{{ Str::limit(strip_tags($description), 160) }}">
<meta name="keywords" content="{{ $news->video_tags ?? 'новости, видео, кулинария' }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Open Graph для видео контента -->
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($description), 160) }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:type" content="video.other">
<meta property="og:image" content="{{ $imageUrl }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="ru_RU">

<!-- Особые теги для видео в Open Graph -->
@if($videoUrl)
    <meta property="og:video" content="{{ $videoUrl }}">
    <meta property="og:video:secure_url" content="{{ $videoUrl }}">
    <meta property="og:video:type" content="text/html">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">
    @if($platform === 'vk')
        <meta property="og:video:app_id" content="7799950"> <!-- ID приложения ВКонтакте -->
    @endif
@endif

<!-- Twitter Card для видео -->
<meta name="twitter:card" content="player">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ Str::limit(strip_tags($description), 160) }}">
<meta name="twitter:image" content="{{ $imageUrl }}">
@if($videoUrl)
    <meta name="twitter:player" content="{{ $videoUrl }}">
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="720">
@endif

<!-- Дополнительные мета-теги -->
<meta name="author" content="{{ $authorName }}">
<meta name="robots" content="index, follow">
<meta property="article:published_time" content="{{ $publishDate }}">
<meta property="article:modified_time" content="{{ $news->updated_at->toIso8601String() }}">

@if(count($tags) > 0)
    @foreach($tags as $tag)
        <meta property="article:tag" content="{{ $tag }}">
    @endforeach
@endif
