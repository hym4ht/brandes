{{-- ============================================================
MODAL: Tambah Log Berkas
Deskripsi: Form untuk menambahkan data aktivitas baru,
           meliputi unggah PDF (KK, KTP, Akte) & input manual.
============================================================ --}}

<div id="tambahLogModal" class="modal-overlay">
    <div class="modal-container log-modal-container">
        
        {{-- Header Modal --}}
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Tambah Log Berkas</h3>
                <p class="modal-subtitle">Lengkapi berkas PDF dan data manual pendukung.</p>
            </div>
            <button class="btn-close-modal" onclick="closeTambahLogModal()" title="Tutup Modal">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        {{-- Body Modal --}}
        <div class="modal-body">
            <form action="{{ route('user.log.berkas.store') }}" method="POST" id="formTambahLog" enctype="multipart/form-data">
                @csrf

                {{-- Judul Aktivitas --}}
                <div class="form-group full-width">
                    <label class="form-label" for="log_judul">Judul Aktivitas <span class="required">*</span></label>
                    <input type="text" class="form-input" id="log_judul" name="judul" placeholder="Contoh: Pendaftaran Nasabah Baru" required>
                </div>

                {{-- Section: KTP --}}
                <div class="form-section">
                    <h4 class="section-title">Dokumen KTP</h4>
                    <div class="section-grid">
                        <div class="form-group">
                            <label class="form-label" for="ktp_nik">Nomor Induk Kependudukan (NIK) <span class="required">*</span></label>
                            <input type="text" class="form-input" id="ktp_nik" name="ktp_nik" placeholder="Masukkan 16 digit NIK" required maxlength="16">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="ktp_nama">Nama Lengkap KTP <span class="required">*</span></label>
                            <input type="text" class="form-input" id="ktp_nama" name="ktp_nama" placeholder="Sesuai KTP" required>
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Upload File KTP (PDF) <span class="required">*</span></label>
                            <div class="file-upload-wrapper" id="dropzoneKtp" onclick="document.getElementById('file_ktp').click()">
                                <input type="file" id="file_ktp" name="file_ktp" accept="application/pdf" hidden required onchange="handleFileSelect(this, 'previewKtp')">
                                <div class="file-upload-content">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                        <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M17 8L12 3M12 3L7 8M12 3V15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="upload-text">Klik atau seret file PDF ke sini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2MB</span>
                                </div>
                                <div class="file-preview" id="previewKtp" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('file_ktp', 'previewKtp')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: KK --}}
                <div class="form-section">
                    <h4 class="section-title">Dokumen Kartu Keluarga (KK)</h4>
                    <div class="section-grid">
                        <div class="form-group">
                            <label class="form-label" for="kk_no">Nomor KK <span class="required">*</span></label>
                            <input type="text" class="form-input" id="kk_no" name="kk_no" placeholder="Masukkan 16 digit No KK" required maxlength="16">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="kk_nama_kepala">Nama Kepala Keluarga <span class="required">*</span></label>
                            <input type="text" class="form-input" id="kk_nama_kepala" name="kk_nama_kepala" placeholder="Sesuai KK" required>
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Upload File KK (PDF) <span class="required">*</span></label>
                            <div class="file-upload-wrapper" id="dropzoneKk" onclick="document.getElementById('file_kk').click()">
                                <input type="file" id="file_kk" name="file_kk" accept="application/pdf" hidden required onchange="handleFileSelect(this, 'previewKk')">
                                <div class="file-upload-content">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                        <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M17 8L12 3M12 3L7 8M12 3V15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="upload-text">Klik atau seret file PDF ke sini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2MB</span>
                                </div>
                                <div class="file-preview" id="previewKk" style="display: none;">
                                    <span class="file-name"></span>
                                    <button type="button" class="btn-remove-file" onclick="event.stopPropagation(); clearFile('file_kk', 'previewKk')">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: AKTE --}}
                <div class="form-section">
                    <h4 class="section-title">Dokumen Akte</h4>
                    <div class="section-grid">
                        <div class="form-group full-width">
                            <label class="form-label" for="akte_no">Nomor Akte <span class="required">*</span></label>
                            <input type="text" class="form-input" id="akte_no" name="akte_no" placeholder="Masukkan Nomor Akte" required>
                        </div>
                        <div class="form-group file-group full-width">
                            <label class="form-label">Upload File Akte (PDF) <span class="required">*</span></label>
                            <div class="file-upload-wrapper" id="dropzoneAkte" onclick="document.getElementById('file_akte').click()">
                                <input type="file" id="file_akte" name="file_akte" accept="application/pdf" hidden required onchange="handleFileSelect(this, 'previewAkte')">
                                <div class="file-upload-content">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="upload-icon">
                                        <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15M17 8L12 3M12 3L7 8M12 3V15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="upload-text">Klik atau seret file PDF ke sini</span>
                                    <span class="upload-hint">Maksimal ukuran file 2MB</span>
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

        {{-- Footer Modal --}}
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeTambahLogModal()">Batal</button>
            <button type="submit" form="formTambahLog" class="btn-submit">Simpan Data</button>
        </div>

    </div>
</div>
