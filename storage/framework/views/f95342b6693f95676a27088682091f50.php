

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Создание новости</h1>
        <a href="<?php echo e(route('admin.news.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад к списку
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Форма создания новости</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.news.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <div class="mb-3">
                    <label for="title" class="form-label required">Заголовок</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="title" name="title" value="<?php echo e(old('title')); ?>" required>
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="short_description" class="form-label required">Краткое описание</label>
                    <textarea class="form-control <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              id="short_description" name="short_description" rows="3" required><?php echo e(old('short_description')); ?></textarea>
                    <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label required">Содержание новости</label>
                    <div id="editor-container">
                        <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="content" name="content"><?php echo e(old('content')); ?></textarea>
                    </div>
                    <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Изображение</label>
                    <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="image" name="image" accept="image/*" onchange="previewImage(event)">
                    <div class="form-text">Рекомендуемый размер: 1200x630px, максимальный размер: 2МБ</div>
                    
                    <!-- Добавляем опцию использования обложки из видео -->
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="use_video_thumbnail" 
                               name="use_video_thumbnail" value="1" <?php echo e(old('use_video_thumbnail') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="use_video_thumbnail">
                            Использовать обложку из видео (если доступна)
                        </label>
                        <div class="form-text">Обложка будет автоматически загружена при извлечении данных видео</div>
                    </div>
                    
                    <!-- Скрытое поле для URL обложки -->
                    <input type="hidden" id="video_thumbnail_url" name="video_thumbnail_url" value="<?php echo e(old('video_thumbnail_url')); ?>">
                    
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    
                    <div class="mt-3">
                        <!-- Блок для превью загруженного изображения -->
                        <div id="uploaded-image-preview" style="<?php echo e(old('video_thumbnail_url') ? 'display:none;' : ''); ?>">
                            <img id="image-preview" src="#" alt="Preview" style="display: none; max-height: 200px; max-width: 100%;" class="img-thumbnail">
                        </div>
                        
                        <!-- Блок для превью обложки из видео -->
                        <div id="video-thumbnail-preview-container" style="<?php echo e(old('video_thumbnail_url') ? '' : 'display:none;'); ?>">
                            <div class="alert alert-info d-flex align-items-center mb-2">
                                <i class="fas fa-film me-2"></i>
                                <div>Будет использована обложка из видео</div>
                            </div>
                            <img id="video-thumbnail-preview" src="<?php echo e(old('video_thumbnail_url')); ?>" 
                                 alt="Video Thumbnail Preview" class="img-thumbnail" style="max-height: 200px; max-width: 100%;">
                        </div>
                    </div>
                </div>
                
                <!-- Блок для встраивания видео -->
                <div class="card card-video">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Встроить видео (ВКонтакте или Rutube)</h5>
                        <button type="button" class="btn btn-link p-0" id="toggle-video-details">
                            <span class="collapse-text">Скрыть детали</span>
                            <i class="fas fa-chevron-up ms-1"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Добавляем новое поле для URL видео -->
                        <div class="mb-3">
                            <label for="video_url" class="form-label">Ссылка на видео</label>
                            <div class="input-group video-url-input" id="video-url-container">
                                <input type="url" class="form-control <?php $__errorArgs = ['video_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="video_url" name="video_url" value="<?php echo e(old('video_url')); ?>" 
                                       placeholder="https://vk.com/video... или https://rutube.ru/video/...">
                                <button class="btn btn-outline-primary" type="button" id="extract-video-data">
                                    Извлечь данные
                                </button>
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Загрузка...</span>
                                </div>
                                <?php $__errorArgs = ['video_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div id="video-url-feedback" class="form-text text-danger"></div>
                        </div>
                        
                        <div id="video-data-container">
                            <div class="mb-3">
                                <label for="video_iframe" class="form-label">Код встраивания видео (iframe)</label>
                                <textarea class="form-control <?php $__errorArgs = ['video_iframe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="video_iframe" name="video_iframe" rows="3" placeholder="Вставьте код iframe с ВКонтакте или Rutube"><?php echo e(old('video_iframe')); ?></textarea>
                                <div class="form-text">
                                    Код встраивания будет автоматически сгенерирован из ссылки на видео.
                                    <button type="button" class="btn btn-sm btn-link p-0 ms-2" data-bs-toggle="modal" data-bs-target="#embedHelpModal">
                                        Как получить код встраивания вручную?
                                    </button>
                                </div>
                                <?php $__errorArgs = ['video_iframe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                
                                <div id="video-preview" class="video-preview mt-3" style="display:none;">
                                    <div id="iframe-container"></div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="video_author_name" class="form-label">Имя автора видео</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['video_author_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="video_author_name" name="video_author_name" value="<?php echo e(old('video_author_name')); ?>">
                                    <?php $__errorArgs = ['video_author_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="video_author_link" class="form-label">Ссылка на автора</label>
                                    <input type="url" class="form-control <?php $__errorArgs = ['video_author_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="video_author_link" name="video_author_link" value="<?php echo e(old('video_author_link')); ?>" placeholder="https://">
                                    <?php $__errorArgs = ['video_author_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="video_title" class="form-label">Название видео</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['video_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="video_title" name="video_title" value="<?php echo e(old('video_title')); ?>">
                                <div class="form-text">Если не указано, будет использован заголовок новости</div>
                                <?php $__errorArgs = ['video_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="video_description" class="form-label">Описание видео</label>
                                <textarea class="form-control <?php $__errorArgs = ['video_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="video_description" name="video_description" rows="3"><?php echo e(old('video_description')); ?></textarea>
                                <div class="form-text">Если не указано, будет использовано краткое описание новости</div>
                                <?php $__errorArgs = ['video_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="video_tags" class="form-label">Теги видео</label>
                                <input type="text" class="form-control video-tags-input <?php $__errorArgs = ['video_tags'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="video_tags" name="video_tags" value="<?php echo e(old('video_tags')); ?>" placeholder="Введите теги через запятую">
                                <?php $__errorArgs = ['video_tags'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_published" 
                               name="is_published" <?php echo e(old('is_published') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_published">Опубликовать</label>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Сохранить
                    </button>
                    <a href="<?php echo e(route('admin.news.index')); ?>" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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

            return fetch('<?php echo e(route('admin.tinymce.upload')); ?>', {
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
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

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
        
        // Копирование заголовка в название видео
        const titleField = document.getElementById('title');
        const videoTitleField = document.getElementById('video_title');
        
        titleField.addEventListener('blur', function() {
            if (!videoTitleField.value && this.value) {
                videoTitleField.value = this.value;
            }
        });
        
        // Копирование краткого описания в описание видео
        const shortDescField = document.getElementById('short_description');
        const videoDescField = document.getElementById('video_description');
        
        shortDescField.addEventListener('blur', function() {
            if (!videoDescField.value && this.value) {
                videoDescField.value = this.value;
            }
        });
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
        const titleField = document.getElementById('title');
        const shortDescField = document.getElementById('short_description');
        const videoDataContainer = document.getElementById('video-data-container');
        const toggleVideoDetails = document.getElementById('toggle-video-details');
        const useVideoThumbnailCheckbox = document.getElementById('use_video_thumbnail');
        const videoThumbnailUrlField = document.getElementById('video_thumbnail_url');
        const videoThumbnailPreviewContainer = document.getElementById('video-thumbnail-preview-container');
        const videoThumbnailPreview = document.getElementById('video-thumbnail-preview');
        const uploadedImagePreview = document.getElementById('uploaded-image-preview');
        
        // Инициализируем состояние контейнера данных видео
        videoDataContainer.style.display = 'none';
        toggleVideoDetails.querySelector('.collapse-text').textContent = 'Показать детали';
        toggleVideoDetails.querySelector('i').classList.replace('fa-chevron-up', 'fa-chevron-down');
        
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
            fetch('<?php echo e(route('admin.video.extract-metadata')); ?>', {
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
                
                // Показываем контейнер с данными видео
                videoDataContainer.style.display = 'block';
                toggleVideoDetails.querySelector('.collapse-text').textContent = 'Скрыть детали';
                toggleVideoDetails.querySelector('i').classList.replace('fa-chevron-down', 'fa-chevron-up');
                
                console.log('Получены метаданные видео:', data.data); // Для отладки
                
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
                
                // Если заголовок и описание новости пусты, заполняем их данными видео
                if (!titleField.value) {
                    titleField.value = data.data.title;
                }
                
                if (!shortDescField.value) {
                    shortDescField.value = data.data.description;
                }
                
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
                
                // Обработка обложки из видео
                if (data.data.thumbnail) {
                    // Сохраняем URL обложки в скрытое поле
                    videoThumbnailUrlField.value = data.data.thumbnail;
                    
                    // Если выбрана опция использования обложки из видео - показываем ее
                    if (useVideoThumbnailCheckbox.checked) {
                        videoThumbnailPreview.src = data.data.thumbnail;
                        videoThumbnailPreviewContainer.style.display = 'block';
                        uploadedImagePreview.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                videoUrlContainer.classList.remove('loading');
                showVideoUrlError('Произошла ошибка при получении данных видео');
                console.error('Error:', error);
            });
        }
        
        // Обработчик изменения чекбокса для использования обложки видео
        if (useVideoThumbnailCheckbox) {
            useVideoThumbnailCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    if (videoThumbnailUrlField.value) {
                        videoThumbnailPreview.src = videoThumbnailUrlField.value;
                        videoThumbnailPreviewContainer.style.display = 'block';
                        uploadedImagePreview.style.display = 'none';
                    }
                } else {
                    videoThumbnailPreviewContainer.style.display = 'none';
                    uploadedImagePreview.style.display = 'block';
                }
            });
        }
        
        // Показываем ошибку URL
        function showVideoUrlError(message) {
            videoUrlFeedback.textContent = message;
            videoUrlFeedback.classList.remove('text-success');
            videoUrlFeedback.classList.add('text-danger');
        }
        
        // Обработка предварительного просмотра видео
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
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/admin/news/create.blade.php ENDPATH**/ ?>