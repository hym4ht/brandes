<?php $__env->startSection('title', 'List Admin'); ?>
<?php $__env->startSection('page_title', 'List Admin'); ?>
<?php $__env->startSection('page_subtitle', 'Kelola Administrator Sistem Keamanan Brankas.'); ?>


<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/admin/list-admin.css',
        'resources/css/components/c-admin/admin-edit.css',
        'resources/css/components/c-admin/admin-tambah.css',
        'resources/css/components/c-shared/tooltip-info.css'
    ]); ?>
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

    
    <div class="stat-grid">

        
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Admin</span>
                <span class="stat-value" id="totalAdminCount"><?php echo e($totalAdmin > 0 ? $totalAdmin : '-'); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    
                    <path
                        d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16666C15.1654 5.58933 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58933 5.83203 8.16666C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M22.4206 8.99915C22.4702 8.72563 22.4956 8.44678 22.4956 8.16577C22.4956 7.13197 22.1523 6.12744 21.5196 5.30984C20.887 4.49225 20.0007 3.90787 19 3.64844"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M24.7812 20.7598C24.9247 21.212 24.9993 21.6864 24.9996 22.1672V24.5005" stroke="#00A63E"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Admin Aktif</span>
                <span class="stat-value" id="adminAktifCount"><?php echo e($adminAktif > 0 ? $adminAktif : '-'); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    
                    <path
                        d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16667C15.1654 5.58934 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58934 5.83203 8.16667C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M19.6641 15.5L21.4974 17.1667L25.6641 13" stroke="#00A63E" stroke-width="2.4"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Admin Nonaktif</span>
                <span class="stat-value" id="adminNonaktifCount"><?php echo e($adminNonaktif ?: '-'); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    
                    <path
                        d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16667C15.1654 5.58934 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58934 5.83203 8.16667C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M21 12L26.2 17.2M26.2 12L21 17.2" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
        </div>

    </div>


    
    <div class="div-toolbar">

        
        <div class="search-box">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                    stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input type="text" id="searchInput" placeholder="Cari Admin..." onkeyup="filterTable()">
        </div>

        
        <div class="toolbar-actions">
            <div class="tooltip-target-wrapper">
                <button class="btn-tambah" onclick="openTambahModal()">
                    Tambah Admin
                </button>
                <?php if(session('user.role') === 'superadmin'): ?>
                    <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '1','title' => 'info 1','text' => 'Super admin bisa melakukan penambahan admin baru','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '1','title' => 'info 1','text' => 'Super admin bisa melakukan penambahan admin baru','position' => 'left']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $attributes = $__attributesOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__attributesOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $component = $__componentOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__componentOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
                <?php elseif(session('user.role') === 'admin'): ?>
                    <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '1','title' => 'info 1','text' => 'admin bisa melakukan penambahan admin baru','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '1','title' => 'info 1','text' => 'admin bisa melakukan penambahan admin baru','position' => 'left']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $attributes = $__attributesOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__attributesOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $component = $__componentOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__componentOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>


    
    <div class="table-card">
        <div class="table-wrap table-responsive">
            <table id="adminTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>PIN</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="data-row <?php echo e($loop->last ? 'last-row' : ''); ?>" data-id="<?php echo e($admin->id); ?>">

                            
                            <td class="td-no"><?php echo e($index + 1); ?></td>

                            
                            <td class="td-nama"><?php echo e($admin->nama); ?></td>

                            
                            <td>
                                <div class="td-pin">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M7.72559 7.84355L15.5376 0L16.945 1.41302L15.5376 2.82703L18 5.29931L16.5927 6.71333L14.1293 4.24005L12.722 5.65307L14.833 7.77259L13.4256 9.18661L11.3146 7.06609L9.13293 9.25656C9.80918 10.2899 10.08 11.5382 9.89306 12.7603C9.70612 13.9823 9.0747 15.0915 8.12078 15.8734C7.16687 16.6554 5.95816 17.0546 4.72818 16.994C3.49821 16.9334 2.33426 16.4172 1.46121 15.5452C0.587591 14.6699 0.0691057 13.4998 0.00642622 12.2622C-0.0562532 11.0246 0.341345 9.8078 1.12203 8.84801C1.90271 7.88821 3.01076 7.25393 4.23105 7.06831C5.45134 6.88269 6.69679 7.15899 7.72559 7.84355ZM7.09159 14.1322C7.38153 13.8568 7.61355 13.5259 7.77394 13.159C7.93433 12.792 8.01982 12.3966 8.02537 11.9959C8.03092 11.5952 7.95641 11.1975 7.80625 10.8262C7.65609 10.455 7.43332 10.1177 7.15111 9.83435C6.86891 9.55101 6.53299 9.32734 6.16322 9.17657C5.79345 9.02581 5.39732 8.951 4.99826 8.95657C4.5992 8.96214 4.2053 9.04798 3.83984 9.20902C3.47439 9.37005 3.1448 9.60301 2.87055 9.89412C2.32665 10.4595 2.02569 11.2168 2.03249 12.0029C2.03929 12.7889 2.35331 13.5408 2.90692 14.0967C3.46053 14.6525 4.20942 14.9678 4.99231 14.9746C5.7752 14.9814 6.52944 14.6793 7.09258 14.1332"
                                            fill="#00A63E" />
                                    </svg>
                                    <span><?php echo e($admin->pin_asli); ?></span>
                                </div>
                            </td>

                            
                            <td class="badge-role">
                                <?php if($admin->role === 'superadmin'): ?>
                                    Super Admin
                                <?php else: ?>
                                    <?php echo e(ucfirst($admin->role)); ?>

                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <span class="badge-status <?php echo e($admin->aktif ? 'aktif' : 'nonaktif'); ?>">
                                    <?php echo e($admin->aktif ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>

                            
                            <td>
                                <div class="tooltip-target-wrapper" style="display: flex; justify-content: center;">
                                    <?php if (isset($component)) { $__componentOriginal14938963ff2c822bd4d0c43b9ba9ccb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14938963ff2c822bd4d0c43b9ba9ccb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.opsi','data' => ['item' => $admin,'type' => 'admin']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.opsi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($admin),'type' => 'admin']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14938963ff2c822bd4d0c43b9ba9ccb4)): ?>
<?php $attributes = $__attributesOriginal14938963ff2c822bd4d0c43b9ba9ccb4; ?>
<?php unset($__attributesOriginal14938963ff2c822bd4d0c43b9ba9ccb4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14938963ff2c822bd4d0c43b9ba9ccb4)): ?>
<?php $component = $__componentOriginal14938963ff2c822bd4d0c43b9ba9ccb4; ?>
<?php unset($__componentOriginal14938963ff2c822bd4d0c43b9ba9ccb4); ?>
<?php endif; ?>
                                    <?php if(session('user.role') === 'superadmin' && $index === 1): ?>
                                        <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '2','title' => 'info 2','text' => 'Super admin bisa melakukan edit dan hapus terhadap admin yang sudah dibuat','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '2','title' => 'info 2','text' => 'Super admin bisa melakukan edit dan hapus terhadap admin yang sudah dibuat','position' => 'left']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $attributes = $__attributesOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__attributesOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $component = $__componentOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__componentOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
                                    <?php elseif(session('user.role') === 'admin' && $index === 1): ?>
                                        <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '2','title' => 'info 2','text' => 'admin bisa melakukan edit dan hapus terhadap admin yang sudah dibuat , tetapi tidak bisa ditujukan ke super admin','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '2','title' => 'info 2','text' => 'admin bisa melakukan edit dan hapus terhadap admin yang sudah dibuat , tetapi tidak bisa ditujukan ke super admin','position' => 'left']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $attributes = $__attributesOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__attributesOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1890100d97d52fcea52026969a199468)): ?>
<?php $component = $__componentOriginal1890100d97d52fcea52026969a199468; ?>
<?php unset($__componentOriginal1890100d97d52fcea52026969a199468); ?>
<?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'emptyState','colspan' => '6','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.','display' => ''.e(($totalAdmin > 0) ? 'none' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'emptyState','colspan' => '6','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.','display' => ''.e(($totalAdmin > 0) ? 'none' : '').'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4e9f5132868f235363a5b31f0c2548c9)): ?>
<?php $attributes = $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9; ?>
<?php unset($__attributesOriginal4e9f5132868f235363a5b31f0c2548c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4e9f5132868f235363a5b31f0c2548c9)): ?>
<?php $component = $__componentOriginal4e9f5132868f235363a5b31f0c2548c9; ?>
<?php unset($__componentOriginal4e9f5132868f235363a5b31f0c2548c9); ?>
<?php endif; ?>

                    
                    <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'searchNotFoundState','colspan' => '6','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'searchNotFoundState','colspan' => '6','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4e9f5132868f235363a5b31f0c2548c9)): ?>
<?php $attributes = $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9; ?>
<?php unset($__attributesOriginal4e9f5132868f235363a5b31f0c2548c9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4e9f5132868f235363a5b31f0c2548c9)): ?>
<?php $component = $__componentOriginal4e9f5132868f235363a5b31f0c2548c9; ?>
<?php unset($__componentOriginal4e9f5132868f235363a5b31f0c2548c9); ?>
<?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    
    <?php $__env->startPush('modals'); ?>
        <?php echo $__env->make('components.c-admin.modal.admin-tambah', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('components.c-shared.delete', ['type' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('components.c-admin.modal.admin-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/js/components/c-shared/opsi.js',
        'resources/js/components/c-admin/admin-tambah.js',
        'resources/js/components/c-admin/admin-edit.js',
        'resources/js/components/c-shared/delete.js',
        'resources/js/components/c-shared/tour-guide.js',
        'resources/js/admin/list-admin.js'
    ]); ?>

    
    <?php if(session('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(() => {
                    if (window.showToast) window.showToast('Berhasil', '<?php echo e(session('success')); ?>', 4000);
                }, 300);
            });
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(() => {
                    if (window.showToast) window.showToast('Gagal', '<?php echo e(session('error')); ?>', 5000);
                }, 300);
            });
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/admin/list-admin.blade.php ENDPATH**/ ?>