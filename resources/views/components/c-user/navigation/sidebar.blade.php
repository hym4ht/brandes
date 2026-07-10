{{-- ======================================================================
     KOMPONEN: Sidebar User
     Deskripsi: Struktur navigasi utama untuk panel user/pelanggan.
                Berisi logo, menu navigasi, dan tombol logout.
     ====================================================================== --}}
<aside class="sidebar" id="sidebar">

    {{-- 1. SIDEBAR BRAND (Logo & Title) --}}
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 94 94" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-img">
                <path d="M0 16C0 7.16344 7.16344 0 16 0H78C86.8366 0 94 7.16344 94 16V78C94 86.8366 86.8366 94 78 94H16C7.16344 94 0 86.8366 0 78V16Z" fill="#00A63E"/>
                <path d="M23 75.4682V34.504L30.7308 30.061L44.3263 37.9695V15.8435L38.9058 19.0424V32.2825L36.6844 30.9496V17.5318L44.3263 13L58.0106 20.9973V30.061L71.6061 37.8806V50.7653L64.4973 55.0305L71.6061 59.2958V72.0027L58.0106 79.8223V30.061L44.3263 37.9695V72.0915L30.7308 80V32.8156L25.3103 35.9257V74.1353L23 75.4682Z" fill="white"/>
            </svg>
        </div>
        <div class="brand-info">
            <strong>BRANDES</strong>
            <span>User Panel</span>
        </div>
        
        {{-- Tombol Toggle (Desktop Expand/Collapse) --}}
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 17L13 12L18 7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 17L6 12L11 7"/>
            </svg>
        </button>
    </div>

    {{-- 2. SIDEBAR NAVIGATION MENUS --}}
    <nav class="sidebar-nav">
        
        {{-- Menu: Dashboard --}}
        <a href="{{ route('dashboard.user') }}" class="nav-item {{ request()->routeIs('dashboard.user') ? 'active' : '' }}" data-title="Dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M9 3H4C3.44772 3 3 3.44772 3 4V11C3 11.5523 3.44772 12 4 12H9C9.55228 12 10 11.5523 10 11V4C10 3.44772 9.55228 3 9 3Z" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M20 3H15C14.4477 3 14 3.44772 14 4V7C14 7.55228 14.4477 8 15 8H20C20.5523 8 21 7.55228 21 7V4C21 3.44772 20.5523 3 20 3Z" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M20 12H15C14.4477 12 14 12.4477 14 13V20C14 20.5523 14.4477 21 15 21H20C20.5523 21 21 20.5523 21 20V13C21 12.4477 20.5523 12 20 12Z" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 16H4C3.44772 16 3 16.4477 3 17V20C3 20.5523 3.44772 21 4 21H9C9.55228 21 10 20.5523 10 20V17C10 16.4477 9.55228 16 9 16Z" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-label">Dashboard</span>
        </a>

        {{-- Menu: History Akses --}}
        <a href="{{ route('history.akses') }}" class="nav-item {{ request()->routeIs('history.akses') ? 'active' : '' }}" data-title="History Akses">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M3 12C3 13.78 3.52784 15.5201 4.51677 17.0001C5.50571 18.4802 6.91131 19.6337 8.55585 20.3149C10.2004 20.9961 12.01 21.1743 13.7558 20.8271C15.5016 20.4798 17.1053 19.6226 18.364 18.364C19.6226 17.1053 20.4798 15.5016 20.8271 13.7558C21.1743 12.01 20.9961 10.2004 20.3149 8.55585C19.6337 6.91131 18.4802 5.50571 17.0001 4.51677C15.5201 3.52784 13.78 3 12 3C9.48395 3.00947 7.06897 3.99122 5.26 5.74L3 8" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3 3V8H8" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 7V12L16 14" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-label">History Akses</span>
        </a>

        {{-- Menu: Log Berkas --}}
        <a href="{{ route('user.log.berkas') }}" class="nav-item {{ request()->routeIs('user.log.berkas') ? 'active' : '' }}" data-title="Log Berkas">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                <path d="M9 14h6" />
                <path d="M9 18h6" />
                <path d="M9 10h6" />
            </svg>
            <span class="nav-label">Log Berkas</span>
        </a>

        {{-- Menu: Notifikasi Sistem --}}
        <a href="{{ route('notifikasi.keamanan') }}" class="nav-item {{ request()->routeIs('notifikasi.keamanan') ? 'active' : '' }}" data-title="Notifikasi Sistem">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M10.2695 21C10.4451 21.304 10.6975 21.5565 11.0016 21.732C11.3056 21.9075 11.6505 21.9999 12.0015 21.9999C12.3526 21.9999 12.6975 21.9075 13.0015 21.732C13.3055 21.5565 13.558 21.304 13.7335 21" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3.26127 15.326C3.13063 15.4692 3.04442 15.6472 3.01312 15.8385C2.98183 16.0298 3.00679 16.226 3.08498 16.4034C3.16316 16.5807 3.2912 16.7316 3.45352 16.8375C3.61585 16.9434 3.80545 16.9999 3.99927 17H19.9993C20.1931 17.0001 20.3827 16.9438 20.5451 16.8381C20.7076 16.7324 20.8358 16.5817 20.9142 16.4045C20.9926 16.2273 21.0178 16.0311 20.9867 15.8398C20.9557 15.6485 20.8697 15.4703 20.7393 15.327C19.4093 13.956 17.9993 12.499 17.9993 8C17.9993 6.4087 17.3671 4.88258 16.2419 3.75736C15.1167 2.63214 13.5906 2 11.9993 2C10.408 2 8.88185 2.63214 7.75663 3.75736C6.63141 4.88258 5.99927 6.4087 5.99927 8C5.99927 12.499 4.58827 13.956 3.26127 15.326Z" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-label">Notifikasi Sistem</span>
        </a>

        {{-- Menu: Profile & Kredensial --}}
        <a href="{{ route('user.kredensial') }}" class="nav-item {{ request()->routeIs('user.kredensial') ? 'active' : '' }}" data-title="Profile & Kredensial">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18 20V18.3333C18 17.4493 17.6388 16.6014 16.9958 15.9763C16.3528 15.3512 15.4807 15 14.5714 15H9.42857C8.51926 15 7.64719 15.3512 7.00421 15.9763C6.36122 16.6014 6 17.4493 6 18.3333V20" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12Z" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="nav-label">Profile & Kredensial</span>
        </a>

    </nav>

    {{-- 3. SIDEBAR FOOTER (Logout Section) --}}
    <div class="sidebar-footer">
        <button type="button" class="nav-logout" onclick="openLogoutModal()">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 17L21 12L16 7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9"/>
            </svg>
            <span class="nav-label">Logout</span>
        </button>
    </div>

</aside>