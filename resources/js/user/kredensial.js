/**
 * ==========================================================================
 * kredensial.js (User)
 * Deskripsi: Mengelola logika halaman Profile & Kredensial User, termasuk
 *            fitur salin data, toggle visibilitas PIN, dan interaksi profil.
 * ==========================================================================
 */

// ==========================================================================
// 1. UTILITAS KREDENSIAL
// ==========================================================================

/**
 * Menyalin teks ke clipboard
 * @param {string} text - Teks yang akan disalin
 * @param {string} msg  - Pesan sukses untuk toast
 */
function copyToClipboard(text, msg = 'Data berhasil disalin') {
    if (!text) return;

    navigator.clipboard.writeText(text).then(() => {
        // Panggil fungsi showToast global jika tersedia
        if (window.showToast) {
            window.showToast(msg, 'success');
        } else {
            alert(msg);
        }
    }).catch(err => {
        console.error('Gagal menyalin:', err);
    });
}

// EKSPOS KE WINDOW
window.copyToClipboard = copyToClipboard;
