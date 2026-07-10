/**
 * ==========================================================================
 * app.js - Enterprise UI Controller
 * Deskripsi: Mengelola interaksi UI global seperti sidebar, modal, dropdown,
 *            dan tema. Dioptimalkan untuk stabilitas dengan Hotwire Turbo 8.
 * ==========================================================================
 */

// ==========================================================================
// 1. MANAJEMEN SIDEBAR (Desktop & Mobile)
// ==========================================================================

/**
 * Mengubah status sidebar (Desktop: Collapsed / Expanded)
 * Dan menyimpan statusnya ke localStorage.
 */
window.toggleSidebar = function () {
    const sidebar  = document.getElementById('sidebar');
    const mainWrap = document.getElementById('mainWrap');

    if (!sidebar || !mainWrap) return;

    const isCollapsed = sidebar.classList.toggle('collapsed');
    mainWrap.classList.toggle('collapsed');

    localStorage.setItem('sidebar_collapsed', isCollapsed);
};

/**
 * Mengelola sidebar versi mobile (Overlay Mode)
 */
window.toggleSidebarMobile = function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (!sidebar || !overlay) return;

    const isActive = sidebar.classList.toggle('mobile-active');
    overlay.classList.toggle('show');

    // Mencegah scroll body saat sidebar mobile terbuka
    if (isActive) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};


// ==========================================================================
// 2. MANAJEMEN MODAL & OVERLAY (Global)
// ==========================================================================

/**
 * Fungsi sentral untuk menutup semua modal, overlay, dan dropdown yang aktif.
 * Mencegah terjadinya penumpukan (stacking) antar komponen UI.
 */
window.closeAllModals = function () {
    // 1. Tutup Semua Dropdown
    if (window.closeAllDropdowns) window.closeAllDropdowns();

    // 2. Tutup Sidebar Mobile
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar) sidebar.classList.remove('mobile-active');
    if (overlay) overlay.classList.remove('show');

    // 3. Tutup Modal Umum (Tambah/Edit)
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('show'));

    // 4. Tutup Modal Hapus
    document.querySelectorAll('.user-delete-overlay').forEach(m => m.classList.remove('show'));

    // 5. Tutup Modal Logout
    const logoutModal = document.getElementById('logoutOverlay');
    if (logoutModal) logoutModal.classList.remove('show');

    // 6. Kembalikan Scroll Body
    document.body.style.overflow = '';
    document.body.classList.remove('no-scroll');
};

/**
 * Logika Modal Logout
 */
window.openLogoutModal = function () {
    window.closeAllModals(); // Pastikan modal lain tertutup
    const modal = document.getElementById('logoutOverlay');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
};

window.closeLogoutModal = function () {
    window.closeAllModals();
};


// ==========================================================================
// 3. MANAJEMEN DROPDOWN (User Profile & Others)
// ==========================================================================

/**
 * Toggle menu dropdown profil user
 */
window.toggleUserDropdown = function (e) {
    if (e) e.stopPropagation();

    const dropdown = document.getElementById('userDropdown');
    const badge    = document.getElementById('userBadge');

    if (dropdown && badge) {
        const isShown = dropdown.classList.toggle('show');
        badge.classList.toggle('active');
        localStorage.setItem('user_dropdown_shown', isShown);
    }
};

/**
 * Menutup seluruh dropdown yang terbuka secara paksa (Helper)
 */
window.closeAllDropdowns = function () {
    // 1. Dropdown Profil User
    const dropdown = document.getElementById('userDropdown');
    const badge    = document.getElementById('userBadge');
    if (dropdown) dropdown.classList.remove('show');
    if (badge)    badge.classList.remove('active');
    localStorage.removeItem('user_dropdown_shown');

    // 2. Dropdown Kalender
    const calendars = document.querySelectorAll('.calendar-dropdown');
    calendars.forEach(cal => cal.classList.remove('show'));

    // 3. Menu Opsi/Context Menu (dari opsi.js)
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        menu.classList.remove('show');
    });
};


// ==========================================================================
// 4. MANAJEMEN TEMA (Dark & Light Mode)
// ==========================================================================

/**
 * Mengatur mode tema dan menyimpannya ke localStorage
 */
window.setTheme = function (mode) {
    if (mode === 'dark') {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme_mode', 'dark');
    } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme_mode', 'light');
    }
    updateThemeUI();
};

/**
 * Memperbarui tampilan tombol toggle tema (Active State)
 */
function updateThemeUI() {
    const isDark   = document.body.classList.contains('dark-mode');
    const btnLight = document.getElementById('btn-light');
    const btnDark  = document.getElementById('btn-dark');

    if (btnLight && btnDark) {
        if (isDark) {
            btnDark.classList.add('active');
            btnLight.classList.remove('active');
        } else {
            btnLight.classList.add('active');
            btnDark.classList.remove('active');
        }
    }
}


// ==========================================================================
// 5. SISTEM TURBO & SINKRONISASI STATE
// ==========================================================================

/**
 * Menyinkronkan status visual UI berdasarkan data di localStorage
 * Dipanggil setiap kali halaman dimuat atau setelah navigasi Turbo.
 */
function syncUIState() {
    // 1. Sinkronisasi Tema
    const isDark = localStorage.getItem('theme_mode') === 'dark';
    if (isDark) {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    updateThemeUI();

    // 2. Sinkronisasi Status Sidebar
    const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
    const sidebar     = document.getElementById('sidebar');
    const mainWrap    = document.getElementById('mainWrap');

    if (sidebar && mainWrap) {
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainWrap.classList.add('collapsed');
        } else {
            sidebar.classList.remove('collapsed');
            mainWrap.classList.remove('collapsed');
        }
    }

    // 3. Sinkronisasi Dropdown Profil (Persistence)
    const isDropdownShown = localStorage.getItem('user_dropdown_shown') === 'true';
    const dropdown        = document.getElementById('userDropdown');
    const badge           = document.getElementById('userBadge');
    
    if (dropdown && badge && isDropdownShown) {
        dropdown.classList.add('show');
        badge.classList.add('active');
    }
}

/**
 * Membersihkan elemen UI yang menggantung sebelum navigasi Turbo berlangsung
 */
function clearUIGlitches() {
    // 1. Tutup Modals & Dropdowns
    window.closeAllDropdowns();
    window.closeLogoutModal();

    // 2. Bersihkan Sidebar & Overlays
    const sidebar             = document.getElementById('sidebar');
    const overlay             = document.getElementById('sidebarOverlay');
    const mobileActiveSidebar = document.querySelector('.sidebar.mobile-active');

    if (sidebar)             sidebar.classList.remove('mobile-active');
    if (mobileActiveSidebar) mobileActiveSidebar.classList.remove('mobile-active');
    if (overlay)             overlay.classList.remove('show');

    // 3. Reset Scroll Body
    document.body.style.overflow = '';
    
    // 4. Hapus State Persistence yang tidak diinginkan saat navigasi
    localStorage.removeItem('user_dropdown_shown');
}

// ==========================================================================
// 6. EVENT LISTENERS GLOBAL
// ==========================================================================

if (!window.turboHandlersInitialized) {
    
    // Klik Global (Delegasi Event)
    document.addEventListener('click', (e) => {
        // Klik di luar dropdown untuk menutupnya
        const dropdown = document.getElementById('userDropdown');
        const badge    = document.getElementById('userBadge');

        if (dropdown && dropdown.classList.contains('show') && !dropdown.contains(e.target) && !badge.contains(e.target)) {
            dropdown.classList.remove('show');
            badge.classList.remove('active');
            localStorage.removeItem('user_dropdown_shown');
        }

        // Klik background overlay logout untuk menutupnya
        if (e.target.id === 'logoutOverlay') {
            window.closeLogoutModal();
        }
    });

    // Lifecycle Turbo Navigation
    document.addEventListener('turbo:render', syncUIState);         // Setelah render navigasi
    document.addEventListener('turbo:load', syncUIState);           // Saat load pertama & navigasi selesai
    document.addEventListener('turbo:visit', clearUIGlitches);      // Sebelum pindah halaman
    document.addEventListener('turbo:before-cache', clearUIGlitches); // Sebelum cache snapshot
    document.addEventListener('turbo:before-render', clearUIGlitches);// Sebelum render konten baru

    window.turboHandlersInitialized = true;
}

// Inisialisasi awal saat pertama kali buka browser
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', syncUIState);
} else {
    syncUIState();
}