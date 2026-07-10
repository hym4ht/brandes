/**
 * ==========================================================================
 * history.js (User)
 * Deskripsi: Mengelola logika halaman Riwayat Akses User, termasuk
 *            pencarian, filter tanggal, perhitungan statistik realtime,
 *            dan ekspor data ke CSV.
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

/**
 * Menangani perubahan tanggal dan memperbarui label UI
 */
function filterByDate() {
    const input = document.getElementById('dateFilter');
    const label = document.getElementById('dateLabel');
    if (!input || !label) return;

    const date = input.value;

    // Perbarui label tanggal visual (mm/dd/yyyy)
    if (date) {
        const d = new Date(date + 'T00:00:00');
        label.textContent = d.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        label.style.color = 'var(--text)';
    } else {
        label.textContent = 'mm/dd/yyyy';
        label.style.color = 'var(--text-muted)';
    }

    const keyword = document.getElementById('searchInput')?.value.toLowerCase() || '';
    applyFilter(keyword, date);
}


// ==========================================================================
// 2. LOGIKA FILTER TABEL
// ==========================================================================

/**
 * Handler utama untuk input pencarian (search bar)
 */
function filterTable() {
    const keyword = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const date    = document.getElementById('dateFilter')?.value || '';
    applyFilter(keyword, date);
}

/**
 * Menerapkan filter ke baris tabel dan menghitung statistik visual
 * @param {string} keyword - Kata kunci pencarian
 * @param {string} date    - String tanggal filter (YYYY-MM-DD)
 */
function applyFilter(keyword, date) {
    const rows                = document.querySelectorAll('#historyTable tbody tr.data-row');
    const emptyState          = document.getElementById('historyEmpty');
    const searchNotFoundState = document.getElementById('historySearchEmpty');
    
    let visibleCount     = 0;
    let visTotalToday    = 0;
    let visTotalBerhasil = 0;
    let visTotalGagal    = 0;
    
    // Format tanggal hari ini untuk pencocokan statistik (YYYY-MM-DD)
    const todayStr = new Date().toLocaleDateString('en-CA');
    // Format tanggal untuk pencocokan statistik (gunakan filter tanggal jika diisi, jika tidak gunakan hari ini)
    const targetDateStr = date !== '' ? date : todayStr; 

    rows.forEach(row => {
        const text         = row.textContent.toLowerCase();
        const fullText     = row.innerText; 
        
        // Data waktu di history.blade.php user: <div class="time-main">2026-05-03 12:00:00</div>
        const waktuElement = row.querySelector('.time-main');
        const waktuFull    = waktuElement ? waktuElement.textContent.trim() : '';
        const waktuDateOnly = waktuFull.split(' ')[0]; // Ambil YYYY-MM-DD

        const matchKeyword = keyword === '' || text.includes(keyword);
        const matchDate    = date === '' || waktuDateOnly === date;

        if (matchKeyword && matchDate) {
            row.style.display = '';
            visibleCount++;
            
            // Hitung Statistik Berdasarkan Data yang Terlihat pada Tanggal Target
            if (waktuDateOnly === targetDateStr) {
                visTotalToday++;
                // Status berdasarkan class badge
                const badge = row.querySelector('.status-badge');
                if (badge) {
                    if (badge.classList.contains('status-berhasil')) visTotalBerhasil++;
                    if (badge.classList.contains('status-gagal'))    visTotalGagal++;
                }
            }
        } else {
            row.style.display = 'none';
        }
    });

    // Perbarui Card Statistik di Bagian Atas
    const elTotal    = document.getElementById('totalAksesValue');
    const elBerhasil = document.getElementById('aksesBerhasilValue');
    const elGagal    = document.getElementById('aksesGagalValue');

    if (elTotal)    elTotal.textContent    = visTotalToday;
    if (elBerhasil) elBerhasil.textContent = visTotalBerhasil;
    if (elGagal)    elGagal.textContent    = visTotalGagal;

    // Manajemen Tampilan Empty State
    const isFilterActive = keyword !== '' || date !== '';

    if (visibleCount > 0) {
        if (emptyState)          emptyState.style.display = 'none';
        if (searchNotFoundState) searchNotFoundState.style.display = 'none';
    } else {
        if (isFilterActive) {
            if (emptyState)          emptyState.style.display = 'none';
            if (searchNotFoundState) searchNotFoundState.style.display = 'table-row';
        } else {
            if (emptyState)          emptyState.style.display = 'table-row';
            if (searchNotFoundState) searchNotFoundState.style.display = 'none';
        }
    }
}


// ==========================================================================
// 3. EKSPOR DATA
// ==========================================================================

/**
 * Mengekspor data riwayat yang saat ini terlihat di tabel ke file CSV
 */

// 🔴 ================= START TAMBAHAN / REPLACE FUNCTION downloadRekap =================

function downloadRekap() {
    // 1. Tampilkan Toast Segera untuk memberikan feedback
    if (typeof showToast === 'function') {
        showToast('Memproses Rekap', 'Sistem sedang menyiapkan file rekap riwayat akses Anda...');
    }

    const keyword = document.getElementById('searchInput')?.value || '';
    const date = document.getElementById('dateFilter')?.value || '';

    // 2. Arahkan browser ke route download
    setTimeout(() => {
        window.location.href = `/history-akses/download?search=${encodeURIComponent(keyword)}&date=${encodeURIComponent(date)}`;
    }, 1000);
}

// 🔴 ================= END TAMBAHAN / REPLACE FUNCTION downloadRekap =================

// EKSPOS KE WINDOW
window.filterByDate = filterByDate;
window.filterTable = filterTable;
window.downloadRekap = downloadRekap;
window.openDatePicker = openDatePicker;
