{{-- ============================================================
     HALAMAN: List User
     Layout   : layouts.app-admin
     Deskripsi: Menampilkan daftar user sistem, lengkap dengan
                statistik, tabel data, dan modal CRUD.
     ============================================================ --}}

@extends('layouts.app-admin')

@section('title',         'List User')
@section('page_title',    'List User')
@section('page_subtitle', 'Kelola Pengakses Sistem dan Akses Brankas.')

{{-- ── Stylesheet ── --}}
@push('styles')
    @vite([
        'resources/css/admin/list-user.css',
        'resources/css/components/c-admin/user-edit.css',
        'resources/css/components/c-admin/user-tambah.css',
        'resources/css/components/c-shared/tooltip-info.css'
    ])
    {{-- CSRF token untuk kebutuhan request AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    {{-- ==============================================================
         1. STATISTIK KARTU
         Tiga ringkasan: Total User, User Aktif, User Nonaktif
         ============================================================== --}}
    <div class="stat-grid">
        {{-- Kartu: Total User --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Total User</span>
                <span class="stat-value" id="totalUserCount">{{ $totalUser > 0 ? $totalUser : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                    <path d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16667C15.1654 5.58934 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58934 5.83203 8.16667C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22.4206 8.99915C22.4702 8.72563 22.4956 8.44678 22.4956 8.16577C22.4956 7.13197 22.1523 6.12744 21.5196 5.30984C20.887 4.49225 20.0007 3.90787 19 3.64844" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M24.7812 20.7598C24.9247 21.212 24.9993 21.6864 24.9996 22.1672V24.5005" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Kartu: User Aktif --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">User Aktif</span>
                <span class="stat-value" id="userAktifCount">{{ $userAktif > 0 ? $userAktif : '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                    <path d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16667C15.1654 5.58934 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58934 5.83203 8.16667C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19.6641 15.5L21.4974 17.1667L25.6641 13" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22.4206 8.99915C22.4702 8.72563 22.4956 8.44678 22.4956 8.16577C22.4956 7.13197 22.1523 6.12744 21.5196 5.30984C20.887 4.49225 20.0007 3.90787 19 3.64844" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M24.7812 20.7598C24.9247 21.212 24.9993 21.6864 24.9996 22.1672V24.5005" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        {{-- Kartu: User Nonaktif --}}
        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">User Nonaktif</span>
                <span class="stat-value" id="userNonaktifCount">{{ $userNonaktif ?: '-' }}</span>
            </div>
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                    <path d="M18.6654 24.5V22.1667C18.6654 20.929 18.1737 19.742 17.2985 18.8668C16.4234 17.9917 15.2364 17.5 13.9987 17.5H6.9987C5.76102 17.5 4.57404 17.9917 3.69887 18.8668C2.8237 19.742 2.33203 20.929 2.33203 22.1667V24.5" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.4987 12.8333C13.076 12.8333 15.1654 10.744 15.1654 8.16667C15.1654 5.58934 13.076 3.5 10.4987 3.5C7.92137 3.5 5.83203 5.58934 5.83203 8.16667C5.83203 10.744 7.92137 12.8333 10.4987 12.8333Z" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22.4206 8.99915C22.4702 8.72563 22.4956 8.44678 22.4956 8.16577C22.4956 7.13197 22.1523 6.12744 21.5196 5.30984C20.887 4.49225 20.0007 3.90787 19 3.64844" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M24.7812 20.7598C24.9247 21.212 24.9993 21.6864 24.9996 22.1672V24.5005" stroke="#00A63E" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21 12L26.2 17.2M26.2 12L21 17.2" stroke="#00A63E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>{{-- /.stat-grid --}}

    {{-- ==============================================================
         2. TOOLBAR (Search & Tombol Tambah User)
         ============================================================== --}}
    <div class="div-toolbar">

        {{-- Input pencarian — memfilter tabel secara real-time --}}
        <div class="search-box">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input type="text" id="searchInput" placeholder="Cari User..." onkeyup="filterTable()">
        </div>

        {{-- Tombol membuka modal Tambah User --}}
        <div class="tooltip-target-wrapper">
            <button class="btn-tambah" onclick="openTambahModalUser()">
                Tambah User
            </button>
            @if(in_array(session('user.role'), ['superadmin', 'admin']))
                <x-c-shared.tooltip-info 
                    number="1" 
                    title="info 1" 
                    text="super admin & admin bisa melakukan penambahan user akses sistem dan brankas IoT" 
                    position="left" 
                />
            @endif
        </div>

    </div>{{-- /.div-toolbar --}}

    {{-- ==============================================================
         3. TABEL DAFTAR USER
         Kolom: No, Nama, Fingerprints, PIN, Role, Status, Aksi
         ============================================================== --}}
    <div class="table-card">
        <div class="table-wrap table-responsive">
            <table id="userTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>FINGERPRINTS</th>
                        <th>PIN</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @php $hasData = count($users) > 0; @endphp
                    {{-- Loop data user --}}
                    @foreach($users as $index => $user)
                        <tr class="data-row {{ $loop->last ? 'last-row' : '' }}" data-id="{{ $user->id }}">
                            {{-- Nomor urut --}}
                            <td class="td-no">{{ $index + 1 }}</td>
                            {{-- Nama user --}}
                            <td class="td-nama">{{ $user->nama }}</td>
                            {{-- Fingerprint ID — tampil italic jika belum terdaftar --}}
                            <td>
                                <div class="td-fp">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M11.9983 10C11.4678 10 10.9591 10.2107 10.5841 10.5858C10.209 10.9609 9.99828 11.4696 9.99828 12C9.99828 13.02 9.89828 14.51 9.73828 16" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M14 13.1201C14 15.5001 14 19.5001 13 22.0001" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M17.2891 21.02C17.4091 20.42 17.7191 18.72 17.7891 18" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2 12C2 9.90118 2.66037 7.85555 3.88758 6.1529C5.11478 4.45024 6.8466 3.17687 8.83772 2.51317C10.8288 1.84946 12.9783 1.82906 14.9817 2.45486C16.985 3.08067 18.7407 4.32094 20 6" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2 16H2.01" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M21.8008 16C22.0008 14 21.9318 10.646 21.8008 10" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5 19.5C5.5 18 6 15 6 12C5.99899 11.3189 6.11397 10.6425 6.34 10" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.64844 22C8.85844 21.34 9.09844 20.68 9.21844 20" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9 6.79994C9.9124 6.27317 10.9474 5.99593 12.001 5.99609C13.0545 5.99626 14.0894 6.27384 15.0017 6.8009C15.9139 7.32797 16.6713 8.08594 17.1976 8.99859C17.7239 9.91124 18.0007 10.9464 18 11.9999V13.9999" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    @if($user->fingerprint_id)
                                        @php
                                            $hash = md5($user->id . 'brandes_salt');
                                            $suffix = chr(65 + (ord($hash[0]) % 26)) . (ord($hash[1]) % 10) . chr(65 + (ord($hash[2]) % 26));
                                        @endphp
                                        FP-{{ str_pad($user->fingerprint_id, 3, '0', STR_PAD_LEFT) }}-{{ $suffix }}
                                    @else
                                        <span style="color:var(--text-muted);font-style:italic;">Belum terdaftar</span>
                                    @endif
                                </div>
                            </td>
                            {{-- PIN asli ditampilkan (dekripsi AES / fallback PIN Lama untuk data bcrypt) --}}
                            <td>
                                <div class="td-pin">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                                        <path d="M7.72559 7.84355L15.5376 0L16.945 1.41302L15.5376 2.82703L18 5.29931L16.5927 6.71333L14.1293 4.24005L12.722 5.65307L14.833 7.77259L13.4256 9.18661L11.3146 7.06609L9.13293 9.25656C9.80918 10.2899 10.08 11.5382 9.89306 12.7603C9.70612 13.9823 9.0747 15.0915 8.12078 15.8734C7.16687 16.6554 5.95816 17.0546 4.72818 16.994C3.49821 16.9334 2.33426 16.4172 1.46121 15.5452C0.587591 14.6699 0.0691057 13.4998 0.00642622 12.2622C-0.0562532 11.0246 0.341345 9.8078 1.12203 8.84801C1.90271 7.88821 3.01076 7.25393 4.23105 7.06831C5.45134 6.88269 6.69679 7.15899 7.72559 7.84355ZM7.09159 14.1322C7.38153 13.8568 7.61355 13.5259 7.77394 13.159C7.93433 12.792 8.01982 12.3966 8.02537 11.9959C8.03092 11.5952 7.95641 11.1975 7.80625 10.8262C7.65609 10.455 7.43332 10.1177 7.15111 9.83435C6.86891 9.55101 6.53299 9.32734 6.16322 9.17657C5.79345 9.02581 5.39732 8.951 4.99826 8.95657C4.5992 8.96214 4.2053 9.04798 3.83984 9.20902C3.47439 9.37005 3.1448 9.60301 2.87055 9.89412C2.32665 10.4595 2.02569 11.2168 2.03249 12.0029C2.03929 12.7889 2.35331 13.5408 2.90692 14.0967C3.46053 14.6525 4.20942 14.9678 4.99231 14.9746C5.7752 14.9814 6.52944 14.6793 7.09258 14.1332" fill="#00A63E" />
                                    </svg>
                                    <span>{{ $user->pin_asli }}</span>
                                </div>
                            </td>
                            {{-- Role user --}}
                            <td style="font-weight:500;">{{ ucfirst($user->role) }}</td>
                            {{-- Badge status aktif / nonaktif --}}
                            <td>
                                <span class="badge-status {{ $user->aktif ? 'aktif' : 'nonaktif' }}">
                                    {{ $user->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            {{-- Dropdown aksi: Edit & Hapus (shared component) --}}
                            <td>
                                <div class="tooltip-target-wrapper" style="display: flex; justify-content: center;">
                                    <x-c-shared.opsi :item="$user" type="user" />
                                    @if(in_array(session('user.role'), ['superadmin', 'admin']) && $index === 0)
                                        <x-c-shared.tooltip-info 
                                            number="2" 
                                            title="info 2" 
                                            text="super admin & admin bisa melakukan edit informasi username dan PIN" 
                                            position="left" 
                                        />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    {{-- Empty State: tampil saat belum ada data user sama sekali --}}
                    <x-c-shared.empty-state 
                        id="emptyState" 
                        colspan="7" 
                        title="Belum Ada Data" 
                        desc="Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat."
                        display="{{ $hasData ? 'none' : '' }}"
                    />

                    {{-- Not Found State: tampil saat hasil pencarian kosong --}}
                    <x-c-shared.empty-state 
                        id="searchNotFoundState" 
                        colspan="7" 
                        type="search"
                        title="Data Tidak Ditemukan" 
                        desc="Hasil pencarian tidak cocok dengan data yang tersedia. Coba gunakan kata kunci yang berbeda."
                        display="none"
                    />
                </tbody>
            </table>
        </div>
    </div>{{-- /.table-card --}}

    {{-- ==============================================================
         4. MODALS
         - Modal Tambah User
         - Modal Edit User
         - Modal Konfirmasi Hapus (shared)
         ============================================================== --}}
    @push('modals')
        @include('components.c-admin.modal.user-tambah')
        @include('components.c-admin.modal.user-edit')
        @include('components.c-shared.delete', ['type' => 'user'])
    @endpush
@endsection

{{-- ── Scripts ── --}}
@push('scripts')
    @vite([
        'resources/js/components/c-shared/opsi.js',
        'resources/js/components/c-admin/user-tambah.js',
        'resources/js/components/c-admin/user-edit.js',
        'resources/js/components/c-shared/delete.js',
        'resources/js/components/c-shared/tour-guide.js',
        'resources/js/admin/list-user.js'
    ])
@endpush