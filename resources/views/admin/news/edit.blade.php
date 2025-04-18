@extends('layouts.app')

@section('styles')
<style>
    .tox-tinymce {
        border-radius: 0.25rem;
    }
    .ck-editor__editable_inline {
        min-height: 400px;
    }
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
    }
    .form-label.required::after {
        content: " *";
        color: red;
    }
    #image-preview {
        max-height: 200px;
        max-width: 100%;
        margin-top: 10px;
    }
    .card-video {
        border-top: 3px solid #007bff;
        margin-top: 20px;
    }
    
    .video-preview {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
        margin-top: 10px;
    }
    
    .video-tags-input {
        min-height: 38px;
    }
    .video-url-input {
        position: relative;
    }
    
    .video-url-input .spinner-border {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        display: none;
    }
    
    .video-url-input.loading .spinner-border {
        display: block;
    }
    
    .video-platform-icon {
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Редактирование новости</h1>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад к списку
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Редактирование: {{ Str::limit($news->title, 70) }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label required">Заголовок</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $news->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="short_description" class="form-label required">Краткое описание</label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror" 
                              id="short_description" name="short_description" rows="3" required>{{ old('short_description', $news->short_description) }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label required">Содержание новости</label>
                    <div id="editor-container">
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" name="content">{{ old('content', $news->content) }}</textarea>
                    </div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Изображение</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*" onchange="previewImage(event)">
                    <div class="form-text">
                        Рекомендуемый размер: 1200x630px, максимальный размер: 2МБ.
                        Изображения сохраняются в директории <code>public/storage/news</code>
                    </div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <div class="mt-2">
                        @if($news->image_url)
                            <div class="mb-2" id="current-image-block">
                                <label class="form-label">Текущее изображение:</label>
                                @if(Storage::disk('public')->exists('' . $news->image_url))
                                    <img src="{{ asset('storage/' . $news->image_url) }}" alt="{{ $news->title }}" 
                                         class="img-thumbnail" style="max-height: 200px;">
                                @else
                                    <div class="alert alert-warning">
                                        Изображение не найдено по пути: storage/{{ $news->image_url }}
                                    </div>
                                @endif
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="delete_image" name="delete_image" value="1">
                                    <label class="form-check-label" for="delete_image">
                                        Удалить текущее изображение
                                    </label>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">Изображение не загружено</p>
                        @endif
                        <img id="image-preview" src="#" alt="Preview" style="display: none;">
                    </div>
                </div>
                
                <!-- Блок для встраивания видео -->
                <div class="card card-video">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Встроить видео (ВКонтакте или Rutube)</h5>
                        <button type="button" class="btn btn-link p-0" id="toggle-video-details">
                            <span class="collapse-text">{{ $news->video_iframe ? 'Скрыть детали' : 'Показать детали' }}</span>
                            <i class="fas fa-chevron-{{ $news->video_iframe ? 'up' : 'down' }} ms-1"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Добавляем новое поле для URL видео -->
                        <div class="mb-3">
                            <label for="video_url" class="form-label">Ссылка на видео</label>
                            <div class="input-group video-url-input" id="video-url-container">
                                <input type="url" class="form-control @error('video_url') is-invalid @enderror" 
                                       id="video_url" name="video_url" value="{{ old('video_url') }}" 
                                       placeholder="https://vk.com/video... или https://rutube.ru/video/...">
                                <button class="btn btn-outline-primary" type="button" id="extract-video-data">
                                    Извлечь данные
                                </button>
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Загрузка...</span>
                                </div>
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="video-url-feedback" class="form-text text-danger"></div>
                        </div>
                        
                        <div id="video-data-container" style="display: {{ $news->video_iframe ? 'block' : 'none' }};">
                            <div class="mb-3">
                                <label for="video_iframe" class="form-label">Код встраивания видео (iframe)</label>
                                <textarea class="form-control @error('video_iframe') is-invalid @enderror" 
                                        id="video_iframe" name="video_iframe" rows="3" placeholder="Вставьте код iframe с ВКонтакте или Rutube">{{ old('video_iframe', $news->video_iframe) }}</textarea>
                                <div class="form-text">
                                    Код встраивания будет автоматически сгенерирован из ссылки на видео.
                                    <button type="button" class="btn btn-sm btn-link p-0 ms-2" data-bs-toggle="modal" data-bs-target="#embedHelpModal">
                                        Как получить код встраивания вручную?
                                    </button>
                                </div>
                                @error('video_iframe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <div id="video-preview" class="video-preview mt-3" style="{{ $news->video_iframe ? '' : 'display:none;' }}">
                                    <div id="iframe-container">
                                        @if($news->video_iframe)
                                            {!! $news->video_iframe !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="video_author_name" class="form-label">Имя автора видео</label>
                                    <input type="text" class="form-control @error('video_author_name') is-invalid @enderror" 
                                        id="video_author_name" name="video_author_name" value="{{ old('video_author_name', $news->video_author_name) }}">
                                    @error('video_author_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="video_author_link" class="form-label">Ссылка на автора</label>
                                    <input type="url" class="form-control @error('video_author_link') is-invalid @enderror" 
                                        id="video_author_link" name="video_author_link" value="{{ old('video_author_link', $news->video_author_link) }}" placeholder="https://">
                                    @error('video_author_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="video_title" class="form-label">Название видео</label>
                                <input type="text" class="form-control @error('video_title') is-invalid @enderror" 
                                    id="video_title" name="video_title" value="{{ old('video_title', $news->video_title) }}">
                                <div class="form-text">Если не указано, будет использован заголовок новости</div>
                                @error('video_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="video_description" class="form-label">Описание видео</label>
                                <textarea class="form-control @error('video_description') is-invalid @enderror" 
                                        id="video_description" name="video_description" rows="3">{{ old('video_description', $news->video_description) }}</textarea>
                                <div class="form-text">Если не указано, будет использовано краткое описание новости</div>
                                @error('video_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="video_tags" class="form-label">Теги видео</label>
                                <input type="text" class="form-control video-tags-input @error('video_tags') is-invalid @enderror" 
                                    id="video_tags" name="video_tags" value="{{ old('video_tags', $news->video_tags) }}" placeholder="Введите теги через запятую">
                                @error('video_tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_published" 
                               name="is_published" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">Опубликовать</label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">ID: {{ $news->id }} | Создано: {{ $news->created_at->format('d.m.Y H:i') }}</span>
                <a href="{{ route('news.show', $news->slug) }}" class="btn btn-sm btn-info" target="_blank">
                    <i class="fas fa-eye"></i> Просмотр на сайте
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно с инструкцией по получению кода встраивания -->
<div class="modal fade" id="embedHelpModal" tabindex="-1" aria-labelledby="embedHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="embedHelpModalLabel">Как получить код встраивания видео</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h5>ВКонтакте:</h5>
                    <ol>
                        <li>Откройте видео на сайте ВКонтакте</li>
                        <li>Нажмите на кнопку "Поделиться" под видео</li>
                        <li>Выберите вкладку "Экспорт"</li>
                        <li>Скопируйте весь код из поля "Код для вставки"</li>
                        <li>Вставьте скопированный код в поле "Код встраивания видео"</li>
                    </ol>
                    <div class="alert alert-info">
                        Пример кода: <code>&lt;iframe src="https://vk.com/video_ext.php?oid=-123456&id=456789&hash=abcdef123456" width="640" height="360" frameborder="0" allowfullscreen&gt;&lt;/iframe&gt;</code>
                    </div>
                </div>
                
                <div>
                    <h5>Rutube:</h5>
                    <ol>
                        <li>Откройте видео на сайте Rutube</li>
                        <li>Нажмите на кнопку "Поделиться" под видео</li>
                        <li>Нажмите на кнопку "&lt;/&gt;"</li>
                        <li>Скопируйте весь код из поля</li>
                        <li>Вставьте скопированный код в поле "Код встраивания видео"</li>
                    </ol>
                    <div class="alert alert-info">
                        Пример кода: <code>&lt;iframe width="720" height="405" src="https://rutube.ru/play/embed/123456" frameborder="0" allow="clipboard-write; autoplay" webkitAllowFullScreen mozallowfullscreen allowfullscreen&gt;&lt;/iframe&gt;</code>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/translations/ru.js"></script>
<script>
    // Класс адаптера загрузки для CKEditor
    class UploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then(file => new Promise((resolve, reject) => {
                    this._uploadFile(file).then(response => {
                        // Проверяем структуру ответа и извлекаем правильный URL
                        let url = '';
                        if (response && response.location) {
                            url = response.location;
                        } else if (response && response.url) {
                            url = response.url;
                        } else if (typeof response === 'string') {
                            url = response;
                        }
                        
                        console.log('Изображение успешно загружено:', url);
                        
                        resolve({
                            default: url
                        });
                    }).catch(error => {
                        console.error('Ошибка загрузки изображения:', error);
                        reject(error);
                    });
                }));
        }

        abort() {
            // Реализация метода отмены загрузки (опционально)
        }

        _uploadFile(file) {
            const data = new FormData();
            data.append('file', file);
            data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            console.log('Начата загрузка файла:', file.name);

            return fetch('{{ route('admin.tinymce.upload') }}', {
                method: 'POST',
                body: data
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка загрузки: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Получен ответ от сервера:', data);
                return data;
            });
        }
    }

    // Плагин для подключения адаптера загрузки
    function uploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new UploadAdapter(loader);
        };
    }

    // Инициализация CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            language: 'ru',
            extraPlugins: [uploadAdapterPlugin], // Добавляем адаптер загрузки
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Параграф', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Заголовок 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Заголовок 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Заголовок 3', class: 'ck-heading_heading3' }
                ]
            },
            image: {
                toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            },
            height: 500
        })
        .then(editor => {
            console.log('CKEditor инициализирован успешно', editor);
        })
        .catch(error => {
            console.error('Произошла ошибка при инициализации CKEditor:', error);
        });
    
    // Функция для предварительного просмотра изображения
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('image-preview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                
                // Если загружается новое изображение, скрыть блок с текущим изображением
                const currentImageBlock = document.getElementById('current-image-block');
                if (currentImageBlock) {
                    currentImageBlock.style.display = 'none';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Добавляем отладочную информацию при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Путь к изображению в БД:', '{{ $news->image_url }}');
        
        // Показываем в консоли полные пути, которые используются для проверки и отображения
        @if($news->image_url)
            console.log('Проверка существования по пути:', 'storage/{{ $news->image_url }}');
            console.log('Отображение по пути:', '{{ asset("storage/" . $news->image_url) }}');
            
            // Альтернативный путь - проверка без префикса 
            console.log('Альтернативный путь (только image_url):', '{{ asset("storage/" . $news->image_url) }}');
        @endif
    });

    // Обработка предварительного просмотра видео
    document.addEventListener('DOMContentLoaded', function() {
        const videoIframeField = document.getElementById('video_iframe');
        const videoPreview = document.getElementById('video-preview');
        const iframeContainer = document.getElementById('iframe-container');
        
        videoIframeField.addEventListener('input', function() {
            const iframeCode = this.value.trim();
            
            if (iframeCode) {
                // Простая проверка на наличие iframe
                if (iframeCode.includes('<iframe') && iframeCode.includes('</iframe>')) {
                    // Проверка на разрешенные домены
                    if (iframeCode.includes('vk.com') || iframeCode.includes('rutube.ru')) {
                        iframeContainer.innerHTML = iframeCode;
                        videoPreview.style.display = 'block';
                    } else {
                        iframeContainer.innerHTML = '<div class="alert alert-danger">Разрешены только видео с ВКонтакте или Rutube</div>';
                        videoPreview.style.display = 'block';
                    }
                } else {
                    iframeContainer.innerHTML = '<div class="alert alert-warning">Введите корректный код iframe</div>';
                    videoPreview.style.display = 'block';
                }
            } else {
                videoPreview.style.display = 'none';
            }
        });
        
        // Инициализируем предпросмотр, если поле не пустое
        if (videoIframeField.value.trim()) {
            videoIframeField.dispatchEvent(new Event('input'));
        }
    });

    // Обработка извлечения метаданных видео из URL
    document.addEventListener('DOMContentLoaded', function() {
        const videoUrlContainer = document.getElementById('video-url-container');
        const videoUrlField = document.getElementById('video_url');
        const extractButton = document.getElementById('extract-video-data');
        const videoUrlFeedback = document.getElementById('video-url-feedback');
        const videoIframeField = document.getElementById('video_iframe');
        const videoPreview = document.getElementById('video-preview');
        const iframeContainer = document.getElementById('iframe-container');
        const videoTitleField = document.getElementById('video_title');
        const videoDescField = document.getElementById('video_description');
        const videoTagsField = document.getElementById('video_tags');
        const videoAuthorNameField = document.getElementById('video_author_name');
        const videoAuthorLinkField = document.getElementById('video_author_link');
        const videoDataContainer = document.getElementById('video-data-container');
        const toggleVideoDetails = document.getElementById('toggle-video-details');
        
        // Переключение отображения деталей видео
        toggleVideoDetails.addEventListener('click', function() {
            const isHidden = videoDataContainer.style.display === 'none';
            videoDataContainer.style.display = isHidden ? 'block' : 'none';
            this.querySelector('.collapse-text').textContent = isHidden ? 'Скрыть детали' : 'Показать детали';
            this.querySelector('i').classList.toggle('fa-chevron-up', isHidden);
            this.querySelector('i').classList.toggle('fa-chevron-down', !isHidden);
        });
        
        // Автоматически извлекаем данные видео при изменении URL
        videoUrlField.addEventListener('blur', function() {
            if (this.value.trim()) {
                extractVideoMetadata();
            }
        });
        
        // Обработчик кнопки извлечения данных
        extractButton.addEventListener('click', function() {
            extractVideoMetadata();
        });
        
        // Функция для извлечения метаданных видео
        function extractVideoMetadata() {
            const url = videoUrlField.value.trim();
            
            // Валидация URL
            if (!url) {
                showVideoUrlError('Введите URL видео');
                return;
            }
            
            if (!url.includes('vk.com') && !url.includes('rutube.ru') && !url.includes('vkvideo.ru')) {
                showVideoUrlError('Поддерживаются только видео с ВКонтакте и Rutube');
                return;
            }
            
            // Показываем индикатор загрузки
            videoUrlContainer.classList.add('loading');
            videoUrlFeedback.textContent = '';
            
            // Отправляем запрос на сервер для извлечения метаданных
            fetch('{{ route('admin.video.extract-metadata') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ url: url })
            })
            .then(response => response.json())
            .then(data => {
                // Скрываем индикатор загрузки
                videoUrlContainer.classList.remove('loading');
                
                if (data.error) {
                    showVideoUrlError(data.error);
                    console.error('Ошибка извлечения видео:', data.error);
                    return;
                }
                
                console.log('Получены метаданные видео:', data.data); // Для отладки
                
                // Показываем контейнер с данными видео
                videoDataContainer.style.display = 'block';
                toggleVideoDetails.querySelector('.collapse-text').textContent = 'Скрыть детали';
                toggleVideoDetails.querySelector('i').classList.replace('fa-chevron-down', 'fa-chevron-up');
                
                // Заполняем поля данными
                if (data.data.iframe) {
                    videoIframeField.value = data.data.iframe;
                    iframeContainer.innerHTML = data.data.iframe;
                    videoPreview.style.display = 'block';
                }
                
                videoTitleField.value = data.data.title;
                videoDescField.value = data.data.description;
                videoTagsField.value = data.data.tags;
                videoAuthorNameField.value = data.data.author_name;
                videoAuthorLinkField.value = data.data.author_link;
                
                // Добавляем индикатор платформы
                let platformIcon = '';
                if (data.data.platform === 'vk') {
                    platformIcon = '<i class="fab fa-vk text-primary video-platform-icon"></i>';
                } else if (data.data.platform === 'rutube') {
                    platformIcon = '<i class="fas fa-play-circle text-danger video-platform-icon"></i>';
                }
                
                // Добавляем сообщение об успехе
                videoUrlFeedback.innerHTML = platformIcon + '<span class="text-success">Данные видео успешно получены</span>';
                videoUrlFeedback.classList.remove('text-danger');
                videoUrlFeedback.classList.add('text-success');
                
                // Если получена обложка видео, добавляем опцию для скачивания
                if (data.data.thumbnail) {
                    const imagePreviewSection = document.createElement('div');
                    imagePreviewSection.className = 'mt-3 border-top pt-3';
                    imagePreviewSection.innerHTML = `
                        <h6 class="mb-2"><i class="fas fa-image me-2"></i>Обложка видео</h6>
                        <div class="d-flex flex-column">
                            <img src="${data.data.thumbnail}" class="img-fluid rounded mb-2" style="max-height: 200px; width: auto;">
                            <div class="btn-group">
                                <a href="${data.data.thumbnail}" class="btn btn-sm btn-primary" download="video_thumbnail.jpg" target="_blank">
                                    <i class="fas fa-download me-1"></i> Скачать обложку
                                </a>
                                <button type="button" class="btn btn-sm btn-success use-as-thumbnail">
                                    <i class="fas fa-check me-1"></i> Использовать как изображение новости
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Проверяем, не добавлена ли уже секция с обложкой
                    const existingSection = document.querySelector('.video-thumbnail-section');
                    if (existingSection) {
                        existingSection.remove();
                    }
                    
                    imagePreviewSection.classList.add('video-thumbnail-section');
                    videoPreview.appendChild(imagePreviewSection);
                    
                    // Добавляем обработчик для кнопки "Использовать как изображение"
                    imagePreviewSection.querySelector('.use-as-thumbnail').addEventListener('click', function() {
                        downloadAndUseAsThumbnail(data.data.thumbnail);
                    });
                }
            })
            .catch(error => {
                videoUrlContainer.classList.remove('loading');
                showVideoUrlError('Произошла ошибка при получении данных видео');
                console.error('Error:', error);
            });
        }
        
        // Функция для скачивания и использования обложки видео
        function downloadAndUseAsThumbnail(thumbnailUrl) {
            // Создаем и показываем уведомление о скачивании
            const downloadNotice = document.createElement('div');
            downloadNotice.className = 'alert alert-info mt-2';
            downloadNotice.innerHTML = '<i class="fas fa-sync fa-spin me-2"></i> Скачивание обложки...';
            
            const videoPreviewSection = document.querySelector('.video-thumbnail-section');
            videoPreviewSection.appendChild(downloadNotice);
            
            // Отправляем запрос на сервер для скачивания обложки
            fetch('{{ route('admin.video.download-thumbnail') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ url: thumbnailUrl })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    downloadNotice.className = 'alert alert-danger mt-2';
                    downloadNotice.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> ' + data.error;
                    setTimeout(() => downloadNotice.remove(), 3000);
                    return;
                }
                
                if (data.success && data.path) {
                    // Обновляем уведомление
                    downloadNotice.className = 'alert alert-success mt-2';
                    downloadNotice.innerHTML = '<i class="fas fa-check-circle me-2"></i> Обложка успешно скачана и будет использована в качестве изображения для новости!';
                    
                    // Создаем скрытое поле с путем к скачанной обложке
                    let hiddenField = document.getElementById('downloaded_thumbnail_path');
                    if (!hiddenField) {
                        hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.id = 'downloaded_thumbnail_path';
                        hiddenField.name = 'downloaded_thumbnail_path';
                        document.querySelector('form').appendChild(hiddenField);
                    }
                    hiddenField.value = data.path;
                    
                    // Показываем превью скачанного изображения
                    const currentImageBlock = document.getElementById('current-image-block');
                    if (currentImageBlock) {
                        const imageContainer = currentImageBlock.querySelector('img') || document.createElement('img');
                        imageContainer.src = '{{ asset("uploads") }}/' + data.path;
                        imageContainer.className = 'img-thumbnail mt-2';
                        imageContainer.style.maxHeight = '200px';
                        
                        if (!currentImageBlock.contains(imageContainer)) {
                            currentImageBlock.appendChild(imageContainer);
                        }
                        
                        const imageStatusText = document.createElement('div');
                        imageStatusText.className = 'text-success mt-2';
                        imageStatusText.innerHTML = '<i class="fas fa-info-circle"></i> Будет использовано новое изображение из обложки видео';
                        
                        // Удаляем предыдущие статусные сообщения
                        currentImageBlock.querySelectorAll('.text-success, .text-warning').forEach(el => el.remove());
                        currentImageBlock.appendChild(imageStatusText);
                    }
                    
                    // Через 3 секунды скрываем уведомление
                    setTimeout(() => downloadNotice.remove(), 3000);
                } else {
                    downloadNotice.className = 'alert alert-warning mt-2';
                    downloadNotice.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i> Не удалось скачать обложку';
                    setTimeout(() => downloadNotice.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Ошибка при скачивании обложки:', error);
                downloadNotice.className = 'alert alert-danger mt-2';
                downloadNotice.innerHTML = '<i class="fas fa-times-circle me-2"></i> Произошла ошибка';
                setTimeout(() => downloadNotice.remove(), 3000);
            });
        }
        
        // Показываем ошибку URL
        function showVideoUrlError(message) {
            videoUrlFeedback.textContent = message;
            videoUrlFeedback.classList.remove('text-success');
            videoUrlFeedback.classList.add('text-danger');
        }
    });
</script>
@endsection
