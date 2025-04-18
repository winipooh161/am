<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Базовые мета-теги -->
    @hasSection('meta_tags')
        @yield('meta_tags')
    @else
        <title>@yield('title', config('app.name', 'Категории рецептов'))</title>
        <meta name="description" content="@yield('description', 'Категории рецептов и кулинарных блюд')">
        <meta name="keywords" content="@yield('keywords', 'категории, рецепты, кулинария')">
    @endif

    <!-- SEO Schema.org разметка -->
    @hasSection('schema_org')
        @yield('schema_org')
    @endif

    <!-- SEO секция для включения в конкретных шаблонах -->
    @hasSection('seo')
        @yield('seo')
    @endif

    <!-- Canonical и ссылки пагинации -->
    @if(isset($canonical))
    <link rel="canonical" href="{{ $canonical }}">
    @endif
    
    @if(isset($prevPageUrl))
    <link rel="prev" href="{{ $prevPageUrl }}">
    @endif
    
    @if(isset($nextPageUrl))
    <link rel="next" href="{{ $nextPageUrl }}">
    @endif

    <!-- XML-фиды -->
    @if(Route::has('feeds.recipes'))
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} - Рецепты" href="{{ route('feeds.recipes') }}" />
    @endif
    
    @if(Route::has('feeds.categories'))
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }} - Категории" href="{{ route('feeds.categories') }}" />
    @endif
    
    @if(Route::has('sitemap'))
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}" />
    @endif

    <!-- Preconnect и DNS-Prefetch для ускорения загрузки -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    
    <!-- Favicons с правильной структурой -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta name="msapplication-config" content="/browserconfig.xml">

    <!-- Предзагрузка критических ресурсов -->
    <link rel="preload" href="{{ asset('assets/fonts/fontawesome-webfont.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/js/bootstrap.bundle.min.js') }}" as="script">
    
    <!-- CSS с оптимизированной загрузкой -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" media="print" onload="this.media='all'">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/pwa.css', 'resources/js/app.js'])
    
    @yield('styles')

    <!-- Аналитика только после принятия cookies -->
    <script>
        if (localStorage.getItem('cookieConsent') === 'accepted') {
            // Здесь код аналитики, если пользователь принял cookies
        }
    </script>
</head>
<body class="{{ session('darkMode', false) ? 'dark-mode' : '' }}">
    <div id="app">
        @include('partials.header')
        
        <main>
            <!-- Breadcrumbs -->
            <div class="container mt-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="{{ url('/') }}" itemprop="item"><span itemprop="name">Главная</span></a>
                            <meta itemprop="position" content="1" />
                        </li>
                        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a href="{{ route('categories.index') }}" itemprop="item"><span itemprop="name">Категории</span></a>
                            <meta itemprop="position" content="2" />
                        </li>
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            </div>
            
            @yield('content')
        </main>
        
        @include('partials.footer')
    </div>
    
    <!-- Кнопка наверх -->
    <div class="back-to-top" id="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <!-- Cookie Consent -->
    <div id="cookie-consent" class="cookie-consent" style="display: none;">
        <div class="container">
            <div class="cookie-content">
                <p>Мы используем файлы cookie для улучшения работы сайта. Продолжая пользоваться сайтом, вы соглашаетесь с использованием cookie.</p>
                <div class="cookie-buttons">
                    <button id="accept-cookies" class="btn btn-primary btn-sm">Принять</button>
                    <button id="decline-cookies" class="btn btn-outline-secondary btn-sm">Отказаться</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript с оптимизированной загрузкой -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    
    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
    
    <!-- Обработчик изображений и общие скрипты -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Исправляем пути с дублированием
            document.querySelectorAll('img[src*="/images/images/"]').forEach(function(img) {
                img.src = img.src.replace('/images/images/', '/images/');
            });
            
            // Обработчик ошибок загрузки изображений
            const imgErrorHandler = function(event) {
                const img = event.target;
                const isCategory = img.closest('.category-card') !== null || img.classList.contains('category-img');
                const placeholderPath = isCategory ? '/images/category-placeholder.jpg' : '/images/placeholder.jpg';
                
                if (!img.src.includes('placeholder')) {
                    img.src = placeholderPath;
                }
                
                // Предотвращаем повторные ошибки
                img.onerror = null;
            };
            
            // Применяем обработчик ко всем изображениям
            document.querySelectorAll('img').forEach(img => {
                if (!img.hasAttribute('data-skip-error-handler')) {
                    img.addEventListener('error', imgErrorHandler);
                }
            });
            
            // Скрипт для кнопки прокрутки вверх
            const backToTopButton = document.getElementById('back-to-top');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.style.display = 'flex';
                    backToTopButton.style.alignItems = 'center';
                    backToTopButton.style.justifyContent = 'center';
                } else {
                    backToTopButton.style.display = 'none';
                }
            });
            
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Cookie Consent Banner
            const cookieConsent = document.getElementById('cookie-consent');
            const acceptCookies = document.getElementById('accept-cookies');
            const declineCookies = document.getElementById('decline-cookies');
            
            // Проверяем, есть ли согласие на использование cookie
            if (!localStorage.getItem('cookieConsent')) {
                cookieConsent.style.display = 'block';
            }
            
            // Обработчик принятия cookie
            acceptCookies.addEventListener('click', function() {
                localStorage.setItem('cookieConsent', 'accepted');
                cookieConsent.style.display = 'none';
                
                // Активируем аналитику после принятия
                if (typeof loadAnalytics === 'function') {
                    loadAnalytics();
                }
            });
            
            // Обработчик отказа от cookie
            declineCookies.addEventListener('click', function() {
                localStorage.setItem('cookieConsent', 'declined');
                cookieConsent.style.display = 'none';
            });
        });
    </script>
    
    <!-- Случайные изображения для замены -->
    <script>
        window.getRandomDefaultImage = function() {
            const defaultImages = [
                '/images/defolt/default1.jpg',
                '/images/defolt/default2.jpg',
                '/images/defolt/default3.jpg',
                '/images/defolt/default4.jpg',
                '/images/defolt/default5.jpg',
                '/images/defolt/default6.jpg',
                '/images/defolt/default7.jpg',
                '/images/defolt/default8.jpg',
                '/images/defolt/default9.jpg',
                '/images/defolt/default10.jpg',
                '/images/defolt/default11.jpg'
            ];
            const randomIndex = Math.floor(Math.random() * defaultImages.length);
            return defaultImages[randomIndex];
        };

        window.handleImageError = function(img) {
            const randomImage = window.getRandomDefaultImage();
            img.src = randomImage;
            img.onerror = null;
        };
    </script>
    
    <!-- Дополнительные скрипты страницы -->
    @stack('scripts')
    @yield('scripts')
</body>
</html>