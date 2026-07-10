
<header class="topbar">

    
    <div class="topbar-left-wrapper">

        
        <button class="mobile-menu-btn" onclick="toggleSidebarMobile()" title="Menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        
        <div class="topbar-left">
            <div class="topbar-text">
                <h1><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
                <p><?php echo $__env->yieldContent('page_subtitle', 'Sistem Keamanan Brankas BRANDES'); ?></p>
            </div>
        </div>
    </div>

    
    <div class="user-menu-container">

        
        <div class="user-badge" id="userBadge" onclick="toggleUserDropdown(event)">
            <div class="user-avatar">
                <?php echo e(strtoupper(substr(session('user.nama', 'A'), 0, 1))); ?>

            </div>
            <span class="user-name-text">
                <?php echo e(session('user.nama', 'Admin')); ?>

            </span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="arrow-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>

        
        <?php echo $__env->make("components.c-shared.profile", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

</header><?php /**PATH /home/restuaja/Downloads/project - revisi tapi benar (sudah sidang TA)/resources/views/components/c-admin/navigation/topbar.blade.php ENDPATH**/ ?>