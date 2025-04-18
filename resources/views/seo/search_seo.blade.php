@php
// Получаем данные для поисковой страницы
$query = isset($query) ? $query : (request()->input('query') ?? request()->input('q') ?? '');
$hasResults = isset($results) && method_exists($results, 'count') && $results->count() > 0;
$resultsCount = $hasResults ? $results->total() : 0;

// Создаем заголовок и метаданные
if (!empty($query)) {
    $pageTitle = 'Поиск: ' . $query . ' - ' . config('app.name');
    $pageDescription = 'Результаты поиска рецептов по запросу: ' . $query . '. Найдено ' . $resultsCount . ' ' . trans_choice('рецепт|рецепта|рецептов', $resultsCount) . '.';
    $pageKeywords = $query . ', поиск рецептов, кулинария, готовка';
} else {
    $pageTitle = 'Поиск рецептов - ' . config('app.name');
    $pageDescription = 'Поиск кулинарных рецептов по названиям, ингредиентам и категориям. Найдите идеальный рецепт для вашего стола!';
    $pageKeywords = 'поиск рецептов, найти рецепт, кулинария, ингредиенты';
}

// Определяем каноничную ссылку и ссылки пагинации
$canonicalUrl = empty($query) ? route('search') : url()->current() . '?' . http_build_query(['query' => $query]);
$paginationLinks = [];

if ($hasResults && $results->hasPages()) {
    if ($results->currentPage() > 1) {
        $paginationLinks['prev'] = $results->previousPageUrl();
    }
    
    if ($results->hasMorePages()) {
        $paginationLinks['next'] = $results->nextPageUrl();
    }
}

// Изображение для Open Graph
$ogImage = asset('images/search-cover.jpg');
@endphp

<!-- Основные метатеги -->
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Метатеги пагинации -->
@if(isset($paginationLinks['prev']))
    <link rel="prev" href="{{ $paginationLinks['prev'] }}">
@endif

@if(isset($paginationLinks['next']))
    <link rel="next" href="{{ $paginationLinks['next'] }}">
@endif

<!-- Запрет индексации поисковой выдачи -->
<meta name="robots" content="noindex, follow">

<!-- Open Graph теги для социальных сетей -->
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="ru_RU">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<!-- Дополнительная информация -->
<meta name="author" content="{{ config('app.name') }}">
