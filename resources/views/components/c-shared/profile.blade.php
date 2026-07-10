{{-- ======================================================================
KOMPONEN: Profile Dropdown (Shared)
Deskripsi: Menu dropdown profil user yang berisi detail informasi
akun dan fitur pemindah tema (Light/Dark Mode).
====================================================================== --}}

<div class="user-dropdown" id="userDropdown">

    {{-- 1. BAGIAN HEADER: Avatar & Info User --}}
    <div class="dropdown-header">

        {{-- Avatar (Inisial Nama) --}}
        <div class="user-avatar big">
            {{ strtoupper(substr(session('user.nama', 'A'), 0, 1)) }}
        </div>

        {{-- Detail Nama & Role --}}
        <div class="user-info-detail">
            <span class="user-name-val">{{ session('user.nama', 'Admin') }}</span>
            <span class="user-role-val">
                @if(session('user.is_superadmin'))
                    Super Admin
                @else
                    {{ ucfirst(session('user.role', 'Admin')) }}
                @endif
            </span>
        </div>

        {{-- Label Status --}}
        <span class="status-badge">Aktif</span>

    </div>

    {{-- Garis Pemisah --}}
    <div class="dropdown-divider"></div>

    {{-- 2. BAGIAN FITUR: Theme Switcher (Mode Terang/Gelap) --}}
    <div class="theme-switcher-wrapper">
        <div class="theme-switcher">

            {{-- Tombol Mode Terang --}}
            <button type="button" class="theme-btn light" onclick="setTheme('light')" id="btn-light">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.07" x2="5.64" y2="17.66"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
            </button>

            {{-- Tombol Mode Gelap --}}
            <button type="button" class="theme-btn dark" onclick="setTheme('dark')" id="btn-dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>

        </div>
    </div>

</div>