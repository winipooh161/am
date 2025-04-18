@php
// Получаем метатеги для страницы отдельного рецепта
$metaTags = isset($seo) && isset($recipe) ? $seo->getRecipeMeta($recipe) : [];

// Заголовок и описание страницы
$title = $metaTags['title'] ?? ($recipe->meta_title ?? ($recipe->title . ' - пошаговый рецепт на Яедок'));
$description = $metaTags['meta_description'] ?? ($recipe->meta_description ?? Str::limit(strip_tags($recipe->description), 160));
$keywords = $metaTags['meta_keywords'] ?? ($recipe->meta_keywords ?? implode(', ', array_merge([$recipe->title], $recipe->categories->pluck('name')->toArray(), ['рецепт', 'готовка', 'кулинария'])));
$canonical = $metaTags['canonical'] ?? route('recipes.show', $recipe->slug);

// Изображение для соцсетей
$ogImage = asset($recipe->image_url);

// Время приготовления в ISO формате
$cookTime = 'PT' . ($recipe->cooking_time ?? 30) . 'M';
$totalTime = 'PT' . (($recipe->prep_time ?? 10) + ($recipe->cooking_time ?? 30)) . 'M';

// Категории
$categoryNames = $recipe->categories->pluck('name')->implode(', ');
@endphp

<!-- Основные метатеги -->
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ $canonical }}">

<!-- Метатеги для AMP-версии, если она есть -->
@if(Route::has('recipes.amp'))
    <link rel="amphtml" href="{{ route('recipes.amp', $recipe->slug) }}">
@endif

<!-- Open Graph теги для социальных сетей -->
<meta property="og:title" content="{{ $metaTags['og_title'] ?? $title }}">
<meta property="og:description" content="{{ $metaTags['og_description'] ?? $description }}">
<meta property="og:url" content="{{ $metaTags['og_url'] ?? url()->current() }}">
<meta property="og:type" content="recipe">
<meta property="og:site_name" content="{{ $metaTags['og_site_name'] ?? config('app.name') }}">
<meta property="og:image" content="{{ $metaTags['og_image'] ?? $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="ru_RU">

<!-- Специальные метатеги для рецептов -->
<meta property="recipe:cook_time" content="{{ $cookTime }}">
<meta property="recipe:yield" content="{{ $recipe->servings ?? '4' }} порций">
@if($recipe->calories)
<meta property="recipe:calories" content="{{ $recipe->calories }} ккал">
@endif
<meta property="recipe:category" content="{{ $categoryNames }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="{{ $metaTags['twitter_card'] ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $metaTags['twitter_title'] ?? $title }}">
<meta name="twitter:description" content="{{ $metaTags['twitter_description'] ?? $description }}">
<meta name="twitter:image" content="{{ $metaTags['twitter_image'] ?? $ogImage }}">

<!-- Дополнительная информация -->
<meta name="author" content="{{ $recipe->user ? $recipe->user->name : config('app.name') }}">
<meta name="article:published_time" content="{{ $recipe->created_at->toIso8601String() }}">
@if($recipe->updated_at && $recipe->updated_at->ne($recipe->created_at))
<meta name="article:modified_time" content="{{ $recipe->updated_at->toIso8601String() }}">
@endif
<meta name="robots" content="{{ $metaTags['robots'] ?? 'index, follow' }}">
