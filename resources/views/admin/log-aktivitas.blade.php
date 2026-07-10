{{-- ============================================================
HALAMAN: Log Berkas
Layout : layouts.app-admin
Deskripsi: Menampilkan daftar log berkas yang membutuhkan
berkas dokumen seperti KK, KTP, dan Akte.
============================================================ --}}

@extends('layouts.app-admin')

@section('title', 'Log Berkas')
@section('page_title', 'Log Berkas')
@section('page_subtitle', 'Kelola data log berkas beserta berkas pendukung.')

{{-- ── Stylesheet ── --}}
@push('styles')
    @vite([
        'resources/css/admin/log-aktivitas.css',
        'resources/css/components/c-admin/log-tambah.css',
        'resources/css/components/c-admin/log-edit.css',
        'resources/css/components/c-shared/empty.css'
    ])
    {{-- CSRF token untuk kebutuhan request AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    {{-- ==============================================================
    1. STATISTIK KARTU
    ============================================================== --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Semua Berkas</span>
                <span class="stat-value" id="totalBerkasCount">{{ $totalBerkas > 0 ? $totalBerkas : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total KK</span>
                <span class="stat-value" id="totalKkCount">{{ $totalKk > 0 ? $totalKk : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total KTP</span>
                <span class="stat-value" id="totalKtpCount">{{ $totalKtp > 0 ? $totalKtp : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Akte</span>
                <span class="stat-value" id="totalAkteCount">{{ $totalAkte > 0 ? $totalAkte : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
            </div>
        </div>

    </div>{{-- /.stat-grid --}}

    {{-- ==============================================================
    2. TOOLBAR (Search & Tombol Tambah Data)
    ============================================================== --}}
    <div class="div-toolbar">
        <div class="search-box">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                    stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input type="text" id="searchInput" placeholder="Cari Log Berkas..." onkeyup="filterTable()">
        </div>

        <div class="toolbar-actions">
            <button class="btn-tambah" onclick="openTambahLogModal()">
                Tambah Log
            </button>
        </div>
    </div>{{-- /.div-toolbar --}}

    {{-- ==============================================================
    3. TABEL DATA
    ============================================================== --}}
    <div class="table-card">
        <div class="table-wrap table-responsive">
            <table id="logTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>TANGGAL</th>
                        <th>BERKAS KTP</th>
                        <th>BERKAS KK</th>
                        <th>BERKAS AKTE</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $index => $log)
                        <tr class="data-row {{ $loop->last ? 'last-row' : '' }}" data-id="{{ $log->id }}">
                            <td class="td-no">{{ $index + 1 }}</td>
                            <td class="td-judul">
                                <strong>{{ $log->judul }}</strong>
                                <div style="font-size: 14px; color: var(--C-Black-Second); margin-top:4px;">
                                    NIK: {{ $log->ktp_nik }}
                                </div>
                            </td>
                            <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($log->file_ktp)
                                    @if(isset($log->status_ktp) && $log->status_ktp == 'Diambil')
                                        <span class="badge-status nonaktif">KTP</span>
                                    @else
                                        <span class="badge-status aktif">KTP</span>
                                    @endif
                                @else
                                    <span class="badge-status nonaktif">Belum Ada</span>
                                @endif
                            </td>
                            <td>
                                @if($log->file_kk)
                                    @if(isset($log->status_kk) && $log->status_kk == 'Diambil')
                                        <span class="badge-status nonaktif">KK</span>
                                    @else
                                        <span class="badge-status aktif">KK</span>
                                    @endif
                                @else
                                    <span class="badge-status nonaktif">Belum Ada</span>
                                @endif
                            </td>
                            <td>
                                @if($log->file_akte)
                                    @if(isset($log->status_akte) && $log->status_akte == 'Diambil')
                                        <span class="badge-status nonaktif">AKTE</span>
                                    @else
                                        <span class="badge-status aktif">AKTE</span>
                                    @endif
                                @else
                                    <span class="badge-status nonaktif">Belum Ada</span>
                                @endif
                            </td>
                            <td>
                                <x-c-shared.opsi :item="$log" type="log" />
                            </td>
                        </tr>
                    @endforeach

                    <x-c-shared.empty-state id="emptyState" colspan="7" title="Belum Ada Data Log"
                        desc="Data log berkas belum tersedia." display="{{ ($totalLogs > 0) ? 'none' : '' }}" />

                    <x-c-shared.empty-state id="searchNotFoundState" colspan="7" type="search" title="Data Tidak Ditemukan"
                        desc="Hasil pencarian tidak cocok dengan data log yang tersedia." display="none" />
                </tbody>
            </table>
        </div>
    </div>{{-- /.table-card --}}

    {{-- ==============================================================
    4. MODALS
    ============================================================== --}}
    @push('modals')
        @include('components.c-admin.modal.log-tambah')
        @include('components.c-admin.modal.log-edit')
        @include('components.c-shared.delete', ['type' => 'log-aktivitas'])
    @endpush

@endsection

{{-- ── Scripts ── --}}
@push('scripts')
    @vite([
        'resources/js/components/c-shared/opsi.js',
        'resources/js/components/c-shared/delete.js',
        'resources/js/components/c-admin/log-tambah.js',
        'resources/js/components/c-admin/log-edit.js',
        'resources/js/admin/log-aktivitas.js'
    ])

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(() => {
                    if (window.showToast) window.showToast('Berhasil', '{{ session('success') }}', 4000);
                }, 300);
            });
        </script>
    @endif
@endpush