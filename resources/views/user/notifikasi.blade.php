{{-- ==========================================================================
     HALAMAN: Notifikasi Sistem User
     Deskripsi: Menampilkan daftar pemberitahuan, peringatan keamanan, 
                dan log berkas akses brankas untuk user.
     ========================================================================== --}}

@extends('layouts.app-user')

{{-- 1. INFORMASI HALAMAN --}}
@section('title', 'Notifikasi Sistem')
@section('page_title', 'Notifikasi Sistem')
@section('page_subtitle', 'Pantau aktivitas anda.')

{{-- 2. KONTEN UTAMA --}}
@section('content')



    {{-- Alert Informasi Konteks Halaman --}}
    @include('components.c-user.alert.alert-notifikasi', [
        'id' => 'alert_notifikasi_v2',
        'message' => 'Pantau seluruh aktivitas akses brankas Anda di sini, termasuk riwayat masuk dan peringatan keamanan sistem.'
    ])

    {{-- SECTION A: Ringkasan Statistik Notifikasi --}}
    <div class="stat-grid">
        {{-- Card: Total Notifikasi --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Notifikasi</span>
                <span class="stat-value">{{ $totalNotifikasi }}</span>
            </div>
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" fill="none">
                    <path d="M11.9766 24.5C12.1814 24.8547 12.4759 25.1492 12.8306 25.354C13.1853 25.5588 13.5877 25.6666 13.9972 25.6666C14.4068 25.6666 14.8091 25.5588 15.1638 25.354C15.5185 25.1492 15.8131 24.8547 16.0179 24.5" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.80481 17.881C3.65241 18.048 3.55183 18.2558 3.51531 18.4789C3.4788 18.7021 3.50792 18.931 3.59914 19.1379C3.69036 19.3449 3.83974 19.5208 4.02911 19.6444C4.21849 19.7679 4.43969 19.8338 4.66581 19.834H23.3325C23.5586 19.8341 23.7798 19.7684 23.9693 19.6451C24.1588 19.5218 24.3084 19.346 24.3999 19.1393C24.4913 18.9325 24.5207 18.7036 24.4845 18.4804C24.4483 18.2572 24.348 18.0494 24.1958 17.8822C22.6441 16.2827 20.9991 14.5828 20.9991 9.33398C20.9991 7.47747 20.2617 5.69699 18.9489 4.38424C17.6361 3.07148 15.8557 2.33398 13.9991 2.33398C12.1426 2.33398 10.3622 3.07148 9.0494 4.38424C7.73665 5.69699 6.99915 7.47747 6.99915 9.33398C6.99915 14.5828 5.35298 16.2827 3.80481 17.881Z" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Card: Peringatan --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Peringatan</span>
                <span class="stat-value">{{ $totalPeringatan }}</span>
            </div>
            <div class="stat-icon yellow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
        </div>

        {{-- Card: Kritis --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Kritis</span>
                <span class="stat-value">{{ $totalKritis }}</span>
            </div>
            <div class="stat-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Card: Akses --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Akses</span>
                <span class="stat-value">{{ $totalAkses }}</span>
            </div>
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- SECTION B: Toolbar Pencarian & Filter --}}
    <div class="div-toolbar">
        <div class="history-inputs-row">
            
            {{-- Input Pencarian Kata Kunci --}}
            <div class="search-box history-search-box">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <input type="text" id="searchInput" placeholder="Cari Notifikasi..." oninput="filterNotif()">
            </div>

            <div class="toolbar-right-actions">
                {{-- Tombol Download Rekap --}}
                <button class="btn-download" onclick="downloadRekapNotif()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Download Rekap
                </button>

                {{-- Filter Tanggal (Custom Calendar) --}}
                <div class="calendar-wrapper" style="position: relative;">
                    <div class="date-picker" onclick="event.stopPropagation(); document.getElementById('notifIndexCalendarDropdown').classList.toggle('show')">
                        <span id="dateLabel">mm / dd / yyyy</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        <input type="date" id="dateFilter" onchange="filterByDate()" hidden>
                    </div>
                    @include('components.c-shared.calendar', ['id' => 'notifIndexCalendarDropdown', 'target' => 'dateFilter'])
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION C: Daftar Panel Notifikasi --}}
    <div class="notif-list-wrapper">
        <div class="panel-header">
            <div class="filter-row">
                {{-- Tab Filter Kategori (Semua, Kritis, Peringatan, Akses) --}}
                <div class="filter-tabs">
                    <button class="tab-btn active" onclick="filterTab('semua', this)">Semua</button>
                    <button class="tab-btn" onclick="filterTab('kritis', this)">Kritis</button>
                    <button class="tab-btn" onclick="filterTab('peringatan', this)">Peringatan</button>
                    <button class="tab-btn" onclick="filterTab('akses', this)">Akses</button>
                </div>
                {{-- Legend Warna Tipe --}}
                <div class="legend">
                    <div class="legend-item"><svg class="dot red" width="6" height="6" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="4" fill="currentColor" /></svg> Kritis</div>
                    <div class="legend-item"><svg class="dot yellow" width="6" height="6" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="4" fill="currentColor" /></svg> Peringatan</div>
                    <div class="legend-item"><svg class="dot green" width="6" height="6" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="4" fill="currentColor" /></svg> Akses</div>
                </div>
            </div>
            <hr class="panel-divider">
        </div>

        {{-- Container Daftar Notifikasi --}}
        <div class="notif-list" id="notifList">
            @forelse($notifikasi as $notif)
                @include('components.c-shared.security-card', [
                    'role'        => 'user',
                    'type'        => $notif['tipe'],
                    'title'       => $notif['judul'],
                    'description' => $notif['deskripsi'],
                    'meta'        => $notif['meta'] ?? [],
                    'time'        => $notif['waktu'],
                ])
            @empty
                {{-- State: Belum Ada Notifikasi --}}
                <x-c-shared.empty-state 
                    id="notifNoData" 
                    :isTable="false"
                    title="Belum Ada Notifikasi" 
                    desc="Belum ada pemberitahuan baru yang masuk. Semua notifikasi keamanan Anda akan muncul di sini."
                />
            @endforelse

            {{-- State: Hasil Pencarian Tidak Ditemukan --}}
            <x-c-shared.empty-state 
                id="notifEmpty" 
                :isTable="false"
                type="search"
                title="Data Tidak Ditemukan" 
                desc="Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda."
                display="none"
            />
        </div>
    </div>

@endsection

{{-- 3. MANAGEMENT ASSET (Khusus Halaman) --}}
@push('styles')
    @vite([
        'resources/css/user/notifikasi.css', 
        'resources/css/components/c-shared/calendar.css',
        'resources/css/components/c-shared/security-card.css'
    ])
@endpush

@push('scripts')
    @vite([
        'resources/js/components/c-shared/calendar.js',
        'resources/js/user/notifikasi.js'
    ])
@endpush