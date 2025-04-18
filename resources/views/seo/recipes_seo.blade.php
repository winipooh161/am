@php
// Определяем текущий контекст для генерации правильных метатегов
$isSearch = isset($search) && !empty($search);
$hasCategory = isset($categoryId) && !empty($categoryId);

// Получаем заголовок в зависимости от контекста
if ($isSearch) {
    $pageTitle = 'Поиск рецептов: ' . $search . ' - ' . config('app.name');
    $pageDescription = 'Результаты поиска рецептов по запросу: ' . $search . '. Подробные пошаговые инструкции с фото.';
    $pageKeywords = $search . ', поиск рецептов, кулинария, блюда';
} elseif ($hasCategory && isset($categories)) {
    $category = $categories->firstWhere('id', $categoryId);
    if ($category) {
        $pageTitle = 'Рецепты в категории ' . $category->name . ' - ' . config('app.name');
        $pageDescription = 'Рецепты в категории ' . $category->name . '. Подробные пошаговые инструкции с фото.';
        $pageKeywords = $category->name . ', рецепты, кулинария, блюда';
    }
} else {
    $pageTitle = 'Все рецепты - ' . config('app.name');
    $pageDescription = 'Коллекция кулинарных рецептов с пошаговыми инструкциями, фото и списком ингредиентов.';
    $pageKeywords = 'рецепты, кулинария, блюда, готовка, все рецепты';
}

// Определяем канонический URL
$canonicalUrl = url()->current();
if (isset($recipes) && $recipes->currentPage() > 1) {
    // Для страниц пагинации добавляем параметр page
    $canonicalUrl = $recipes->url($recipes->currentPage());
}

// Ссылки для пагинации
$paginationLinks = [];
if (isset($recipes) && $recipes->hasPages()) {
    if ($recipes->currentPage() > 1) {
        $paginationLinks['prev'] = $recipes->previousPageUrl();
    }
    
    if ($recipes->hasMorePages()) {
        $paginationLinks['next'] = $recipes->nextPageUrl();
    }
}

// Изображение для Open Graph
$ogImage = asset('images/recipes-cover.jpg');
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
<meta name="robots" content="{{ isset($recipes) && $recipes->currentPage() > 1 ? 'noindex, follow' : 'index, follow' }}">
<meta name="author" content="{{ config('app.name') }}">

<!-- Подключаем Schema.org структурированные данные для страницы списка рецептов -->
@include('schema_org.recipes_schema', ['recipes' => $recipes])
