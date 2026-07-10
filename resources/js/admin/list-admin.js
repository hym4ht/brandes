/**
 * ==========================================================================
 * list-admin.js
 * Deskripsi: Mengelola logika halaman Daftar Admin, termasuk fitur pencarian
 *            dan pembaruan statistik (total, aktif, nonaktif) secara dinamis.
 * ==========================================================================
 */

// Konfigurasi Token CSRF (Jika diperlukan untuk request AJAX di masa depan)
const CSRF_TOKEN_ADMIN = document.querySelector('meta[name="csrf-token"]')?.content;

// ==========================================================================
// 1. FILTER & PENCARIAN
// ==========================================================================

/**
 * Memfilter baris tabel Admin berdasarkan input pencarian
 */
window.filterTable = function() {
    const query               = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const rows                = document.querySelectorAll('#adminTable tbody tr.data-row');
    const emptyState          = document.getElementById('emptyState');
    const searchNotFoundState = document.getElementById('searchNotFoundState');

    let visibleCount = 0;
    let lastVisibleRow = null;

    rows.forEach(row => {
        const text    = row.textContent.toLowerCase();
        const isMatch = text.includes(query);
        
        row.style.display = isMatch ? '' : 'none';
        
        // Bersihkan class last-row setiap iterasi pencarian
        row.classList.remove('last-row');

        if (isMatch) {
            visibleCount++;
            lastVisibleRow = row;
        }
    });

    // Tambahkan class last-row ke baris yang paling bawah terlihat
    if (lastVisibleRow) {
        lastVisibleRow.classList.add('last-row');
    }

    // Manajemen Tampilan State Kosong
    const isSearching = query.length > 0;

    if (visibleCount > 0) {
        if (emptyState)          emptyState.style.display = 'none';
        if (searchNotFoundState) searchNotFoundState.style.display = 'none';
    } else {
        if (isSearching) {
            if (emptyState)          emptyState.style.display = 'none';
            if (searchNotFoundState) searchNotFoundState.style.display = '';
        } else {
            // Jika tidak sedang mencari tapi baris kosong, berarti database kosong
            if (emptyState)          emptyState.style.display = '';
            if (searchNotFoundState) searchNotFoundState.style.display = 'none';
        }
    }
};


// ==========================================================================
// 2. LOGIKA STATISTIK
// ==========================================================================

/**
 * Memperbarui angka pada Stat Cards secara dinamis berdasarkan data di tabel.
 * Menghitung jumlah total admin, admin aktif, dan admin nonaktif.
 */
window.updateStatCards = function() {
    const rows       = document.querySelectorAll('#adminTable tbody tr.data-row');
    const totalEl    = document.getElementById('totalAdminCount');
    const aktifEl    = document.getElementById('adminAktifCount');
    const nonaktifEl = document.getElementById('adminNonaktifCount');

    let total    = rows.length;
    let aktif    = 0;
    let nonaktif = 0;

    rows.forEach(row => {
        // Cari badge status di kolom status (bukan badge di dalam dropdown)
        const badge = row.querySelector('td .badge-status:not([onclick])');
        if (badge) {
            if (badge.classList.contains('aktif')) {
                aktif++;
            } else if (badge.classList.contains('nonaktif')) {
                nonaktif++;
            }
        }
    });

    // Perbarui teks pada elemen UI
    if (totalEl)    totalEl.textContent    = total > 0    ? total    : '-';
    if (aktifEl)    aktifEl.textContent    = aktif > 0    ? aktif    : '-';
    if (nonaktifEl) nonaktifEl.textContent = nonaktif > 0 ? nonaktif : '-';
}
