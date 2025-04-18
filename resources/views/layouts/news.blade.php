<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
   
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @yield('seo')
    
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

   	<!-- Обязательный (и достаточный) тег для браузеров -->
	<link type="image/x-icon" rel="shortcut icon" href="./favicon.ico">

	<!-- Дополнительные иконки для десктопных браузеров -->
	<link type="image/png" sizes="16x16" rel="icon" href="./favicon-16x16.png">
	<link type="image/png" sizes="32x32" rel="icon" href="./favicon-32x32.png">
	<link type="image/png" sizes="96x96" rel="icon" href="./favicon-96x96.png">
	<link type="image/png" sizes="120x120" rel="icon" href="./favicon-120x120.png">

	<!-- Иконки для Android -->
	<link type="image/png" sizes="72x72" rel="icon" href="./android-icon-72x72.png">
	<link type="image/png" sizes="96x96" rel="icon" href="./android-icon-96x96.png">
	<link type="image/png" sizes="144x144" rel="icon" href="./android-icon-144x144.png">
	<link type="image/png" sizes="192x192" rel="icon" href="./android-icon-192x192.png">
	<link type="image/png" sizes="512x512" rel="icon" href="./android-icon-512x512.png">
	<link rel="manifest" href="./manifest.json">

	<!-- Иконки для iOS (Apple) -->
	<link sizes="57x57" rel="apple-touch-icon" href="./apple-touch-icon-57x57.png">
	<link sizes="60x60" rel="apple-touch-icon" href="./apple-touch-icon-60x60.png">
	<link sizes="72x72" rel="apple-touch-icon" href="./apple-touch-icon-72x72.png">
	<link sizes="76x76" rel="apple-touch-icon" href="./apple-touch-icon-76x76.png">
	<link sizes="114x114" rel="apple-touch-icon" href="./apple-touch-icon-114x114.png">
	<link sizes="120x120" rel="apple-touch-icon" href="./apple-touch-icon-120x120.png">
	<link sizes="144x144" rel="apple-touch-icon" href="./apple-touch-icon-144x144.png">
	<link sizes="152x152" rel="apple-touch-icon" href="./apple-touch-icon-152x152.png">
	<link sizes="180x180" rel="apple-touch-icon" href="./apple-touch-icon-180x180.png">

	<!-- Иконки для MacOS (Apple) -->
	<link color="#e52037" rel="mask-icon" href="./safari-pinned-tab.svg">

	<!-- Иконки и цвета для плиток Windows -->
	<meta name="msapplication-TileColor" content="#2b5797">
	<meta name="msapplication-TileImage" content="./mstile-144x144.png">
	<meta name="msapplication-square70x70logo" content="./mstile-70x70.png">
	<meta name="msapplication-square150x150logo" content="./mstile-150x150.png">
	<meta name="msapplication-wide310x150logo" content="./mstile-310x310.png">
	<meta name="msapplication-square310x310logo" content="./mstile-310x150.png">
	<meta name="application-name" content="My Application">
	<meta name="msapplication-config" content="./browserconfig.xml">
  <!-- Preconnect and DNS-Prefetch для ускорения загрузки -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://yandex.ru">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//yandex.ru">
    
    <!-- Шрифты с оптимизированной загрузкой -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
    
    <!-- CSS с указанием media и загрузкой по очереди важности -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" media="print" onload="this.media='all'">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/pwa.css', 'resources/js/pwa.js', 'resources/js/app.js'])
    
    <!-- Обработчик изображений -->
    <script src="{{ asset('js/image-loader.js') }}"></script>
    
    @auth
    @if(auth()->user()->isAdmin())
    <!-- Инструменты отладки для администраторов -->
    <script src="{{ asset('js/debug-tools.js') }}"></script>
    @endif
    @endauth
    
    @yield('styles')

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('assets/fonts/fontawesome-webfont.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/js/bootstrap.bundle.min.js') }}" as="script">
    
    <!-- Styles -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    
    <!-- AMP Link for recipes -->
    @if(isset($isRecipe) && $isRecipe && Route::has('recipes.amp'))
    <link rel="amphtml" href="{{ route('recipes.amp', ['slug' => $recipe->slug]) }}">
    @endif

    <!-- Критический CSS inline -->
    <style>
      
    </style>
  <div id="app">
    @extends('partials.footer')   
    @extends('partials.header')     
</div>
        
        <script>
            // Скрипт для кнопки прокрутки вверх
            document.addEventListener('DOMContentLoaded', function() {
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
                });
                
                // Обработчик отказа от cookie
                declineCookies.addEventListener('click', function() {
                    localStorage.setItem('cookieConsent', 'declined');
                    cookieConsent.style.display = 'none';
                    // Дополнительно можно отключить аналитику
                    // ym(96182066, "disable");
                });
                
                // Инициализация бургер-меню для мобильной версии
                const navbarToggler = document.querySelector('.navbar-toggler');
                if (navbarToggler) {
                    navbarToggler.addEventListener('click', function() {
                        const target = document.querySelector(this.getAttribute('data-bs-target'));
                        if (target) {
                            if (target.classList.contains('show')) {
                                target.classList.remove('show');
                            } else {
                                target.classList.add('show');
                            }
                        }
                    });
                }
            });
        </script>
        
        <!-- PWA Related Scripts -->
        <!-- Скрипт загружается через Vite, и дополнительная загрузка не требуется -->
        
        <!-- Загрузка JS с отложенным исполнением для улучшения Core Web Vitals -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
                crossorigin="anonymous" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
        
        @stack('scripts')

    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/app.js') }}" defer></script>
    <script src="{{ asset('assets/js/scroll-top.js') }}" defer></script>
    <script src="{{ asset('assets/js/cookie-consent.js') }}" defer></script>
    <script src="{{ asset('assets/js/search.js') }}" defer></script>
    <script src="{{ asset('assets/js/pwa-installer.js') }}" defer></script>
    <script src="{{ asset('assets/js/random-images.js') }}" defer></script>
    <script src="{{ asset('assets/js/service-worker.js') }}" defer></script>
    <script src="{{ asset('assets/js/mobile-menu.js') }}" defer></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    
    <!-- Обработчик изображений -->
    <script src="{{ asset('assets/js/image-loader.js') }}" defer></script>
    
    <!-- Page specific scripts -->
    @yield('scripts')
    
    <!-- Подключаем Service Worker для кэширования -->
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
    <script>
        // Глобальный обработчик ошибок загрузки изображений
        document.addEventListener('DOMContentLoaded', function() {
            // Исправляем пути с дублированием
            document.querySelectorAll('img[src*="/images/images/"]').forEach(function(img) {
                img.src = img.src.replace('/images/images/', '/images/');
            });
            
            // Остальной код обработчика
            const imgErrorHandler = function(event) {
                const img = event.target;
                const isCategory = img.closest('.category-card') !== null || img.classList.contains('category-img');
                const placeholderPath = isCategory ? '/images/category-placeholder.jpg' : '/images/placeholder.jpg';
                
                if (!img.src.includes('placeholder')) {
                    console.log('Заменяем неудачное изображение:', img.src);
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
        });
    </script>
    <!-- Подключение нашего виджета PWA -->
    <script src="{{ asset('js/pwa-install-widget.js') }}" defer></script>
    <!-- Функция для выбора случайного изображения из папки defolt -->
    <script>
        window.getRandomDefaultImage = function() {
            // Массив доступных изображений в папке defolt
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
            // Выбираем случайное изображение из массива
            const randomIndex = Math.floor(Math.random() * defaultImages.length);
            return defaultImages[randomIndex];
        };

        // Функция для обработки ошибок загрузки изображений
        window.handleImageError = function(img) {
            // Получаем случайное изображение
            const randomImage = window.getRandomDefaultImage();
            // Устанавливаем его в качестве источника
            img.src = randomImage;
            // Сбрасываем обработчик ошибок, чтобы избежать рекурсии
            img.onerror = null;
        };

        // Инициализация всех изображений при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            // Находим все изображения на странице
            const images = document.querySelectorAll('img');
            
            // Для каждого изображения добавляем обработчик ошибок
            images.forEach(img => {
                if (!img.hasAttribute('data-no-random')) {
                    img.addEventListener('error', function() {
                        window.handleImageError(this);
                    });
                }
            });
        });
    </script>
</body>
</html>