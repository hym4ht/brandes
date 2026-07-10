<?php $__env->startSection('page_title'); ?>
    <div style="display: flex; align-items: center; gap: 8px;">
        Hi, <?php echo e(session('user.nama', 'User')); ?>

        <img src="<?php echo e(asset('images/img_shakehand.png')); ?>" alt="icon" style="width: 25px; height: 25px; object-fit: contain;">
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_subtitle', 'Kontrol kegiatan akses brankas anda disini.'); ?>


<?php $__env->startSection('content'); ?>
    <div class="dashboard-wrapper">
        
        
        <?php echo $__env->make('components.c-shared.status-brankas', ['brankas' => $brankas], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="stat-grid">
            
            
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Akses Saya Hari ini</span>
                    <div class="stat-icon">
                        <svg viewBox="0 0 28 28" fill="none">
                            <path d="M8.41711 25.9729C11.9594 25.9729 14.8342 22.9373 14.8342 19.1971C14.8342 17.8148 14.4364 16.5409 13.769 15.4704L18.0428 10.9577L20.3401 13.3834L22.1497 11.4726L19.8524 9.04687L21.2513 7.56973L24.1904 10.6731L26 8.76228L23.061 5.65895L24.7166 3.91078L22.907 2L11.9465 13.5731C10.9326 12.8684 9.71337 12.4483 8.41711 12.4483C4.87487 12.4483 2 15.4839 2 19.2242C2 22.9644 4.87487 26 8.41711 26V25.9729ZM8.41711 15.1316C10.5348 15.1316 12.2674 16.961 12.2674 19.1971C12.2674 21.4331 10.5348 23.2626 8.41711 23.2626C6.29946 23.2626 4.56684 21.4331 4.56684 19.1971C4.56684 16.961 6.29946 15.1316 8.41711 15.1316Z" fill="currentColor" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value"><?php echo e($totalAksesHariIni); ?></div>
                <div class="stat-divider"></div>
                <div class="stat-footer">
                    <span class="footer-text"><?php echo e($totalAksesHariIni > 0 ? 'Aktivitas terdeteksi hari ini' : 'Tidak ada aktivitas terdeteksi'); ?></span>
                </div>
            </div>

            
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Akses Terakhir Saya</span>
                    <div class="stat-icon">
                        <svg viewBox="0 0 28 28" fill="none">
                            <path d="M3.5 14C3.5 16.0767 4.11581 18.1068 5.26957 19.8335C6.42332 21.5602 8.0632 22.906 9.98182 23.7007C11.9004 24.4955 14.0116 24.7034 16.0484 24.2982C18.0852 23.8931 19.9562 22.8931 21.4246 21.4246C22.8931 19.9562 23.8931 18.0852 24.2982 16.0484C24.7034 14.0116 24.4955 11.9004 23.7007 9.98182C22.906 8.0632 21.5602 6.42332 19.8335 5.26957C18.1068 4.11581 16.0767 3.5 14 3.5C11.0646 3.51104 8.24713 4.65643 6.13667 6.69667L3.5 9.33333" stroke="#00A63E" stroke-width="2.24" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3 4V9.83333H8.83333" stroke="#00A63E" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M14 8.16699V14.0003L18.6667 16.3337" stroke="#00A63E" stroke-width="2.24" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <div class="stat-value"><?php echo e($lastAkses); ?></div>
                <div class="stat-divider"></div>
                <div class="stat-footer">
                    <span class="footer-text"><?php echo e($lastAkses !== '-' ? 'Aktivitas terakhir terdeteksi' : 'Belum ada aktivitas'); ?></span>
                </div>
            </div>

        </div>

        
        <div class="history-table-card">
            
            
            <a href="<?php echo e(route('history.akses')); ?>" class="table-card-header">
                <h3>Riwayat Akses</h3>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"></path>
                </svg>
            </a>



            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th width="80">NO</th>
                            <th>AKTIVITAS</th>
                            <th>METODE</th>
                            <th>WAKTU</th>
                            <th>TOTAL AKSES</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($histories) && count($histories) > 0): ?>
                            <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="data-row" data-date="<?php echo e(\Carbon\Carbon::parse($history['waktu'])->format('Y-m-d')); ?>">
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($history['aktivitas']); ?></td>
                                    <td>
                                        <?php echo str_replace(' + ', ' <span class="span-meta-dot"></span> ', $history['metode'] ?? '-'); ?>

                                    </td>
                                    <td>
                                        <div class="time-main"><?php echo e($history['waktu']); ?></div>
                                        <div class="time-sub"><?php echo e($history['waktu_lalu']); ?></div>
                                    </td>
                                    <td><?php echo e($history['total_akses']); ?>X</td>
                                    <td>
                                        <span class="badge <?php echo e($history['status'] === 'Berhasil' ? 'badge-success' : 'badge-danger'); ?>">
                                            <?php echo e($history['status']); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'historyEmpty','colspan' => '6','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'historyEmpty','colspan' => '6','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']); ?>
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
                        <?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'historySearchEmpty','colspan' => '6','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'historySearchEmpty','colspan' => '6','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']); ?>
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

    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/user/dashboard.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/user/dashboard-user.js']); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/user/dashboard.blade.php ENDPATH**/ ?>