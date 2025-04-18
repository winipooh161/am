<?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100 news-card">
            <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="card-img-top-link">
                <img src="<?php echo e($item->getThumbnailUrl()); ?>" 
                     class="card-img-top" 
                     alt="<?php echo e($item->title); ?>" 
                     loading="lazy">
            </a>
            <div class="card-body">
                <h5 class="card-title">
                    <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="text-dark">
                        <?php echo e($item->title); ?>

                    </a>
                </h5>
                <p class="card-text text-muted small mb-2">
                    <i class="far fa-calendar-alt me-1"></i> <?php echo e($item->created_at->format('d.m.Y')); ?>

                    <i class="far fa-eye ms-2 me-1"></i> <?php echo e($item->views); ?>

                </p>
                <p class="card-text"><?php echo e(Str::limit($item->short_description, 100)); ?></p>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="btn btn-outline-primary btn-sm">
                    Читать полностью
                </a>
                <?php if($item->hasVideo()): ?>
                <span class="badge bg-danger ms-2">
                    <i class="fas fa-play-circle"></i> Видео
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="alert alert-info">
            Новости не найдены. Пожалуйста, попробуйте другой поисковый запрос.
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\OSPanel\domains\eats\resources\views/news/partials/news_items.blade.php ENDPATH**/ ?>