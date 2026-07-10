

<div id="tambahLogModal" class="modal-overlay">
    <div class="modal-container log-modal-container" style="position: relative;">
        <?php if(in_array(session('user.role'), ['superadmin', 'admin'])): ?>
            <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '3','title' => 'info 3','text' => 'admin bisa menambahkan admin baru melalui form tambah admin ini','position' => 'left','target' => '.modal-container.log-modal-container']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '3','title' => 'info 3','text' => 'admin bisa menambahkan admin baru melalui form tambah admin ini','position' => 'left','target' => '.modal-container.log-modal-container']); ?>
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
        
        
        <div class="modal-header">
            <span class="modal-title">Tambah Log Berkas</span>
            <button class="btn-close-modal" onclick="closeTambahLogModal()" title="Tutup Modal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        
        <div class="modal-body">
            <form action="<?php echo e(route('log.berkas.store')); ?>" method="POST" id="formTambahLog" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                
                <div class="form-group full-width">
                    <label class="form-label" for="log_judul">Nama Lengkap</label>
                    <input type="text" class="form-input" id="log_judul" name="judul" placeholder="Masukan nama lengkap...." required oninput="this.value = this.value.replace(/[^a-zA-Z\s\']/g, '')">
                </div>

                
                <div class="form-section">
                    <h4 class="section-title">Dokumen KTP</h4>
                    <div class="section-grid">
                        <div class="form-group">
                            <label class="form-label" for="ktp_nik"><span class="required">*</span> Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" class="form-input" id="ktp_nik" name="ktp_nik" placeholder="Masukkan 16 digit NIK" required minlength="16" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="ktp_nama"><span class="required">*</span> Nama Lengkap KTP</label>
                            <input type="text" class="form-input" id="ktp_nama" name="ktp_nama" placeholder="Sesuai KTP" required oninput="this.value = this.value.replace(/[^a-zA-Z\s\']/g, '')">
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Upload File KTP (PDF)</label>
                            <div class="file-upload-wrapper" id="dropzoneKtp" onclick="document.getElementById('file_ktp').click()">
                                <input type="file" id="file_ktp" name="file_ktp" accept="application/pdf" hidden required onchange="handleFileSelect(this, 'previewKtp')">
                                <div class="file-upload-content">
                                    <div class="icon-wrapper">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                                            <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                                            <path d="M12 12v9"/>
                                            <path d="m16 16-4-4-4 4"/>
                                        </svg>
                                    </div>
                                    <span class="upload-text"><span>KLIK</span> atau seret file PDF kesini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2 MB</span>
                                </div>
                                <div class="file-preview" id="previewKtp" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('file_ktp', 'previewKtp')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="form-section">
                    <h4 class="section-title">Dokumen Kartu Keluarga (KK)</h4>
                    <div class="section-grid">
                        <div class="form-group">
                            <label class="form-label" for="kk_no"><span class="required">*</span> Nomor KK</label>
                            <input type="text" class="form-input" id="kk_no" name="kk_no" placeholder="Masukkan 16 digit No KK" required minlength="16" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="kk_nama_kepala"><span class="required">*</span> Nama Kepala Keluarga</label>
                            <input type="text" class="form-input" id="kk_nama_kepala" name="kk_nama_kepala" placeholder="Sesuai KK" required oninput="this.value = this.value.replace(/[^a-zA-Z\s\']/g, '')">
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Upload File KK (PDF)</label>
                            <div class="file-upload-wrapper" id="dropzoneKk" onclick="document.getElementById('file_kk').click()">
                                <input type="file" id="file_kk" name="file_kk" accept="application/pdf" hidden required onchange="handleFileSelect(this, 'previewKk')">
                                <div class="file-upload-content">
                                    <div class="icon-wrapper">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                                            <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                                            <path d="M12 12v9"/>
                                            <path d="m16 16-4-4-4 4"/>
                                        </svg>
                                    </div>
                                    <span class="upload-text"><span>KLIK</span> atau seret file PDF kesini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2 MB</span>
                                </div>
                                <div class="file-preview" id="previewKk" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('file_kk', 'previewKk')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="form-section">
                    <h4 class="section-title">Dokumen Akte</h4>
                    <div class="section-grid">
                        <div class="form-group full-width">
                            <label class="form-label" for="akte_no"><span class="required">*</span> Nomor Akte</label>
                            <input type="text" class="form-input" id="akte_no" name="akte_no" placeholder="Masukkan Nomor Akte" required>
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Upload File Akte (PDF)</label>
                            <div class="file-upload-wrapper" id="dropzoneAkte" onclick="document.getElementById('file_akte').click()">
                                <input type="file" id="file_akte" name="file_akte" accept="application/pdf" hidden required onchange="handleFileSelect(this, 'previewAkte')">
                                <div class="file-upload-content">
                                    <div class="icon-wrapper">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                                            <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                                            <path d="M12 12v9"/>
                                            <path d="m16 16-4-4-4 4"/>
                                        </svg>
                                    </div>
                                    <span class="upload-text"><span>KLIK</span> atau seret file PDF kesini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2 MB</span>
                                </div>
                                <div class="file-preview" id="previewAkte" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('file_akte', 'previewAkte')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeTambahLogModal()">Batal</button>
            <div class="tooltip-target-wrapper" style="width: 100%;">
                <button type="submit" form="formTambahLog" class="btn-submit" id="btnSubmitLog">Simpan Data</button>
                <?php if(in_array(session('user.role'), ['superadmin', 'admin'])): ?>
                    <?php if (isset($component)) { $__componentOriginal1890100d97d52fcea52026969a199468 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1890100d97d52fcea52026969a199468 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.c-shared.tooltip-info','data' => ['number' => '4','title' => 'info 4','text' => 'jika sudah melakukan pengisian data, selanjutnya klik button &quot;simpan data&quot;','position' => 'right','target' => '#btnSubmitLog']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('c-shared.tooltip-info'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '4','title' => 'info 4','text' => 'jika sudah melakukan pengisian data, selanjutnya klik button &quot;simpan data&quot;','position' => 'right','target' => '#btnSubmitLog']); ?>
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
</div>
<?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-admin/modal/log-tambah.blade.php ENDPATH**/ ?>