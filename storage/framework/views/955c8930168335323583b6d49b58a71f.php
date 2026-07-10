<?php $__env->startSection('title', 'Log Berkas'); ?>
<?php $__env->startSection('page_title', 'Log Berkas'); ?>
<?php $__env->startSection('page_subtitle', 'Kelola data log berkas beserta berkas pendukung.'); ?>


<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/user/log-berkas.css',
        'resources/css/components/c-shared/empty.css',
        'resources/css/components/c-shared/tooltip-info.css'
    ]); ?>
    
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Semua Berkas</span>
                <span class="stat-value" id="totalBerkasCount"><?php echo e($totalBerkas > 0 ? $totalBerkas : '-'); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total KK</span>
                <span class="stat-value" id="totalKkCount"><?php echo e($totalKk > 0 ? $totalKk : '-'); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total KTP</span>
                <span class="stat-value" id="totalKtpCount"><?php echo e($totalKtp > 0 ? $totalKtp : '-'); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Akte</span>
                <span class="stat-value" id="totalAkteCount"><?php echo e($totalAkte > 0 ? $totalAkte : '-'); ?></span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
        </div>

    </div>

    
    <div class="div-toolbar">
        <div class="search-box">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input type="text" id="searchInput" placeholder="Cari Log Berkas..." onkeyup="filterTable()">
        </div>

        <div class="toolbar-actions">
            
        </div>
    </div>

    
    <div class="table-card">
        <div class="table-wrap table-responsive">
            <table id="logTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>TANGGAL</th>
                        <th>BERKAS KTP</th>
                        <th>BERKAS KK</th>
                        <th>BERKAS AKTE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="data-row <?php echo e($loop->last ? 'last-row' : ''); ?>" data-id="<?php echo e($log->id); ?>">
                            <td class="td-no"><?php echo e($index + 1); ?></td>
                            <td class="td-judul">
                                <strong><?php echo e($log->judul); ?></strong>
                                <div style="font-size: 14px; color: var(--C-Black-Second); margin-top:4px;">
                                    NIK: <?php echo e($log->ktp_nik); ?>

                                </div>
                            </td>
                            <td><?php echo e(\Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i')); ?></td>
                            
                            
                            <td>
                                <?php if($log->file_ktp): ?>
                                    <?php if(isset($log->status_ktp) && $log->status_ktp == 'Diambil'): ?>
                                        <span class="badge-status nonaktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>" style="cursor: pointer;" onclick="openDetailDokumenModal('KTP', '<?php echo e($log->file_ktp); ?>', '<?php echo e(route('dokumen.download', $log->file_ktp)); ?>', '<?php echo e($log->ktp_nik); ?>', '<?php echo e($log->ktp_nama); ?>')">KTP</span>
                                    <?php else: ?>
                                        <span class="badge-status aktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>" style="cursor: pointer;" onclick="openDetailDokumenModal('KTP', '<?php echo e($log->file_ktp); ?>', '<?php echo e(route('dokumen.download', $log->file_ktp)); ?>', '<?php echo e($log->ktp_nik); ?>', '<?php echo e($log->ktp_nama); ?>')">KTP</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge-status nonaktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>">Belum Ada</span>
                                <?php endif; ?>
                                
                                <?php if($index === 0): ?>
                                    <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '1','title' => 'info 1','text' => 'badge ini sebagai indikator arsip/berkas fisik ada atau tidak ada , (jika hijau &quot;ada&quot; , jika merah &quot;tidak ada&quot;), jika di tekan akan muncul detail digitalnya','position' => 'left','targetGroup' => '.tour-group-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '1','title' => 'info 1','text' => 'badge ini sebagai indikator arsip/berkas fisik ada atau tidak ada , (jika hijau &quot;ada&quot; , jika merah &quot;tidak ada&quot;), jika di tekan akan muncul detail digitalnya','position' => 'left','targetGroup' => '.tour-group-1']); ?>
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
                            </td>
                            
                            
                            <td>
                                <?php if($log->file_kk): ?>
                                    <?php if(isset($log->status_kk) && $log->status_kk == 'Diambil'): ?>
                                        <span class="badge-status nonaktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>" style="cursor: pointer;" onclick="openDetailDokumenModal('KK', '<?php echo e($log->file_kk); ?>', '<?php echo e(route('dokumen.download', $log->file_kk)); ?>', '<?php echo e($log->kk_no); ?>', '<?php echo e($log->kk_nama_kepala); ?>')">KK</span>
                                    <?php else: ?>
                                        <span class="badge-status aktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>" style="cursor: pointer;" onclick="openDetailDokumenModal('KK', '<?php echo e($log->file_kk); ?>', '<?php echo e(route('dokumen.download', $log->file_kk)); ?>', '<?php echo e($log->kk_no); ?>', '<?php echo e($log->kk_nama_kepala); ?>')">KK</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge-status nonaktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>">Belum Ada</span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td>
                                <?php if($log->file_akte): ?>
                                    <?php if(isset($log->status_akte) && $log->status_akte == 'Diambil'): ?>
                                        <span class="badge-status nonaktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>" style="cursor: pointer;" onclick="openDetailDokumenModal('AKTE', '<?php echo e($log->file_akte); ?>', '<?php echo e(route('dokumen.download', $log->file_akte)); ?>', '<?php echo e($log->akte_no); ?>', '')">AKTE</span>
                                    <?php else: ?>
                                        <span class="badge-status aktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>" style="cursor: pointer;" onclick="openDetailDokumenModal('AKTE', '<?php echo e($log->file_akte); ?>', '<?php echo e(route('dokumen.download', $log->file_akte)); ?>', '<?php echo e($log->akte_no); ?>', '')">AKTE</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge-status nonaktif <?php echo e($index === 0 ? 'tour-group-1' : ''); ?>">Belum Ada</span>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <div class="tooltip-target-wrapper" style="display: flex; justify-content: center;">
                                    <?php if (isset($component)) { $__componentOriginal14938963ff2c822bd4d0c43b9ba9ccb4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14938963ff2c822bd4d0c43b9ba9ccb4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.opsi','data' => ['item' => $log,'type' => 'log-user']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.opsi'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($log),'type' => 'log-user']); ?>
                                        <form action="<?php echo e(route('user.log.berkas.ambil', $log->id)); ?>" method="POST" style="margin: 0; padding: 0;">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="jenis" value="ktp">
                                            <button type="submit" class="dropdown-item" style="color: <?php echo e((isset($log->status_ktp) && $log->status_ktp == 'Diambil') ? 'var(--C-Red)' : 'var(--C-Black)'); ?>;">
                                                <span><?php echo e((isset($log->status_ktp) && $log->status_ktp == 'Diambil') ? 'KTP Sudah Diambil' : 'Ambil KTP'); ?></span>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('user.log.berkas.ambil', $log->id)); ?>" method="POST" style="margin: 0; padding: 0;">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="jenis" value="kk">
                                            <button type="submit" class="dropdown-item" style="color: <?php echo e((isset($log->status_kk) && $log->status_kk == 'Diambil') ? 'var(--C-Red)' : 'var(--C-Black)'); ?>;">
                                                <span><?php echo e((isset($log->status_kk) && $log->status_kk == 'Diambil') ? 'KK Sudah Diambil' : 'Ambil KK'); ?></span>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('user.log.berkas.ambil', $log->id)); ?>" method="POST" style="margin: 0; padding: 0;">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="jenis" value="akte">
                                            <button type="submit" class="dropdown-item" style="color: <?php echo e((isset($log->status_akte) && $log->status_akte == 'Diambil') ? 'var(--C-Red)' : 'var(--C-Black)'); ?>;">
                                                <span><?php echo e((isset($log->status_akte) && $log->status_akte == 'Diambil') ? 'Akte Sudah Diambil' : 'Ambil Akte'); ?></span>
                                            </button>
                                        </form>
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
                                
                                    <?php if($index === 1): ?>
                                        <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '2','title' => 'info 2','text' => 'user bisa melakukan pengambilan data arsip lewat sini ada status &quot;Ambil Berkas&quot; &amp; &quot;Berkas Sudah Diambil&quot;','position' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '2','title' => 'info 2','text' => 'user bisa melakukan pengambilan data arsip lewat sini ada status &quot;Ambil Berkas&quot; &amp; &quot;Berkas Sudah Diambil&quot;','position' => 'left']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'emptyState','colspan' => '7','title' => 'Belum Ada Data Log','desc' => 'Data log berkas belum tersedia.','display' => ''.e(($totalLogs > 0) ? 'none' : '').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'emptyState','colspan' => '7','title' => 'Belum Ada Data Log','desc' => 'Data log berkas belum tersedia.','display' => ''.e(($totalLogs > 0) ? 'none' : '').'']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.empty-state','data' => ['id' => 'searchNotFoundState','colspan' => '7','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data log yang tersedia.','display' => 'none']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'searchNotFoundState','colspan' => '7','type' => 'search','title' => 'Data Tidak Ditemukan','desc' => 'Hasil pencarian tidak cocok dengan data log yang tersedia.','display' => 'none']); ?>
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
        <?php echo $__env->make('components.c-user.modal.detail-dokumen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/js/components/c-shared/opsi.js',
        'resources/js/components/c-user/detail-dokumen.js',
        'resources/js/components/c-shared/tour-guide.js',
        'resources/js/user/log-berkas.js'
    ]); ?>

    <?php if(session('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if(window.showToast) window.showToast('Berhasil', '<?php echo e(session('success')); ?>', 4000);
            }, 300);
        });
    </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/user/log-berkas.blade.php ENDPATH**/ ?>