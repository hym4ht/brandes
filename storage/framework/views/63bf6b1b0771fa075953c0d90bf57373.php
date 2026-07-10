

<div id="detailDokumenModal" class="modal-overlay">
    <div class="modal-container detail-modal-container">

        
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Detail Dokumen</h3>
            </div>
            <button class="btn-close-modal" onclick="closeDetailDokumenModal()" title="Tutup Modal">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        
        <div class="modal-body detail-modal-body">
            <div class="detail-card">
                
                
                <div class="detail-grid">
                    <div class="detail-group" id="detailGroup1">
                        <label class="detail-label" id="detailLabel1"><span class="required">*</span> Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" class="detail-input" id="detailInput1" readonly>
                    </div>
                    <div class="detail-group" id="detailGroup2">
                        <label class="detail-label" id="detailLabel2"><span class="required">*</span> Nama Lengkap (KTP)</label>
                        <input type="text" class="detail-input" id="detailInput2" readonly>
                    </div>
                </div>

                
                <div class="detail-group full-width" style="margin-bottom: 0;">
                    <label class="detail-label" id="detailLabelFile" style="margin-bottom: 12px; display: block;">File KTP (PDF)</label>
                    <div class="detail-file-wrapper">
                        <div class="detail-icon-wrapper">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#00A63E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <h4 id="detailDokumenType" class="detail-file-title">Dokumen KTP</h4>
                        <p class="detail-file-subtitle">Berkas yang ada berupa PDF file (Digital File)</p>
                    </div>
                </div>

            </div>
        </div>

        
        <div class="modal-footer detail-modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDetailDokumenModal()">Batal</button>
            <a href="#" id="btnDownloadDokumen" class="btn-submit btn-download-doc" download>
                Download
            </a>
        </div>

    </div>
</div><?php /**PATH C:\laragon\www\project - revisi tapi benar (sudah sidang TA)\resources\views/components/c-user/modal/detail-dokumen.blade.php ENDPATH**/ ?>