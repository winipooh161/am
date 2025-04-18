<!-- PWA Install Widget для разных устройств -->
        <div id="pwa-install-widget">
            <div class="pwa-widget-header">
                <span>Установите приложение</span>
                <button class="close-pwa-widget" aria-label="Закрыть">&times;</button>
            </div>
            <div class="pwa-widget-icon-container">
                <i class="fas fa-mobile-alt device-icon"></i>
                <div class="pwa-app-info">
                    <img src="{{ asset('images/logo.png') }}" alt="Логотип приложения" class="pwa-app-icon">
                    <div>
                        <strong>{{ config('app.name', 'Я едок') }}</strong>
                        <div class="text-muted small">Лучшие рецепты всегда под рукой</div>
                    </div>
                </div>
            </div>
            <div class="pwa-widget-content">
                <!-- Контент будет добавлен JavaScript в зависимости от устройства -->
            </div>
            <div class="pwa-widget-footer">
                <button id="install-pwa" class="btn btn-primary d-flex align-items-center mx-auto">
                    <i class="fas fa-download me-2"></i> Установить приложение
                </button>
            </div>
        </div>
      
        <!-- Оригинальный контейнер для установки PWA (можно удалить или оставить) -->
        <div id="pwa-install-container" style="display: none;" class="position-fixed bottom-0 end-0 m-3 z-3">
            <button id="install-pwa-original" class="btn btn-primary d-flex align-items-center" style="display: none;">
                <i class="fas fa-download me-2"></i> Установить приложение
            </button>
        </div>
      
        
        <!-- Основная навигация -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="fas fa-utensils me-2 text-primary"></i>
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('recipes.index') ? 'active' : '' }}" href="{{ route('recipes.index') }}">
                                <i class="fas fa-book-open me-1"></i> {{ __('Рецепты') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                <i class="fas fa-tags me-1"></i> {{ __('Категории') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}" href="{{ route('search') }}">
                                <i class="fas fa-search me-1"></i> {{ __('Поиск') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">Новости</a>
                        </li>
                    </ul>
                    
                    <!-- Поисковая строка в хедере -->
                    <form class="d-flex mx-auto my-2 my-lg-0 header-search-form position-relative" action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input id="global-search-input" class="form-control" type="search" name="query" placeholder="Найти рецепт..." aria-label="Search" 
                                   autocomplete="off" value="{{ request('query') ?? '' }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="autocomplete-results d-none position-absolute w-100 mt-1 shadow-sm" style="z-index: 1050; top: 100%;">
                            <!-- Автодополнения будут добавлены JS-ом -->
                        </div>
                    </form>
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Войти') }}
                                    </a>
                                </li>
                            @endif
                            
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i> {{ __('Регистрация') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.recipes.index') }}">
                                    <i class="fas fa-cog me-1"></i> {{ __('Админка') }}
                                </a>
                            </li>
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.parser.*') ? 'active' : '' }}" href="{{ route('admin.parser.index') }}">
                                            <i class="fas fa-code me-1"></i> {{ __('Парсер') }}
                                        </a>
                                    </li>
                                @endif
                                
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                        @if(auth()->user()->isAdmin())
                                            <span class="badge bg-danger">Admin</span>
                                        @endif
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        @if(auth()->user()->isAdmin())
                                        <a class="dropdown-item" href="{{ route('admin.recipes.index') }}">
                                            <i class="fas fa-cogs"></i> Админ-панель
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('admin.recipes.index') }}">
                                            <i class="fas fa-utensils"></i> Управление рецептами
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.categories.index') }}">
                                            <i class="fas fa-list"></i> Управление категориями
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.parser.index') }}">
                                            <i class="fas fa-robot"></i> Парсер рецептов
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.social-posts.index') }}">
                                            <i class="fas fa-share-alt"></i> Постинг в соцсети
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        @elseif(auth()->user()->can('manage-recipes'))
                                        <a class="dropdown-item" href="{{ route('admin.recipes.index') }}">
                                            <i class="fas fa-utensils"></i> Управление рецептами
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user"></i> Профиль
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endauth
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Скрипт для корректной работы мобильного меню -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Находим все ссылки внутри навигационного меню
                const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
                const navbarMenu = document.getElementById('navbarSupportedContent');
                const navbarToggler = document.querySelector('.navbar-toggler');
                // Добавляем обработчик событий для каждой ссылки
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        // Проверяем, открыто ли меню (имеет класс 'show')
                        if (navbarMenu && navbarMenu.classList.contains('show')) {
                            // Закрываем меню, удаляя класс 'show'
                            navbarMenu.classList.remove('show');
                            // Также меняем атрибут aria-expanded у кнопки бургера
                            if (navbarToggler) {
                                navbarToggler.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });
                });
                // Обработчик для закрытия меню по клику вне меню
                document.addEventListener('click', function(event) {
                    // Если меню открыто и клик был не по меню и не по кнопке бургера
                    if (navbarMenu && navbarMenu.classList.contains('show') && 
                        !navbarMenu.contains(event.target) && 
                        !navbarToggler.contains(event.target)) {
                        navbarMenu.classList.remove('show');
                        if (navbarToggler) {
                            navbarToggler.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                // Дополнительная проверка кнопки бургера
                if (navbarToggler) {
                    navbarToggler.addEventListener('click', function() {
                        if (navbarMenu) {
                            navbarMenu.classList.toggle('show');
                            const isExpanded = navbarMenu.classList.contains('show');
                            navbarToggler.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                        }
                    });
                }
            });
        </script>
