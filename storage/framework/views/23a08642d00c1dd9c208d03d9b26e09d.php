<?php
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
?>

<!-- Основные мета-теги -->
<title><?php echo e($title); ?> | <?php echo e(config('app.name')); ?></title>
<meta name="description" content="<?php echo e(Str::limit(strip_tags($description), 160)); ?>">
<meta name="keywords" content="<?php echo e($news->video_tags ?? 'новости, видео, кулинария'); ?>">
<link rel="canonical" href="<?php echo e($canonicalUrl); ?>">

<!-- Open Graph для видео контента -->
<meta property="og:title" content="<?php echo e($title); ?>">
<meta property="og:description" content="<?php echo e(Str::limit(strip_tags($description), 160)); ?>">
<meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
<meta property="og:type" content="video.other">
<meta property="og:image" content="<?php echo e($imageUrl); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="<?php echo e(config('app.name')); ?>">
<meta property="og:locale" content="ru_RU">

<!-- Особые теги для видео в Open Graph -->
<?php if($videoUrl): ?>
    <meta property="og:video" content="<?php echo e($videoUrl); ?>">
    <meta property="og:video:secure_url" content="<?php echo e($videoUrl); ?>">
    <meta property="og:video:type" content="text/html">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">
    <?php if($platform === 'vk'): ?>
        <meta property="og:video:app_id" content="7799950"> <!-- ID приложения ВКонтакте -->
    <?php endif; ?>
<?php endif; ?>

<!-- Twitter Card для видео -->
<meta name="twitter:card" content="player">
<meta name="twitter:title" content="<?php echo e($title); ?>">
<meta name="twitter:description" content="<?php echo e(Str::limit(strip_tags($description), 160)); ?>">
<meta name="twitter:image" content="<?php echo e($imageUrl); ?>">
<?php if($videoUrl): ?>
    <meta name="twitter:player" content="<?php echo e($videoUrl); ?>">
    <meta name="twitter:player:width" content="1280">
    <meta name="twitter:player:height" content="720">
<?php endif; ?>

<!-- Дополнительные мета-теги -->
<meta name="author" content="<?php echo e($authorName); ?>">
<meta name="robots" content="index, follow">
<meta property="article:published_time" content="<?php echo e($publishDate); ?>">
<meta property="article:modified_time" content="<?php echo e($news->updated_at->toIso8601String()); ?>">

<?php if(count($tags) > 0): ?>
    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <meta property="article:tag" content="<?php echo e($tag); ?>">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH C:\OSPanel\domains\eats\resources\views/seo/video_news_seo.blade.php ENDPATH**/ ?>