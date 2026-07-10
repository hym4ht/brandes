/**
 * ==========================================================================
 * opsi.js
 * Deskripsi: Mengelola kemunculan menu dropdown aksi (Opsi) pada tabel.
 *            Dilengkapi dengan fitur dynamic positioning (body-appending)
 *            untuk menghindari menu terpotong oleh overflow container.
 * ==========================================================================
 */

// ==========================================================================
// 1. KONTROL DROPDOWN
// ==========================================================================

/**
 * Toggle menu dropdown aksi dan mengatur posisi kemunculannya.
 * @param {HTMLElement} btn - Elemen tombol yang diklik
 */
window.toggleDropdown = function(btn) {
    if (!btn) return;
    
    // Cari pembungkus terdekat untuk menemukan elemen menu
    const wrapper = btn.closest('.aksi-wrap');
    if (!wrapper) return;
    
    /**
     * Cache referensi menu pada elemen tombol.
     * Ini penting karena menu akan dipindahkan ke document.body saat pertama kali dibuka.
     */
    let menu = btn.__dropdownMenu || wrapper.querySelector('.dropdown-menu');
    if (!menu) return;

    if (!btn.__dropdownMenu) {
        btn.__dropdownMenu = menu;
    }

    // 1. Cek apakah menu yang sama sedang terbuka
    const isCurrentlyOpen = menu.classList.contains('show');

    // 2. Tutup semua dropdown lain yang sedang terbuka
    if (window.closeAllDropdowns) {
        window.closeAllDropdowns();
    }

    // 3. Jika sebelumnya tertutup, maka buka dan atur posisinya
    if (!isCurrentlyOpen) {
        
        // Pindahkan elemen menu ke <body> untuk menghindari masalah overflow:hidden pada parent (table/div)
        if (menu.parentElement !== document.body) {
            document.body.appendChild(menu);
        }

        menu.classList.add('show');
        
        // Ambil dimensi tombol dan menu untuk perhitungan posisi
        const rect = btn.getBoundingClientRect();
        
        // Render sementara (hidden) untuk mendapatkan dimensi akurat
        menu.style.visibility = 'hidden';
        menu.style.display    = 'block';
        const menuRect        = menu.getBoundingClientRect();
        menu.style.display    = ''; 
        menu.style.visibility = '';

        /**
         * Kalkulasi Posisi (Absolute terhadap Document)
         * Default: Sisi kanan menu sejajar dengan sisi kanan tombol (melebar ke kiri)
         */
        let top  = rect.bottom + window.scrollY + 8; // Muncul di bawah tombol
        let left = (rect.right + window.scrollX) - menuRect.width;

        // Proteksi: Jika menu keluar dari layar sebelah kiri
        if (left < window.scrollX + 20) {
            left = rect.left + window.scrollX;
        }

        // Proteksi: Jika menu keluar dari layar sebelah kanan
        if (left + menuRect.width > window.innerWidth + window.scrollX - 20) {
            left = (window.innerWidth + window.scrollX) - menuRect.width - 20;
        }

        // Proteksi Tabrakan: Jika menu keluar dari layar sebelah bawah, pindahkan ke atas tombol
        if (rect.bottom + menuRect.height > window.innerHeight + window.scrollY - 20) {
            top = rect.top + window.scrollY - menuRect.height - 8;
        }

        // Terapkan gaya posisi akhir
        menu.style.position = 'absolute'; 
        menu.style.top      = `${top}px`;
        menu.style.left     = `${left}px`;
        menu.style.zIndex   = '999999'; // Pastikan di atas elemen apapun (seperti navbar/sidebar)
    }
};


// ==========================================================================
// 2. EVENT LISTENERS GLOBAL
// ==========================================================================

/**
 * Menutup dropdown saat klik di luar area menu atau tombol toggle
 */
document.addEventListener('mousedown', (e) => {
    const openMenu = document.querySelector('.dropdown-menu.show');
    if (!openMenu) return;

    const isClickInsideMenu = openMenu.contains(e.target);
    const isClickOnBtn      = e.target.closest('.btn-aksi');

    if (!isClickInsideMenu && !isClickOnBtn) {
        if (window.closeAllDropdowns) {
            window.closeAllDropdowns();
        }
    }
});

/**
 * Pembersihan UI sebelum Turbo mengambil snapshot halaman
 */
document.addEventListener('turbo:before-cache', () => {
    if (window.closeAllDropdowns) {
        window.closeAllDropdowns();
    }
});
