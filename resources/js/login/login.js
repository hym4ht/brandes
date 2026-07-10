/**
 * ==========================================================================
 * login.js
 * Deskripsi: Mengelola interaksi pada halaman login, termasuk fitur toggle 
 *            visibilitas PIN dan kustomisasi selector peran (Role Selector).
 * ==========================================================================
 */

// ==========================================================================
// 1. TOGGLE VISIBILITAS PIN
// ==========================================================================

// Template path SVG untuk ikon mata
const ICON_EYE_SLASH = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle><line x1="1" y1="1" x2="23" y2="23"></line>`;
const ICON_EYE_OPEN  = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>`;

/**
 * Mengubah tipe input PIN antara 'password' dan 'text' serta mengganti ikonnya.
 */
window.togglePin = function() {
    const pinInput = document.getElementById('pin');
    const eyeIcon  = document.getElementById('eyeIcon');
    if (!pinInput || !eyeIcon) return;

    const isHidden = pinInput.type === 'password';
    
    // Switch tipe input
    pinInput.type = isHidden ? 'text' : 'password';

    // Perbarui ikon SVG
    eyeIcon.innerHTML = isHidden ? ICON_EYE_SLASH : ICON_EYE_OPEN;
    eyeIcon.setAttribute('stroke-width', '2');
};


// ==========================================================================
// 2. KUSTOMISASI ROLE SELECTOR
// ==========================================================================

document.addEventListener('DOMContentLoaded', () => {
    const roleSelect = document.getElementById('role');
    const roleLabel  = document.getElementById('role-label');
    if (!roleSelect) return;

    const selectWrapper = roleSelect.closest('.select-wrapper');
    if (!selectWrapper) return;

    const selectTrigger = selectWrapper.querySelector('.select-trigger');
    const customOptions = selectWrapper.querySelectorAll('.custom-option');

    // Toggle buka/tutup dropdown kustom
    selectTrigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        selectWrapper.classList.toggle('active');
    });

    // Menangani pemilihan opsi kustom
    customOptions.forEach(option => {
        option.addEventListener('click', () => {
            const value = option.getAttribute('data-value');
            const label = option.textContent;

            // Perbarui label visual dan nilai pada elemen <select> asli
            if (roleLabel) roleLabel.textContent = label;
            roleSelect.value = value;

            // Perbarui status 'selected' pada elemen UI kustom
            customOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');

            // Tutup dropdown setelah memilih
            selectWrapper.classList.remove('active');
        });
    });

    // Tutup dropdown saat pengguna mengklik di luar area selector
    document.addEventListener('click', () => {
        selectWrapper.classList.remove('active');
    });
});
