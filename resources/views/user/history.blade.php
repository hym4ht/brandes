{{-- ==========================================================================
     HALAMAN: Riwayat Akses User
     Deskripsi: Menampilkan rekap statistik dan tabel riwayat aktivitas 
                akses brankas khusus untuk user yang sedang login.
     ========================================================================== --}}

@extends('layouts.app-user')

{{-- 1. INFORMASI HALAMAN --}}
@section('title', 'Riwayat Akses')
@section('page_title', 'Riwayat Akses')
@section('page_subtitle', 'Riwayat aktivitas akses brankas Anda.')

{{-- 2. KONTEN UTAMA --}}
@section('content')



    {{-- Alert Informasi Konteks Halaman --}}
    @include('components.c-user.alert.alert-history', [
        'id' => 'alert_history_v2',
        'title' => 'Riwayat Akses Pribadi',
        'message' => 'Halaman ini hanya menampilkan riwayat aktivitas akses Anda sendiri. Pantau seluruh aktivitas masuk dan status akses brankas di sini.'
    ])

    {{-- SECTION A: Ringkasan Statistik --}}
    <div class="stat-grid">
        
        {{-- Card 1: Total Akses Hari Ini --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Akses Hari ini</span>
                <span class="stat-value" id="totalAksesValue">{{ $totalAkses }}</span>
            </div>
            <div class="stat-icon green">
                <svg viewBox="0 0 28 28" fill="none">
                    <path d="M8.41711 25.9729C11.9594 25.9729 14.8342 22.9373 14.8342 19.1971C14.8342 17.8148 14.4364 16.5409 13.769 15.4704L18.0428 10.9577L20.3401 13.3834L22.1497 11.4726L19.8524 9.04687L21.2513 7.56973L24.1904 10.6731L26 8.76228L23.061 5.65895L24.7166 3.91078L22.907 2L11.9465 13.5731C10.9326 12.8684 9.71337 12.4483 8.41711 12.4483C4.87487 12.4483 2 15.4839 2 19.2242C2 22.9644 4.87487 26 8.41711 26V25.9729ZM8.41711 15.1316C10.5348 15.1316 12.2674 16.961 12.2674 19.1971C12.2674 21.4331 10.5348 23.2626 8.41711 23.2626C6.29946 23.2626 4.56684 21.4331 4.56684 19.1971C4.56684 16.961 6.29946 15.1316 8.41711 15.1316Z" fill="currentColor"/>
                </svg>
            </div>
        </div>

        {{-- Card 2: Akses Berhasil --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Akses Berhasil</span>
                <span class="stat-value" id="aksesBerhasilValue">{{ $aksesBerhasil }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    <path d="M14 26C20.6274 26 26 20.6274 26 14C26 7.37258 20.6274 2 14 2C7.37258 2 2 7.37258 2 14C2 20.6274 7.37258 26 14 26Z" stroke="#00A63E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 14L12.3333 17L19 11" stroke="#00A63E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Card 3: Akses Gagal --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Akses Gagal</span>
                <span class="stat-value" id="aksesGagalValue">{{ $aksesGagal }}</span>
            </div>
            <div class="stat-icon red">
                <svg viewBox="0 0 28 28" fill="none">
                    <g clip-path="url(#clip0_history_gagal)">
                        <path d="M14 26C20.6274 26 26 20.6274 26 14C26 7.37258 20.6274 2 14 2C7.37258 2 2 7.37258 2 14C2 20.6274 7.37258 26 14 26Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.5 10.5L10.5 17.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.5 10.5L17.5 17.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_history_gagal">
                            <rect width="28" height="28" fill="white"/>
                        </clipPath>
                    </defs>
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
                    <path d="M21.0002 21.0002L16.6602 16.6602" stroke="#101828" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="#101828" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <input type="text" id="searchInput" placeholder="Cari History..." oninput="filterTable()">
            </div>

            <div class="toolbar-right-actions">
                {{-- Tombol Download Rekap --}}
                <button class="btn-download" onclick="downloadRekap()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Download Rekap
                </button>

                {{-- Filter Tanggal (Custom Calendar) --}}
                <div class="calendar-wrapper" style="position: relative;">
                    <div class="date-box" onclick="event.stopPropagation(); document.getElementById('historyIndexCalendarDropdown').classList.toggle('show')">
                        <span id="dateLabel">mm / dd / yyyy</span>
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M19 4H17V3C17 2.73478 16.8946 2.48043 16.7071 2.29289C16.5196 2.10536 16.2652 2 16 2C15.7348 2 15.4804 2.10536 15.2929 2.29289C15.1054 2.48043 15 2.73478 15 3V4H9V3C9 2.73478 8.89464 2.48043 8.70711 2.29289C8.51957 2.10536 8.26522 2 8 2C7.73478 2 7.48043 2.10536 7.29289 2.29289C7.10536 2.48043 7 2.73478 7 3V4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V19C2 19.7956 2.31607 20.5587 2.87868 21.1213C3.44129 21.6839 4.20435 22 5 22H19C19.7956 22 20.5587 21.6839 21.1213 21.1213C21.6839 20.5587 22 19.7956 22 19V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM20 19C20 19.2652 19.8946 19.5196 19.7071 19.7071C19.5196 19.8946 19.2652 20 19 20H5C4.73478 20 4.48043 19.8946 4.29289 19.7071C4.10536 19.5196 4 19.2652 4 19V12H20V19ZM20 10H4V7C4 6.73478 4.10536 6.48043 4.29289 6.29289C4.48043 6.10536 4.73478 6 5 6H7V7C7 7.26522 7.10536 7.51957 7.29289 7.70711C7.48043 7.89464 7.73478 8 8 8C8.26522 8 8.51957 7.89464 8.70711 7.70711C8.89464 7.51957 9 7.26522 9 7V6H15V7C15 7.26522 15.1054 7.51957 15.2929 7.70711C15.4804 7.89464 15.7348 8 16 8C16.2652 8 16.5196 7.89464 16.7071 7.70711C16.8946 7.51957 17 7.26522 17 7V6H19C19.2652 6 19.5196 6.10536 19.7071 6.29289C19.8946 6.48043 20 6.73478 20 7V10Z" fill="#101828" />
                        </svg>
                        <input type="date" id="dateFilter" onchange="filterByDate()" hidden>
                    </div>
                    @include('components.c-shared.calendar', ['id' => 'historyIndexCalendarDropdown', 'target' => 'dateFilter'])
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION C: Tabel Riwayat Aktivitas --}}
    <div class="table-card">
        <div class="table-wrap table-responsive">
            <table id="historyTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>AKTIVITAS</th>
                        <th>METODE</th>
                        <th>WAKTU</th>
                        <th>TOTAL AKSES</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($histories) && count($histories) > 0)
                        {{-- Baris Data Riwayat --}}
                        @foreach($histories as $index => $history)
                        @php
                            $aktivitas = $history['aktivitas'] ?? $history['aktivitas_bersih'] ?? 'Akses Brankas';
                            $metode = $history['metode'] ?? '-';
                            $waktu = $history['waktu'] ?? '-';
                            $waktuLalu = $history['waktu_lalu'] ?? '-';
                            $totalAksesRow = $history['total_akses'] ?? 0;
                            $status = $history['status'] ?? '-';
                        @endphp
                        <tr class="data-row">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $aktivitas }}</td>
                            <td>
                                {!! str_replace(' + ', ' <span class="span-meta-dot"></span> ', $metode) !!}
                            </td>
                            <td>
                                <div class="time-wrapper">
                                    <div class="time-main">{{ $waktu }}</div>
                                    <div class="time-ago">{{ $waktuLalu }}</div>
                                </div>
                            </td>
                            <td>{{ $totalAksesRow > 0 ? $totalAksesRow . 'X' : '-' }}</td>
                            <td>
                                <span class="status-badge {{ $status === 'Berhasil' ? 'status-berhasil' : 'status-gagal' }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @endif

                    {{-- Tampilan Saat Data Belum Tersedia --}}
                    <x-c-shared.empty-state 
                        id="historyEmpty" 
                        colspan="6" 
                        title="Belum Ada Data" 
                        desc="Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat."
                        :display="!(isset($histories) && count($histories) > 0) ? 'table-row' : 'none'"
                    />

                    {{-- Tampilan Saat Hasil Pencarian Tidak Ditemukan --}}
                    <x-c-shared.empty-state 
                        id="historySearchEmpty" 
                        colspan="6" 
                        type="search"
                        title="Data Tidak Ditemukan" 
                        desc="Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda."
                        display="none"
                    />
                </tbody>
            </table>
        </div>
    </div>

@endsection

{{-- 3. MANAGEMENT ASSET (Khusus Halaman) --}}
@push('styles')
    @vite(['resources/css/user/history.css'])
@endpush

@push('scripts')
    @vite(['resources/js/user/history.js'])
@endpush
