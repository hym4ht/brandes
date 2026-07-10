{{-- ======================================================================
KOMPONEN: Security Card
Deskripsi: Menampilkan kartu ringkasan notifikasi keamanan.
Parameter:
- type: (string) 'kritis', 'peringatan', atau 'akses'
- title: (string) Judul notifikasi
- description: (string) Deskripsi aktivitas
- meta: (array) Data tambahan (Username, ID, dll)
- time: (string) Waktu kejadian
====================================================================== --}}

@php
    // 1. INISIALISASI NILAI DEFAULT
    $type = $type ?? 'peringatan';
    $title = $title ?? 'Pemberitahuan Sistem';
    $description = $description ?? 'Aktivitas sistem terdeteksi.';
    $meta = $meta ?? [];
    $time = $time ?? now()->format('Y-m-d H:i:s');
    $role = $role ?? 'admin';
    $customTag = $tagLabel ?? null;

    // 3. DATA CONTOH (Hanya jika meta kosong dan bukan dari database)
    // Dihapus logic dummy agar selalu mengikuti data asli

    // 2. MAPPING KONTEN DINAMIS
    $contentMapping = [
        'akses' => [
            'admin' => [
                'title' => 'Akses Brankas',
                'desc' => 'Anda berhasil membuka Brankas menggunakan autentikasi Fingerprints dan keypad PIN.'
            ],
            'user' => [
                'title' => 'Akses Brankas',
                'desc' => 'Anda berhasil membuka Brankas menggunakan autentikasi Fingerprints dan keypad PIN.'
            ]
        ],
        'peringatan' => [
            'admin' => [
                'title' => 'Percobaan Akses',
                'desc' => 'Terdeteksi percobaan akses yang tidak terdaftar di sistem.'
            ],
            'user' => [
                'title' => $title,
                'desc' => $description
            ]
        ],
        'kritis' => [
            'admin' => [
                'title' => 'Percobaan Pembobolan',
                'desc' => 'Sistem mendeteksi guncangan atau percobaan paksa pada brankas.'
            ],
            'user' => [
                'title' => 'Percobaan Pembobolan',
                'desc' => 'Percobaan pembobolan brankas terdeteksi. Sistem keamanan aktif.'
            ]
        ]
    ];

    // Prioritaskan data dinamis dari parameter $title/$description, gunakan mapping hanya sebagai fallback
    $isDefaultTitle = $title === 'Pemberitahuan Sistem';
    $isDefaultDesc = $description === 'Aktivitas sistem terdeteksi.';

    $finalTitle = !$isDefaultTitle ? $title : ($contentMapping[$type][$role]['title'] ?? $title);
    $finalDesc = !$isDefaultDesc ? $description : ($contentMapping[$type][$role]['desc'] ?? $description);

    // 3. PEMETAAN KELAS & LABEL BERDASARKAN TIPE
    $typeClass = [
        'kritis' => 'red',
        'peringatan' => 'yellow',
        'akses' => 'green'
    ][$type] ?? 'yellow';

    $tagLabel = $customTag ?? ([
        'kritis' => 'KRITIS',
        'peringatan' => 'PERINGATAN',
        'akses' => 'AKSES'
    ][$type] ?? 'PERINGATAN');

    $tagClass = [
        'kritis' => 'kritis',
        'peringatan' => 'peringatan',
        'akses' => 'akses'
    ][$type] ?? 'peringatan';
@endphp

{{-- STRUKTUR KARTU NOTIFIKASI --}}
<div class="notif-item {{ $typeClass }}" data-time="{{ $time }}" data-tipe="{{ $type }}"
    data-dibaca="{{ $dibaca ?? 'tidak' }}">

    {{-- BAGIAN 1: IKON STATUS (Dinamis) --}}
    <div class="notif-icon-box">
        @if($type === 'kritis')
            {{-- Icon Bahaya/Kritis (Lingkaran Tanda Seru) --}}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @elseif($type === 'peringatan')
            {{-- Icon Peringatan --}}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        @else
            {{-- Icon Akses Berhasil --}}
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        @endif
    </div>

    {{-- BAGIAN 2: KONTEN NOTIFIKASI --}}
    <div class="notif-content">

        {{-- Header Konten (Judul & Tag) --}}
        <div class="notif-header">
            <div class="notif-title-group">
                <span class="notif-title">{{ $finalTitle }}</span>
                <div class="notif-desc">{{ $finalDesc }}</div>

                {{-- Metadata (Hanya muncul untuk Admin jika tipe adalah 'akses' atau 'peringatan') --}}
                @php
                    $showMeta = ($role === 'admin' && ($type === 'akses' || $type === 'peringatan'));
                @endphp

                @if($showMeta && !empty($meta))
                    <div class="notif-meta">
                        @if(is_array($meta) && count($meta) >= 3)
                            <span><strong>{{ $meta[0] }}</strong></span>
                            <svg class="meta-dot" width="6" height="6" viewBox="0 0 8 8" fill="none">
                                <circle cx="4" cy="4" r="4" fill="currentColor" />
                            </svg>
                            <span><strong>{{ $meta[1] }}</strong></span>
                            <svg class="meta-dot" width="6" height="6" viewBox="0 0 8 8" fill="none">
                                <circle cx="4" cy="4" r="4" fill="currentColor" />
                            </svg>
                            <span><strong>{{ $meta[2] }}</strong></span>
                        @elseif(is_array($meta))
                            @foreach($meta as $index => $m)
                                <span>{{ $m }}</span>
                                @if(!$loop->last)
                                    <svg class="meta-dot" width="6" height="6" viewBox="0 0 8 8" fill="none">
                                        <circle cx="4" cy="4" r="4" fill="currentColor" />
                                    </svg>
                                @endif
                            @endforeach
                        @else
                            <span>{{ $meta }}</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Badge Status Tipe --}}
            <span class="notif-tag {{ $tagClass }}">{{ $tagLabel }}</span>
        </div>

        {{-- Footer Konten (Informasi Waktu) --}}
        <div class="notif-footer">
            <div class="notif-divider"></div>
            <div class="notif-time">
                {{-- Info Tanggal (Kalender) --}}
                <div class="time-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($time)->format('Y-m-d') }}</span>
                </div>

                <span class="time-divider">-</span>

                {{-- Info Waktu (Jam) --}}
                <div class="time-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($time)->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>

    </div>
</div>