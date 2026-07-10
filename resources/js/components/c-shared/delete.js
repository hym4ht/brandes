/**
 * ==========================================================================
 * delete.js
 * Deskripsi: Mengelola logika modal konfirmasi hapus yang bersifat global
 *            (shared). Mendukung penghapusan data via form konvensional
 *            maupun asinkron (AJAX).
 * ==========================================================================
 */

// ==========================================================================
// 1. STATE & KONFIGURASI
// ==========================================================================

// Variabel penampung ID dan tipe data yang sedang akan dihapus
let currentDeleteId   = null;
let currentDeleteType = null;


// ==========================================================================
// 2. KONTROL MODAL
// ==========================================================================

/**
 * Membuka modal konfirmasi hapus
 * @param {number|string} id   - ID data yang akan dihapus
 * @param {string}        name - Nama data untuk ditampilkan di teks konfirmasi
 * @param {string}        type - Tipe endpoint (contoh: 'user', 'admin')
 */
window.openDeleteModal = function(id, name, type = 'user') {
    // Pastikan modal lain tertutup
    if (window.closeAllModals) window.closeAllModals();

    currentDeleteId   = id;
    currentDeleteType = type;
    
    const modal  = document.getElementById('deleteModalOverlay');
    const nameEl = document.getElementById('deleteTargetName');
    const form   = document.getElementById('deleteForm');

    if (modal) {
        // Isi data target ke dalam elemen UI modal
        if (nameEl) nameEl.textContent = name;
        if (form)   form.action         = `/${type}/${id}`; 
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Kunci scroll halaman belakang
        
        // Tutup dropdown opsi jika masih terbuka
        if (window.closeAllDropdowns) window.closeAllDropdowns();
    } else {
        console.error('Elemen modal hapus tidak ditemukan!');
    }
}

/**
 * Menutup modal konfirmasi hapus
 */
window.closeDeleteModal = function() {
    if (window.closeAllModals) window.closeAllModals();
    currentDeleteId = null;
}

/**
 * Menutup modal saat area luar (overlay) diklik
 */
window.closeDeleteModalOutside = function(e) {
    if (e.target.id === 'deleteModalOverlay') {
        window.closeDeleteModal();
    }
}


// ==========================================================================
// 3. AKSI AJAX (Hapus Tanpa Reload)
// ==========================================================================

/**
 * Menangani proses penghapusan data secara asinkron via AJAX (Fetch API)
 */
window.handleDeleteAJAX = function(e) {
    e.preventDefault();
    if (!currentDeleteId) return;

    const url   = e.target.action;
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(url, {
        method:  'DELETE',
        headers: {
            'X-CSRF-TOKEN':     token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept':           'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Hapus baris data dari tabel secara visual
            const row = document.querySelector(`tr[data-id="${currentDeleteId}"]`);
            if (row) row.remove();
            
            window.closeDeleteModal();
            
            // Sinkronisasi angka statistik di halaman (jika tersedia)
            if (window.updateStatCards) window.updateStatCards();

            // Sinkronisasi penomoran baris tabel agar urut kembali & styling border baris terakhir
            const remainingRows = document.querySelectorAll('tr.data-row');
            remainingRows.forEach((r, idx) => {
                // Update penomoran
                const tdNo = r.querySelector('.td-no');
                if (tdNo) tdNo.textContent = idx + 1;
                
                // Bersihkan border bawah pada baris terakhir
                r.classList.remove('last-row');
                if (idx === remainingRows.length - 1) {
                    r.classList.add('last-row');
                }
            });
        } else {
            alert(data.message || 'Gagal menghapus data.');
        }
    })
    .catch(() => alert('Terjadi kesalahan koneksi saat menghapus data.'));
}