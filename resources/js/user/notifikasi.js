/**
 * notifikasi.js (User)
 * Notification Filtering & Management for the User Dashboard
 */

// ==========================================================================
// 1. DATE PICKER & FILTERING UTILITIES
// ==========================================================================

/**
 * Triggers the browser-native date picker for filters
 */
// OpenDatePicker removed - handled by custom dropdown component

// ==========================================================================
// 2. FILTERING LOGIC
// ==========================================================================

/**
 * Main Search Bar filter handler
 */
function filterNotif() {
    const keywordInput = document.getElementById('searchInput');
    const dateInput = document.getElementById('dateFilter');

    if (!keywordInput || !dateInput) return;

    const keyword = keywordInput.value.toLowerCase();
    const date = dateInput.value;
    applyFilter(keyword, date);
}

/**
 * Handles visual updates and card filtering when the date changes
 */
function filterByDate() {
    const input = document.getElementById('dateFilter');
    const label = document.getElementById('dateLabel');
    if (!input || !label) return;

    const date = input.value;

    // Update visual date label
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

// State Filter Global
window.notifCurrentTab = 'semua';

/**
 * Toggles visibility between different notification categories
 * @param {string} tab - Tab name ('semua', 'kritis', 'peringatan', 'akses')
 * @param {HTMLElement} btn - The button element that was clicked
 */
function filterTab(tab, btn) {
    if (!btn) return;

    // 1. Update State Global
    window.notifCurrentTab = tab;

    // 2. Update Tab Button UI
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // 3. Apply all filters
    filterNotif();

    // 4. Reset scroll list
    const listWrapper = document.getElementById('notifList');
    if (listWrapper) listWrapper.scrollTop = 0;
}

/**
 * Applies search, date, and category filtering to the notification cards
 * @param {string} keyword - Search term from search input
 * @param {string} date - Date string from filter input
 */
function applyFilter(keyword, date) {
    const cards = document.querySelectorAll('.notif-item');
    let visibleCount = 0;

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const cardType = card.getAttribute('data-tipe') || '';

        // Check Category (Tab)
        const matchTab = window.notifCurrentTab === 'semua' || cardType === window.notifCurrentTab;
        
        // Check Keyword (Search)
        const matchKeyword = keyword === '' || text.includes(keyword);
        
        // Check Date
        const matchDate = date === '' || text.includes(date);

        if (matchTab && matchKeyword && matchDate) {
            card.style.display = 'flex'; // Gunakan flex agar layout tidak rusak
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Handle Empty States Visibility (Sync with Admin logic)
    const noDataState = document.getElementById('notifNoData');
    const emptyState  = document.getElementById('notifEmpty');
    
    const totalCards  = cards.length;
    const isEmpty     = visibleCount === 0;
    const isSearching = keyword !== '' || date !== '';

    if (noDataState) {
        // Tampilkan "Belum Ada Data" jika database kosong DAN tidak sedang mencari
        noDataState.style.display = (totalCards === 0 && !isSearching) ? 'flex' : 'none';
    }

    if (emptyState) {
        // Tampilkan "Data Tidak Ditemukan" jika (ada data tapi tidak cocok) ATAU (database kosong tapi sedang mencari)
        emptyState.style.display = (isEmpty && isSearching) ? 'flex' : 'none';
    }
}

// ==========================================================================
// 3. EXPORT FUNCTIONS
// ==========================================================================

// EKSPOS FUNGSI KE WINDOW SECARA EKSPLISIT (Untuk akses via onclick di Blade)
window.filterTab = filterTab;
window.filterNotif = filterNotif;
window.filterByDate = filterByDate;
window.downloadRekapNotif = downloadRekapNotif;

// 🔴 ================= START TAMBAHAN / REPLACE FUNCTION downloadRekapNotif =================

/**
 * Menangani proses pengunduhan rekap notifikasi user dalam format PDF.
 */
function downloadRekapNotif() {
    // 1. Tampilkan Toast Segera untuk memberikan feedback
    if (typeof showToast === 'function') {
        showToast('Memproses Rekap', 'Sistem sedang menyiapkan file rekap notifikasi Anda...');
    }

    const keyword = document.getElementById('searchInput')?.value || '';
    const date = document.getElementById('dateFilter')?.value || '';
    const tab = window.notifCurrentTab || 'semua';

    // 2. Arahkan browser ke route download
    setTimeout(() => {
        window.location.href = `/notifikasi/download?search=${encodeURIComponent(keyword)}&date=${encodeURIComponent(date)}&tab=${encodeURIComponent(tab)}`;
    }, 1000);
}

// 🔴 ================= END TAMBAHAN / REPLACE FUNCTION downloadRekapNotif =================
