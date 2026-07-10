{{-- ============================================================
HALAMAN: Log Berkas User
Layout : layouts.app-user
Deskripsi: Menampilkan daftar log berkas untuk user.
============================================================ --}}

@extends('layouts.app-user')

@section('title', 'Log Berkas')
@section('page_title', 'Log Berkas')
@section('page_subtitle', 'Kelola data log berkas beserta berkas pendukung.')

{{-- ── Stylesheet ── --}}
@push('styles')
    @vite([
        'resources/css/user/log-berkas.css',
        'resources/css/components/c-shared/empty.css',
        'resources/css/components/c-shared/tooltip-info.css'
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
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
                <path d="M21.0002 21.0002L16.6602 16.6602" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <input type="text" id="searchInput" placeholder="Cari Log Berkas..." onkeyup="filterTable()">
        </div>

        <div class="toolbar-actions">
            {{-- Tombol Tambah Log dihapus karena User hanya mengambil dokumen --}}
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
                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }}</td>
                            
                            {{-- BERKAS KTP --}}
                            <td>
                                @if($log->file_ktp)
                                    @if(isset($log->status_ktp) && $log->status_ktp == 'Diambil')
                                        <span class="badge-status nonaktif {{ $index === 0 ? 'tour-group-1' : '' }}" style="cursor: pointer;" onclick="openDetailDokumenModal('KTP', '{{ $log->file_ktp }}', '{{ route('dokumen.download', $log->file_ktp) }}', '{{ $log->ktp_nik }}', '{{ $log->ktp_nama }}')">KTP</span>
                                    @else
                                        <span class="badge-status aktif {{ $index === 0 ? 'tour-group-1' : '' }}" style="cursor: pointer;" onclick="openDetailDokumenModal('KTP', '{{ $log->file_ktp }}', '{{ route('dokumen.download', $log->file_ktp) }}', '{{ $log->ktp_nik }}', '{{ $log->ktp_nama }}')">KTP</span>
                                    @endif
                                @else
                                    <span class="badge-status nonaktif {{ $index === 0 ? 'tour-group-1' : '' }}">Belum Ada</span>
                                @endif
                                
                                @if($index === 0)
                                    <x-c-shared.tooltip-info 
                                        number="1" 
                                        title="info 1" 
                                        text="badge ini sebagai indikator arsip/berkas fisik ada atau tidak ada , (jika hijau &quot;ada&quot; , jika merah &quot;tidak ada&quot;), jika di tekan akan muncul detail digitalnya" 
                                        position="left" 
                                        targetGroup=".tour-group-1"
                                    />
                                @endif
                            </td>
                            
                            {{-- BERKAS KK --}}
                            <td>
                                @if($log->file_kk)
                                    @if(isset($log->status_kk) && $log->status_kk == 'Diambil')
                                        <span class="badge-status nonaktif {{ $index === 0 ? 'tour-group-1' : '' }}" style="cursor: pointer;" onclick="openDetailDokumenModal('KK', '{{ $log->file_kk }}', '{{ route('dokumen.download', $log->file_kk) }}', '{{ $log->kk_no }}', '{{ $log->kk_nama_kepala }}')">KK</span>
                                    @else
                                        <span class="badge-status aktif {{ $index === 0 ? 'tour-group-1' : '' }}" style="cursor: pointer;" onclick="openDetailDokumenModal('KK', '{{ $log->file_kk }}', '{{ route('dokumen.download', $log->file_kk) }}', '{{ $log->kk_no }}', '{{ $log->kk_nama_kepala }}')">KK</span>
                                    @endif
                                @else
                                    <span class="badge-status nonaktif {{ $index === 0 ? 'tour-group-1' : '' }}">Belum Ada</span>
                                @endif
                            </td>
                            
                            {{-- BERKAS AKTE --}}
                            <td>
                                @if($log->file_akte)
                                    @if(isset($log->status_akte) && $log->status_akte == 'Diambil')
                                        <span class="badge-status nonaktif {{ $index === 0 ? 'tour-group-1' : '' }}" style="cursor: pointer;" onclick="openDetailDokumenModal('AKTE', '{{ $log->file_akte }}', '{{ route('dokumen.download', $log->file_akte) }}', '{{ $log->akte_no }}', '')">AKTE</span>
                                    @else
                                        <span class="badge-status aktif {{ $index === 0 ? 'tour-group-1' : '' }}" style="cursor: pointer;" onclick="openDetailDokumenModal('AKTE', '{{ $log->file_akte }}', '{{ route('dokumen.download', $log->file_akte) }}', '{{ $log->akte_no }}', '')">AKTE</span>
                                    @endif
                                @else
                                    <span class="badge-status nonaktif {{ $index === 0 ? 'tour-group-1' : '' }}">Belum Ada</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="tooltip-target-wrapper" style="display: flex; justify-content: center;">
                                    <x-c-shared.opsi :item="$log" type="log-user">
                                        <form action="{{ route('user.log.berkas.ambil', $log->id) }}" method="POST" style="margin: 0; padding: 0;">
                                            @csrf
                                            <input type="hidden" name="jenis" value="ktp">
                                            <button type="submit" class="dropdown-item" style="color: {{ (isset($log->status_ktp) && $log->status_ktp == 'Diambil') ? 'var(--C-Red)' : 'var(--C-Black)' }};">
                                                <span>{{ (isset($log->status_ktp) && $log->status_ktp == 'Diambil') ? 'KTP Sudah Diambil' : 'Ambil KTP' }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('user.log.berkas.ambil', $log->id) }}" method="POST" style="margin: 0; padding: 0;">
                                            @csrf
                                            <input type="hidden" name="jenis" value="kk">
                                            <button type="submit" class="dropdown-item" style="color: {{ (isset($log->status_kk) && $log->status_kk == 'Diambil') ? 'var(--C-Red)' : 'var(--C-Black)' }};">
                                                <span>{{ (isset($log->status_kk) && $log->status_kk == 'Diambil') ? 'KK Sudah Diambil' : 'Ambil KK' }}</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('user.log.berkas.ambil', $log->id) }}" method="POST" style="margin: 0; padding: 0;">
                                            @csrf
                                            <input type="hidden" name="jenis" value="akte">
                                            <button type="submit" class="dropdown-item" style="color: {{ (isset($log->status_akte) && $log->status_akte == 'Diambil') ? 'var(--C-Red)' : 'var(--C-Black)' }};">
                                                <span>{{ (isset($log->status_akte) && $log->status_akte == 'Diambil') ? 'Akte Sudah Diambil' : 'Ambil Akte' }}</span>
                                            </button>
                                        </form>
                                    </x-c-shared.opsi>
                                
                                    @if($index === 1)
                                        <x-c-shared.tooltip-info 
                                            number="2" 
                                            title="info 2" 
                                            text="user bisa melakukan pengambilan data arsip lewat sini ada status &quot;Ambil Berkas&quot; &amp; &quot;Berkas Sudah Diambil&quot;" 
                                            position="left" 
                                        />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <x-c-shared.empty-state 
                        id="emptyState" 
                        colspan="7" 
                        title="Belum Ada Data Log" 
                        desc="Data log berkas belum tersedia."
                        display="{{ ($totalLogs > 0) ? 'none' : '' }}"
                    />

                    <x-c-shared.empty-state 
                        id="searchNotFoundState" 
                        colspan="7" 
                        type="search"
                        title="Data Tidak Ditemukan" 
                        desc="Hasil pencarian tidak cocok dengan data log yang tersedia."
                        display="none"
                    />
                </tbody>
            </table>
        </div>
    </div>{{-- /.table-card --}}

    {{-- ==============================================================
    4. MODALS
    ============================================================== --}}
    @push('modals')
        @include('components.c-user.modal.detail-dokumen')
    @endpush

@endsection

{{-- ── Scripts ── --}}
@push('scripts')
    @vite([
        'resources/js/components/c-shared/opsi.js',
        'resources/js/components/c-user/detail-dokumen.js',
        'resources/js/components/c-shared/tour-guide.js',
        'resources/js/user/log-berkas.js'
    ])

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if(window.showToast) window.showToast('Berhasil', '{{ session('success') }}', 4000);
            }, 300);
        });
    </script>
    @endif
@endpush
