@php
// Подготавливаем Schema.org данные для страницы новостей
$newsSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'headline' => isset($searchTerm) ? "Поиск: $searchTerm - Кулинарные новости" : "Кулинарные новости и статьи",
    'description' => isset($searchTerm) ? "Результаты поиска новостей по запросу '$searchTerm'" : "Последние новости из мира кулинарии, интересные факты о продуктах и рецептах, советы шеф-поваров",
    'url' => url()->current(),
    'publisher' => [
        '@type' => 'Organization',
        'name' => config('app.name'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png')
        ]
    ],
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => []
    ]
];

// Добавляем элементы новостей в список
if (isset($news) && $news->count() > 0) {
    foreach ($news as $index => $item) {
        $newsSchema['mainEntity']['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => ($news->currentPage() - 1) * $news->perPage() + $index + 1,
            'url' => route('news.show', $item->slug),
            'name' => $item->title
        ];
    }
}

// Хлебные крошки для новостей
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
            'name' => 'Новости',
            'item' => route('news.index')
        ]
    ]
];

// Если есть поисковый запрос, добавляем его в хлебные крошки
if (isset($searchTerm) && !empty($searchTerm)) {
    $breadcrumbSchema['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => 3,
        'name' => "Поиск: $searchTerm",
        'item' => url()->full()
    ];
}

// Если есть категория, добавляем её в хлебные крошки
if (isset($category) && isset($category->slug) && isset($category->name)) {
    $breadcrumbSchema['itemListElement'][] = [
        '@type' => 'ListItem',
        'position' => 3,
        'name' => $category->name,
        'item' => route('news.index', ['category' => $category->slug])
    ];
}
@endphp

<script type="application/ld+json">
    @json($newsSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

<script type="application/ld+json">
    @json($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
</script>

{{-- Добавляем информацию о веб-сайте --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ route('news.index') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
