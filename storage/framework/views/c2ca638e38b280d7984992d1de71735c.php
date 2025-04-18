

<?php $__env->startSection('title', 'Страница не найдена - 404'); ?>
<?php $__env->startSection('description', 'К сожалению, запрашиваемая страница не найдена.'); ?>

<?php $__env->startSection('seo'); ?>
<meta name="robots" content="noindex, follow">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 fw-bold">404</h1>
            <p class="fs-1 text-muted">Страница не найдена</p>
            <p class="lead mb-4">К сожалению, запрашиваемая страница не существует или была удалена.</p>
            
            <div class="mb-5">
                <a href="<?php echo e(url('/')); ?>" class="btn btn-primary me-2">
                    <i class="fas fa-home me-1"></i> На главную
                </a>
                <a href="<?php echo e(route('recipes.index')); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-utensils me-1"></i> Смотреть рецепты
                </a>
            </div>
            
            <div class="mt-5">
                <h3 class="h5 mb-3">Возможно, вас заинтересует:</h3>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                        // Получаем случайные рецепты для рекомендаций
                        $randomRecipes = \App\Models\Recipe::where('is_published', true)
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();
                    ?>
                    
                    <?php $__currentLoopData = $randomRecipes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="<?php echo e(asset($recipe->image_url)); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo e($recipe->title); ?>" 
                                     style="height: 150px; object-fit: cover;"
                                     onerror="window.handleImageError(this)">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo e($recipe->title); ?></h5>
                                    <a href="<?php echo e(route('recipes.show', $recipe->slug)); ?>" class="btn btn-sm btn-primary mt-2">Посмотреть</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/errors/404.blade.php ENDPATH**/ ?>