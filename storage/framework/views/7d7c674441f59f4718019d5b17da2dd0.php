<?php $__env->startSection('title', 'Lokasi Brankas'); ?>
<?php $__env->startSection('page_title', 'Lokasi Brankas'); ?>
<?php $__env->startSection('page_subtitle', 'Monitoring lokasi brankas real-time dari GPS Neo-6M via ESP32'); ?>


<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/admin/lokasi.css'); ?>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>

    

    <div class="lokasi-grid">

        
        <div class="map-card">

            
            <div class="map-card-header">
                <span>Lokasi Saat Ini</span>

                <span class="gps-badge <?php echo e(($brankas->hdop ?? 99) <= 2.0 ? 'valid' : 'invalid'); ?>"></span>
            </div>

            
            <div class="map-wrap">
                <iframe id="lokasi-map"
                    src="https://maps.google.com/maps?q=<?php echo e($brankas->latitude ?? -6.9105); ?>,<?php echo e($brankas->longitude ?? 109.1479); ?>&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

        </div>


        
        <div class="info-col">
            <div class="info-card">
                <div class="info-card-header">Koordinat detail GPS</div>

                <div class="info-card-body">
                    <div class="coord-group">

                        
                        <div class="coord-pair">

                            
                            <div class="coord-box">
                                <div class="coord-label">Latitude</div>
                                <div class="coord-value" id="val-lat">
                                    <?php echo e($brankas->latitude ?? '-'); ?>

                                </div>
                            </div>

                            
                            <div class="coord-box">
                                <div class="coord-label">Longtitude</div>
                                <div class="coord-value" id="val-lng">
                                    <?php echo e($brankas->longitude ?? '-'); ?>

                                </div>
                            </div>

                        </div>

                        
                        <div class="coord-secondary">

                            
                            <div class="coord-box">
                                <div class="coord-label">HDOP</div>
                                <div class="coord-value" id="val-hdop">
                                    <?php echo e($brankas->hdop ?? '-'); ?>

                                </div>
                            </div>

                            
                            <div class="coord-box">
                                <div class="coord-label">Satelit</div>
                                <div class="coord-value" id="val-sat">
                                    <?php echo e($brankas->satellites ?? '-'); ?>

                                </div>
                            </div>

                            
                            <div class="coord-box">
                                <div class="coord-label">Timestamp</div>
                                <div class="coord-value" id="val-time">
                                    <?php echo e($brankas?->last_gps_update?->format('Y-m-d H:i:s') ?? '-'); ?>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="info-divider"></div>

                    
                    <div class="brankas-section">
                        <div class="brankas-title">Detail Lokasi</div>

                        <div class="info-group-wrapper">
                            
                            <div class="info-row">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M3 21H21M9 8H10M9 12H10M9 16H10M14 8H15M14 12H15M14 16H15M5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21"
                                        stroke="currentColor" stroke-width="1.67" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="brankas-text" id="lokasi-nama-val">
                                    <?php echo e($brankas->nama_brankas ?? '-'); ?>

                                </span>
                            </div>

                            
                            <div class="info-row">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M20 10C20 14.993 14.461 20.193 12.601 21.799C12.4277 21.9293 12.2168 21.9998 12 21.9998C11.7832 21.9998 11.5723 21.9293 11.399 21.799C9.539 20.193 4 14.993 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z"
                                        stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z"
                                        stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="brankas-text" id="lokasi-detail-val">
                                    <?php echo e($brankas->lokasi ?? '-'); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    
    <div class="history-table-card">

        
        <div class="table-card-header">
            <h3>Riwayat Posisi GPS</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>WAKTU</th>
                        <th>BRANKAS</th>
                        <th>KOORDINAT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        
                        <tr class="data-row">
                            <td class="td-waktu">
                                <div class="time-main"><?php echo e($h->recorded_at->format('d M Y')); ?></div>
                                <div class="time-sub"><?php echo e($h->recorded_at->format('H:i:s')); ?></div>
                            </td>
                            <td class="td-brankas"><?php echo e($h->brankas?->nama_brankas ?? '-'); ?></td>

                            
                            <td class="td-koordinat">
                                <div>Lat: <strong><?php echo e($h->latitude ?? '-'); ?></strong></div>
                                <div>Lng: <strong><?php echo e($h->longitude ?? '-'); ?></strong></div>
                            </td>

                            
                            <td>
                                <span class="badge <?php echo e($h->status === 'normal' ? 'badge-success' : ($h->status === 'waspada' ? 'badge-warning' : 'badge-danger')); ?>">
                                    <?php echo e($h->statusLabel()); ?>

                                </span>
                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        
                        <?php if (isset($component)) { $__componentOriginal4e9f5132868f235363a5b31f0c2548c9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4e9f5132868f235363a5b31f0c2548c9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'historyEmpty','colspan' => '4','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'historyEmpty','colspan' => '4','title' => 'Belum Ada Data','desc' => 'Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat.']); ?>
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
                </tbody>
            </table>
        </div>

    </div>

<?php $__env->stopSection(); ?>



<?php $__env->startPush('scripts'); ?>

    <script>
        window.GPS_CONFIG = {
            lat: <?php echo json_encode($brankas->latitude ?? -6.9105, 15, 512) ?>,
            lng: <?php echo json_encode($brankas->longitude ?? 109.1479, 15, 512) ?>,
            gpsValid: <?php echo json_encode(($brankas->hdop ?? 99) <= 2.0, 15, 512) ?>,
            namaBrankas: <?php echo json_encode($brankas->nama_brankas ?? 'Sistem Monitoring Brankas', 15, 512) ?>,
            kodeBrankas: <?php echo json_encode($brankas->kode_brankas ?? 'BRX-001', 15, 512) ?>,
            lokasi: <?php echo json_encode($brankas->lokasi ?? '-', 15, 512) ?>,
            status: <?php echo json_encode($brankas->status ?? 'normal', 15, 512) ?>
        };
    </script>

    
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/admin/lokasi.js'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/restuaja/Downloads/project - revisi tapi benar (sudah sidang TA)/resources/views/admin/lokasi.blade.php ENDPATH**/ ?>