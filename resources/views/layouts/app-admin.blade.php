<!DOCTYPE html>
<html lang="id">

<head>
    {{-- 1. META & INFORMASI DASAR --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Brandes') - Sistem Monitoring Brankas</title>

    {{-- 2. TYPOGRAPHY (Google Fonts) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    {{-- 3. ASSET CSS (Layout & Components) --}}
    @vite('resources/css/shared/colors.css')
    @vite('resources/css/layouts/app-admin.css')
    
    {{-- Import CSS Komponen Shared --}}
    @vite([
        'resources/css/components/c-shared/calendar.css',
        'resources/css/components/c-shared/profile.css',
        'resources/css/components/c-shared/status.css',
        'resources/css/components/c-shared/delete.css',
        'resources/css/components/c-shared/logout.css',
        'resources/css/components/c-shared/opsi.css',
        'resources/css/components/c-shared/security-card.css',
        'resources/css/components/c-shared/toast.css'
    ])

    @stack('styles')

    {{-- 4. ASSET JS (Framework & Global Logic) --}}
    {{-- Turbo 8: Untuk navigasi cepat ala SPA --}}
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js" defer></script>
    
    @vite('resources/js/layouts/app.js')
    
    {{-- Import JS Komponen Shared --}}
    @vite([
        'resources/js/components/c-shared/calendar.js',
        'resources/js/components/c-shared/logout.js',
        'resources/js/components/c-shared/status-toggle.js',
        'resources/js/components/c-shared/toast.js'
    ])
</head>

<body data-turbo-prefetch="true">
    
    {{-- Data Sesi untuk Tracking Perubahan Data User --}}
    <meta name="user-updated-at" content="{{ session('user.updated_at') ?? '0' }}">

    {{-- Deteksi Tema (Light/Dark) Sebelum Rendering --}}
    <script>
        if (localStorage.getItem('theme_mode') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    {{-- Komponen Navigasi Utama --}}
    @include('components.c-admin.navigation.sidebar')
    
    {{-- Komponen Modal Global --}}
    @include('components.c-shared.logout')
    @include('components.c-shared.toast')

    {{-- AREA UTAMA APLIKASI --}}
    <div class="main-wrap" id="mainWrap">
        
        {{-- Deteksi Status Sidebar (Collapsed/Expanded) --}}
        <script>
            if (localStorage.getItem('sidebar_collapsed') === 'true') {
                document.getElementById('mainWrap').classList.add('collapsed');
            }
        </script>

        {{-- Overlay Sidebar (Tampil hanya di Mobile) --}}
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebarMobile()"></div>

        {{-- Navigasi Atas --}}
        @include('components.c-admin.navigation.topbar')

        {{-- Konten Halaman --}}
        <main class="page-content">
            @yield('content')
        </main>

        {{-- Slot untuk Modal Halaman Tertentu --}}
        @stack('modals')

    </div>

    {{-- Script Tambahan dari Masing-masing Halaman --}}
    @stack('scripts')

</body>
</html>