@forelse($news as $item)
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 news-card">
            <a href="{{ route('news.show', $item->slug) }}" class="card-img-top-link">
                <img src="{{ $item->getThumbnailUrl() }}" 
                     class="card-img-top" 
                     alt="{{ $item->title }}" 
                     loading="lazy">
            </a>
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ route('news.show', $item->slug) }}" class="text-dark">
                        {{ $item->title }}
                    </a>
                </h5>
                <p class="card-text text-muted small mb-2">
                    <i class="far fa-calendar-alt me-1"></i> {{ $item->created_at->format('d.m.Y') }}
                    <i class="far fa-eye ms-2 me-1"></i> {{ $item->views }}
                </p>
                <p class="card-text">{{ Str::limit($item->short_description, 100) }}</p>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="{{ route('news.show', $item->slug) }}" class="btn btn-outline-primary btn-sm">
                    Читать полностью
                </a>
                @if($item->hasVideo())
                <span class="badge bg-danger ms-2">
                    <i class="fas fa-play-circle"></i> Видео
                </span>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info">
            Новости не найдены. Пожалуйста, попробуйте другой поисковый запрос.
        </div>
    </div>
@endforelse
