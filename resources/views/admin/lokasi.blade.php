{{-- ============================================================
HALAMAN: Lokasi Brankas
Layout : layouts.app-admin
Deskripsi: Monitoring lokasi brankas secara real-time
menggunakan GPS Neo-6M via ESP32 dan Google Maps.
============================================================ --}}

@extends('layouts.app-admin')

@section('title', 'Lokasi Brankas')
@section('page_title', 'Lokasi Brankas')
@section('page_subtitle', 'Monitoring lokasi brankas real-time dari GPS Neo-6M via ESP32')

{{-- ── Stylesheet ── --}}
@push('styles')
    @vite('resources/css/admin/lokasi.css')
@endpush


@section('content')

    {{-- Data default koordinat peta diletakkan langsung di template --}}

    <div class="lokasi-grid">

        {{-- ============================================================
        2. PETA LOKASI (Google Maps Embed)
        Badge GPS valid / tidak valid ditampilkan di header peta
        ============================================================ --}}
        <div class="map-card">

            {{-- Header peta dengan badge status GPS --}}
            <div class="map-card-header">
                <span>Lokasi Saat Ini</span>

                <span class="gps-badge {{ ($brankas->hdop ?? 99) <= 2.0 ? 'valid' : 'invalid' }}"></span>
            </div>

            {{-- Peta Google Maps — Diupdate via lokasi.js --}}
            <div class="map-wrap">
                <iframe id="lokasi-map"
                    src="https://maps.google.com/maps?q={{ $brankas->latitude ?? -6.9105 }},{{ $brankas->longitude ?? 109.1479 }}&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

        </div>{{-- /.map-card --}}


        {{-- ============================================================
        3. DETAIL KOORDINAT GPS
        Menampilkan Lat, Lng, HDOP, Timestamp, dan info brankas
        ============================================================ --}}
        <div class="info-col">
            <div class="info-card">
                <div class="info-card-header">Koordinat detail GPS</div>

                <div class="info-card-body">
                    <div class="coord-group">

                        {{-- Pasangan Latitude & Longitude --}}
                        <div class="coord-pair">

                            {{-- Latitude --}}
                            <div class="coord-box">
                                <div class="coord-label">Latitude</div>
                                <div class="coord-value" id="val-lat">
                                    {{ $brankas->latitude ?? '-' }}
                                </div>
                            </div>

                            {{-- Longitude --}}
                            <div class="coord-box">
                                <div class="coord-label">Longtitude</div>
                                <div class="coord-value" id="val-lng">
                                    {{ $brankas->longitude ?? '-' }}
                                </div>
                            </div>

                        </div>{{-- /.coord-pair --}}

                        {{-- Info GPS Sekunder: HDOP, Satelit & Timestamp --}}
                        <div class="coord-secondary">

                            {{-- HDOP --}}
                            <div class="coord-box">
                                <div class="coord-label">HDOP</div>
                                <div class="coord-value" id="val-hdop">
                                    {{ $brankas->hdop ?? '-' }}
                                </div>
                            </div>

                            {{-- Satelit --}}
                            <div class="coord-box">
                                <div class="coord-label">Satelit</div>
                                <div class="coord-value" id="val-sat">
                                    {{ $brankas->satellites ?? '-' }}
                                </div>
                            </div>

                            {{-- Waktu update GPS terakhir --}}
                            <div class="coord-box">
                                <div class="coord-label">Timestamp</div>
                                <div class="coord-value" id="val-time">
                                    {{ $brankas?->last_gps_update?->format('Y-m-d H:i:s') ?? '-' }}
                                </div>
                            </div>

                        </div>{{-- /.coord-secondary --}}

                    </div>{{-- /.coord-group --}}

                    <div class="info-divider"></div>

                    {{-- Informasi kontekstual brankas (nama & alamat) --}}
                    <div class="brankas-section">
                        <div class="brankas-title">Detail Lokasi</div>

                        <div class="info-group-wrapper">
                            {{-- Nama Brankas --}}
                            <div class="info-row">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M3 21H21M9 8H10M9 12H10M9 16H10M14 8H15M14 12H15M14 16H15M5 21V5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21"
                                        stroke="currentColor" stroke-width="1.67" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="brankas-text" id="lokasi-nama-val">
                                    {{ $brankas->nama_brankas ?? '-' }}
                                </span>
                            </div>

                            {{-- Alamat Lokasi Brankas --}}
                            <div class="info-row">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M20 10C20 14.993 14.461 20.193 12.601 21.799C12.4277 21.9293 12.2168 21.9998 12 21.9998C11.7832 21.9998 11.5723 21.9293 11.399 21.799C9.539 20.193 4 14.993 4 10C4 7.87827 4.84285 5.84344 6.34315 4.34315C7.84344 2.84285 9.87827 2 12 2C14.1217 2 16.1566 2.84285 17.6569 4.34315C19.1571 5.84344 20 7.87827 20 10Z"
                                        stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z"
                                        stroke="currentColor" stroke-width="1.66667" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <span class="brankas-text" id="lokasi-detail-val">
                                    {{ $brankas->lokasi ?? '-' }}
                                </span>
                            </div>
                        </div>{{-- /.info-group-wrapper --}}
                    </div>{{-- /.brankas-section --}}

                </div>{{-- /.info-card-body --}}
            </div>{{-- /.info-card --}}
        </div>{{-- /.info-col --}}

    </div>{{-- /.lokasi-grid --}}


    {{-- ==============================================================
    4. TABEL RIWAYAT POSISI GPS
    Kolom: Waktu, Brankas, Status
    ============================================================== --}}
    <div class="history-table-card">

        {{-- Header statis --}}
        <div class="table-card-header">
            <h3>Riwayat Posisi GPS</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>WAKTU</th>
                        <th>BRANKAS</th>
                        <th>KOORDINAT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $h)

                        {{-- Baris data riwayat GPS --}}
                        <tr class="data-row">
                            <td class="td-waktu">
                                <div class="time-main">{{ $h->recorded_at->format('d M Y') }}</div>
                                <div class="time-sub">{{ $h->recorded_at->format('H:i:s') }}</div>
                            </td>
                            <td class="td-brankas">{{ $h->brankas?->nama_brankas ?? '-' }}</td>

                            {{-- Koordinat: Lat & Lng --}}
                            <td class="td-koordinat">
                                <div>Lat: <strong>{{ $h->latitude ?? '-' }}</strong></div>
                                <div>Lng: <strong>{{ $h->longitude ?? '-' }}</strong></div>
                            </td>

                            {{-- Badge status: normal / waspada / bahaya --}}
                            <td>
                                <span class="badge {{ $h->status === 'normal' ? 'badge-success' : ($h->status === 'waspada' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $h->statusLabel() }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        {{-- Empty State: tampil saat belum ada riwayat GPS --}}
                        <x-c-shared.empty-state
                            id="historyEmpty"
                            colspan="4"
                            title="Belum Ada Data"
                            desc="Data aktivitas belum tersedia. Data akan muncul di sini setelah ada aktivitas yang tercatat."
                        />

                    @endforelse
                </tbody>
            </table>
        </div>

    </div>{{-- /.history-table-card --}}

@endsection


{{-- ── Scripts ── --}}
@push('scripts')

    <script>
        window.GPS_CONFIG = {
            lat: @json($brankas->latitude ?? -6.9105),
            lng: @json($brankas->longitude ?? 109.1479),
            gpsValid: @json(($brankas->hdop ?? 99) <= 2.0),
            namaBrankas: @json($brankas->nama_brankas ?? 'Sistem Monitoring Brankas'),
            kodeBrankas: @json($brankas->kode_brankas ?? 'BRX-001'),
            lokasi: @json($brankas->lokasi ?? '-'),
            status: @json($brankas->status ?? 'normal')
        };
    </script>

    {{-- Controller utama: polling realtime & update peta --}}
    @vite('resources/js/admin/lokasi.js')
@endpush
