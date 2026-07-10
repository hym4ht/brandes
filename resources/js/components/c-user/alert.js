/**
 * ==========================================================================
 * user-security-alert.js
 * Deskripsi: Mengelola interaksi komponen alert keamanan (Kredensial, 
 *            History, Notifikasi). Menangani fitur penutupan (dismiss)
 *            dan persistensi status menggunakan localStorage.
 * ==========================================================================
 */

// ==========================================================================
// 1. FUNGSI DISMISS (PENUTUPAN)
// ==========================================================================

/**
 * Menutup alert secara visual dan menyimpan statusnya agar tidak muncul lagi.
 * @param {string} alertId - ID unik dari elemen alert yang akan ditutup.
 */
window.dismissSecurityAlert = function(alertId) {
    const alertElement = document.getElementById(alertId);
    if (!alertElement) return;

    // A. Jalankan Animasi Keluar (Fade Out & Slide Up)
    alertElement.style.opacity    = '0';
    alertElement.style.transform  = 'translateY(-10px)';
    alertElement.style.transition = 'all 0.3s ease';

    // B. Simpan status ke localStorage berdasarkan data-storage-key
    const storageKey = alertElement.getAttribute('data-storage-key');
    if (storageKey) {
        localStorage.setItem(storageKey, 'true');
    }

    // C. Sembunyikan elemen sepenuhnya setelah animasi selesai (300ms)
    setTimeout(() => {
        alertElement.style.display = 'none';
    }, 300);
};


// ==========================================================================
// 2. PENGECEKAN PERSISTENSI (ON LOAD)
// ==========================================================================

/**
 * Memeriksa status dismiss saat halaman dimuat untuk menyembunyikan 
 * alert yang sudah pernah ditutup oleh user sebelumnya.
 */
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('[data-storage-key]');
    
    alerts.forEach(alert => {
        const storageKey = alert.getAttribute('data-storage-key');
        
        // Jika kunci ditemukan di localStorage dengan nilai 'true', maka sembunyikan
        if (storageKey && localStorage.getItem(storageKey) === 'true') {
            alert.style.display = 'none';
        } else {
            // Jika belum pernah ditutup, pastikan elemen terlihat
            alert.style.display = ''; 
        }
    });
});