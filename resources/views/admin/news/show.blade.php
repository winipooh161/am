@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Просмотр новости</h1>
        <div>
            <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Редактировать
            </a>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад к списку
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $news->title }}</h5>
                <span class="badge {{ $news->is_published ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $news->is_published ? 'Опубликовано' : 'Черновик' }}
                </span>
            </div>
        </div>
        
        @if($news->image_url)
        <div class="text-center p-3 bg-light">
            <img src="{{ asset('uploads/' . $news->image_url) }}" 
                 alt="{{ $news->title }}" 
                 class="img-fluid rounded" 
                 style="max-height: 400px;">
        </div>
        @endif
        
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-2">ID:</dt>
                <dd class="col-sm-10">{{ $news->id }}</dd>
                
                <dt class="col-sm-2">Заголовок:</dt>
                <dd class="col-sm-10">{{ $news->title }}</dd>
                
                <dt class="col-sm-2">Slug:</dt>
                <dd class="col-sm-10">{{ $news->slug }}</dd>
                
                <dt class="col-sm-2">Краткое описание:</dt>
                <dd class="col-sm-10">{{ $news->short_description }}</dd>
                
                <dt class="col-sm-2">Просмотры:</dt>
                <dd class="col-sm-10">{{ $news->views }}</dd>
                
                <dt class="col-sm-2">Дата создания:</dt>
                <dd class="col-sm-10">{{ $news->created_at->format('d.m.Y H:i:s') }}</dd>
                
                <dt class="col-sm-2">Последнее обновление:</dt>
                <dd class="col-sm-10">{{ $news->updated_at->format('d.m.Y H:i:s') }}</dd>
                
                <dt class="col-sm-2">Автор:</dt>
                <dd class="col-sm-10">
                    @if($news->user)
                        {{ $news->user->name }}
                    @else
                        <span class="text-muted">Не указан</span>
                    @endif
                </dd>
            </dl>
            
            <h5 class="mt-4 mb-3">Содержание новости:</h5>
            <div class="card">
                <div class="card-body content-preview">
                    {!! $news->content !!}
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-external-link-alt"></i> Открыть на сайте
                    </a>
                </div>
                <div>
                    <form action="{{ route('admin.news.destroy', $news) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Вы уверены, что хотите удалить эту новость?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Удалить новость
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .content-preview img {
        max-width: 100%;
        height: auto;
    }
</style>
@endsection
