/**
 * ==========================================================================
 * status-toggle.js
 * Deskripsi: Mengelola pengubahan status (Aktif/Nonaktif) secara asinkron
 *            melalui AJAX. Mendukung modul User maupun Admin.
 * ==========================================================================
 */

/**
 * Mengubah status data melalui request PATCH dan memperbarui UI secara dinamis.
 * @param {HTMLElement}   btn  - Elemen tombol yang diklik (di dalam dropdown)
 * @param {number|string} id   - ID data yang akan diubah statusnya
 * @param {string}        type - Tipe modul (contoh: 'user', 'admin')
 */
window.toggleStatus = function(btn, id, type) {
    if (!btn || !id || !type) return;

    // 1. Persiapkan Data Request
    const url   = `/${type}/${id}/toggle-status`;
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    // 2. Berikan Feedback Visual (Loading State)
    btn.style.opacity       = '0.5';
    btn.style.pointerEvents = 'none';

    // 3. Eksekusi Request ke Server
    fetch(url, {
        method:  'PATCH',
        headers: {
            'X-CSRF-TOKEN':     token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept':           'application/json',
            'Content-Type':     'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const newStatus = data.aktif; // Nilai boolean dari server
            const label     = newStatus ? 'Aktif' : 'Nonaktif';
            const className = newStatus ? 'aktif' : 'nonaktif';

            // A. Perbarui tampilan tombol yang diklik
            btn.textContent         = label;
            btn.className           = `badge-status ${className}`;
            btn.style.opacity       = '1';
            btn.style.pointerEvents = 'auto';

            // B. Perbarui badge status utama pada baris tabel (sync)
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                // Cari badge di dalam kolom tabel (bukan badge di dalam dropdown)
                const tableBadge = row.querySelector('td .badge-status:not([onclick])');
                if (tableBadge) {
                    tableBadge.textContent = label;
                    tableBadge.className   = `badge-status ${className}`;
                }
            }

            // C. Sinkronisasi angka statistik di bagian atas halaman (jika tersedia)
            if (window.updateStatCards) {
                window.updateStatCards();
            }

        } else {
            alert(data.message || 'Gagal mengubah status.');
            btn.style.opacity       = '1';
            btn.style.pointerEvents = 'auto';
        }
    })
    .catch(err => {
        console.error('Error toggling status:', err);
        alert('Terjadi kesalahan koneksi.');
        btn.style.opacity       = '1';
        btn.style.pointerEvents = 'auto';
    });
};
