/**
 * ==========================================================================
 * history.js
 * Deskripsi: Mengelola logika halaman Riwayat Akses Admin, termasuk
 *            pencarian, filter tanggal, perhitungan statistik realtime,
 *            dan ekspor data ke CSV.
 * ==========================================================================
 */

// ==========================================================================
// 1. UTILITAS KALENDER
// ==========================================================================

function openDatePicker() {
    const input = document.getElementById('dateFilter');
    if (!input) return;
    if (input.showPicker) input.showPicker(); else input.click();
}

function filterByDate() {
    const input = document.getElementById('dateFilter');
    const label = document.getElementById('dateLabel');
    if (!input || !label) return;
    const date = input.value;
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
    const keyword = document.getElementById('searchInput')?.value.toLowerCase() || '';
    applyFilter(keyword, date);
}

// ==========================================================================
// 2. LOGIKA FILTER TABEL
// ==========================================================================

function filterTable() {
    const keyword = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const date    = document.getElementById('dateFilter')?.value || '';
    applyFilter(keyword, date);
}

function applyFilter(keyword, date) {
    const rows                = document.querySelectorAll('#historyTable tbody tr.data-row');
    const emptyState          = document.getElementById('historyEmpty');
    const searchNotFoundState = document.getElementById('historySearchEmpty');
    
    let visibleCount = 0;

    rows.forEach(row => {
        const text          = row.textContent.toLowerCase();
        const waktuElement  = row.querySelector('.time-main');
        const waktuFull     = waktuElement ? waktuElement.textContent.trim() : '';
        const waktuDateOnly = waktuFull.split(' ')[0]; 

        const matchKeyword = keyword === '' || text.includes(keyword);
        const matchDate    = date === '' || waktuDateOnly === date;

        if (matchKeyword && matchDate) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    if (visibleCount > 0) {
        if (emptyState)          emptyState.style.display = 'none';
        if (searchNotFoundState) searchNotFoundState.style.display = 'none';
    } else {
        if (keyword !== '' || date !== '') {
            if (emptyState)          emptyState.style.display = 'none';
            if (searchNotFoundState) searchNotFoundState.style.display = 'table-row';
        } else {
            if (emptyState)          emptyState.style.display = 'table-row';
            if (searchNotFoundState) searchNotFoundState.style.display = 'none';
        }
    }
}

// ==========================================================================
// 3. REALTIME STATS POLLING
// ==========================================================================

function syncStatsRealtime() {
    // Hanya sync jika tidak sedang mencari (agar UI tidak loncat-loncat)
    const keyword = document.getElementById('searchInput')?.value || '';
    const date    = document.getElementById('dateFilter')?.value || '';
    if (keyword !== '' || date !== '') return;

    fetch('/history-akses/stats')
        .then(response => response.json())
        .then(data => {
            const elTotal    = document.getElementById('totalAksesValue');
            const elBerhasil = document.getElementById('aksesBerhasilValue');
            const elGagal    = document.getElementById('aksesGagalValue');

            if (elTotal)    elTotal.textContent    = data.total;
            if (elBerhasil) elBerhasil.textContent = data.berhasil;
            if (elGagal)    elGagal.textContent    = data.gagal;
        })
        .catch(err => console.log('Polling stats error'));
}

// Jalankan setiap 3 detik
setInterval(syncStatsRealtime, 3000);

// ==========================================================================
// 4. EKSPOR DATA
// ==========================================================================

// 🔴 ================= START TAMBAHAN / REPLACE FUNCTION downloadRekap =================

function downloadRekap() {
    if (typeof showToast === 'function') {
        showToast('Memproses Rekap', 'Sistem sedang menyiapkan file rekap riwayat akses...');
    }

    const keyword = document.getElementById('searchInput')?.value || '';
    const date = document.getElementById('dateFilter')?.value || '';
    
    // Tambahkan Telp/Timestamp unik agar bypass cache web server/proxy
    const cacheBuster = new Date().getTime();

    setTimeout(() => {
        window.location.href = `/history-akses/download?search=${encodeURIComponent(keyword)}&date=${encodeURIComponent(date)}&_cb=${cacheBuster}`;
    }, 1000);
}

// 🔴 ================= END TAMBAHAN / REPLACE FUNCTION downloadRekap =================

window.filterByDate = filterByDate;
window.filterTable = filterTable;
window.downloadRekap = downloadRekap;
window.openDatePicker = openDatePicker;
window.syncStatsRealtime = syncStatsRealtime;