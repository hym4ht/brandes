/**
 * ==========================================================================
 * logout.js
 * Deskripsi: Mengelola logika modal konfirmasi logout (keluar akun).
 *            Dapat digunakan secara global baik di panel Admin maupun User.
 * ==========================================================================
 */

// ==========================================================================
// 1. KONTROL MODAL
// ==========================================================================

/**
 * Membuka modal konfirmasi logout
 */
window.openLogoutModal = function() {
    const modal = document.getElementById('logoutOverlay');
    if (modal) {
        modal.classList.add('show');
        // Kunci scroll halaman belakang
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Menutup modal konfirmasi logout
 */
window.closeLogoutModal = function() {
    const modal = document.getElementById('logoutOverlay');
    if (modal) {
        modal.classList.remove('show');
        // Kembalikan scroll halaman belakang
        document.body.style.overflow = '';
    }
}

/**
 * Menutup modal saat area background (overlay) diklik
 * @param {Event} e - Event klik dari browser
 */
window.closeLogoutOutside = function(e) {
    if (e.target.id === 'logoutOverlay') {
        window.closeLogoutModal();
    }
}