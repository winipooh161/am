<?php
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
?>

<!-- Основные метатеги -->
<title><?php echo e($title); ?></title>
<meta name="description" content="<?php echo e($description); ?>">
<meta name="keywords" content="<?php echo e($keywords); ?>">
<link rel="canonical" href="<?php echo e($canonicalUrl); ?>">

<!-- Метатеги пагинации -->
<?php if(isset($paginationLinks['prev'])): ?>
    <link rel="prev" href="<?php echo e($paginationLinks['prev']); ?>">
<?php endif; ?>

<?php if(isset($paginationLinks['next'])): ?>
    <link rel="next" href="<?php echo e($paginationLinks['next']); ?>">
<?php endif; ?>

<!-- Open Graph теги для социальных сетей -->
<meta property="og:title" content="<?php echo e($title); ?>">
<meta property="og:description" content="<?php echo e($description); ?>">
<meta property="og:url" content="<?php echo e(url()->current()); ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo e(config('app.name')); ?>">
<meta property="og:image" content="<?php echo e($ogImage); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="ru_RU">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo e($title); ?>">
<meta name="twitter:description" content="<?php echo e($description); ?>">
<meta name="twitter:image" content="<?php echo e($ogImage); ?>">

<!-- Дополнительная информация -->
<meta name="robots" content="<?php echo e(isset($news) && $news->currentPage() > 1 ? 'noindex, follow' : 'index, follow'); ?>">
<meta name="author" content="<?php echo e(config('app.name')); ?>">
<?php /**PATH C:\OSPanel\domains\eats\resources\views/seo/news_seo.blade.php ENDPATH**/ ?>