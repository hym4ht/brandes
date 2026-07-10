/**
 * ==========================================================================
 * detail-dokumen.js
 * Deskripsi: Mengelola interaksi modal detail dokumen PDF di dashboard user.
 * ==========================================================================
 */

window.openDetailDokumenModal = function(type, fileName, fileUrl, val1, val2) {
    const modal = document.getElementById('detailDokumenModal');
    if (!modal) return;

    const docType = type.toUpperCase();

    // Mapping Icons
    const icons = {
        'KTP': `<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#00A63E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>`,
        'KK': `<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#00A63E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>`,
        'AKTE': `<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#00A63E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>`
    };

    // Set Icon
    const iconWrapper = document.querySelector('.detail-icon-wrapper');
    if (iconWrapper && icons[docType]) {
        iconWrapper.innerHTML = icons[docType];
    }

    // Set teks tipe dokumen pada H4 area upload
    const typeEl = document.getElementById('detailDokumenType');
    if (typeEl) {
        typeEl.textContent = 'Dokumen ' + docType;
    }

    // Set label file upload
    const labelFile = document.getElementById('detailLabelFile');
    if (labelFile) {
        labelFile.textContent = 'File ' + docType + ' (PDF)';
    }

    // Mapping elemen form
    const group1 = document.getElementById('detailGroup1');
    const label1 = document.getElementById('detailLabel1');
    const input1 = document.getElementById('detailInput1');
    const group2 = document.getElementById('detailGroup2');
    const label2 = document.getElementById('detailLabel2');
    const input2 = document.getElementById('detailInput2');

    // Menyesuaikan label dan isi value sesuai tipe dokumen (KTP, KK, AKTE)
    if (docType === 'KTP') {
        if(label1) label1.innerHTML = '<span class="required">*</span> Nomor Induk Kependudukan (NIK)';
        if(input1) input1.value = val1 || '-';
        if(group1) group1.classList.remove('full-width');
        
        if(group2) group2.style.display = 'flex';
        if(label2) label2.innerHTML = '<span class="required">*</span> Nama Lengkap (KTP)';
        if(input2) input2.value = val2 || '-';
        
    } else if (docType === 'KK') {
        if(label1) label1.innerHTML = '<span class="required">*</span> Nomor Kartu Keluarga (KK)';
        if(input1) input1.value = val1 || '-';
        if(group1) group1.classList.remove('full-width');
        
        if(group2) group2.style.display = 'flex';
        if(label2) label2.innerHTML = '<span class="required">*</span> Nama Kepala Keluarga (KK)';
        if(input2) input2.value = val2 || '-';
        
    } else if (docType === 'AKTE') {
        if(label1) label1.innerHTML = '<span class="required">*</span> Nomor Akte';
        if(input1) input1.value = val1 || '-';
        if(group1) group1.classList.add('full-width');
        
        // Akte tidak butuh field nama pada design tambah-log, jadi sembunyikan kolom kedua
        if(group2) group2.style.display = 'none';
    }

    // Set link download
    const btnDownload = document.getElementById('btnDownloadDokumen');
    if (btnDownload) {
        if (fileName && fileName !== '-') {
            btnDownload.href = fileUrl || '#';
            btnDownload.style.display = 'inline-flex';
        } else {
            btnDownload.style.display = 'none';
        }
    }

    // Tampilkan modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
};

window.closeDetailDokumenModal = function() {
    const modal = document.getElementById('detailDokumenModal');
    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = '';
};

// Menutup modal jika mengklik area luar (overlay)
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('detailDokumenModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDetailDokumenModal();
            }
        });
    }
});
