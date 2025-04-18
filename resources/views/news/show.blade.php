@extends('layouts.news')

@section('title', $news->title)
@section('description', Str::limit($news->short_description, 160))

@section('schema_org')
    @if($news->video_iframe)
        @include('schema_org.video_news_schema', ['news' => $news])
    @else
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "NewsArticle",
            "headline": "{{ $news->title }}",
            "description": "{{ Str::limit($news->short_description, 160) }}",
            "image": [
                "{{ $news->image_url ? asset('uploads/' . $news->image_url) : asset('images/news-placeholder.jpg') }}"
            ],
            "datePublished": "{{ $news->created_at->toIso8601String() }}",
            "dateModified": "{{ $news->updated_at->toIso8601String() }}",
            "author": {
                "@type": "Person",
                "name": "{{ $news->user ? $news->user->name : config('app.name') }}"
            },
            "publisher": {
                "@type": "Organization",
                "name": "{{ config('app.name') }}",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ asset('images/logo.png') }}",
                    "width": "192",
                    "height": "192"
                }
            },
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "{{ url()->current() }}"
            }
        }
        </script>
    @endif
@endsection

@section('meta_tags')
    @if($news->video_iframe)
        @include('seo.video_news_seo', ['news' => $news])
    @else
        <meta property="og:article:published_time" content="{{ $news->created_at->toIso8601String() }}">
        <meta property="og:article:modified_time" content="{{ $news->updated_at->toIso8601String() }}">
        <meta property="og:article:author" content="{{ $news->user ? $news->user->name : config('app.name') }}">
        <meta property="og:article:section" content="Кулинария">
        <meta name="twitter:label1" content="Время чтения">
        <meta name="twitter:data1" content="{{ $readingTime }} минут">
    @endif
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
    <a href="{{ route('news.index') }}" itemprop="item">
        <span itemprop="name">Новости</span>
    </a>
    <meta itemprop="position" content="2" />
</li>
<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
    <span itemprop="name">{{ $news->title }}</span>
    <meta itemprop="position" content="3" />
</li>
@endsection

@section('content')
<div class="container py-4">
    <article class="news-article" itemscope itemtype="https://schema.org/NewsArticle">
        <meta itemprop="datePublished" content="{{ $news->created_at->toIso8601String() }}">
        <meta itemprop="dateModified" content="{{ $news->updated_at->toIso8601String() }}">
        <meta itemprop="headline" content="{{ $news->title }}">
        <link itemprop="mainEntityOfPage" href="{{ url()->current() }}">
        <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <meta itemprop="name" content="{{ config('app.name') }}">
            <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                <meta itemprop="url" content="{{ asset('images/logo.png') }}">
            </span>
        </span>
        
        <header class="mb-4">
            <h1 class="display-5 fw-bold" itemprop="headline">{{ $news->title }}</h1>
            <div class="d-flex flex-wrap justify-content-between align-items-center text-muted mb-3">
                <div class="d-flex align-items-center">
                    <span class="me-3">
                        <i class="far fa-calendar-alt"></i> 
                        <time itemprop="datePublished" datetime="{{ $news->created_at->toIso8601String() }}">
                            {{ $news->created_at->format('d.m.Y') }}
                        </time>
                    </span>
                    <span class="me-3">
                        <i class="far fa-eye"></i> <span itemprop="interactionCount" content="UserPageVisits:{{ $news->views }}">{{ $news->views }}</span> просмотров
                    </span>
                    <span>
                        <i class="far fa-clock"></i> <meta itemprop="timeRequired" content="PT{{ $readingTime }}M">{{ $readingTime }} мин. чтения
                    </span>
                </div>
                
                <!-- Кнопки шаринга -->
                <div class="share-buttons mt-2 mt-md-0">
                    <span class="me-2">Поделиться:</span>
                    <a href="https://vk.com/share.php?url={{ urlencode(url()->current()) }}" target="_blank" class="text-primary mx-1" aria-label="Поделиться ВКонтакте">
                        <i class="fab fa-vk fa-lg"></i>
                    </a>
                    <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($news->title) }}" target="_blank" class="text-info mx-1" aria-label="Поделиться в Telegram">
                        <i class="fab fa-telegram fa-lg"></i>
                    </a>
                    <a href="https://connect.ok.ru/offer?url={{ urlencode(url()->current()) }}&title={{ urlencode($news->title) }}" target="_blank" class="text-warning mx-1" aria-label="Поделиться в Одноклассниках">
                        <i class="fab fa-odnoklassniki fa-lg"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' ' . url()->current()) }}" target="_blank" class="text-success mx-1" aria-label="Поделиться в WhatsApp">
                        <i class="fab fa-whatsapp fa-lg"></i>
                    </a>
                </div>
            </div>
            
            <!-- Метки публикации -->
            <div class="news-tags mb-4" itemprop="keywords">
                <a href="{{ route('news.index', ['search' => 'кулинария']) }}" class="badge bg-light text-dark text-decoration-none me-1">Кулинария</a>
                <a href="{{ route('news.index', ['search' => 'рецепты']) }}" class="badge bg-light text-dark text-decoration-none me-1">Рецепты</a>
                <a href="{{ route('news.index', ['search' => 'новости']) }}" class="badge bg-light text-dark text-decoration-none">Новости</a>
            </div>
        </header>
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Изображение новости -->
                @if($news->image_url)
                <div class="text-center mb-4">
                    <img src="{{ $news->getImageUrl() }}" 
                         class="img-fluid rounded" 
                         alt="{{ $news->title }}" 
                         loading="lazy">
                </div>
                @endif

                <!-- Видео, если есть -->
                @if($news->hasVideo())
                <div class="video-container mb-4">
                    {!! $news->video_iframe !!}
                </div>
                @endif
                
                <!-- Краткое описание -->
                <div class="lead mb-4 p-3 bg-light rounded" itemprop="description">
                    {{ $news->short_description }}
                </div>
                
                <!-- Полный текст новости -->
                <div class="news-content mb-5" itemprop="articleBody">
                    {!! $news->content !!}
                </div>
                
                <!-- Блок "Читайте также" в мобильной версии -->
                <div class="d-block d-lg-none">
                    @if($relatedNews->count() > 0)
                    <div class="related-news-mobile mb-4">
                        <h3 class="h4 border-bottom pb-2 mb-3">Читайте также</h3>
                        <div class="list-group">
                            @foreach($relatedNews as $item)
                                <a href="{{ route('news.show', $item->slug) }}" class="list-group-item list-group-item-action">
                                    {{ $item->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Автор новости -->
                @if($news->user)
                    <div class="news-author card mb-4">
                        <div class="card-body d-flex align-items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                            <div class="author-avatar me-3">
                                <img src="{{ $news->user->avatar ?? asset('images/default-avatar.png') }}" 
                                    class="rounded-circle" width="60" height="60" 
                                    alt="{{ $news->user->name }}" 
                                    itemprop="image">
                            </div>
                            <div>
                                <h5 class="mb-1" itemprop="name">{{ $news->user->name }}</h5>
                                <p class="text-muted mb-0" itemprop="description">{{ $news->user->bio ?? 'Автор кулинарных статей' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Навигация по новостям (предыдущая/следующая) -->
                <div class="news-navigation d-flex justify-content-between mt-5">
                    <div>
                        @if($prevNews)
                            <a href="{{ route('news.show', $prevNews->slug) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i> Предыдущая
                            </a>
                        @endif
                    </div>
                    <div>
                        @if($nextNews)
                            <a href="{{ route('news.show', $nextNews->slug) }}" class="btn btn-outline-primary">
                                Следующая <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Блок комментариев -->
                <div class="comments-section mt-5 pt-4 border-top">
                    <h3 class="mb-4">Комментарии</h3>
                    
                    <!-- Форма комментариев для авторизованных пользователей -->
                    @auth
                        <div class="card mb-4 comment-form-card">
                            <div class="card-body">
                                <form id="comment-form" action="{{ route('news.comments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="news_id" value="{{ $news->id }}">
                                    <div class="mb-3">
                                        <label for="comment-content" class="form-label">Ваш комментарий</label>
                                        <textarea id="comment-content" name="content" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="far fa-paper-plane me-1"></i> Отправить
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div>
                                Чтобы оставить комментарий, пожалуйста, <a href="{{ route('login') }}" class="fw-bold">войдите</a> или <a href="{{ route('register') }}" class="fw-bold">зарегистрируйтесь</a>.
                            </div>
                        </div>
                    @endauth
                    
                    <!-- Список комментариев -->
                    <div id="comments-list" class="comments-container">
                        @if(isset($comments) && $comments instanceof \Illuminate\Support\Collection && $comments->count() > 0)
                            @foreach($comments as $comment)
                            <div class="comment-item card mb-3">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <img src="{{ isset($comment->user) && $comment->user->avatar ? asset($comment->user->avatar) : asset('images/default-avatar.png') }}" 
                                            alt="{{ isset($comment->user) ? $comment->user->name : 'Пользователь' }}" 
                                            class="rounded-circle me-3" 
                                            width="50" height="50">
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="card-subtitle mb-0 fw-bold">{{ isset($comment->user) ? $comment->user->name : 'Пользователь' }}</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="card-text">{{ $comment->content }}</p>
                                            @if(Auth::check() && (Auth::id() == $comment->user_id || (Auth::user()->isAdmin && Auth::user()->isAdmin())))
                                            <div class="text-end">
                                                <form action="{{ route('news.comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                                        <i class="far fa-trash-alt"></i> Удалить
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted my-5 py-5">
                                <div class="empty-comments-icon mb-3">
                                    <i class="far fa-comments fa-4x"></i>
                                </div>
                                <h5>Комментариев пока нет</h5>
                                <p class="mb-0">Будьте первым, кто оставит комментарий к этой новости!</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Удаляем второй дублирующийся блок комментариев -->
                
            </div>
            
            <!-- Боковая колонка -->
            <div class="col-lg-4">
                <!-- Блок с рекомендуемыми рецептами -->
                @if(isset($recommendedRecipes) && $recommendedRecipes->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-utensils me-2"></i> Рекомендуем приготовить
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($recommendedRecipes as $recipe)
                        <a href="{{ route('recipes.show', $recipe->slug) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            @if($recipe->image)
                                <img src="{{ asset($recipe->image) }}" alt="{{ $recipe->title }}" class="me-3" 
                                     style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                            @endif
                            <div>
                                <h6 class="mb-1">{{ Str::limit($recipe->title, 40) }}</h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning text-dark me-2">
                                        <i class="fas fa-star me-1"></i>{{ number_format($recipe->rating_avg ?? 4.5, 1) }}
                                    </span>
                                    <small class="text-muted">{{ $recipe->cooking_time ?? '30 мин' }}</small>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Форма подписки на обновления -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-envelope me-2"></i> Подпишитесь на обновления
                    </div>
                    <div class="card-body">
                        <form id="sidebar-subscribe-form">
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Ваш email" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="subscribe-terms" required>
                                <label class="form-check-label small" for="subscribe-terms">
                                    Я согласен на обработку персональных данных
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Подписаться</button>
                        </form>
                    </div>
                </div>
                
                <!-- Блок "Читайте также" для десктопов -->
                @if($relatedNews->count() > 0)
                <div class="related-news d-none d-lg-block">
                    <div class="card">
                        <div class="card-header bg-light">
                            <i class="fas fa-newspaper me-2"></i> Читайте также
                        </div>
                        <div class="card-body p-0">
                            @foreach($relatedNews as $item)
                                <a href="{{ route('news.show', $item->slug) }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border-bottom">
                                        @if($item->image_url)
                                            <img src="{{ asset('uploads/' . $item->image_url) }}" 
                                                alt="{{ $item->title }}" 
                                                class="me-3"
                                                style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;"
                                                data-no-random>
                                        @endif
                                        <div>
                                            <h6 class="mb-1 text-dark">{{ Str::limit($item->title, 60) }}</h6>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <span>{{ $item->created_at->format('d.m.Y') }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="far fa-eye me-1"></i>
                                                <span>{{ $item->views }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Полезные ссылки -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <i class="fas fa-link me-2"></i> Полезные ссылки
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('recipes.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-utensils me-2 text-primary"></i> Все рецепты
                        </a>
                        <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-list me-2 text-success"></i> Категории
                        </a>
                        <a href="{{ route('search') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="fas fa-search me-2 text-info"></i> Поиск рецептов
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    
    <!-- CTA блок внизу страницы -->
    <div class="cta-section bg-light p-4 rounded-3 my-5 text-center">
        <h3>Понравилась статья? Читайте больше!</h3>
        <p class="mb-3">Подпишитесь на наши новости и получайте лучшие рецепты и кулинарные советы.</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('news.index') }}" class="btn btn-primary btn-lg px-4 me-md-2">Больше новостей</a>
                    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary btn-lg px-4">Перейти к рецептам</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Анимация появления контента
    const animateElements = document.querySelectorAll('.news-content p, .news-content h2, .news-content h3, .news-content ul, .news-content img');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    animateElements.forEach(element => {
        element.classList.add('animate-element');
        observer.observe(element);
    });
    
    // Обработчик формы комментариев
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const commentText = document.getElementById('comment-content').value;
            if (!commentText.trim()) return;
            
            const formData = new FormData(this);
            
            // Показываем индикатор загрузки
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Отправка...';
            
            // Отправляем AJAX запрос
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка сети');
                }
                return response.json();
            })
            .then(data => {
                // Возвращаем кнопку в исходное состояние
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                // Очищаем форму
                this.reset();
                
                // Показываем уведомление об успехе
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = '<i class="fas fa-check-circle me-2"></i> Ваш комментарий добавлен! <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                this.parentNode.insertBefore(alert, this);
                
                // Если есть данные комментария, добавляем его в список
                if (data.comment) {
                    const commentsList = document.getElementById('comments-list');
                    
                    // Если это первый комментарий, очищаем сообщение "комментариев пока нет"
                    if (commentsList.querySelector('.empty-comments-icon')) {
                        commentsList.innerHTML = '';
                    }
                    
                    // Создаем HTML для нового комментария
                    const commentItem = document.createElement('div');
                    commentItem.className = 'comment-item card mb-3';
                    commentItem.innerHTML = `
                        <div class="card-body">
                            <div class="d-flex">
                                <img src="${data.userAvatar || '{{ asset("images/default-avatar.png") }}'}" 
                                    alt="${data.userName}" 
                                    class="rounded-circle me-3" 
                                    width="50" height="50">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="card-subtitle mb-0 fw-bold">${data.userName}</h6>
                                        <small class="text-muted">только что</small>
                                    </div>
                                    <p class="card-text">${data.comment.content}</p>
                                    <div class="text-end">
                                        <form action="{{ route('news.comments.destroy', '') }}/${data.comment.id}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                                <i class="far fa-trash-alt"></i> Удалить
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Добавляем комментарий в начало списка
                    commentsList.insertBefore(commentItem, commentsList.firstChild);
                    
                    // Анимация появления комментария
                    commentItem.style.opacity = '0';
                    commentItem.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        commentItem.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        commentItem.style.opacity = '1';
                        commentItem.style.transform = 'translateY(0)';
                    }, 10);
                }
                
                // Автоматически закрываем уведомление через 3 секунды
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 3000);
            })
            .catch(error => {
                // Возвращаем кнопку в исходное состояние
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                // Показываем уведомление об ошибке
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> Произошла ошибка при добавлении комментария. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                this.parentNode.insertBefore(alert, this);
            });
        });
    }
    
    // Форма подписки
    const subscribeForm = document.getElementById('sidebar-subscribe-form');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            this.innerHTML = `
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    Спасибо! Вы успешно подписались на обновления.
                </div>
            `;
        });
    }
    
    // Увеличение изображений при клике
    const newsImages = document.querySelectorAll('.news-content img');
    newsImages.forEach(img => {
        img.classList.add('img-fluid', 'rounded', 'mb-3');
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            // Создаем модальное окно для просмотра изображения
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${this.src}" class="img-fluid" alt="${this.alt}">
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
            
            modal.addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        });
    });
    
    // Скачивание и отображение обложки видео для администраторов
    const videoEmbeds = document.querySelectorAll('iframe[src*="vk.com"], iframe[src*="rutube.ru"]');
    if (videoEmbeds.length > 0 && document.querySelector('.admin-video-tools')) {
        const videoEmbed = videoEmbeds[0];
        const videoSrc = videoEmbed.getAttribute('src');
        
        // Проверяем есть ли кнопка для извлечения обложки
        const extractThumbBtn = document.querySelector('.extract-thumbnail-btn');
        
        if (extractThumbBtn) {
            extractThumbBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const loadingText = this.dataset.loading || 'Извлечение...';
                const originalText = this.innerHTML;
                this.disabled = true;
                this.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${loadingText}`;
                
                // Отправляем запрос на сервер для получения метаданных видео
                fetch('/admin/video/extract-metadata', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ url: videoSrc })
                })
                .then(response => response.json())
                .then(data => {
                    this.disabled = false;
                    this.innerHTML = originalText;
                    
                    if (data.error) {
                        alert('Ошибка: ' + data.error);
                        console.error('Ошибка извлечения обложки:', data.error);
                        return;
                    }
                    
                    if (data.data && data.data.thumbnail) {
                        const thumbnailBlock = document.querySelector('.video-thumbnail-block');
                        if (thumbnailBlock) {
                            const thumbnailImg = document.createElement('img');
                            thumbnailImg.src = data.data.thumbnail;
                            thumbnailImg.alt = 'Обложка видео';
                            thumbnailImg.className = 'img-fluid rounded mt-2';
                            
                            thumbnailBlock.innerHTML = '';
                            thumbnailBlock.appendChild(thumbnailImg);
                            
                            const downloadBtn = document.createElement('a');
                            downloadBtn.href = data.data.thumbnail;
                            downloadBtn.download = 'video-thumbnail.jpg';
                            downloadBtn.className = 'btn btn-sm btn-success mt-2';
                            downloadBtn.innerHTML = '<i class="fas fa-download me-1"></i> Скачать обложку';
                            downloadBtn.target = '_blank';
                            
                            thumbnailBlock.appendChild(downloadBtn);
                        }
                    } else {
                        alert('Не удалось извлечь обложку видео');
                    }
                })
                .catch(error => {
                    this.disabled = false;
                    this.innerHTML = originalText;
                    console.error('Ошибка:', error);
                    alert('Произошла ошибка при извлечении обложки');
                });
            });
        }
    }
});
</script>
@endsection
