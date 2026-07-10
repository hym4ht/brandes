{{-- ============================================================
HALAMAN: List Admin
Layout : layouts.app-admin
Deskripsi: Menampilkan daftar administrator sistem, lengkap
dengan statistik, tabel data, dan modal CRUD.
============================================================ --}}

@extends('layouts.app-admin')

@section('title', 'List Admin')
@section('page_title', 'List Admin')
@section('page_subtitle', 'Kelola Administrator Sistem Keamanan Brankas.')

{{-- ── Stylesheet ── --}}
@push('styles')
    @vite([
        'resources/css/admin/list-admin.css',
        'resources/css/components/c-admin/admin-edit.css',
        'resources/css/components/c-admin/admin-tambah.css',
        'resources/css/components/c-shared/tooltip-info.css'
    ])
    {{-- CSRF token untuk kebutuhan request AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush


@section('content')

    {{-- ==============================================================
    1. STATISTIK KARTU
    Tiga ringkasan: Total Admin, Admin Aktif, Admin Nonaktif
    ============================================================== --}}
    <div class="stat-grid">

        {{-- Kartu: Total Admin --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total Admin</span>
                <span class="stat-value" id="totalAdminCount">{{ $totalAdmin > 0 ? $totalAdmin : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    {{-- Ikon: dua orang (grup) --}}
                    <path
                        d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16666C15.1654 5.58933 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58933 5.83203 8.16666C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M22.4206 8.99915C22.4702 8.72563 22.4956 8.44678 22.4956 8.16577C22.4956 7.13197 22.1523 6.12744 21.5196 5.30984C20.887 4.49225 20.0007 3.90787 19 3.64844"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M24.7812 20.7598C24.9247 21.212 24.9993 21.6864 24.9996 22.1672V24.5005" stroke="#00A63E"
                        stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        {{-- Kartu: Admin Aktif --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Admin Aktif</span>
                <span class="stat-value" id="adminAktifCount">{{ $adminAktif > 0 ? $adminAktif : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    {{-- Ikon: orang dengan centang (aktif) --}}
                    <path
                        d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16667C15.1654 5.58934 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58934 5.83203 8.16667C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M19.6641 15.5L21.4974 17.1667L25.6641 13" stroke="#00A63E" stroke-width="2.4"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        {{-- Kartu: Admin Nonaktif --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Admin Nonaktif</span>
                <span class="stat-value" id="adminNonaktifCount">{{ $adminNonaktif ?: '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    {{-- Ikon: orang dengan silang (nonaktif) --}}
                    <path
                        d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16667C15.1654 5.58934 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58934 5.83203 8.16667C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z"
                        stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M21 12L26.2 17.2M26.2 12L21 17.2" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
        </div>

    </div>{{-- /.stat-grid --}}


    {{-- ==============================================================
    2. TOOLBAR (Search & Tombol Tambah Admin)
    ============================================================== --}}
    <div class="div-toolbar">

        {{-- Input pencarian — memfilter tabel secara real-time --}}
        <div class="search-box">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                    stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input type="text" id="searchInput" placeholder="Cari Admin..." onkeyup="filterTable()">
        </div>

        {{-- Tombol membuka modal Tambah Admin --}}
        <div class="toolbar-actions">
            <div class="tooltip-target-wrapper">
                <button class="btn-tambah" onclick="openTambahModal()">
                    Tambah Admin
                </button>
                @if(session('user.role') === 'superadmin')
                    <x-c-shared.tooltip-info 
                        number="1" 
                        title="info 1" 
                        text="Super admin bisa melakukan penambahan admin baru" 
                        position="left" 
                    />
                @elseif(session('user.role') === 'admin')
                    <x-c-shared.tooltip-info 
                        number="1" 
                        title="info 1" 
                        text="admin bisa melakukan penambahan admin baru" 
                        position="left" 
                    />
                @endif
            </div>
        </div>

    </div>{{-- /.div-toolbar --}}


    {{-- ==============================================================
    3. TABEL DAFTAR ADMIN
    Menampilkan seluruh data admin dengan kolom: No, Nama, PIN,
    Role, Status, dan Aksi (edit / hapus)
    ============================================================== --}}
    <div class="table-card">
        <div class="table-wrap table-responsive">
            <table id="adminTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>PIN</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $index => $admin)
                        <tr class="data-row {{ $loop->last ? 'last-row' : '' }}" data-id="{{ $admin->id }}">

                            {{-- Nomor urut --}}
                            <td class="td-no">{{ $index + 1 }}</td>

                            {{-- Nama admin --}}
                            <td class="td-nama">{{ $admin->nama }}</td>

                            {{-- PIN ditampilkan --}}
                            <td>
                                <div class="td-pin">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <path
                                            d="M7.72559 7.84355L15.5376 0L16.945 1.41302L15.5376 2.82703L18 5.29931L16.5927 6.71333L14.1293 4.24005L12.722 5.65307L14.833 7.77259L13.4256 9.18661L11.3146 7.06609L9.13293 9.25656C9.80918 10.2899 10.08 11.5382 9.89306 12.7603C9.70612 13.9823 9.0747 15.0915 8.12078 15.8734C7.16687 16.6554 5.95816 17.0546 4.72818 16.994C3.49821 16.9334 2.33426 16.4172 1.46121 15.5452C0.587591 14.6699 0.0691057 13.4998 0.00642622 12.2622C-0.0562532 11.0246 0.341345 9.8078 1.12203 8.84801C1.90271 7.88821 3.01076 7.25393 4.23105 7.06831C5.45134 6.88269 6.69679 7.15899 7.72559 7.84355ZM7.09159 14.1322C7.38153 13.8568 7.61355 13.5259 7.77394 13.159C7.93433 12.792 8.01982 12.3966 8.02537 11.9959C8.03092 11.5952 7.95641 11.1975 7.80625 10.8262C7.65609 10.455 7.43332 10.1177 7.15111 9.83435C6.86891 9.55101 6.53299 9.32734 6.16322 9.17657C5.79345 9.02581 5.39732 8.951 4.99826 8.95657C4.5992 8.96214 4.2053 9.04798 3.83984 9.20902C3.47439 9.37005 3.1448 9.60301 2.87055 9.89412C2.32665 10.4595 2.02569 11.2168 2.03249 12.0029C2.03929 12.7889 2.35331 13.5408 2.90692 14.0967C3.46053 14.6525 4.20942 14.9678 4.99231 14.9746C5.7752 14.9814 6.52944 14.6793 7.09258 14.1332"
                                            fill="#00A63E" />
                                    </svg>
                                    <span>{{ $admin->pin_asli }}</span>
                                </div>
                            </td>

                            {{-- Role (Admin / User) --}}
                            <td class="badge-role">
                                @if($admin->role === 'superadmin')
                                    Super Admin
                                @else
                                    {{ ucfirst($admin->role) }}
                                @endif
                            </td>

                            {{-- Badge status aktif / nonaktif --}}
                            <td>
                                <span class="badge-status {{ $admin->aktif ? 'aktif' : 'nonaktif' }}">
                                    {{ $admin->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            {{-- Dropdown aksi: Edit & Hapus (shared component) --}}
                            <td>
                                <div class="tooltip-target-wrapper" style="display: flex; justify-content: center;">
                                    <x-c-shared.opsi :item="$admin" type="admin" />
                                    @if(session('user.role') === 'superadmin' && $index === 1)
                                        <x-c-shared.tooltip-info 
                                            number="2" 
                                            title="info 2" 
                                            text="Super admin bisa melakukan edit dan hapus terhadap admin yang sudah dibuat" 
                                            position="left" 
                                        />
                                    @elseif(session('user.role') === 'admin' && $index === 1)
                                        <x-c-shared.tooltip-info 
                                            number="2" 
                                            title="info 2" 
                                            text="admin bisa melakukan edit dan hapus terhadap admin yang sudah dibuat , tetapi tidak bisa ditujukan ke super admin" 
                                            position="left" 
                                        />
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach

                    {{-- Empty State: tampil saat belum ada data admin sama sekali --}}
                    <x-c-shared.empty-state id="emptyState" colspan="6" title="Belum Ada Data"
                        desc="Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat."
                        display="{{ ($totalAdmin > 0) ? 'none' : '' }}" />

                    {{-- Not Found State: tampil saat hasil pencarian kosong --}}
                    <x-c-shared.empty-state id="searchNotFoundState" colspan="6" type="search" title="Data Tidak Ditemukan"
                        desc="Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda."
                        display="none" />
                </tbody>
            </table>
        </div>
    </div>{{-- /.table-card --}}


    {{-- ==============================================================
    4. MODALS
    - Modal Tambah Admin
    - Modal Konfirmasi Hapus (shared)
    - Modal Edit Admin
    ============================================================== --}}
    @push('modals')
        @include('components.c-admin.modal.admin-tambah')
        @include('components.c-shared.delete', ['type' => 'admin'])
        @include('components.c-admin.modal.admin-edit')
    @endpush

@endsection


{{-- ── Scripts ── --}}
@push('scripts')
    @vite([
        'resources/js/components/c-shared/opsi.js',
        'resources/js/components/c-admin/admin-tambah.js',
        'resources/js/components/c-admin/admin-edit.js',
        'resources/js/components/c-shared/delete.js',
        'resources/js/components/c-shared/tour-guide.js',
        'resources/js/admin/list-admin.js'
    ])

    {{-- Menangkap Flash Session Laravel dan mengubahnya menjadi Toast --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(() => {
                    if (window.showToast) window.showToast('Berhasil', '{{ session('success') }}', 4000);
                }, 300);
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(() => {
                    if (window.showToast) window.showToast('Gagal', '{{ session('error') }}', 5000);
                }, 300);
            });
        </script>
    @endif
@endpush