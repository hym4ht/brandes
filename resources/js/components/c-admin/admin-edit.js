/**
 * ==========================================================================
 * admin-edit.js
 * Deskripsi: Mengelola logika modal Edit Admin, termasuk pengisian data
 *            otomatis ke form dan fitur toggle visibilitas PIN.
 * ==========================================================================
 */

// ==========================================================================
// 1. KONFIGURASI ICON (SVG)
// ==========================================================================

// Template SVG untuk ikon mata (Terbuka & Tertutup)
const EDIT_EYE_OPEN_ADMIN = `<path d="M2.06251 12.3474C1.97916 12.1229 1.97916 11.8759 2.06251 11.6514C2.87421 9.68324 4.25202 8.00042 6.02128 6.81628C7.79053 5.63214 9.87155 5 12.0005 5C14.1295 5 16.2105 5.63214 17.9797 6.81628C19.749 8.00042 21.1268 9.68324 21.9385 11.6514C22.0218 11.8759 22.0218 12.1229 21.9385 12.3474C21.1268 14.3155 19.749 15.9983 17.9797 17.1825C16.2105 18.3666 14.1295 18.9988 12.0005 18.9988C9.87155 18.9988 7.79053 18.3666 6.02128 17.1825C4.25202 15.9983 2.87421 14.3155 2.06251 12.3474Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke-linecap="round" stroke-linejoin="round"/>`;
const EDIT_EYE_SLASH_ADMIN = `<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22" stroke-linecap="round" stroke-linejoin="round"/>`;


// ==========================================================================
// 2. KONTROL MODAL
// ==========================================================================

/**
 * Membuka modal edit admin dan mengisi form dengan data admin yang dipilih
 * @param {Object} admin - Data objek admin dari baris tabel
 */
window.openEditModalAdmin = function (admin) {
    // Tutup modal lain yang mungkin terbuka
    if (window.closeAllModals) window.closeAllModals();

    const modal = document.getElementById('adminEditModalOverlay');
    if (!modal) return;

    if (admin) {
        // Atur action form ke endpoint update yang sesuai
        const form = document.getElementById('editAdminForm');
        if (form) form.action = '/admin/' + admin.id;

        // Isi field input Nama & Username
        const inputNama = document.getElementById('editNamaAdmin');
        const inputUser = document.getElementById('editUsernameAdmin');
        if (inputNama) inputNama.value = admin.nama || '';
        if (inputUser) inputUser.value = admin.username || '';

        // Reset field PIN (Kosongkan demi keamanan)
        const inputPin = document.getElementById('editAdminPinInput');
        const eyeIcon = document.getElementById('editEyeIconAdmin');
        if (inputPin) {
            inputPin.value = '';
            inputPin.type = 'password';
        }
        if (eyeIcon) {
            eyeIcon.innerHTML = EDIT_EYE_OPEN_ADMIN;
        }
    }

    // Tampilkan modal ke UI
    modal.classList.add('show');
    document.body.classList.add('no-scroll');
};

/**
 * Menutup modal edit admin
 */
window.closeEditModalAdmin = function () {
    if (window.closeAllModals) window.closeAllModals();
};

/**
 * Menutup modal saat area background (overlay) diklik
 */
window.closeEditModalOutsideAdmin = function (e) {
    if (e.target.id === 'adminEditModalOverlay') {
        window.closeEditModalAdmin();
    }
};


// ==========================================================================
// 3. UTILITAS FORM
// ==========================================================================

/**
 * Toggle visibilitas karakter pada input PIN (Password/Text)
 */
window.toggleEditPinAdmin = function () {
    const input = document.getElementById('editAdminPinInput');
    const icon = document.getElementById('editEyeIconAdmin');
    if (!input || !icon) return;

    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden ? EDIT_EYE_SLASH_ADMIN : EDIT_EYE_OPEN_ADMIN;
};
