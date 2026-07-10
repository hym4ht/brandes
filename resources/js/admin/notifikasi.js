/**
 * ==========================================================================
 * notifikasi.js
 * Deskripsi: Mengelola logika halaman Notifikasi Admin, termasuk fitur 
 *            pencarian, filter tanggal, dan perpindahan tab (Semua/Belum Dibaca).
 * ==========================================================================
 */

// ==========================================================================
// 1. UTILITAS KALENDER
// ==========================================================================

/**
 * Memicu kemunculan date picker bawaan browser
 */
function openDatePicker() {
    const input = document.getElementById('dateFilter');
    if (!input) return;

    if (input.showPicker) {
        input.showPicker();
    } else {
        input.click();
    }
}

/// State Filter Global
window.notifCurrentTab = 'semua';

/**
 * Menangani perubahan tanggal dan memperbarui label UI visual
 */
function filterByDate() {
    const input = document.getElementById('dateFilter');
    const label = document.getElementById('dateLabel');
    if (!input || !label) return;

    const date = input.value;

    // Perbarui label tanggal visual (mm/dd/yyyy)
    if (date) {
        const d = new Date(date + 'T00:00:00');
        const year  = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day   = String(d.getDate()).padStart(2, '0');
        label.textContent = `${year}-${month}-${day}`;
        label.style.color = 'var(--text)';
    } else {
        label.textContent = 'mm/dd/yyyy';
        label.style.color = 'var(--text-muted)';
    }

    filterNotif();
}


// ==========================================================================
// 2. LOGIKA TAB & FILTER
// ==========================================================================

/**
 * Handler utama untuk input pencarian (search bar)
 */
function filterNotif() {
    const keyword = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const date    = document.getElementById('dateFilter')?.value || '';
    applyFilter(keyword, date);
}

/**
 * Berpindah antar tab kategori (Semua, Kritis, Peringatan, Akses)
 */
function filterTab(tab, btn) {
    if (!btn) return;

    // 1. Simpan state tab aktif secara global
    window.notifCurrentTab = tab;

    // 2. Perbarui UI tombol tab
    const allTabs = document.querySelectorAll('.tab-btn');
    allTabs.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // 3. Terapkan seluruh filter (termasuk pencarian & tanggal)
    filterNotif();

    // 4. Reset scroll list
    const listWrapper = document.getElementById('notifList');
    if (listWrapper) listWrapper.scrollTop = 0;
}

// EKSPOS FUNGSI KE WINDOW SECARA EKSPLISIT
window.filterTab = filterTab;
window.openDatePicker = openDatePicker;
window.filterByDate = filterByDate;
window.filterNotif = filterNotif;
window.downloadRekap = downloadRekap;

/**
 * Menerapkan logika filter pencarian dan tanggal ke kartu notifikasi
 * @param {string} keyword - Kata kunci dari input pencarian
 * @param {string} date    - String tanggal filter
 */
function applyFilter(keyword, date) {
    const cards       = document.querySelectorAll('.notif-item');
    let visibleCount  = 0;
    
    cards.forEach(card => {
        const text          = card.textContent.toLowerCase();
        const cardType      = card.getAttribute('data-tipe') || '';
        
        // Cek kecocokan Kategori (Tab)
        const matchTab      = window.notifCurrentTab === 'semua' || cardType === window.notifCurrentTab;
        
        // Cek kecocokan Kata Kunci (Search)
        const matchKeyword  = keyword === '' || text.includes(keyword);
        
        // Cek kecocokan Tanggal (Date Picker)
        const matchDate     = date === '' || text.includes(date);

        // Kartu ditampilkan hanya jika SEMUA kriteria terpenuhi
        if (matchTab && matchKeyword && matchDate) {
            card.style.display = 'flex'; // Gunakan flex agar layout tidak rusak
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Manajemen Tampilan State Kosong (Empty State)
    const noDataState  = document.getElementById('notifNoData');
    const emptyState   = document.getElementById('notifEmpty');
    
    const totalCards   = cards.length;
    const isEmpty      = visibleCount === 0;
    const isSearching  = keyword !== '' || date !== '';

    if (noDataState) {
        // Tampilkan "Belum Ada Data" hanya jika database kosong DAN tidak sedang mencari
        noDataState.style.display = (totalCards === 0 && !isSearching) ? 'flex' : 'none';
    }

    if (emptyState) {
        // Tampilkan "Data Tidak Ditemukan" jika (ada data tapi tidak cocok) ATAU (database kosong tapi sedang mencari)
        emptyState.style.display = (isEmpty && isSearching) ? 'flex' : 'none';
    }
}

// ==========================================================================
// 3. EKSPOR DATA
// ==========================================================================

/**
 * Mengunduh rekap notifikasi dalam format PDF
 */

// 🔴 ================= START TAMBAHAN / REPLACE FUNCTION downloadRekap =================

function downloadRekap() {
    // 1. Tampilkan Toast Segera untuk memberikan feedback
    if (typeof showToast === 'function') {
        showToast('Memproses Rekap', 'Sistem sedang menyiapkan file rekap notifikasi...');
    }

    const keyword = document.getElementById('searchInput')?.value || '';
    const date = document.getElementById('dateFilter')?.value || '';
    const tab = window.notifCurrentTab || 'semua';

    // 2. Arahkan browser ke route download
    setTimeout(() => {
        window.location.href = `/notifikasi/download?search=${encodeURIComponent(keyword)}&date=${encodeURIComponent(date)}&tab=${encodeURIComponent(tab)}`;
    }, 1000);
}

// 🔴 ================= END TAMBAHAN / REPLACE FUNCTION downloadRekap =================
