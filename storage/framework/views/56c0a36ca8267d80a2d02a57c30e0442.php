

<div id="editLogModal" class="modal-overlay">
    <div class="modal-container log-edit-modal-container">
        
        
        <div class="modal-header">
            <span class="modal-title">Edit Log Berkas</span>
            <button class="btn-close-modal" onclick="closeEditModalLog()" title="Tutup Modal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        
        <div class="modal-body">
            <form action="" method="POST" id="formEditLog" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="form-group full-width">
                    <label class="form-label" for="edit_log_judul">Nama Lengkap</label>
                    <input type="text" class="form-input" id="edit_log_judul" name="judul" placeholder="Masukan nama lengkap...." required oninput="this.value = this.value.replace(/[^a-zA-Z\s\']/g, '')">
                </div>

                
                <div class="form-section">
                    <h4 class="section-title">Dokumen KTP</h4>
                    <div class="section-grid">
                        <div class="form-group">
                            <label class="form-label" for="edit_ktp_nik"><span class="required">*</span> Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" class="form-input" id="edit_ktp_nik" name="ktp_nik" placeholder="Masukkan 16 digit NIK" required minlength="16" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_ktp_nama"><span class="required">*</span> Nama Lengkap KTP</label>
                            <input type="text" class="form-input" id="edit_ktp_nama" name="ktp_nama" placeholder="Sesuai KTP" required oninput="this.value = this.value.replace(/[^a-zA-Z\s\']/g, '')">
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Ganti File KTP (Opsional)</label>
                            <div class="file-upload-wrapper" id="editDropzoneKtp" onclick="document.getElementById('edit_file_ktp').click()">
                                <input type="file" id="edit_file_ktp" name="file_ktp" accept="application/pdf" hidden onchange="handleFileSelect(this, 'editPreviewKtp')">
                                <div class="file-upload-content">
                                    <div class="icon-wrapper">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                                            <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                                            <path d="M12 12v9"/>
                                            <path d="m16 16-4-4-4 4"/>
                                        </svg>
                                    </div>
                                    <span class="upload-text"><span>KLIK</span> atau seret file PDF baru kesini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2 MB. Kosongkan jika tidak ingin mengubah.</span>
                                </div>
                                <div class="file-preview" id="editPreviewKtp" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('edit_file_ktp', 'editPreviewKtp')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="form-section">
                    <h4 class="section-title">Dokumen Kartu Keluarga (KK)</h4>
                    <div class="section-grid">
                        <div class="form-group">
                            <label class="form-label" for="edit_kk_no"><span class="required">*</span> Nomor KK</label>
                            <input type="text" class="form-input" id="edit_kk_no" name="kk_no" placeholder="Masukkan 16 digit No KK" required minlength="16" maxlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_kk_nama_kepala"><span class="required">*</span> Nama Kepala Keluarga</label>
                            <input type="text" class="form-input" id="edit_kk_nama_kepala" name="kk_nama_kepala" placeholder="Sesuai KK" required oninput="this.value = this.value.replace(/[^a-zA-Z\s\']/g, '')">
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Ganti File KK (Opsional)</label>
                            <div class="file-upload-wrapper" id="editDropzoneKk" onclick="document.getElementById('edit_file_kk').click()">
                                <input type="file" id="edit_file_kk" name="file_kk" accept="application/pdf" hidden onchange="handleFileSelect(this, 'editPreviewKk')">
                                <div class="file-upload-content">
                                    <div class="icon-wrapper">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                                            <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                                            <path d="M12 12v9"/>
                                            <path d="m16 16-4-4-4 4"/>
                                        </svg>
                                    </div>
                                    <span class="upload-text"><span>KLIK</span> atau seret file PDF baru kesini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2 MB. Kosongkan jika tidak ingin mengubah.</span>
                                </div>
                                <div class="file-preview" id="editPreviewKk" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('edit_file_kk', 'editPreviewKk')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="form-section">
                    <h4 class="section-title">Dokumen Akte</h4>
                    <div class="section-grid">
                        <div class="form-group full-width">
                            <label class="form-label" for="edit_akte_no"><span class="required">*</span> Nomor Akte</label>
                            <input type="text" class="form-input" id="edit_akte_no" name="akte_no" placeholder="Masukkan Nomor Akte" required>
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Ganti File Akte (Opsional)</label>
                            <div class="file-upload-wrapper" id="editDropzoneAkte" onclick="document.getElementById('edit_file_akte').click()">
                                <input type="file" id="edit_file_akte" name="file_akte" accept="application/pdf" hidden onchange="handleFileSelect(this, 'editPreviewAkte')">
                                <div class="file-upload-content">
                                    <div class="icon-wrapper">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                                            <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                                            <path d="M12 12v9"/>
                                            <path d="m16 16-4-4-4 4"/>
                                        </svg>
                                    </div>
                                    <span class="upload-text"><span>KLIK</span> atau seret file PDF baru kesini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2 MB. Kosongkan jika tidak ingin mengubah.</span>
                                </div>
                                <div class="file-preview" id="editPreviewAkte" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('edit_file_akte', 'editPreviewAkte')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeEditModalLog()">Batal</button>
            <button type="submit" form="formEditLog" class="btn-submit">Simpan Perubahan</button>
        </div>

    </div>
</div>
<?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-admin/modal/log-edit.blade.php ENDPATH**/ ?>