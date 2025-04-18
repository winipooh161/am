@php
// Подготавливаем SEO метаданные для страницы новостей
$title = isset($searchTerm) ? "Поиск: $searchTerm - Кулинарные новости" : "Кулинарные новости и статьи";
$description = isset($searchTerm) 
    ? "Результаты поиска новостей по запросу '$searchTerm'. Интересные статьи и новости из мира кулинарии на сайте " . config('app.name')
    : "Последние новости из мира кулинарии, интересные факты о продуктах и рецептах, советы шеф-поваров. Будьте в курсе кулинарных трендов с " . config('app.name');
$keywords = isset($searchTerm)
    ? "$searchTerm, новости кулинарии, кулинарные статьи, кулинарные тренды"
    : "новости кулинарии, кулинарные статьи, кулинарные тренды, рецепты, шеф-повара, кулинарные советы";

// Определяем каноническую ссылку и ссылки пагинации
$canonicalUrl = request()->url();
if (isset($category)) {
    $canonicalUrl = route('news.index', ['category' => $category->slug]);
} elseif (isset($tag)) {
    $canonicalUrl = route('news.index', ['tag' => $tag->slug]);
} elseif (isset($searchTerm)) {
    $canonicalUrl = route('news.index', ['search' => $searchTerm]);
}

// Для страниц пагинации добавляем номер страницы к каноническому URL
if (isset($news) && $news->currentPage() > 1) {
    $canonicalUrl = $news->url($news->currentPage());
}

// Ссылки пагинации
$paginationLinks = [];
if (isset($news) && $news->hasPages()) {
    if ($news->currentPage() > 1) {
        $paginationLinks['prev'] = $news->previousPageUrl();
    }
    
    if ($news->hasMorePages()) {
        $paginationLinks['next'] = $news->nextPageUrl();
    }
}

// Изображение для Open Graph
$ogImage = asset('images/news-cover.jpg');
if (isset($news) && $news->count() > 0 && isset($news[0]->image_url)) {
    $ogImage = asset('uploads/' . $news[0]->image_url);
}
@endphp

<!-- Основные метатеги -->
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Метатеги пагинации -->
@if(isset($paginationLinks['prev']))
    <link rel="prev" href="{{ $paginationLinks['prev'] }}">
@endif

@if(isset($paginationLinks['next']))
    <link rel="next" href="{{ $paginationLinks['next'] }}">
@endif

<!-- Open Graph теги для социальных сетей -->
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="ru_RU">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<!-- Дополнительная информация -->
<meta name="robots" content="{{ isset($news) && $news->currentPage() > 1 ? 'noindex, follow' : 'index, follow' }}">
<meta name="author" content="{{ config('app.name') }}">
