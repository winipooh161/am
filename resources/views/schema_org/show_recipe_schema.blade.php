@php
// Получаем все необходимые данные для рецепта
$ingredients = [];

// Собираем ингредиенты в зависимости от формата хранения
if (isset($recipe->ingredients) && is_object($recipe->ingredients) && $recipe->ingredients->count() > 0) {
    foreach ($recipe->ingredients as $ingredient) {
        $ingredients[] = ($ingredient->quantity ? $ingredient->quantity . ' ' . ($ingredient->unit ?? '') . ' ' : '') . $ingredient->name;
    }
} elseif (is_array($recipe->ingredients)) {
    foreach ($recipe->ingredients as $ingredient) {
        if (is_array($ingredient)) {
            $ingredients[] = ($ingredient['quantity'] ?? '') . ' ' . ($ingredient['unit'] ?? '') . ' ' . ($ingredient['name'] ?? '');
        } else {
            $ingredients[] = $ingredient;
        }
    }
} elseif (is_string($recipe->ingredients)) {
    $ingredients = array_filter(explode("\n", $recipe->ingredients), function($item) {
        return !empty(trim($item));
    });
}

// Подготавливаем инструкции
$instructions = [];
if (is_array($recipe->instructions)) {
    foreach ($recipe->instructions as $index => $instruction) {
        $instructionText = is_array($instruction) ? ($instruction['text'] ?? $instruction) : $instruction;
        $instructionImage = is_array($instruction) && isset($instruction['image']) ? asset($instruction['image']) : null;
        
        $instructionData = [
            "@type" => "HowToStep",
            "position" => $index + 1,
            "name" => "Шаг " . ($index + 1),
            "text" => $instructionText
        ];
        
        if ($instructionImage) {
            $instructionData["image"] = $instructionImage;
        }
        
        $instructions[] = $instructionData;
    }
} elseif (is_string($recipe->instructions)) {
    $instructionSteps = array_filter(explode("\n", $recipe->instructions), function($item) {
        return !empty(trim($item));
    });
    
    foreach ($instructionSteps as $index => $step) {
        $instructions[] = [
            "@type" => "HowToStep",
            "position" => $index + 1,
            "name" => "Шаг " . ($index + 1),
            "text" => trim($step)
        ];
    }
}

// Подготавливаем информацию о времени
$prepTime = 'PT' . ($recipe->prep_time ?? 10) . 'M';
$cookTime = 'PT' . ($recipe->cooking_time ?? 20) . 'M';
$totalTime = 'PT' . (($recipe->prep_time ?? 10) + ($recipe->cooking_time ?? 20)) . 'M';

// Получаем данные о рейтинге
$ratingValue = 0;
$ratingCount = 0;

if (isset($recipe->additional_data['rating'])) {
    $ratingValue = $recipe->additional_data['rating']['value'] ?? 0;
    $ratingCount = $recipe->additional_data['rating']['count'] ?? 0;
}

// Подготавливаем данные о категориях
$categories = $recipe->categories->pluck('name')->toArray();
$categoryName = $categories ? $categories[0] : 'Основные блюда';

// Создаем объект Schema.org Recipe
$recipeData = [
    "@context" => "https://schema.org",
    "@type" => "Recipe",
    "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id" => route('recipes.show', $recipe->slug)
    ],
    "name" => $recipe->title,
    "image" => asset($recipe->image_url),
    "author" => [
        "@type" => "Person",
        "name" => $recipe->user ? $recipe->user->name : config('app.name')
    ],
    "datePublished" => $recipe->created_at->toIso8601String(),
    "dateModified" => $recipe->updated_at->toIso8601String(),
    "description" => $recipe->description,
    "recipeCategory" => $categoryName,
    "keywords" => implode(", ", array_merge($categories, ['рецепт', 'кулинария'])),
    "recipeYield" => $recipe->servings . " " . trans_choice('порция|порции|порций', $recipe->servings),
    "recipeIngredient" => $ingredients,
    "recipeInstructions" => $instructions,
    "prepTime" => $prepTime,
    "cookTime" => $cookTime,
    "totalTime" => $totalTime
];

// Добавляем информацию о питательных веществах
if ($recipe->calories || $recipe->proteins || $recipe->fats || $recipe->carbs) {
    $recipeData["nutrition"] = [
        "@type" => "NutritionInformation"
    ];
    
    if ($recipe->calories) $recipeData["nutrition"]["calories"] = $recipe->calories . " ккал";
    if ($recipe->proteins) $recipeData["nutrition"]["proteinContent"] = $recipe->proteins . " г";
    if ($recipe->fats) $recipeData["nutrition"]["fatContent"] = $recipe->fats . " г";
    if ($recipe->carbs) $recipeData["nutrition"]["carbohydrateContent"] = $recipe->carbs . " г";
}

// Добавляем рейтинг, если есть отзывы
if ($ratingCount > 0) {
    $recipeData["aggregateRating"] = [
        "@type" => "AggregateRating",
        "ratingValue" => $ratingValue,
        "ratingCount" => $ratingCount,
        "bestRating" => 5,
        "worstRating" => 1
    ];
}

// Добавляем видео, если оно есть
if (isset($recipe->video_url) && !empty($recipe->video_url)) {
    // Извлекаем ID видео из YouTube-ссылки
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $recipe->video_url, $matches)) {
        $videoId = $matches[1];
        
        $recipeData["video"] = [
            "@type" => "VideoObject",
            "name" => "Видео: " . $recipe->title,
            "description" => "Видео-инструкция по приготовлению: " . $recipe->title,
            "thumbnailUrl" => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
            "contentUrl" => $recipe->video_url,
            "embedUrl" => "https://www.youtube.com/embed/{$videoId}",
            "uploadDate" => $recipe->created_at->toIso8601String()
        ];
    }
}

// Добавляем отзывы
if (isset($recipe->comments) && $recipe->comments->count() > 0) {
    $recipeData["review"] = [];
    
    foreach($recipe->comments->take(5) as $comment) {
        $reviewRating = isset($comment->rating) && $comment->rating > 0 ? $comment->rating : 5;
        
        $recipeData["review"][] = [
            "@type" => "Review",
            "reviewRating" => [
                "@type" => "Rating",
                "ratingValue" => $reviewRating,
                "bestRating" => 5
            ],
            "author" => [
                "@type" => "Person",
                "name" => $comment->user ? $comment->user->name : "Пользователь"
            ],
            "datePublished" => $comment->created_at->toIso8601String(),
            "reviewBody" => $comment->content
        ];
    }
}
@endphp

<script type="application/ld+json">
    @json($recipeData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

{{-- Хлебные крошки для навигации --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Главная",
            "item": "{{ url('/') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Рецепты",
            "item": "{{ route('recipes.index') }}"
        },
        @if($recipe->categories->isNotEmpty())
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $recipe->categories->first()->name }}",
            "item": "{{ route('categories.show', $recipe->categories->first()->slug) }}"
        },
        {
            "@type": "ListItem",
            "position": 4,
            "name": "{{ $recipe->title }}",
            "item": "{{ route('recipes.show', $recipe->slug) }}"
        }
        @else
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $recipe->title }}",
            "item": "{{ route('recipes.show', $recipe->slug) }}"
        }
        @endif
    ]
}
</script>

{{-- Данные о веб-сайте --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search') }}?query={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
