/**
 * ==========================================================================
 * list-user.js
 * Deskripsi: Mengelola logika halaman Daftar User, termasuk fitur pencarian,
 *            manajemen statistik user, serta aksi AJAX (Hapus & Ubah Status).
 * ==========================================================================
 */

// ==========================================================================
// 1. FILTER & PENCARIAN
// ==========================================================================

/**
 * Memfilter baris tabel User berdasarkan input pencarian secara real-time
 */
window.filterTable = function() {
    const query               = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const rows                = document.querySelectorAll('#userTable tbody tr.data-row');
    const emptyState          = document.getElementById('emptyState');
    const searchNotFoundState = document.getElementById('searchNotFoundState');

    let visibleCount = 0;

    rows.forEach(row => {
        const text    = row.textContent.toLowerCase();
        const isMatch = text.includes(query);
        
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) visibleCount++;
    });

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
}


// ==========================================================================
// 2. LOGIKA STATISTIK
// ==========================================================================

/**
 * Memperbarui angka pada Stat Cards user (Total, Aktif, Nonaktif)
 * secara dinamis berdasarkan data terbaru di tabel.
 */
window.updateStatCards = function() {
    const rows       = document.querySelectorAll('#userTable tbody tr.data-row');
    const totalEl    = document.getElementById('totalUserCount');
    const aktifEl    = document.getElementById('userAktifCount');
    const nonaktifEl = document.getElementById('userNonaktifCount');

    let total    = rows.length;
    let aktif    = 0;
    let nonaktif = 0;

    rows.forEach(row => {
        const badge = row.querySelector('td .badge-status:not([onclick])');
        if (badge) {
            if (badge.classList.contains('aktif')) {
                aktif++;
            } else if (badge.classList.contains('nonaktif')) {
                nonaktif++;
            }
        }
    });

    if (totalEl)    totalEl.textContent    = total > 0    ? total    : '-';
    if (aktifEl)    aktifEl.textContent    = aktif > 0    ? aktif    : '-';
    if (nonaktifEl) nonaktifEl.textContent = nonaktif > 0 ? nonaktif : '-';
}


// ==========================================================================
// 3. AKSI AJAX (Hapus & Status)
// ==========================================================================

// Konfigurasi Header AJAX Global
const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]')?.content;
const AJAX_HEADERS = {
    'X-CSRF-TOKEN':    CSRF_TOKEN,
    'X-Requested-With': 'XMLHttpRequest',
    'Accept':           'application/json',
};

/**
 * Menghapus data User secara asinkron via AJAX
 */
window.deleteUserAJAX = function(e) {
    e.preventDefault();
    
    // global currentDeleteUserId didefinisikan di komponen modal hapus
    if (!currentDeleteUserId || !CSRF_TOKEN) return;

    const url = e.target.action;

    fetch(url, {
        method:  'DELETE',
        headers: AJAX_HEADERS
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const row = document.querySelector(`tr[data-id="${currentDeleteUserId}"]`);
            if (row) row.remove();
            
            closeDeleteModal(); // Menutup modal konfirmasi
            updateStatCards();  // Sinkronisasi angka statistik
        }
    })
    .catch(() => alert('Gagal menghapus user. Silakan coba lagi.'));
}

/**
 * Mengubah status aktif/nonaktif User via AJAX (Metode PATCH)
 */
window.toggleUserStatus = function(btn, userId) {
    if (!CSRF_TOKEN) return;

    fetch(`/user/${userId}/toggle-status`, {
        method:  'PATCH',
        headers: AJAX_HEADERS
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const isAktif = data.aktif;

            // 1. Perbarui tampilan tombol yang diklik (di dalam dropdown)
            btn.textContent = isAktif ? 'Aktif' : 'Nonaktif';
            btn.className   = `badge-status ${isAktif ? 'aktif' : 'nonaktif'}`;
            btn.style.cssText = 'border:none;cursor:pointer;font-family:inherit;width:100%;';

            // 2. Sinkronkan badge status utama pada baris tabel yang sama
            const row = btn.closest('tr');
            if (row) {
                const statusBadge = row.querySelector('td .badge-status:not([onclick])');
                if (statusBadge && !statusBadge.closest('.dropdown-menu')) {
                    statusBadge.textContent = isAktif ? 'Aktif' : 'Nonaktif';
                    statusBadge.className   = `badge-status ${isAktif ? 'aktif' : 'nonaktif'}`;
                }
            }

            closeAllDropdowns(); // Tutup dropdown setelah aksi selesai
            updateStatCards();   // Perbarui total aktif/nonaktif di atas
        }
    })
    .catch(() => alert('Gagal mengubah status. Silakan coba lagi.'));
}