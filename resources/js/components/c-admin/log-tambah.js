/**
 * ==========================================================================
 * log-tambah.js
 * Deskripsi: Menangani interaksi pada modal tambah log aktivitas, termasuk
 *            drag & drop file, preview nama file PDF, dan modal toggle.
 * ==========================================================================
 */

window.openTambahLogModal = function() {
    const modal = document.getElementById('tambahLogModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Trigger tour untuk elemen di dalam modal (jika ada)
        setTimeout(() => {
            if (window.TourGuideInstance) {
                window.TourGuideInstance.start();
            }
        }, 300);
    }
};

window.closeTambahLogModal = function() {
    const modal = document.getElementById('tambahLogModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        
        // Reset form
        const form = document.getElementById('formTambahLog');
        if (form) {
            form.reset();
            
            // Clear all file previews
            window.clearFile('file_ktp', 'previewKtp');
            window.clearFile('file_kk', 'previewKk');
            window.clearFile('file_akte', 'previewAkte');
        }
    }
};

// Handle file selection
window.handleFileSelect = function(inputElement, previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    const dropzoneContent = inputElement.nextElementSibling; // The .file-upload-content div
    
    if (inputElement.files && inputElement.files[0]) {
        const file = inputElement.files[0];
        
        // Cek ekstensi
        if (file.type !== 'application/pdf') {
            if (window.showToast) window.showToast('Error', 'File harus berupa PDF!', 4000);
            inputElement.value = '';
            return;
        }

        // Tampilkan preview
        dropzoneContent.style.display = 'none';
        previewContainer.style.display = 'flex';
        
        const fileNameSpan = previewContainer.querySelector('.file-name');
        if (fileNameSpan) {
            fileNameSpan.textContent = file.name;
        }
    }
};

// Clear file selection
window.clearFile = function(inputId, previewContainerId) {
    const inputElement = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewContainerId);
    const dropzoneContent = inputElement.nextElementSibling;
    
    if (inputElement) {
        inputElement.value = '';
    }
    
    if (previewContainer && dropzoneContent) {
        previewContainer.style.display = 'none';
        dropzoneContent.style.display = 'flex';
        
        const fileNameSpan = previewContainer.querySelector('.file-name');
        if (fileNameSpan) {
            fileNameSpan.textContent = '';
        }
    }
};

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('tambahLogModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTambahLogModal();
            }
        });
    }
});
