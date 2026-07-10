{{-- ============================================================
     ADMIN DASHBOARD
     Layout   : layouts.app-admin
     Deskripsi: Halaman utama admin — menampilkan status brankas,
                statistik harian, history akses, dan notifikasi keamanan.
     ============================================================ --}}

@extends('layouts.app-admin')

@section('title', 'Admin Dashboard')
@section('header-title', 'Dashboard')

{{-- ── Stylesheet ── --}}
@push('styles')
    @vite('resources/css/admin/dashboard.css')
    @vite('resources/css/components/c-shared/history-card.css')
    @vite('resources/css/components/c-shared/security-card.css')
@endpush


@section('content')

    {{-- ==============================================================
         1. STATUS BRANKAS (shared component)
         Menampilkan status realtime brankas: Terkunci / Terbuka / Offline
         ============================================================== --}}
    @include('components.c-shared.status-brankas', [
        'brankas' => $brankas
    ])


    {{-- ==============================================================
         2. STATISTIK HARIAN
         Tiga kartu ringkasan: Total Akses, Total Notifikasi, Akses Terakhir
         ============================================================== --}}
    <div class="stat-grid">

        {{-- Kartu: Total Akses Hari Ini --}}
        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Total Akses Hari Ini</span>
                <div class="stat-icon">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                        <path d="M8.41711 25.9729C11.9594 25.9729 14.8342 22.9373 14.8342 19.1971C14.8342 17.8148 14.4364 16.5409 13.769 15.4704L18.0428 10.9577L20.3401 13.3834L22.1497 11.4726L19.8524 9.04687L21.2513 7.56973L24.1904 10.6731L26 8.76228L23.061 5.65895L24.7166 3.91078L22.907 2L11.9465 13.5731C10.9326 12.8684 9.71337 12.4483 8.41711 12.4483C4.87487 12.4483 2 15.4839 2 19.2242C2 22.9644 4.87487 26 8.41711 26V25.9729ZM8.41711 15.1316C10.5348 15.1316 12.2674 16.961 12.2674 19.1971C12.2674 21.4331 10.5348 23.2626 8.41711 23.2626C6.29946 23.2626 4.56684 21.4331 4.56684 19.1971C4.56684 16.961 6.29946 15.1316 8.41711 15.1316Z" fill="currentColor" />
                    </svg>
                </div>
            </div>

            <div class="stat-value" id="val-stat-akses">{{ $statAkses['total'] > 0 ? $statAkses['total'] : '-' }}</div>
            <div class="stat-divider"></div>

            <div class="stat-footer">
                <span class="footer-text">Aktivitas terdeteksi hari ini</span>
            </div>
        </div>

        {{-- Kartu: Total Notifikasi Hari Ini --}}
        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Total Notifikasi Hari Ini</span>
                <div class="stat-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M10.2695 21C10.4451 21.304 10.6975 21.5565 11.0016 21.732C11.3056 21.9075 11.6505 21.9999 12.0015 21.9999C12.3526 21.9999 12.6975 21.9075 13.0015 21.732C13.3055 21.5565 13.558 21.304 13.7335 21" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.26127 15.326C3.13063 15.4692 3.04442 15.6472 3.01312 15.8385C2.98183 16.0298 3.00679 16.226 3.08498 16.4034C3.16316 16.5807 3.2912 16.7316 3.45352 16.8375C3.61585 16.9434 3.80545 16.9999 3.99927 17H19.9993C20.1931 17.0001 20.3827 16.9438 20.5451 16.8381C20.7076 16.7324 20.8358 16.5817 20.9142 16.4045C20.9926 16.2273 21.0178 16.0311 20.9867 15.8398C20.9557 15.6485 20.8697 15.4703 20.7393 15.327C19.4093 13.956 17.9993 12.449 17.9993 8C17.9993 6.4087 17.3671 4.88258 16.2419 3.75736C15.1167 2.63214 13.5906 2 11.9993 2C10.408 2 8.88185 2.63214 7.75663 3.75736C6.63141 4.88258 5.99927 6.4087 5.99927 8C5.99927 12.449 4.58827 13.956 3.26127 15.326Z" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <div class="stat-value" id="val-stat-notif">{{ $statNotif['total'] > 0 ? $statNotif['total'] : '-' }}</div>
            <div class="stat-divider"></div>

            <div class="stat-footer">
                <span class="footer-text">Notifikasi keamanan hari ini</span>
            </div>
        </div>

        {{-- Kartu: Akses Terakhir Hari Ini --}}
        <div class="stat-card">
            <div class="stat-top">
                <span class="stat-label">Akses Terakhir Hari Ini</span>
                <div class="stat-icon">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                        <path d="M3.5 14C3.5 16.0767 4.11581 18.1068 5.26957 19.8335C6.42332 21.5602 8.0632 22.906 9.98182 23.7007C11.9004 24.4955 14.0116 24.7034 16.0484 24.2982C18.0852 23.8931 19.9562 22.8931 21.4246 21.4246C22.8931 19.9562 23.8931 18.0852 24.2982 16.0484C24.7034 14.0116 24.4955 11.9004 23.7007 9.98182C22.906 8.0632 21.5602 6.42332 19.8335 5.26957C18.1068 4.11581 16.0767 3.5 14 3.5C11.0646 3.51104 8.24713 4.65643 6.13667 6.69667L3.5 9.33333" stroke="currentColor" stroke-width="2.24" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3 4V9.83333H8.83333" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M14 8.16699V14.0003L18.6667 16.3337" stroke="currentColor" stroke-width="2.24" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

            <div class="stat-value" id="val-stat-last-name">{{ $lastAkses['name'] }}</div>
            <div class="stat-divider"></div>

            <div class="stat-footer">
                <span class="footer-text" id="val-stat-last-time">{{ $lastAkses['label'] }}</span>
            </div>
        </div>

    </div>{{-- /.stat-grid --}}


    {{-- ==============================================================
         3. DASHBOARD GRID
         Dua kolom: Lokasi Brankas (2/3) dan History Akses Terbaru (1/3)
         ============================================================== --}}
    <div class="dashboard-grid">

        {{-- ── Kartu: Lokasi Brankas ── --}}
        <div class="card">

            {{-- Header dengan link ke halaman detail lokasi --}}
            <a href="{{ route('lokasi.brankas') }}" class="card-header">
                <span class="card-title">Lokasi Brankas</span>
                <span class="card-arrow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9.5 6L15.5 12L9.5 18" stroke="#6A7282" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
            <div class="card-divider"></div>

            {{-- Peta Google Maps — src diperbarui via JS polling --}}
            <div class="map-wrap">
                <iframe id="brankas-map"
                    src="https://maps.google.com/maps?q=-6.9105,109.1479&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            {{-- Informasi detail lokasi brankas --}}
            <div class="brankas-info">
                <div class="brankas-content-top">
                    <div class="brankas-info-title">Detail Lokasi</div>

                    <div class="info-group-wrapper">

                        {{-- Nama Brankas --}}
                        <div class="info-row">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M3 21H21M9 8H10M9 12H10M9 16H10M14 8H15M14 12H15M14 16H15M5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span id="brankas-nama-val">
                                {{ $brankas->nama_brankas ?? '-' }}
                            </span>
                        </div>

                        {{-- Alamat / Lokasi --}}
                        <div class="info-row">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M20 10C20 14.993 14.461 20.193 12.601 21.799C12.4277 21.9293 12.2168 21.9998 12 21.9998C11.7832 21.9998 11.5723 21.9293 11.399 21.799C9.539 20.193 4 14.993 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span id="brankas-lokasi-val">
                                {{ $brankas->lokasi ?? '-' }}
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Koordinat GPS (Lat / Lng) --}}
                <div class="brankas-footer">
                    <div class="info-coords">
                        <span>Lat: <strong id="brankas-lat-val">-</strong></span>
                        <svg class="meta-dot" width="6" height="6" viewBox="0 0 8 8" fill="none">
                            <circle cx="4" cy="4" r="4" fill="#D1D5DB" />
                        </svg>
                        <span>Lng: <strong id="brankas-lng-val">-</strong>°</span>
                    </div>
                </div>
            </div>

        </div>{{-- /.card Lokasi Brankas --}}


        {{-- ── Kartu: History Akses Terbaru ── --}}
        <div class="card">

            {{-- Header dengan link ke halaman history lengkap --}}
            <a href="{{ route('history.akses') }}" class="card-header">
                <span class="card-title">History Akses Terbaru</span>
                <span class="card-arrow">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9.5 6L15.5 12L9.5 18" stroke="#6A7282" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </a>
            <div class="card-divider"></div>

            {{-- Group Header: Search + Banner Tanggal --}}
            <div class="history-header-group">
                {{-- Area Search & Filter Tanggal --}}
                <div class="history-search">
                    <div class="history-inputs-row">

                        {{-- Input pencarian berdasarkan nama --}}
                        <div class="search-box history-search-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L16.65 16.65M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <input type="text" id="historySearch" placeholder="Cari History..." oninput="filterHistory()">
                        </div>

                        {{-- Date Picker — terhubung ke custom calendar component --}}
                        <div class="calendar-wrapper" style="position: relative;">
                            <div class="date-picker" onclick="event.stopPropagation(); document.getElementById('dashboardCalendarDropdown').classList.toggle('show')">
                                <span id="dateLabel">mm / dd / yyyy</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                </svg>
                                <input type="date" id="historyDateFilter" onchange="filterHistory()" hidden>
                            </div>
                            @include('components.c-shared.calendar', ['id' => 'dashboardCalendarDropdown', 'target' => 'historyDateFilter'])
                        </div>

                    </div>
                </div>

                {{-- Banner tanggal: Selalu tampil --}}
                <div class="history-banner-wrapper" id="historyDateGroup">
                    <div class="history-banner-content">
                        <span id="historyDateText">-</span>
                    </div>
                </div>
            </div>

            <div class="card-divider"></div>

            {{-- Daftar History (scrollable) --}}
            <div id="historyList">

                {{-- Label tanggal: Hanya muncul jika ada data, mengikuti tanggal akses terbaru --}}
                @if($histories->isNotEmpty())
                    <div class="history-label-date">
                        {{ \Carbon\Carbon::parse($histories->first()->waktu)->format('Y-m-d') }}
                    </div>
                @endif

                {{-- 1. Kondisi: Benar-benar Belum Ada Data (Database Kosong) --}}
                @if($histories->isEmpty())
                    <x-c-shared.empty-state 
                        id="historyNoData" 
                        :isTable="false" 
                        title="Belum Ada Data" 
                        desc="Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat." 
                    />
                @else
                    {{-- 2. Kondisi: Ada Data (Looping) --}}
                    @foreach($histories as $history)
                        @php
                            $userId = $history->user_id ?? 99;
                            $hash = md5($userId . 'brandes_salt');
                            $suffix = chr(65 + (ord($hash[0]) % 26)) . (ord($hash[1]) % 10) . chr(65 + (ord($hash[2]) % 26));

                            // Ambil dari tabel user jika ada relasi, atau fallback ke tabel history
                            $fpId = $history->user->fingerprint_id ?? $history->fingerprint_id;

                            $metodeLabel = str_contains($history->aktivitas ?? '', 'PIN') ? '+ PIN' : '';
                            $fp_id_formatted = $fpId ? 'FP-' . str_pad($fpId, 3, '0', STR_PAD_LEFT) . '-' . $suffix . ' ' . $metodeLabel : '-';
                        @endphp
                        @include('components.c-shared.history-card', [
                            'type' => $history->status === 'Berhasil' ? 'success' : 'danger',
                            'name' => $history->user->nama ?? ($history->nama ?? 'Unknown'),
                            'action' => trim(explode('(', ($history->aktivitas ?? 'Akses Brankas'))[0]),
                            'badge' => $fp_id_formatted,
                            'time' => $history->waktu ? (method_exists($history->waktu, 'diffForHumans') ? $history->waktu->diffForHumans() : \Carbon\Carbon::parse($history->waktu)->diffForHumans()) : '-',
                            'date' => $history->waktu ? \Carbon\Carbon::parse($history->waktu)->format('Y-m-d') : date('Y-m-d')
                        ])
                    @endforeach

                @endif

                {{-- 3. Empty State Khusus: Hasil Pencarian/Filter Tidak Ditemukan (JS) --}}
                <x-c-shared.empty-state 
                    id="historyEmpty" 
                    display="none"
                    :isTable="false" 
                    type="search"
                    title="Data Tidak Ditemukan" 
                    desc="Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda." 
                />

            </div>{{-- /#historyList --}}

        </div>{{-- /.card History Akses Terbaru --}}

    </div>{{-- /.dashboard-grid --}}


    {{-- ==============================================================
         4. NOTIFIKASI KEAMANAN TERBARU
         Menampilkan daftar notifikasi dengan filter pencarian & tanggal
         ============================================================== --}}
    <div class="card">

        {{-- Header dengan link ke halaman notifikasi lengkap --}}
        <a href="{{ route('notifikasi.keamanan') }}" class="card-header">
            <span class="card-title">Notifikasi Keamanan Terbaru</span>
            <span class="card-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M9.5 6L15.5 12L9.5 18" stroke="#6A7282" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </a>
        <div class="card-divider"></div>

        {{-- Area Search & Filter Tanggal Notifikasi --}}
        <div class="history-search">
            <div class="history-inputs-row">

                {{-- Input pencarian notifikasi --}}
                <div class="search-box">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L16.65 16.65M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <input type="text" id="notifSearch" placeholder="Cari Notifikasi..." oninput="filterNotifications()">
                </div>

                {{-- Date Picker — terhubung ke custom calendar component --}}
                <div class="calendar-wrapper" style="position: relative;">
                    <div class="date-picker" onclick="event.stopPropagation(); document.getElementById('notifCalendarDropdown').classList.toggle('show')">
                        <span id="notifDateLabel">mm / dd / yyyy</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        <input type="date" id="notifDateFilter" onchange="filterNotifByDate()" hidden>
                    </div>
                    @include('components.c-shared.calendar', ['id' => 'notifCalendarDropdown', 'target' => 'notifDateFilter', 'labelId' => 'notifDateLabel'])
                </div>

            </div>
        </div>

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
                    <div class="legend-item"><span class="dot red"></span> Kritis</div>
                    <div class="legend-item"><span class="dot yellow"></span> Peringatan</div>
                    <div class="legend-item"><span class="dot green"></span> Akses</div>
                </div>

            </div>
            <hr class="panel-divider">
        </div>

        {{-- Daftar Kartu Notifikasi --}}
        <div class="notif-list" id="notifList">
            @forelse($notifications as $notif)
                @include('components.c-shared.security-card', [
                    'role' => 'admin',
                    'type' => $notif['tipe'],
                    'title' => $notif['judul'],
                    'description' => $notif['deskripsi'],
                    'meta' => $notif['meta'] ?? [],
                    'time' => $notif['waktu']
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
        </div>

    </div>{{-- /.card Notifikasi Keamanan --}}

@endsection

{{-- ── Scripts ── --}}
@push('scripts')
    @vite('resources/js/admin/dashboard-admin.js')
@endpush
