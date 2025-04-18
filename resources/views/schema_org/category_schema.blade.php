@php
// Подготавливаем Schema.org данные для страницы отдельной категории

// Основные данные для страницы категории
$categorySchema = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => $category->name,
    'description' => $category->description ?? 'Рецепты в категории ' . $category->name,
    'url' => route('categories.show', $category->slug),
    'mainEntity' => [
        '@type' => 'ItemList',
        'numberOfItems' => $recipes->total(),
        'itemListElement' => []
    ]
];

// Добавляем рецепты в схему
if($recipes->count() > 0) {
    foreach($recipes as $index => $recipe) {
        $categorySchema['mainEntity']['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => ($recipes->currentPage() - 1) * $recipes->perPage() + $index + 1,
            'item' => [
                '@type' => 'Recipe',
                'name' => $recipe->title,
                'url' => route('recipes.show', $recipe->slug),
                'image' => asset($recipe->image_url),
                'description' => Str::limit(strip_tags($recipe->description), 150),
                'datePublished' => $recipe->created_at->toIso8601String(),
                'author' => [
                    '@type' => 'Person',
                    'name' => $recipe->user ? $recipe->user->name : config('app.name')
                ],
                'recipeCategory' => $category->name,
                'recipeYield' => $recipe->servings ? $recipe->servings . ' ' . trans_choice('порция|порции|порций', $recipe->servings) : '4 порции',
            ]
        ];
    }
}

// Хлебные крошки для страницы категории
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
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $category->name,
            'item' => route('categories.show', $category->slug)
        ]
    ]
];

// Добавляем информацию о веб-сайте
$siteSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => config('app.name'),
    'url' => url('/'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/search?query={search_term_string}'),
        'query-input' => 'required name=search_term_string'
    ]
];
@endphp

<script type="application/ld+json">
    @json($categorySchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

<script type="application/ld+json">
    @json($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

<script type="application/ld+json">
    @json($siteSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

{{-- Добавляем разметку для навигации по категориям --}}
@if(isset($popularCategories) && $popularCategories->count() > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Популярные категории рецептов",
    "itemListElement": [
        @foreach($popularCategories as $index => $popularCategory)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "item": {
                "@type": "Thing",
                "name": "{{ $popularCategory->name }}",
                "url": "{{ route('categories.show', $popularCategory->slug) }}",
                "image": "{{ $popularCategory->image_path ? asset($popularCategory->image_path) : asset('images/category-placeholder.jpg') }}"
            }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endif
