/**
 * ==========================================================================
 * toast.js
 * Deskripsi: Mengelola sistem notifikasi toast premium yang bersifat global.
 *            Digunakan untuk memberikan feedback visual kepada pengguna.
 * ==========================================================================
 */

/**
 * Menampilkan notifikasi toast di layar
 * @param {string} title    - Judul notifikasi
 * @param {string} message  - Pesan detail notifikasi
 * @param {number} duration - Durasi tampilan (dalam milidetik)
 */
window.showToast = function(title = "Berhasil", message = "Aksi Anda telah berhasil diproses.", duration = 4000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const template = document.getElementById('toastTemplate');
    if (!template) return;
    
    // 1. Persiapkan elemen toast dari template HTML
    const clone = template.content.cloneNode(true);
    const toast = clone.querySelector('.toast');
    
    // 2. Isi data teks (Judul & Pesan)
    const titleEl = toast.querySelector('.toast-title');
    const textEl  = toast.querySelector('.toast-text');
    
    if (titleEl) titleEl.textContent = title;
    if (textEl)  textEl.textContent  = message;

    // 3. Masukkan toast ke dalam container di DOM
    container.appendChild(toast);

    // 4. Picu animasi masuk (show)
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // 5. Logika penghapusan otomatis berdasarkan durasi
    setTimeout(() => {
        // Picu animasi keluar (hiding)
        toast.classList.add('hiding');
        
        // Hapus elemen dari DOM setelah animasi selesai (500ms)
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, duration);
};

// ==========================================================================
// INISIALISASI LIFECYCLE
// ==========================================================================
document.addEventListener('DOMContentLoaded', () => {
    // Listener global bisa ditambahkan di sini jika diperlukan
});
