<!DOCTYPE html>
<html lang="id">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Brandes'); ?> - Sistem Monitoring Brankas</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/shared/colors.css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/layouts/app-admin.css'); ?>
    
    
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/components/c-shared/calendar.css',
        'resources/css/components/c-shared/profile.css',
        'resources/css/components/c-shared/status.css',
        'resources/css/components/c-shared/delete.css',
        'resources/css/components/c-shared/logout.css',
        'resources/css/components/c-shared/opsi.css',
        'resources/css/components/c-shared/security-card.css',
        'resources/css/components/c-shared/toast.css'
    ]); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>

    
    
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js" defer></script>
    
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/layouts/app.js'); ?>
    
    
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/js/components/c-shared/calendar.js',
        'resources/js/components/c-shared/logout.js',
        'resources/js/components/c-shared/status-toggle.js',
        'resources/js/components/c-shared/toast.js'
    ]); ?>
</head>

<body data-turbo-prefetch="true">
    
    
    <meta name="user-updated-at" content="<?php echo e(session('user.updated_at') ?? '0'); ?>">

    
    <script>
        if (localStorage.getItem('theme_mode') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    
    <?php echo $__env->make('components.c-admin.navigation.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php echo $__env->make('components.c-shared.logout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.c-shared.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="main-wrap" id="mainWrap">
        
        
        <script>
            if (localStorage.getItem('sidebar_collapsed') === 'true') {
                document.getElementById('mainWrap').classList.add('collapsed');
            }
        </script>

        
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebarMobile()"></div>

        
        <?php echo $__env->make('components.c-admin.navigation.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <main class="page-content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        <?php echo $__env->yieldPushContent('modals'); ?>

    </div>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/layouts/app-admin.blade.php ENDPATH**/ ?>