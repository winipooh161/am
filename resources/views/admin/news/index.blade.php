@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Управление новостями</h1>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Создать новость
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Все новости</h5>
                <span class="badge bg-primary">Всего: {{ $news->total() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Заголовок</th>
                            <th>Статус</th>
                            <th>Просмотры</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($news as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->image_url)
                                        <img src="{{ asset('uploads/' . $item->image_url) }}" 
                                             alt="{{ $item->title }}" 
                                             class="img-thumbnail" 
                                             style="width: 80px;"
                                             data-no-random>
                                    @else
                                        <div class="bg-light text-center" style="width: 80px; height: 60px;">
                                            <i class="fas fa-image text-muted" style="line-height: 60px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.news.edit', $item) }}">
                                        {{ Str::limit($item->title, 50) }}
                                    </a>
                                    <div class="small text-muted">{{ Str::limit($item->short_description, 70) }}</div>
                                </td>
                                <td>
                                    @if($item->is_published)
                                        <span class="badge bg-success">Опубликовано</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Черновик</span>
                                    @endif
                                </td>
                                <td>{{ $item->views }}</td>
                                <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('news.show', $item->slug) }}" class="btn btn-sm btn-info" target="_blank" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-primary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите удалить эту новость?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">Новости отсутствуют</div>
                                    <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Создать новость
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $news->links() }}
        </div>
    </div>
</div>
@endsection
