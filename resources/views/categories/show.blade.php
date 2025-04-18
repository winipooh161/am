@extends('layouts.categories')

@section('seo')
    @include('seo.category_seo', ['category' => $category, 'recipes' => $recipes, 'seo' => app('App\Services\SeoService')])
@endsection

@section('meta_tags')
    {{-- Мета-теги теперь включаются через секцию в seo.category_seo.blade.php --}}
@endsection

@section('schema_org')
    @include('schema_org.category_schema', ['category' => $category, 'recipes' => $recipes])
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a href="{{ route('categories.index') }}" itemprop="item"><span itemprop="name">Категории</span></a>
        <meta itemprop="position" content="2" />
    </li>
    <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
        <span itemprop="name">{{ $category->name }}</span>
        <meta itemprop="position" content="3" />
    </li>
@endsection

@section('title', $title ?? $category->title)
@section('description', $description ?? 'Рецепты из категории ' . $category->title)
@section('keywords', $keywords ?? $category->title . ', рецепты')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="category-header position-relative">
                <div class="category-image-container rounded overflow-hidden shadow-sm">
                    @if($category->image_path)
                        <img src="{{ asset($category->image_path) }}" alt="{{ $category->name }}" class="img-fluid w-100 category-image" 
                             style="max-height: 300px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/category-placeholder.jpg') }}" alt="{{ $category->name }}" class="img-fluid w-100 category-image"
                             style="max-height: 300px; object-fit: cover;">
                    @endif
                    <div class="category-overlay position-absolute w-100 h-100 top-0 start-0 d-flex align-items-center justify-content-center">
                        <div class="text-center text-white px-3">
                            <h1 class="display-5 fw-bold">{{ $category->name }}</h1>
                            <p class="lead">{{ $category->short_description ?? 'Рецепты в категории ' . $category->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Управление отображением и фильтрация -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                        <div>
                            <label for="sort" class="form-label me-2">Сортировать по:</label>
                            <select id="sort" class="form-select-sm d-inline-block w-auto">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Новизне</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Популярности</option>
                                <option value="cooking_time_asc" {{ request('sort') == 'cooking_time_asc' ? 'selected' : '' }}>Времени (возр.)</option>
                                <option value="cooking_time_desc" {{ request('sort') == 'cooking_time_desc' ? 'selected' : '' }}>Времени (убыв.)</option>
                            </select>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <span class="me-2 d-none d-md-inline">Вид:</span>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary view-btn" data-view="grid" title="Плитка">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary view-btn" data-view="list" title="Список">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="recipe-count">
                        <p class="mb-0 text-muted">
                            <i class="fas fa-info-circle me-1"></i> 
                            Найдено {{ $recipes->total() }} {{ trans_choice('рецепт|рецепта|рецептов', $recipes->total()) }} в категории "{{ $category->name }}"
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Список рецептов -->
            @if($recipes->count() > 0)
                <div class="recipe-container" id="recipe-grid">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($recipes as $recipe)
                            <div class="col">
                                <div class="card h-100 recipe-card shadow-sm hrecipe">
                                    <a href="{{ route('recipes.show', $recipe->slug) }}" class="text-decoration-none">
                                        <div class="image-container position-relative" style="height: 180px; overflow: hidden;">
                                            <img src="{{ asset($recipe->image_url) }}" 
                                                 class="card-img-top recipe-img w-100 h-100 photo" 
                                                 alt="{{ $recipe->title }}" 
                                                 style="object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                                        </div>
                                    </a>
                                    <div class="position-absolute top-0 end-0 p-2">
                                        @if($recipe->cooking_time)
                                            <span class="badge bg-primary rounded-pill">
                                                <i class="far fa-clock me-1"></i> <span class="cooktime">{{ $recipe->cooking_time }} мин</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h2 class="h5 card-title fn">
                                            <a href="{{ route('recipes.show', $recipe->slug) }}" class="text-decoration-none text-dark">
                                                {{ $recipe->title }}
                                            </a>
                                        </h2>
                                        <p class="card-text small text-muted summary">
                                            {{ Str::limit($recipe->description, 100) }}
                                        </p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="recipe-stats small text-muted">
                                                <span class="me-2"><i class="far fa-eye"></i> {{ $recipe->views }}</span>
                                                <span><i class="far fa-calendar-alt"></i> <time class="published" datetime="{{ $recipe->created_at->toIso8601String() }}">{{ $recipe->created_at->diffForHumans() }}</time></span>
                                            </div>
                                            <a href="{{ route('recipes.show', $recipe->slug) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> Смотреть
                                            </a>
                                        </div>
                                        <div class="d-none">
                                            <span class="yield">{{ $recipe->servings ?? '4' }} порции</span>
                                            <span class="author">{{ $recipe->user ? $recipe->user->name : config('app.name') }}</span>
                                            
                                            <!-- Скрытые ингредиенты для соответствия hRecipe -->
                                            <div class="ingredients">
                                                @php
                                                    $hasIngredients = false;
                                                    if(isset($recipe->ingredients) && is_object($recipe->ingredients) && $recipe->ingredients->count() > 0) {
                                                        $hasIngredients = true;
                                                    } elseif($recipe->structured_ingredients) {
                                                        $hasIngredients = true;
                                                    }
                                                @endphp
                                                
                                                @if($hasIngredients)
                                                    @if(is_object($recipe->ingredients) && $recipe->ingredients->count() > 0)
                                                        @foreach($recipe->ingredients as $ingredient)
                                                            <span class="ingredient">
                                                                @if($ingredient->quantity)
                                                                    <span class="amount">{{ $ingredient->quantity }}</span> 
                                                                    <span class="type">{{ $ingredient->unit ?? '' }}</span>
                                                                @endif
                                                                <span class="name">{{ $ingredient->name }}</span>
                                                            </span>
                                                        @endforeach
                                                    @elseif($recipe->structured_ingredients)
                                                        @foreach($recipe->structured_ingredients as $group)
                                                            @if(isset($group['name']) && isset($group['items']))
                                                                @foreach($group['items'] as $ingredient)
                                                                    <span class="ingredient">
                                                                        @if(isset($ingredient['quantity']) && $ingredient['quantity'])
                                                                            <span class="amount">{{ $ingredient['quantity'] }}</span> 
                                                                            <span class="type">{{ $ingredient['unit'] ?? '' }}</span>
                                                                        @endif
                                                                        <span class="name">{{ $ingredient['name'] }}</span>
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <span class="ingredient">
                                                                    @if(isset($group['quantity']) && $group['quantity'])
                                                                        <span class="amount">{{ $group['quantity'] }}</span> 
                                                                        <span class="type">{{ $group['unit'] ?? '' }}</span>
                                                                    @endif
                                                                    <span class="name">{{ $group['name'] ?? 'Ингредиент' }}</span>
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <!-- Минимальный набор ингредиентов для валидации hRecipe -->
                                                    <span class="ingredient">
                                                        <span class="name">Основные ингредиенты</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="recipe-container d-none" id="recipe-list">
                    <div class="list-group">
                        @foreach($recipes as $recipe)
                            <a href="{{ route('recipes.show', $recipe->slug) }}" class="list-group-item list-group-item-action mb-3 rounded shadow-sm hrecipe">
                                <div class="row g-0">
                                    <div class="col-md-2">
                                        <img src="{{ $recipe->image_url }}" class="img-fluid rounded photo" alt="{{ $recipe->title }}" 
                                             onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title fn">{{ $recipe->title }}</h5>
                                                @if($recipe->cooking_time)
                                                    <span class="badge bg-primary rounded-pill cooktime">
                                                        <i class="far fa-clock me-1"></i> {{ $recipe->cooking_time }} мин
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="card-text summary">{{ Str::limit($recipe->description, 150) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="recipe-stats small text-muted">
                                                    <span class="me-2"><i class="far fa-eye"></i> {{ $recipe->views }}</span>
                                                    <span><i class="far fa-calendar-alt"></i> <time class="published" datetime="{{ $recipe->created_at->toIso8601String() }}">{{ $recipe->created_at->diffForHumans() }}</time></span>
                                                </div>
                                                <span class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Смотреть рецепт
                                                </span>
                                            </div>
                                            <div class="d-none">
                                                <span class="yield">{{ $recipe->servings ?? '4' }} порции</span>
                                                <span class="author">{{ $recipe->user ? $recipe->user->name : config('app.name') }}</span>
                                                
                                                <!-- Скрытые ингредиенты для соответствия hRecipe -->
                                                <div class="ingredients">
                                                    <span class="ingredient">
                                                        <span class="name">Основные ингредиенты</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-4 d-flex justify-content-center">
                    {{ $recipes->withQueryString()->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> В этой категории пока нет рецептов. Попробуйте выбрать другую категорию или вернитесь позже.
                </div>
            @endif
            
            <!-- Описание категории и советы -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h4 mb-3">О категории "{{ $category->name }}"</h3>
                    <p>{{ $category->long_description ?? 'В разделе ' . $category->name . ' вы найдете различные рецепты блюд, которые можно легко приготовить в домашних условиях. Выбирайте понравившийся рецепт и наслаждайтесь результатом!' }}</p>
                    
                    <!-- Исправление ошибки: добавляем проверку наличия $categoryTips -->
                    @if(isset($categoryTips) && count($categoryTips) > 0)
                    <div class="category-tips mt-4">
                        <h4 class="h5">Советы по приготовлению:</h4>
                        <ul class="list-group list-group-flush">
                            @foreach($categoryTips as $tip)
                                <li class="list-group-item border-0 ps-0">
                                    <i class="fas fa-check-circle text-success me-2"></i> {{ $tip }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="category-tips mt-4">
                        <h4 class="h5">Советы по приготовлению:</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 ps-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Выбирайте свежие ингредиенты для лучшего вкуса блюд
                            </li>
                            <li class="list-group-item border-0 ps-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Следуйте рецепту, но не бойтесь экспериментировать
                            </li>
                            <li class="list-group-item border-0 ps-0">
                                <i class="fas fa-check-circle text-success me-2"></i> Готовьте с любовью и удовольствием!
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <!-- Сайдбар с дополнительной информацией -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">Все категории</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($categories ?? [] as $cat)
                            <li class="list-group-item {{ $cat->id == $category->id ? 'active' : '' }}">
                                <a href="{{ route('categories.show', $cat->slug) }}" class="text-decoration-none {{ $cat->id == $category->id ? 'text-white' : 'text-dark' }}">
                                    {{ $cat->name }} 
                                    <span class="badge bg-light text-dark float-end">{{ $cat->recipes_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Популярные рецепты в этой категории -->
            @if(isset($popularRecipes) && $popularRecipes->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h5 mb-0">Популярные рецепты</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($popularRecipes as $popular)
                                <li class="list-group-item border-0">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0" style="width: 60px; height: 60px;">
                                            <a href="{{ route('recipes.show', $popular->slug) }}">
                                                <img src="{{ asset($popular->image_url) }}" 
                                                     alt="{{ $popular->title }}" 
                                                     class="img-fluid rounded" 
                                                     style="width: 60px; height: 60px; object-fit: cover;"
                                                     onerror="window.handleImageError(this)">
                                            </a>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">
                                                <a href="{{ route('recipes.show', $popular->slug) }}" class="text-decoration-none text-dark">
                                                    {{ $popular->title }}
                                                </a>
                                            </h6>
                                            <div class="text-muted small">
                                                <i class="far fa-eye me-1"></i> {{ $popular->views }}
                                                @if($popular->cooking_time)
                                                    <span class="ms-2">
                                                        <i class="far fa-clock me-1"></i> {{ $popular->cooking_time }} мин
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            <!-- Дополнительные теги или фильтры -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">Быстрые фильтры</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Время приготовления:</label>
                        <div class="d-grid gap-2">
                            <a href="{{ route('categories.show', ['slug' => $category->slug, 'cooking_time' => 15]) }}" class="btn btn-sm btn-outline-primary">До 15 минут</a>
                            <a href="{{ route('categories.show', ['slug' => $category->slug, 'cooking_time' => 30]) }}" class="btn btn-sm btn-outline-primary">До 30 минут</a>
                            <a href="{{ route('categories.show', ['slug' => $category->slug, 'cooking_time' => 60]) }}" class="btn btn-sm btn-outline-primary">До 1 часа</a>
                        </div>
                    </div>
                    
                    @if(request()->has('cooking_time') || request()->has('sort'))
                        <div class="d-grid">
                            <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-times me-1"></i> Сбросить фильтры
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .category-overlay {
        background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.6));
    }
    
    .recipe-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .recipe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .recipe-img {
        height: 180px;
        object-fit: cover;
    }
    
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Переключение вида отображения рецептов
        const viewButtons = document.querySelectorAll('.view-btn');
        const recipeContainers = document.querySelectorAll('.recipe-container');
        
        // Установка сохраненного вида из localStorage
        const savedView = localStorage.getItem('recipeView') || 'grid';
        setActiveView(savedView);
        
        viewButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                setActiveView(view);
                localStorage.setItem('recipeView', view);
            });
        });
        
        function setActiveView(view) {
            // Скрываем все контейнеры
            recipeContainers.forEach(container => container.classList.add('d-none'));
            
            // Показываем нужный контейнер
            document.getElementById('recipe-' + view).classList.remove('d-none');
            
            // Активируем соответствующую кнопку
            viewButtons.forEach(btn => {
                if (btn.getAttribute('data-view') === view) {
                    btn.classList.add('active');
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary');
                } else {
                    btn.classList.remove('active');
                    btn.classList.add('btn-outline-primary');
                    btn.classList.remove('btn-primary');
                }
            });
        }
        
        // Обработка изменения сортировки
        const sortSelect = document.getElementById('sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('sort', this.value);
                window.location.href = currentUrl.toString();
            });
        }
    });
</script>
@endsection
