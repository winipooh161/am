

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Управление новостями</h1>
        <a href="<?php echo e(route('admin.news.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Создать новость
        </a>
    </div>
    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Все новости</h5>
                <span class="badge bg-primary">Всего: <?php echo e($news->total()); ?></span>
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
                        <?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->id); ?></td>
                                <td>
                                    <?php if($item->image_url): ?>
                                        <img src="<?php echo e(asset('uploads/' . $item->image_url)); ?>" 
                                             alt="<?php echo e($item->title); ?>" 
                                             class="img-thumbnail" 
                                             style="width: 80px;"
                                             data-no-random>
                                    <?php else: ?>
                                        <div class="bg-light text-center" style="width: 80px; height: 60px;">
                                            <i class="fas fa-image text-muted" style="line-height: 60px;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.news.edit', $item)); ?>">
                                        <?php echo e(Str::limit($item->title, 50)); ?>

                                    </a>
                                    <div class="small text-muted"><?php echo e(Str::limit($item->short_description, 70)); ?></div>
                                </td>
                                <td>
                                    <?php if($item->is_published): ?>
                                        <span class="badge bg-success">Опубликовано</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Черновик</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($item->views); ?></td>
                                <td><?php echo e($item->created_at->format('d.m.Y H:i')); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('news.show', $item->slug)); ?>" class="btn btn-sm btn-info" target="_blank" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.news.edit', $item)); ?>" class="btn btn-sm btn-primary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.news.destroy', $item)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите удалить эту новость?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">Новости отсутствуют</div>
                                    <a href="<?php echo e(route('admin.news.create')); ?>" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Создать новость
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <?php echo e($news->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\eats\resources\views/admin/news/index.blade.php ENDPATH**/ ?>