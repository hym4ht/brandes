/**
 * ==========================================================================
 * dashboard-admin.js
 * Deskripsi: Mengelola logika dashboard panel admin, termasuk pemantauan 
 *            real-time status brankas, GPS, jam, dan filter data.
 * ==========================================================================
 */

import { reverseGeocodeAddress } from '../utils/reverse-geocode';

let lastDashboardReverseGeocodeKey = '';
let lastDashboardMapKey = '';
const resolvedDashboardAddressByCoordinate = new Map();

// ==========================================================================
// 1. UTILITAS FORM & FILTER
// ==========================================================================

/**
 * Toggle status visual sederhana
 */
function toggleStatus() {
    const toggle = document.getElementById('statusToggle');
    if (toggle) toggle.classList.toggle('off');
}

/**
 * Membuka date picker browser untuk pencarian History
 */
function openDatePicker() {
    const input = document.getElementById('historyDate');
    if (input) {
        input.showPicker ? input.showPicker() : input.click();
    }
}

/**
 * Membuka date picker browser untuk pencarian Notifikasi
 */
function openNotifDatePicker() {
    const input = document.getElementById('notifDate');
    if (input) {
        input.showPicker ? input.showPicker() : input.click();
    }
}

/**
 * Memfilter daftar Riwayat Akses berdasarkan kata kunci dan tanggal
 */
function filterHistory() {
    const keywordInput = document.getElementById('historySearch');
    const dateInput = document.getElementById('historyDateFilter');
    const label = document.getElementById('dateLabel');

    if (!keywordInput || !dateInput) return;

    const keyword = keywordInput.value.toLowerCase().trim();
    const date = dateInput.value;

    // 1. Update visual label untuk Date Picker
    if (date) {
        const d = new Date(date + 'T00:00:00');
        label.textContent = d.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }).replace(/\//g, ' / ');
        label.style.color = 'var(--C-Black, #101828)';
    } else {
        label.textContent = 'mm / dd / yyyy';
        label.style.color = 'var(--C-Black-Second, #6A7282)';
    }

    // 2. Filter Baris Data
    const items = document.querySelectorAll('#historyList .history-item');
    let visibleCount = 0;

    items.forEach(item => {
        const name = item.dataset.name || '';
        const itemDate = item.dataset.date || '';
        const matchName = keyword === '' || name.includes(keyword);
        const matchDate = date === '' || itemDate === date;

        if (matchName && matchDate) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // 3. Manajemen Status Visual (Banner & Empty States)
    const isSearching = keyword !== '' || date !== '';
    const isEmpty = visibleCount === 0;

    // --- Banner Tanggal ---
    const dateGroup = document.getElementById('historyDateGroup');
    const dateText = document.getElementById('historyDateText');
    if (dateGroup && dateText) {
        if (date !== '') {
            const d = new Date(date + 'T00:00:00');
            const formatted = d.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric'
            });
            dateText.textContent = `History Akses Pada Tanggal : ${formatted}`;
            dateGroup.classList.add('active');
        } else {
            dateText.textContent = `-`;
            dateGroup.classList.remove('active');
        }
    }

    // --- Label Tanggal Group (Today, dll) ---
    const historyLabelDate = document.querySelector('#historyList .history-label-date');
    if (historyLabelDate) {
        historyLabelDate.style.display = (isEmpty || date !== '') ? 'none' : '';
    }

    // --- Empty States ---
    const emptyState = document.getElementById('historyEmpty');
    const noDataState = document.getElementById('historyNoData');

    if (emptyState) {
        emptyState.style.display = (isEmpty && isSearching) ? 'flex' : 'none';
    }
    if (noDataState) {
        noDataState.style.display = (isEmpty && !isSearching) ? 'flex' : 'none';
    }
}

// State Filter Global
window.notifCurrentTab = 'semua';

/**
 * Menangani perubahan tanggal pada filter notifikasi (Date Picker)
 */
function filterNotifByDate() {
    const input = document.getElementById('notifDateFilter');
    const label = document.getElementById('notifDateLabel');
    if (!input || !label) return;

    const date = input.value;

    // Perbarui label tanggal visual (mm/dd/yyyy)
    if (date) {
        const d = new Date(date + 'T00:00:00');
        label.textContent = d.toLocaleDateString('id-ID', {
            day: '2-digit', month: '2-digit', year: 'numeric'
        }).replace(/\//g, ' / ');
        label.style.color = 'var(--C-Black, #101828)';
    } else {
        label.textContent = 'mm / dd / yyyy';
        label.style.color = 'var(--C-Black-Second, #6A7282)';
    }

    filterNotifications();
}

/**
 * Memfilter daftar Notifikasi berdasarkan kata kunci dan tanggal
 */
function filterNotifications() {
    const keyword = document.getElementById('notifSearch')?.value.toLowerCase().trim() || '';
    const date = document.getElementById('notifDateFilter')?.value || '';

    applyNotifFilter(keyword, date);
}

/**
 * Menerapkan logika filter ke kartu notifikasi (Tab, Search, & Date)
 */
function applyNotifFilter(keyword, date) {
    const items = document.querySelectorAll('#notifList .notif-item');
    let visibleCount = 0;

    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        const cardType = item.getAttribute('data-tipe') || '';

        // Cek kecocokan Kategori (Tab)
        const matchTab = window.notifCurrentTab === 'semua' || cardType === window.notifCurrentTab;

        // Cek kecocokan Kata Kunci (Search)
        const matchKeyword = keyword === '' || text.includes(keyword);

        // Cek kecocokan Tanggal (Date Picker)
        const matchDate = date === '' || text.includes(date);

        if (matchTab && matchKeyword && matchDate) {
            item.style.display = 'flex';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Manajemen Tampilan State Kosong (Empty State)
    const emptyState = document.getElementById('notifEmpty');
    const noDataState = document.getElementById('notifNoData');

    const totalCards = items.length;
    const isEmpty = visibleCount === 0;
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
    filterNotifications();

    // 4. Reset scroll list
    const listWrapper = document.getElementById('notifList');
    if (listWrapper) listWrapper.scrollTop = 0;
}

// EKSPOS KE WINDOW
window.filterTab = filterTab;


// ==========================================================================
// 3. LOGIKA POLLING STATUS IOT (REAL-TIME)
// ==========================================================================

/**
 * Mengambil status terbaru brankas dari API IoT
 */
function fetchBrankasStatus() {
    fetch('/api/iot/status')
        .then(response => response.json())
        .then(data => {
            if (!data.success) return;

            const isOnline = data.is_online === 1 || data.is_online === true || data.is_online === '1';
            const isOffline = !isOnline;
            const isOpen = (data.status_pintu === 'TERBUKA');

            // 1. Update Teks Status Utama
            const statusLabel = document.getElementById('brankas-status-text');
            if (statusLabel) {
                statusLabel.textContent = isOpen ? 'TERBUKA' : 'TERKUNCI';
            }

            // 2. Update Ikon Dinamis
            const iconLocked = document.getElementById('icon-locked');
            const iconUnlocked = document.getElementById('icon-unlocked');

            if (iconLocked) iconLocked.style.display = isOpen ? 'none' : 'block';
            if (iconUnlocked) iconUnlocked.style.display = isOpen ? 'block' : 'none';

            // 3. Update Meta Info (Koneksi & Monitoring)
            const koneksiText = document.getElementById('koneksi-text');
            if (koneksiText) koneksiText.textContent = `Koneksi IoT: ${isOffline ? 'Offline' : 'Online'}`;

            const monitoringText = document.getElementById('monitoring-text');
            if (monitoringText) monitoringText.textContent = `Monitoring: ${isOffline ? 'Non-Aktif' : 'Aktif'}`;

            const liveText = document.getElementById('live-indicator-text');
            if (liveText) liveText.textContent = isOffline ? 'OFFLINE' : 'LIVE';

            // Update Ikon Koneksi (Signal)
            const koneksiOnIcon = document.getElementById('koneksi-on-icon');
            const koneksiOffIcon = document.getElementById('koneksi-off-icon');
            if (koneksiOnIcon) koneksiOnIcon.style.display = isOffline ? 'none' : 'block';
            if (koneksiOffIcon) koneksiOffIcon.style.display = isOffline ? 'block' : 'none';

            // Update Ikon Monitoring (Shield)
            const monitorOnIcon = document.getElementById('monitoring-on-icon');
            const monitorOffIcon = document.getElementById('monitoring-off-icon');
            if (monitorOnIcon) monitorOnIcon.style.display = isOffline ? 'none' : 'block';
            if (monitorOffIcon) monitorOffIcon.style.display = isOffline ? 'block' : 'none';


            // 4. Update Background Card Class
            const card = document.getElementById('brankas-card');
            if (card) {
                card.classList.remove('is-locked', 'is-unlocked', 'is-offline');
                const newClass = isOpen ? 'is-unlocked' : 'is-locked';
                card.classList.add(newClass);
                if (isOffline) card.classList.add('is-offline');
            }

            // 6. Update Koordinat GPS (Hanya Lat & Lng sesuai request)
            if (data.latitude !== undefined && data.longitude !== undefined) {
                const namaEl = document.getElementById('brankas-nama-val');
                const lokasiEl = document.getElementById('brankas-lokasi-val');
                const latEl = document.getElementById('brankas-lat-val');
                const lngEl = document.getElementById('brankas-lng-val');

                const coordinateKey = getCoordinateKey(data.latitude, data.longitude);
                const currentAddress = resolvedDashboardAddressByCoordinate.get(coordinateKey) || data.lokasi || '-';

                if (namaEl) namaEl.textContent = data.nama_brankas || data.kode_brankas || '-';
                if (lokasiEl) lokasiEl.textContent = currentAddress;
                if (latEl) latEl.textContent = data.latitude;
                if (lngEl) lngEl.textContent = data.longitude;
                resolveDashboardAddress(data.latitude, data.longitude, data.lokasi || '-');

                const mapIframe = document.getElementById('brankas-map');
                if (mapIframe && coordinateKey && coordinateKey !== lastDashboardMapKey) {
                    lastDashboardMapKey = coordinateKey;
                    mapIframe.src = buildGoogleMapsEmbedUrl(data.latitude, data.longitude);
                }
            }

            // 7. Update Statistik Real-time (Akses, Notif, dan Akses Terakhir)
            const statAkses = document.getElementById('val-stat-akses');
            const statNotif = document.getElementById('val-stat-notif');
            const statLastName = document.getElementById('val-stat-last-name');
            const statLastTime = document.getElementById('val-stat-last-time');

            if (statAkses && data.stat_akses !== undefined) statAkses.textContent = data.stat_akses > 0 ? data.stat_akses : '-';
            if (statNotif && data.stat_notif !== undefined) statNotif.textContent = data.stat_notif > 0 ? data.stat_notif : '-';
            if (statLastName && data.last_akses_name) statLastName.textContent = data.last_akses_name;
            if (statLastTime && data.last_akses_label) statLastTime.textContent = data.last_akses_label;

        })
        .catch(err => console.error('Error fetching brankas status:', err));
}

function resolveDashboardAddress(latValue, lngValue, fallbackAddress = '-') {
    const latNumber = Number(latValue);
    const lngNumber = Number(lngValue);
    const key = getCoordinateKey(latValue, lngValue);
    if (!key) return;

    if (key === lastDashboardReverseGeocodeKey && resolvedDashboardAddressByCoordinate.has(key)) return;

    reverseGeocodeAddress(latNumber, lngNumber, { fallback: fallbackAddress })
        .then((address) => {
            const lokasiEl = document.getElementById('brankas-lokasi-val');
            if (address && address !== fallbackAddress) {
                resolvedDashboardAddressByCoordinate.set(key, address);
                lastDashboardReverseGeocodeKey = key;
            }
            if (lokasiEl && address) lokasiEl.textContent = address;
        });
}

function getCoordinateKey(latValue, lngValue) {
    const latNumber = Number(latValue);
    const lngNumber = Number(lngValue);
    if (!Number.isFinite(latNumber) || !Number.isFinite(lngNumber)) return '';

    return `${latNumber.toFixed(4)},${lngNumber.toFixed(4)}`;
}

function buildGoogleMapsEmbedUrl(latValue, lngValue) {
    const latNumber = Number(latValue);
    const lngNumber = Number(lngValue);

    return `https://maps.google.com/maps?q=${latNumber},${lngNumber}&t=&z=17&ie=UTF8&iwloc=&output=embed`;
}


// ==========================================================================
// 4. LOGIKA REALTIME CLOCK
// ==========================================================================

/**
 * Memperbarui tampilan jam dan tanggal realtime di header dashboard
 */
function updateRealtimeClock() {
    const dateElement = document.getElementById("realtime-date");
    const clockElement = document.getElementById("realtime-clock");

    if (!dateElement || !clockElement) return;

    const now = new Date();

    // Format Tanggal: YYYY-MM-DD
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const dateStr = `${year}-${month}-${day}`;

    // Format Jam: HH:mm:ss
    const timeStr = now.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false
    }).replace(/\./g, ":");

    dateElement.textContent = dateStr;
    clockElement.textContent = timeStr;
}



// ==========================================================================
// 5. INISIALISASI & LIFECYCLE
// ==========================================================================

document.addEventListener('DOMContentLoaded', function () {
    // 1. Inisialisasi status sidebar dari localStorage
    if (localStorage.getItem('sidebar_collapsed') === 'true') {
        const mainWrap = document.getElementById('mainWrap');
        if (mainWrap) mainWrap.classList.add('collapsed');
    }

    // 2. Event listener untuk filter tanggal (untuk menangani trigger manual dari calendar.js)
    const historyDateFilter = document.getElementById('historyDateFilter');
    if (historyDateFilter) {
        historyDateFilter.addEventListener('change', filterHistory);
    }

    const notifDateFilter = document.getElementById('notifDateFilter');
    if (notifDateFilter) {
        notifDateFilter.addEventListener('change', filterNotifByDate);
    }

    // 3. Jalankan Jam Realtime segera
    updateRealtimeClock();

    // 4. Inisialisasi tampilan filter (Banner, dll)
    filterHistory();
    filterNotifications();

    // 5. Jalankan Polling Status Brankas (Instan 2 Detik)
    fetchBrankasStatus();
    setInterval(fetchBrankasStatus, 2000);
});

// Jalankan update jam setiap detik
setInterval(updateRealtimeClock, 1000);

// EKSPOS KE WINDOW (Agar bisa dipanggil dari atribut onclick/oninput di HTML)
window.filterHistory = filterHistory;
window.filterNotifications = filterNotifications;
window.filterNotifByDate = filterNotifByDate;
window.filterTab = filterTab;
window.openDatePicker = openDatePicker;
window.openNotifDatePicker = openNotifDatePicker;
window.toggleStatus = toggleStatus;
window.updateRealtimeClock = updateRealtimeClock;
window.applyNotifFilter = applyNotifFilter;
