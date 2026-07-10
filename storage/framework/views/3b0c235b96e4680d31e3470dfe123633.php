<?php $__env->startSection('title',         'History Akses'); ?>
<?php $__env->startSection('page_title',    'History Akses'); ?>
<?php $__env->startSection('page_subtitle', 'Riwayat aktivitas akses brankas.'); ?>


<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/admin/history.css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="stat-grid">

        
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Akses Hari ini</span>
                <span class="stat-value" id="totalAksesValue"><?php echo e($totalAkses); ?></span>
            </div>
            <div class="stat-icon green">
                <svg viewBox="0 0 28 28" fill="none">
                    <path d="M8.41711 25.9729C11.9594 25.9729 14.8342 22.9373 14.8342 19.1971C14.8342 17.8148 14.4364 16.5409 13.769 15.4704L18.0428 10.9577L20.3401 13.3834L22.1497 11.4726L19.8524 9.04687L21.2513 7.56973L24.1904 10.6731L26 8.76228L23.061 5.65895L24.7166 3.91078L22.907 2L11.9465 13.5731C10.9326 12.8684 9.71337 12.4483 8.41711 12.4483C4.87487 12.4483 2 15.4839 2 19.2242C2 22.9644 4.87487 26 8.41711 26V25.9729ZM8.41711 15.1316C10.5348 15.1316 12.2674 16.961 12.2674 19.1971C12.2674 21.4331 10.5348 23.2626 8.41711 23.2626C6.29946 23.2626 4.56684 21.4331 4.56684 19.1971C4.56684 16.961 6.29946 15.1316 8.41711 15.1316Z" fill="#00A63E"/>
                </svg>
            </div>
        </div>

        
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Akses Berhasil</span>
                <span class="stat-value" id="aksesBerhasilValue"><?php echo e($aksesBerhasil); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    <path d="M14 26C20.6274 26 26 20.6274 26 14C26 7.37258 20.6274 2 14 2C7.37258 2 2 7.37258 2 14C2 20.6274 7.37258 26 14 26Z" stroke="#00A63E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 14L12.3333 17L19 11" stroke="#00A63E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Akses Gagal</span>
                <span class="stat-value" id="aksesGagalValue"><?php echo e($aksesGagal); ?></span>
            </div>
            <div class="stat-icon red">
                <svg viewBox="0 0 28 28" fill="none">
                    <g clip-path="url(#clip0_208_2614)">
                        <path d="M14 26C20.6274 26 26 20.6274 26 14C26 7.37258 20.6274 2 14 2C7.37258 2 2 7.37258 2 14C2 20.6274 7.37258 26 14 26Z" stroke="#EF4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.5 10.5L10.5 17.5" stroke="#EF4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.5 10.5L17.5 17.5" stroke="#EF4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_208_2614">
                            <rect width="28" height="28" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
        </div>

    </div>

    
    <div class="div-toolbar">

        
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari History..." oninput="filterTable()">
        </div>

        
        <div class="toolbar-actions">
            
            <button class="btn-download" onclick="downloadRekap()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Download Rekap
            </button>

            
            <div class="calendar-wrapper" style="position: relative;">
                <div class="date-picker" onclick="event.stopPropagation(); document.getElementById('historyIndexCalendarDropdown').classList.toggle('show')">
                    <span id="dateLabel">mm/dd/yyyy</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <input type="date" id="dateFilter" onchange="filterByDate()" hidden>
                </div>
                <?php echo $__env->make('components.c-shared.calendar', ['id' => 'historyIndexCalendarDropdown', 'target' => 'dateFilter'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

    </div>

    
    <div class="table-card">
        <div class="table-wrap table-responsive">
            <table id="historyTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>AKTIVITAS</th>
                        <th>METODE</th>
                        <th>WAKTU</th>
                        <th>TOTAL AKSES</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $hasData = count($histories) > 0; ?>
                    <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="data-row">
                            <td class="td-no"><?php echo e($index + 1); ?></td>
                            <td class="td-nama">
                                <span class="nama-user"><?php echo e($history['nama']); ?></span>
                            </td>
                            <td class="td-aktivitas"><?php echo e($history['aktivitas_bersih']); ?></td>
                            <td class="td-metode">
                                <?php if($history['fp_id']): ?>
                                    <?php
                                        $hash = md5(($history['user_id_asli'] ?? 99) . 'brandes_salt');
                                        $suffix = chr(65 + (ord($hash[0]) % 26)) . (ord($hash[1]) % 10) . chr(65 + (ord($hash[2]) % 26));
                                        $fullId = 'FP-' . str_pad($history['fp_id'], 3, '0', STR_PAD_LEFT) . '-' . $suffix;
                                    ?>
                                    <?php echo e($fullId); ?> <?php echo e(str_contains($history['metode'], '+ PIN') ? '+ PIN' : ''); ?>

                                <?php else: ?>
                                    <?php echo e($history['metode']); ?>

                                <?php endif; ?>
                            </td>
                            <td class="td-waktu">
                                <div class="time-column">
                                    <span class="time-main"><?php echo e(\Carbon\Carbon::parse($history['waktu'])->format('d M Y')); ?></span>
                                    <span class="time-sub"><?php echo e(\Carbon\Carbon::parse($history['waktu'])->format('H:i')); ?> WIB</span>
                                </div>
                            </td>
                            <td class="td-total">
                                <span class="badge-total"><?php echo e($history['total_akses']); ?>X</span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo e($history['status'] === 'Berhasil' ? 'status-berhasil' : 'status-gagal'); ?>">
                                    <?php echo e($history['status']); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'historyEmpty','colspan' => '7','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.','display' => ''.e($hasData ? 'none' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'historyEmpty','colspan' => '7','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.','display' => ''.e($hasData ? 'none' : '').'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'historySearchEmpty','colspan' => '7','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'historySearchEmpty','colspan' => '7','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']); ?>
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

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/admin/history.js'); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/restuaja/Downloads/project - revisi tapi benar (sudah sidang TA)/resources/views/admin/history.blade.php ENDPATH**/ ?>