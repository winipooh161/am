          
        <!-- Хлебные крошки (microdata) для SEO -->
        <?php if(!request()->is('/')): ?>
        <div class="container mt-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?php echo e(url('/')); ?>" itemprop="item"><span itemprop="name">Главная</span></a>
                        <meta itemprop="position" content="1" />
                    </li>
                    
                    <?php echo $__env->yieldContent('breadcrumbs'); ?>
                    
                    <?php if(isset($activeRoute)): ?>
                        <li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" aria-current="page">
                            <span itemprop="name"><?php echo e($activeRoute); ?></span>
                            <meta itemprop="position" content="2" />
                        </li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
        <?php endif; ?>
            <?php if(session('success')): ?>
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </main>
        
        <footer class="bg-light py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="mb-3">О проекте</h5>
                        <p class="text-muted">Яедок.ру - ваша онлайн кулинарная книга с тысячами разнообразных рецептов.</p>
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-3">Быстрые ссылки</h5>
                        <ul class="list-unstyled">
                            <li><a href="<?php echo e(route('recipes.index')); ?>" class="text-decoration-none">Все рецепты</a></li>
                            <li><a href="<?php echo e(route('categories.index')); ?>" class="text-decoration-none">Категории</a></li>
                            <li><a href="<?php echo e(route('search')); ?>" class="text-decoration-none">Поиск</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-3">Связаться с нами</h5>
                        <p class="text-muted">
                            <i class="fas fa-envelope me-2"></i> <a href="mailto:w1nishko@yandex.ru">w1nishko@yandex.ru</a><br>
                            <i class="fas fa-phone me-2"></i> <a href="tel:+79044482283">+7 904 448-22-83</a>
                            <i class="fas  me-2"></i> <a href="https://vkvideo.ru/@imedokru/clips">ВК-ВИДЕО shorts</a>
                            <i class="fas  me-2"></i> <a href="https://rutube.ru/channel/60757569/shorts/">RUTUBE shorts</a>
                            <i class="fas  me-2"></i> <a href="https://vk.com/imedokru">ВК-ГРУППА</a>
                            <i class="fas  me-2"></i> <a href="https://t.me/imedokru">Телеграм канал</a>
                        </p>
                    </div>
                </div>
               
            </div>
        </footer>
        
        <!-- Футер с правовыми ссылками и структурированными данными для SEO -->
        <div class="footer mt-auto py-3 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'Я едок')); ?>. Все права защищены.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="<?php echo e(route('legal.terms')); ?>" class="text-muted">Пользовательское соглашение</a></li>
                            <li class="list-inline-item"><a href="<?php echo e(route('legal.privacy')); ?>" class="text-muted">Политика конфиденциальности</a></li>
                            <li class="list-inline-item"><a href="<?php echo e(route('legal.disclaimer')); ?>" class="text-muted">Отказ от ответственности</a></li>
                            <li class="list-inline-item"><a href="<?php echo e(route('legal.dmca')); ?>" class="text-muted">Правообладателям</a></li>
                            <li class="list-inline-item"><a href="<?php echo e(route('sitemap')); ?>" class="text-muted">Карта сайта</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
     
        <!-- Cookie Consent Banner -->
        <div id="cookie-consent" class="position-fixed bottom-0 start-0 end-0 bg-dark text-white p-3" style="z-index: 9999; display: none;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-md-0">Мы используем файлы cookie для улучшения работы сайта. Продолжая пользоваться сайтом, вы соглашаетесь с использованием файлов cookie и принимаете условия <a href="<?php echo e(route('legal.privacy')); ?>" class="text-white text-decoration-underline">Политики конфиденциальности</a>.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <button id="accept-cookies" class="btn btn-primary me-2">Принять</button>
                        <button id="decline-cookies" class="btn btn-outline-light">Отказаться</button>
                    </div>
                </div>
            </div>
        </div>
        |
          <!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();
    for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
    k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
 
    ym(100639873, "init", {
         clickmap:true,
         trackLinks:true,
         accurateTrackBounce:true,
         webvisor:true
    });
 </script>
 <noscript><div><img src="https://mc.yandex.ru/watch/100639873" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<?php /**PATH C:\OSPanel\domains\eats\resources\views/partials/footer.blade.php ENDPATH**/ ?>