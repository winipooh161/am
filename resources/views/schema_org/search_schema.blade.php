@php
// Подготавливаем Schema.org данные для страницы поиска
$searchAction = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'url' => url('/'),
    'name' => config('app.name'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => url('/search?query={search_term_string}'),
        'query-input' => 'required name=search_term_string'
    ]
];

// Если у нас есть результаты поиска и строка запроса, добавляем разметку ItemList
$searchResults = null;
if (isset($results) && method_exists($results, 'total') && $results->total() > 0 && !empty($query)) {
    $searchResults = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Результаты поиска: ' . $query,
        'description' => 'Результаты поиска рецептов по запросу: ' . $query,
        'numberOfItems' => $results->total(),
        'itemListOrder' => 'https://schema.org/ItemListOrderDescending',
        'itemListElement' => []
    ];
    
    foreach ($results as $index => $recipe) {
        $searchResults['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => ($results->currentPage() - 1) * $results->perPage() + $index + 1,
            'item' => [
                '@type' => 'Recipe',
                'name' => $recipe->title,
                'url' => route('recipes.show', $recipe->slug),
                'image' => asset($recipe->image_url ?: 'images/placeholder.jpg'),
                'description' => Str::limit(strip_tags($recipe->description), 150)
            ]
        ];
    }
}

// Хлебные крошки для страницы поиска
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
            'name' => 'Поиск',
            'item' => route('search')
        ]
    ]
];

if (!empty($query)) {
    $breadcrumbSchema['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => 3,
        'name' => 'Запрос: ' . $query,
        'item' => url()->current() . '?query=' . urlencode($query)
    ];
}
@endphp

<script type="application/ld+json">
    @json($searchAction, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

@if($searchResults)
<script type="application/ld+json">
    @json($searchResults, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>
@endif

<script type="application/ld+json">
    @json($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>
