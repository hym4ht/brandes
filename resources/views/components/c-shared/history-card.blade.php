{{-- ======================================================================
     KOMPONEN: History Card
     Deskripsi: Menampilkan kartu riwayat akses tunggal.
     Parameter:
     - name: (string) Nama user
     - action: (string) Aktivitas (misal: 'Membuka Brankas')
     - badge: (string) Teks ID/Badge (misal: 'FP-001-A2F3')
     - time: (string) Waktu relatif (misal: '5 Menit yang lalu')
     - type: (string) 'success' atau 'danger'
     - date: (string) Format YYYY-MM-DD untuk filter
     ====================================================================== --}}

<div class="history-card history-item {{ $type }}" data-name="{{ strtolower($name) }}" data-date="{{ $date }}">

    {{-- 1. BAGIAN ATAS: Info User, Ikon & Badge --}}
    <div class="history-card-top">
        <div class="history-card-user">
            
            {{-- Ikon Status (Success/Danger) --}}
            <div class="history-card-icon {{ $type }}">
                @if($type === 'success')
                    {{-- Ikon Centang (Akses Berhasil) --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                @else
                    {{-- Ikon Peringatan (Akses Ditolak/Bahaya) --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                @endif
            </div>

            {{-- Detail Nama & Aktivitas --}}
            <div class="history-card-info">
                <span class="history-card-name">{{ $name }}</span>
                <span class="history-card-action">{{ $action }}</span>
            </div>
        </div>

        {{-- Label Badge (ID Akses) --}}
        <span class="history-card-badge">{{ $badge }}</span>
    </div>

    {{-- Garis Pemisah Visual --}}
    <div class="history-card-divider"></div>

    {{-- 2. BAGIAN BAWAH: Informasi Waktu --}}
    <div class="history-card-footer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
        </svg>
        <span>{{ $time }}</span>
    </div>

</div>