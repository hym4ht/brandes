/**
 * ==========================================================================
 * user-edit.js
 * Deskripsi: Mengelola logika modal Edit User, termasuk pengisian data 
 *            otomatis (auto-populate) dan fitur toggle visibilitas PIN.
 * ==========================================================================
 */

// ==========================================================================
// 1. KONFIGURASI ICON (SVG)
// ==========================================================================

// Template SVG untuk ikon mata (Terbuka & Tertutup)
const EYE_OPEN_ICON_USER  = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
const EYE_SLASH_ICON_USER = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>`;


// ==========================================================================
// 2. KONTROL MODAL
// ==========================================================================

/**
 * Membuka modal edit user dan mengisi form dengan data user yang dipilih
 */
window.openEditModalUser = function(user) {
    // Tutup modal lain yang mungkin terbuka
    if (window.closeAllModals) window.closeAllModals();

    const modal = document.getElementById('userEditModalOverlay');
    if (!modal) return;

    if (user) {
        // Atur action form ke endpoint update user yang sesuai
        const form = document.getElementById('editUserForm');
        if (form) form.action = '/user/' + user.id;

        // Isi field input Nama, Username, dan Fingerprint ID
        const inputNama   = document.getElementById('editNamaUser');
        const inputUser   = document.getElementById('editUsernameUser');
        const displayFp   = document.getElementById('editDisplayFingerprint');
        const hiddenFp    = document.getElementById('editHiddenFingerprintId');
        
        if (inputNama) inputNama.value = user.nama     || '';
        if (inputUser) inputUser.value = user.username || '';
        
        if (displayFp) {
            displayFp.value = user.fingerprint_id ? ('FP-' + String(user.fingerprint_id).padStart(3, '0')) : '';
        }
        if (hiddenFp) {
            hiddenFp.value = user.fingerprint_id || '';
        }
        
        // Isi field PIN dengan data asli dari database
        const inputPin = document.getElementById('editPinInputUser');
        const eyeIcon  = document.getElementById('editEyeIconUser');
        if (inputPin) {
            inputPin.value = user.pin || ''; 
            inputPin.type  = 'password';
        }
        if (eyeIcon) eyeIcon.innerHTML = EYE_OPEN_ICON_USER;
    }

    // Tampilkan modal ke UI
    modal.classList.add('show');
    document.body.classList.add('no-scroll');
};

/**
 * Menutup modal edit user
 */
window.closeEditModalUser = function() {
    if (window.closeAllModals) window.closeAllModals();
};

/**
 * Menutup modal saat area background (overlay) diklik
 */
window.closeEditModalOutsideUser = function(e) {
    if (e.target.id === 'userEditModalOverlay') {
        window.closeEditModalUser();
    }
};


// ==========================================================================
// 3. UTILITAS FORM
// ==========================================================================

/**
 * Toggle visibilitas karakter pada input PIN (Password/Text)
 */
window.toggleEditPinUser = function() {
    const input = document.getElementById('editPinInputUser');
    const icon  = document.getElementById('editEyeIconUser');
    if (!input || !icon) return;

    const isHidden = input.type === 'password';
    input.type     = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden ? EYE_SLASH_ICON_USER : EYE_OPEN_ICON_USER;
};
