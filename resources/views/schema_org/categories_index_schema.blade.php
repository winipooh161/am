@php
// Подготавливаем Schema.org данные для страницы категорий
$categoriesSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'Категории рецептов',
    'description' => 'Полный каталог кулинарных категорий. Найдите рецепты по любой категории блюд.',
    'url' => route('categories.index'),
    'image' => asset('images/categories-cover.jpg'),
    'mainEntity' => [
        '@type' => 'ItemList',
        'numberOfItems' => $categoriesCount ?? count($popularCategories),
        'itemListElement' => []
    ]
];

// Добавляем популярные категории в schema
if(isset($popularCategories) && $popularCategories->count() > 0) {
    foreach($popularCategories as $index => $category) {
        $categoriesSchema['mainEntity']['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'Thing',
                'name' => $category->name,
                'url' => route('categories.show', $category->slug),
                'image' => asset($category->image_path ?: 'images/category-placeholder.jpg'),
                'description' => $category->description ?? 'Рецепты в категории ' . $category->name
            ]
        ];
    }
}

// Хлебные крошки для страницы категорий
$breadcrumbSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Главная',
            'item' => url('/')
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Категории',
            'item' => route('categories.index')
        ]
    ]
];

// Добавляем информацию об организации/сайте
$siteSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => config('app.name'),
    'url' => url('/'),
    'description' => 'Кулинарные рецепты с пошаговыми инструкциями',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/search?query={search_term_string}'),
        'query-input' => 'required name=search_term_string'
    ]
];
@endphp

<script type="application/ld+json">
    @json($categoriesSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

<script type="application/ld+json">
    @json($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

<script type="application/ld+json">
    @json($siteSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>
