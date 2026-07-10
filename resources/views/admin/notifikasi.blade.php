{{-- ============================================================
     HALAMAN: Notifikasi Keamanan
     Layout   : layouts.app-admin
     Deskripsi: Halaman untuk memantau aktivitas mencurigakan dan
                peringatan keamanan secara real-time.
     ============================================================ --}}

@extends('layouts.app-admin')

@section('title',         'Notifikasi Keamanan')
@section('page_title',    'Notifikasi Keamanan')
@section('page_subtitle', 'Pantau aktivitas mencurigakan dan peringatan keamanan.')

{{-- ── Stylesheet ── --}}
@push('styles')
    @vite(['resources/css/admin/notifikasi.css', 'resources/css/components/c-shared/security-card.css'])
@endpush


@section('content')

@php
    // Perhitungan statistik dinamis berdasarkan data $notifikasi
    $totalPeringatan = $notifikasi->where('tipe', 'peringatan')->count();
    $totalKritis     = $notifikasi->where('tipe', 'kritis')->count();
    $totalAkses      = $notifikasi->where('tipe', 'akses')->count();
    
    // Total akumulasi dari semua kategori
    $totalNotifikasi = $totalPeringatan + $totalKritis + $totalAkses;
@endphp

    {{-- ==============================================================
         1. RINGKASAN STATISTIK (STAT CARDS)
         Empat ringkasan: Total Notifikasi, Peringatan, Kritis, Akses
         ============================================================== --}}
    <div class="stat-grid">
        
        {{-- Kartu: Total Notifikasi --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Notifikasi</span>
                <span class="stat-value">{{ $totalNotifikasi }}</span>
            </div>
            <div class="stat-icon green">
                <svg viewBox="0 0 28 28" fill="none">
                    <path d="M11.9805 24.5C12.1853 24.8547 12.4798 25.1492 12.8345 25.354C13.1892 25.5588 13.5916 25.6666 14.0011 25.6666C14.4107 25.6666 14.8131 25.5588 15.1678 25.354C15.5225 25.1492 15.817 24.8547 16.0218 24.5" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.80481 17.88C3.65241 18.0471 3.55183 18.2548 3.51531 18.4779C3.4788 18.7021 3.50792 18.9301 3.59914 19.137C3.69036 19.3439 3.83974 19.5198 4.02911 19.6434C4.21849 19.767 4.43969 19.8328 4.66581 19.833H23.3325C23.5586 19.8331 23.7798 19.7675 23.9693 19.6441C24.1588 19.5208 24.3084 19.345 24.3999 19.1383C24.4913 18.9315 24.5207 18.7026 24.4845 18.4794C24.4483 18.2562 24.348 18.0484 24.1958 17.8812C22.6441 16.2817 20.9991 14.5818 20.9991 9.33301C20.9991 7.47649 20.2617 5.69601 18.9489 4.38326C17.6361 3.07051 15.8557 2.33301 13.9991 2.33301C12.1426 2.33301 10.3622 3.07051 9.0494 4.38326C7.73665 5.69601 6.99915 7.47649 6.99915 9.33301C6.99915 14.5818 5.35298 16.2817 3.80481 17.88Z" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        {{-- Kartu: Peringatan --}}
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

        {{-- Kartu: Kritis --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Kritis</span>
                <span class="stat-value">{{ $totalKritis }}</span>
            </div>
            <div class="stat-icon red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>
        </div>

        {{-- Kartu: Akses --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Akses</span>
                <span class="stat-value">{{ $totalAkses }}</span>
            </div>
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

    </div>{{-- /.stat-grid --}}


    {{-- ==============================================================
         2. TOOLBAR (Pencarian, Filter Tanggal & Download Rekap)
         ============================================================== --}}
    <div class="div-toolbar">

        {{-- Input pencarian notifikasi --}}
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" id="searchInput" placeholder="Cari Notifikasi..." oninput="filterNotif()">
        </div>

        {{-- Aksi Toolbar (Download & Date Picker) --}}
        <div class="toolbar-actions">
            
            {{-- Tombol Download Rekap --}}
            <button class="btn-download" onclick="downloadRekap()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Download Rekap
            </button>

            {{-- Custom Calendar Dropdown untuk Filter Tanggal --}}
            <div class="calendar-wrapper" style="position: relative;">
                <div class="date-picker" onclick="event.stopPropagation(); document.getElementById('notifCalendarDropdown').classList.toggle('show')">
                    <span id="dateLabel">mm/dd/yyyy</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <input type="date" id="dateFilter" onchange="filterByDate()" hidden>
                </div>
                @include('components.c-shared.calendar', ['id' => 'notifCalendarDropdown', 'target' => 'dateFilter'])
            </div>

        </div>{{-- /.toolbar-actions --}}

    </div>{{-- /.div-toolbar --}}


    {{-- ==============================================================
         3. DAFTAR NOTIFIKASI
         Tab navigasi, Keterangan tingkat keparahan, dan List Data
         ============================================================== --}}
    <div class="notif-list-wrapper">

        {{-- Panel Header: Tabs Filter & Legenda --}}
        <div class="panel-header">
            <div class="filter-row">
                
                {{-- Tabs Filter Status --}}
                <div class="filter-tabs">
                    <button class="tab-btn active" onclick="filterTab('semua', this)">Semua</button>
                    <button class="tab-btn" onclick="filterTab('kritis', this)">Kritis</button>
                    <button class="tab-btn" onclick="filterTab('peringatan', this)">Peringatan</button>
                    <button class="tab-btn" onclick="filterTab('akses', this)">Akses</button>
                </div>

                {{-- Legenda Tingkat Keparahan --}}
                <div class="legend">
                    <div class="legend-item"><svg class="dot red" width="6" height="6" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="4" fill="currentColor" /></svg> Kritis</div>
                    <div class="legend-item"><svg class="dot yellow" width="6" height="6" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="4" fill="currentColor" /></svg> Peringatan</div>
                    <div class="legend-item"><svg class="dot green" width="6" height="6" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="4" fill="currentColor" /></svg> Akses</div>
                </div>

            </div>
            <hr class="panel-divider">
        </div>{{-- /.panel-header --}}

        {{-- Wrapper List Notifikasi --}}
        <div class="notif-list" id="notifList">

            @forelse($notifikasi as $notif)
                @include('components.c-shared.security-card', [
                    'role'        => 'admin',
                    'type'        => $notif['tipe'],
                    'title'       => $notif['judul'],
                    'description' => $notif['deskripsi'],
                    'meta'        => $notif['meta'] ?? [],
                    'time'        => $notif['waktu']
                ])
            @empty
                {{-- 1. Kondisi: Belum Ada Data (Database Kosong) --}}
                <x-c-shared.empty-state 
                    id="notifNoData" 
                    :isTable="false"
                    title="Belum Ada Data" 
                    desc="Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat."
                />
            @endforelse

            {{-- 2. Kondisi: Hasil Pencarian Tidak Ditemukan (Pencarian/Filter Aktif) --}}
            <x-c-shared.empty-state 
                id="notifEmpty" 
                :isTable="false"
                type="search"
                title="Data Tidak Ditemukan" 
                desc="Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda."
                display="none"
            />
        </div>

    </div>{{-- /.notif-list-wrapper --}}

@endsection


{{-- ── Scripts ── --}}
@push('scripts')
    @vite('resources/js/admin/notifikasi.js')
@endpush