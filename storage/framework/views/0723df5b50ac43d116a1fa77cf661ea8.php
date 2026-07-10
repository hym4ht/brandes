<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('header-title', 'Dashboard'); ?>


<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/admin/dashboard.css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/components/c-shared/history-card.css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/components/c-shared/security-card.css'); ?>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('components.c-shared.status-brankas', [
        'brankas' => $brankas
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    
    <div class="stat-grid">

        
        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Total Akses Hari Ini</span>
                <div class="stat-icon">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                        <path d="M8.41711 25.9729C11.9594 25.9729 14.8342 22.9373 14.8342 19.1971C14.8342 17.8148 14.4364 16.5409 13.769 15.4704L18.0428 10.9577L20.3401 13.3834L22.1497 11.4726L19.8524 9.04687L21.2513 7.56973L24.1904 10.6731L26 8.76228L23.061 5.65895L24.7166 3.91078L22.907 2L11.9465 13.5731C10.9326 12.8684 9.71337 12.4483 8.41711 12.4483C4.87487 12.4483 2 15.4839 2 19.2242C2 22.9644 4.87487 26 8.41711 26V25.9729ZM8.41711 15.1316C10.5348 15.1316 12.2674 16.961 12.2674 19.1971C12.2674 21.4331 10.5348 23.2626 8.41711 23.2626C6.29946 23.2626 4.56684 21.4331 4.56684 19.1971C4.56684 16.961 6.29946 15.1316 8.41711 15.1316Z" fill="currentColor" />
                    </svg>
                </div>
            </div>

            <div class="stat-value" id="val-stat-akses"><?php echo e($statAkses['total'] > 0 ? $statAkses['total'] : '-'); ?></div>
            <div class="stat-divider"></div>

            <div class="stat-footer">
                <span class="footer-text">Aktivitas terdeteksi hari ini</span>
            </div>
        </div>

        
        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Total Notifikasi Hari Ini</span>
                <div class="stat-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M10.2695 21C10.4451 21.304 10.6975 21.5565 11.0016 21.732C11.3056 21.9075 11.6505 21.9999 12.0015 21.9999C12.3526 21.9999 12.6975 21.9075 13.0015 21.732C13.3055 21.5565 13.558 21.304 13.7335 21" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.26127 15.326C3.13063 15.4692 3.04442 15.6472 3.01312 15.8385C2.98183 16.0298 3.00679 16.226 3.08498 16.4034C3.16316 16.5807 3.2912 16.7316 3.45352 16.8375C3.61585 16.9434 3.80545 16.9999 3.99927 17H19.9993C20.1931 17.0001 20.3827 16.9438 20.5451 16.8381C20.7076 16.7324 20.8358 16.5817 20.9142 16.4045C20.9926 16.2273 21.0178 16.0311 20.9867 15.8398C20.9557 15.6485 20.8697 15.4703 20.7393 15.327C19.4093 13.956 17.9993 12.449 17.9993 8C17.9993 6.4087 17.3671 4.88258 16.2419 3.75736C15.1167 2.63214 13.5906 2 11.9993 2C10.408 2 8.88185 2.63214 7.75663 3.75736C6.63141 4.88258 5.99927 6.4087 5.99927 8C5.99927 12.449 4.58827 13.956 3.26127 15.326Z" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="stat-value" id="val-stat-notif"><?php echo e($statNotif['total'] > 0 ? $statNotif['total'] : '-'); ?></div>
            <div class="stat-divider"></div>

            <div class="stat-footer">
                <span class="footer-text">Notifikasi keamanan hari ini</span>
            </div>
        </div>

        
        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Akses Terakhir Hari Ini</span>
                <div class="stat-icon">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                        <path d="M3.5 14C3.5 16.0767 4.11581 18.1068 5.26957 19.8335C6.42332 21.5602 8.0632 22.906 9.98182 23.7007C11.9004 24.4955 14.0116 24.7034 16.0484 24.2982C18.0852 23.8931 19.9562 22.8931 21.4246 21.4246C22.8931 19.9562 23.8931 18.0852 24.2982 16.0484C24.7034 14.0116 24.4955 11.9004 23.7007 9.98182C22.906 8.0632 21.5602 6.42332 19.8335 5.26957C18.1068 4.11581 16.0767 3.5 14 3.5C11.0646 3.51104 8.24713 4.65643 6.13667 6.69667L3.5 9.33333" stroke="currentColor" stroke-width="2.24" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3 4V9.83333H8.83333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M14 8.16699V14.0003L18.6667 16.3337" stroke="currentColor" stroke-width="2.24" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

            <div class="stat-value" id="val-stat-last-name"><?php echo e($lastAkses['name']); ?></div>
            <div class="stat-divider"></div>

            <div class="stat-footer">
                <span class="footer-text" id="val-stat-last-time"><?php echo e($lastAkses['label']); ?></span>
            </div>
        </div>

    </div>


    
    <div class="dashboard-grid">

        
        <div class="card">

            
            <a href="<?php echo e(route('lokasi.brankas')); ?>" class="card-header">
                <span class="card-title">Lokasi Brankas</span>
                <span class="card-arrow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9.5 6L15.5 12L9.5 18" stroke="#6A7282" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
            <div class="card-divider"></div>

            
            <div class="map-wrap">
                <iframe id="brankas-map"
                    src="https://maps.google.com/maps?q=-6.9105,109.1479&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            
            <div class="brankas-info">
                <div class="brankas-content-top">
                    <div class="brankas-info-title">Detail Lokasi</div>

                    <div class="info-group-wrapper">

                        
                        <div class="info-row">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M3 21H21M9 8H10M9 12H10M9 16H10M14 8H15M14 12H15M14 16H15M5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span id="brankas-nama-val">
                                <?php echo e($brankas->nama_brankas ?? '-'); ?>

                            </span>
                        </div>

                        
                        <div class="info-row">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M20 10C20 14.993 14.461 20.193 12.601 21.799C12.4277 21.9293 12.2168 21.9998 12 21.9998C11.7832 21.9998 11.5723 21.9293 11.399 21.799C9.539 20.193 4 14.993 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span id="brankas-lokasi-val">
                                <?php echo e($brankas->lokasi ?? '-'); ?>

                            </span>
                        </div>

                    </div>
                </div>

                
                <div class="brankas-footer">
                    <div class="info-coords">
                        <span>Lat: <strong id="brankas-lat-val">-</strong></span>
                        <svg class="meta-dot" width="6" height="6" viewBox="0 0 8 8" fill="none">
                            <circle cx="4" cy="4" r="4" fill="#D1D5DB" />
                        </svg>
                        <span>Lng: <strong id="brankas-lng-val">-</strong>°</span>
                    </div>
                </div>
            </div>

        </div>


        
        <div class="card">

            
            <a href="<?php echo e(route('history.akses')); ?>" class="card-header">
                <span class="card-title">History Akses Terbaru</span>
                <span class="card-arrow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9.5 6L15.5 12L9.5 18" stroke="#6A7282" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
            <div class="card-divider"></div>

            
            <div class="history-header-group">
                
                <div class="history-search">
                    <div class="history-inputs-row">

                        
                        <div class="search-box history-search-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L16.65 16.65M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <input type="text" id="historySearch" placeholder="Cari History..." oninput="filterHistory()">
                        </div>

                        
                        <div class="calendar-wrapper" style="position: relative;">
                            <div class="date-picker" onclick="event.stopPropagation(); document.getElementById('dashboardCalendarDropdown').classList.toggle('show')">
                                <span id="dateLabel">mm / dd / yyyy</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                </svg>
                                <input type="date" id="historyDateFilter" onchange="filterHistory()" hidden>
                            </div>
                            <?php echo $__env->make('components.c-shared.calendar', ['id' => 'dashboardCalendarDropdown', 'target' => 'historyDateFilter'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                    </div>
                </div>

                
                <div class="history-banner-wrapper" id="historyDateGroup">
                    <div class="history-banner-content">
                        <span id="historyDateText">-</span>
                    </div>
                </div>
            </div>

            <div class="card-divider"></div>

            
            <div id="historyList">

                
                <?php if($histories->isNotEmpty()): ?>
                    <div class="history-label-date">
                        <?php echo e(\Carbon\Carbon::parse($histories->first()->waktu)->format('Y-m-d')); ?>

                    </div>
                <?php endif; ?>

                
                <?php if($histories->isEmpty()): ?>
                    <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'historyNoData','isTable' => false,'title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'historyNoData','isTable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']); ?>
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
                <?php else: ?>
                    
                    <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $userId = $history->user_id ?? 99;
                            $hash = md5($userId . 'brandes_salt');
                            $suffix = chr(65 + (ord($hash[0]) % 26)) . (ord($hash[1]) % 10) . chr(65 + (ord($hash[2]) % 26));

                            // Ambil dari tabel user jika ada relasi, atau fallback ke tabel history
                            $fpId = $history->user->fingerprint_id ?? $history->fingerprint_id;

                            $metodeLabel = str_contains($history->aktivitas ?? '', 'PIN') ? '+ PIN' : '';
                            $fp_id_formatted = $fpId ? 'FP-' . str_pad($fpId, 3, '0', STR_PAD_LEFT) . '-' . $suffix . ' ' . $metodeLabel : '-';
                        ?>
                        <?php echo $__env->make('components.c-shared.history-card', [
                            'type' => $history->status === 'Berhasil' ? 'success' : 'danger',
                            'name' => $history->user->nama ?? ($history->nama ?? 'Unknown'),
                            'action' => trim(explode('(', ($history->aktivitas ?? 'Akses Brankas'))[0]),
                            'badge' => $fp_id_formatted,
                            'time' => $history->waktu ? (method_exists($history->waktu, 'diffForHumans') ? $history->waktu->diffForHumans() : \Carbon\Carbon::parse($history->waktu)->diffForHumans()) : '-',
                            'date' => $history->waktu ? \Carbon\Carbon::parse($history->waktu)->format('Y-m-d') : date('Y-m-d')
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php endif; ?>

                
                <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'historyEmpty','display' => 'none','isTable' => false,'type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'historyEmpty','display' => 'none','isTable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.']); ?>
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

            </div>

        </div>

    </div>


    
    <div class="card">

        
        <a href="<?php echo e(route('notifikasi.keamanan')); ?>" class="card-header">
            <span class="card-title">Notifikasi Keamanan Terbaru</span>
            <span class="card-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M9.5 6L15.5 12L9.5 18" stroke="#6A7282" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </a>
        <div class="card-divider"></div>

        
        <div class="history-search">
            <div class="history-inputs-row">

                
                <div class="search-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L16.65 16.65M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <input type="text" id="notifSearch" placeholder="Cari Notifikasi..." oninput="filterNotifications()">
                </div>

                
                <div class="calendar-wrapper" style="position: relative;">
                    <div class="date-picker" onclick="event.stopPropagation(); document.getElementById('notifCalendarDropdown').classList.toggle('show')">
                        <span id="notifDateLabel">mm / dd / yyyy</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        <input type="date" id="notifDateFilter" onchange="filterNotifByDate()" hidden>
                    </div>
                    <?php echo $__env->make('components.c-shared.calendar', ['id' => 'notifCalendarDropdown', 'target' => 'notifDateFilter', 'labelId' => 'notifDateLabel'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

            </div>
        </div>

        
        <div class="panel-header">
            <div class="filter-row">

                
                <div class="filter-tabs">
                    <button class="tab-btn active" onclick="filterTab('semua', this)">Semua</button>
                    <button class="tab-btn" onclick="filterTab('kritis', this)">Kritis</button>
                    <button class="tab-btn" onclick="filterTab('peringatan', this)">Peringatan</button>
                    <button class="tab-btn" onclick="filterTab('akses', this)">Akses</button>
                </div>

                
                <div class="legend">
                    <div class="legend-item"><span class="dot red"></span> Kritis</div>
                    <div class="legend-item"><span class="dot yellow"></span> Peringatan</div>
                    <div class="legend-item"><span class="dot green"></span> Akses</div>
                </div>

            </div>
            <hr class="panel-divider">
        </div>

        
        <div class="notif-list" id="notifList">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('components.c-shared.security-card', [
                    'role' => 'admin',
                    'type' => $notif['tipe'],
                    'title' => $notif['judul'],
                    'description' => $notif['deskripsi'],
                    'meta' => $notif['meta'] ?? [],
                    'time' => $notif['waktu']
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                
                <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'notifNoData','isTable' => false,'title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'notifNoData','isTable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'notifEmpty','isTable' => false,'type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'notifEmpty','isTable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda.','display' => 'none']); ?>
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
        </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/admin/dashboard-admin.js'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/restuaja/Downloads/project - revisi tapi benar (sudah sidang TA)/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>