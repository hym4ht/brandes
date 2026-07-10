/**
 * ==========================================================================
 * log-edit.js
 * Deskripsi: Mengelola interaksi modal edit log aktivitas,
 *            seperti membuka modal, mengisi form dengan data, 
 *            dan membersihkan form saat ditutup.
 * ==========================================================================
 */

/**
 * Membuka Modal Edit Log & Mengisi Data Form
 */
window.openEditModalLog = function(logData) {
    const modal = document.getElementById('editLogModal');
    if (!modal) return;

    // Set action URL form
    const form = document.getElementById('formEditLog');
    if (form) {
        form.action = `/log-berkas/${logData.id}`;
    }

    // Isi data input teks
    const inputMap = {
        'edit_log_judul': logData.judul,
        'edit_ktp_nik': logData.ktp_nik,
        'edit_ktp_nama': logData.ktp_nama,
        'edit_kk_no': logData.kk_no,
        'edit_kk_nama_kepala': logData.kk_nama_kepala,
        'edit_akte_no': logData.akte_no
    };

    for (const [id, value] of Object.entries(inputMap)) {
        const el = document.getElementById(id);
        if (el) el.value = value || '';
    }

    // Tampilkan data file PDF saat ini di area dropzone
    const fileMap = {
        'edit_file_ktp': { name: logData.file_ktp, previewId: 'editPreviewKtp' },
        'edit_file_kk': { name: logData.file_kk, previewId: 'editPreviewKk' },
        'edit_file_akte': { name: logData.file_akte, previewId: 'editPreviewAkte' }
    };

    for (const [inputId, fileData] of Object.entries(fileMap)) {
        const inputElement = document.getElementById(inputId);
        const previewContainer = document.getElementById(fileData.previewId);
        if (inputElement && previewContainer) {
            const dropzoneContent = inputElement.nextElementSibling;
            
            if (fileData.name) {
                // Tampilkan preview dengan nama file existing
                dropzoneContent.style.display = 'none';
                previewContainer.style.display = 'flex';
                
                const fileNameSpan = previewContainer.querySelector('.file-name');
                if (fileNameSpan) {
                    // Extract original file name: remove 'dokumen/' and the optional timestamp prefix (e.g. '170000000_')
                    let cleanName = fileData.name.replace(/^dokumen\/(?:\d+_)?/, '');
                    fileNameSpan.textContent = cleanName;
                }
            } else {
                // Jika tidak ada file, reset tampilan ke dropzone
                dropzoneContent.style.display = 'flex';
                previewContainer.style.display = 'none';
                if (inputElement) inputElement.value = '';
            }
        }
    }

    // Tutup menu dropdown opsi sebelum membuka modal
    if (window.closeAllDropdowns) {
        window.closeAllDropdowns();
    }

    // Tampilkan modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Kunci scroll halaman belakang
};

/**
 * Menutup Modal Edit Log & Reset Form
 */
window.closeEditModalLog = function() {
    const modal = document.getElementById('editLogModal');
    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = ''; // Buka kembali scroll halaman belakang

    // Reset isi form
    const form = document.getElementById('formEditLog');
    if (form) {
        form.reset();
        
        // Reset preview files
        clearFile('edit_file_ktp', 'editPreviewKtp');
        clearFile('edit_file_kk', 'editPreviewKk');
        clearFile('edit_file_akte', 'editPreviewAkte');
    }
};

/**
 * Menutup Modal Jika Mengklik Area Luar (Overlay)
 */
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editLogModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            // Jika yang diklik adalah background (modal-overlay), tutup modalnya
            if (e.target === modal) {
                closeEditModalLog();
            }
        });
    }
});
