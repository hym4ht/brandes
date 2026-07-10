{{-- ======================================================================
KOMPONEN: Topbar User
Deskripsi: Header bagian atas yang berisi Judul Halaman (Dynamic)
dan Menu Profil User.
====================================================================== --}}
<header class="topbar">

    {{-- 1. BAGIAN KIRI: Mobile Menu & Page Titles --}}
    <div class="topbar-left-wrapper">

        {{-- Tombol Hamburger (Muncul hanya di Mobile) --}}
        <button class="mobile-menu-btn" onclick="toggleSidebarMobile()" title="Menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Judul Halaman (Diambil dari @yield di masing-masing page) --}}
        <div class="topbar-left">
            <div class="topbar-text">
                <h1>@yield('page_title', 'Dashboard')</h1>
                <p>@yield('page_subtitle', 'Sistem Keamanan Brankas BRANDES.')</p>
            </div>
        </div>
    </div>

    {{-- 2. BAGIAN KANAN: User Profile & Dropdown --}}
    <div class="user-menu-container">

        {{-- User Badge (Trigger untuk memunculkan dropdown profil) --}}
        <div class="user-badge" id="userBadge" onclick="toggleUserDropdown(event)">
            <div class="user-avatar">
                {{ strtoupper(substr(session('user.nama', 'U'), 0, 1)) }}
            </div>
            <span class="user-name-text">
                {{ session('user.nama', 'User') }}
            </span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="arrow-icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>

        {{-- Komponen Dropdown Profil (Shared) --}}
        @include('components.c-shared.profile')
    </div>

</header>