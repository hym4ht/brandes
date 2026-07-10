/**
 * dashboard-user.js
 * Logika interaksi untuk Dashboard User: Jam Real-time, Filter History, dan Status Brankas.
 */

// ==========================================================================
// 1. UTILITAS & GREETING
// ==========================================================================


// ==========================================================================
// 2. KOMPONEN REAL-TIME (Jam & Status Brankas)
// ==========================================================================

/**
 * Memperbarui tampilan jam dan tanggal real-time di Dashboard.
 */
function updateRealtimeClock() {
    const dateElement = document.getElementById("realtime-date");
    const clockElement = document.getElementById("realtime-clock");

    if (!dateElement || !clockElement) return;

    const now = new Date();

    // Format Tanggal: DD Bulan YYYY (contoh: 06 April 2026)
    const dateOptions = { day: "2-digit", month: "long", year: "numeric" };
    const dateStr = now.toLocaleDateString("id-ID", dateOptions);

    // Format Waktu: HH:mm:ss
    const timeStr = now.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false
    }).replace(/\./g, ":");

    dateElement.textContent = dateStr;
    clockElement.textContent = timeStr;
}

/**
 * Memperbarui tampilan kartu status Brankas (Terkunci/Terbuka/Offline).
 * @param {boolean} isOnline - true jika terhubung ke IoT.
 * @param {string} statusPintu - 'TERKUNCI' atau 'TERBUKA'.
 */
function updateBrankasStatus(isOnline, statusPintu) {
    const card = document.getElementById('brankas-card');
    const label = document.getElementById('brankas-status-text');
    const liveText = document.getElementById('live-indicator-text');
    
    if (!card || !label) return;

    const iconLocked = card.querySelector('.icon-locked');
    const iconUnlocked = card.querySelector('.icon-unlocked');
    const isOpen = statusPintu === 'TERBUKA';

    label.innerText = isOpen ? 'TERBUKA' : 'TERKUNCI';
    if (iconLocked) iconLocked.style.display = isOpen ? 'none' : 'block';
    if (iconUnlocked) iconUnlocked.style.display = isOpen ? 'block' : 'none';

    const koneksiText = document.getElementById('koneksi-text');
    if (koneksiText) koneksiText.textContent = `Koneksi IoT: ${isOnline ? 'Online' : 'Offline'}`;

    const monitoringText = document.getElementById('monitoring-text');
    if (monitoringText) monitoringText.textContent = `Monitoring: ${isOnline ? 'Aktif' : 'Non-Aktif'}`;

    const koneksiOnIcon = document.getElementById('koneksi-on-icon');
    const koneksiOffIcon = document.getElementById('koneksi-off-icon');
    if (koneksiOnIcon) koneksiOnIcon.style.display = isOnline ? 'block' : 'none';
    if (koneksiOffIcon) koneksiOffIcon.style.display = isOnline ? 'none' : 'block';

    const monitorOnIcon = document.getElementById('monitoring-on-icon');
    const monitorOffIcon = document.getElementById('monitoring-off-icon');
    if (monitorOnIcon) monitorOnIcon.style.display = isOnline ? 'block' : 'none';
    if (monitorOffIcon) monitorOffIcon.style.display = isOnline ? 'none' : 'block';

    card.classList.remove('is-locked', 'is-unlocked', 'is-offline');
    card.classList.add(isOpen ? 'is-unlocked' : 'is-locked');

    // 1. Tangani Kondisi Offline
    if (!isOnline) {
        card.classList.add('is-offline');
        if (liveText) liveText.innerText = 'OFFLINE';
        return;
    }

    // 2. Tangani Kondisi Online
    card.classList.remove('is-offline');
    if (liveText) liveText.innerText = 'LIVE';
}

function fetchBrankasStatus() {
    fetch('/api/iot/status')
        .then(response => response.json())
        .then(data => {
            if (!data.success) return;

            const isOnline = data.is_online === 1 || data.is_online === true || data.is_online === '1';
            updateBrankasStatus(isOnline, data.status_pintu);
        })
        .catch(error => console.error('Gagal mengambil status brankas:', error));
}

// Menjalankan pembaruan jam setiap detik
setInterval(updateRealtimeClock, 1000);

// EKSPOS KE WINDOW (Agar bisa dipanggil dari atribut onclick/oninput di HTML)
window.updateRealtimeClock = updateRealtimeClock;

document.addEventListener("DOMContentLoaded", () => {
    updateRealtimeClock();
    fetchBrankasStatus();
    setInterval(fetchBrankasStatus, 2000);

    // Mengatur ucapan salam berdasarkan waktu saat ini
    const hour = new Date().getHours();
    let greeting = 'Selamat Datang';

    if (hour >= 5 && hour < 12) {
        greeting = 'Selamat Pagi';
    } else if (hour >= 12 && hour < 15) {
        greeting = 'Selamat Siang';
    } else if (hour >= 15 && hour < 18) {
        greeting = 'Selamat Sore';
    } else {
        greeting = 'Selamat Malam';
    }

    // Menerapkan salam ke elemen Topbar
    const subtitle = document.querySelector('.topbar-left p');
    if (subtitle) {
        const currentText = subtitle.textContent;
        // Cek placeholder standar
        if (currentText.includes('Selamat datang')) {
            subtitle.textContent = currentText.replace('Selamat datang', greeting);
        }
    }
});
