{{-- ==========================================================================
     HALAMAN: Profile & Kredensial User
     Deskripsi: Menampilkan informasi keamanan personal (Security Index),
                status kredensial (Fingerprint & PIN), serta data profil lengkap.
     ========================================================================== --}}

@extends('layouts.app-user')

{{-- 1. INFORMASI HALAMAN --}}
@section('title', 'Profile & Kredensial')
@section('page_title', 'Profile & Kredensial')
@section('page_subtitle', 'Data profil dan kredensial akses Anda.')

{{-- 2. KONTEN UTAMA --}}
@section('content')
    <div class="kredensial-wrapper">

        {{-- Alert Keamanan & Instruksi --}}
        @include('components.c-user.alert.alert-kredensial', [
            'id' => 'alert_kredensial_premium_final',
            'message' => 'Jangan bagikan PIN atau informasi akses Anda kepada siapa pun. Jika Anda merasa akun telah dikompromikan, segera hubungi Admin. Untuk mengubah fingerprint dan PIN karena proses harus dilakukan langsung di perangkat IoT.',
            'showAvatar' => false
        ])

        {{-- SECTION A: Row Keamanan & Kredensial --}}
        <div class="security-row">

            {{-- Kartu Personal Security Index --}}
            <div class="security-index-card">
                <h3 class="card-title-sec">Personal Security Index</h3>

                {{-- Logika Perhitungan Visual Progress --}}
                @php
                    $circumference = 282.7;
                    $offset = $circumference - ($securityIndex / 100) * $circumference;

                    // Kondisi Status: Excellent (>=80), Empty (0 & No History), or Warning (<80)
                    $isExcellent = $securityIndex >= 80;
                    $isEmpty = $securityIndex === 0 && (str_contains($securityMessage, 'Belum ada') || empty($securityMessage));

                    // Pemetaan Warna & Styling
                    $boxBg = '#FEF2F2'; // Default warning red
                    $textColor = '#B91C1C';

                    if ($isExcellent) {
                        $boxBg = '#F0FDF4'; // Success green emerald-50
                        $textColor = '#166534';
                    } elseif ($isEmpty) {
                        $boxBg = '#F3F4F6'; // Muted grey
                        $textColor = '#4B5563';
                    }
                @endphp

                <div class="index-content">
                    {{-- Grafik Lingkaran Progress --}}
                    <div class="progress-circle-wrap">
                        <svg class="progress-svg" viewBox="0 0 100 100">
                            <circle class="bg" cx="50" cy="50" r="45"></circle>
                            <circle class="meter" cx="50" cy="50" r="45" 
                                    style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $offset }}; stroke: {{ $isExcellent ? '#00A63E' : ($isEmpty ? '#E5E7EB' : '#EF4444') }};">
                            </circle>
                        </svg>

                        <div class="progress-text-box">
                            <span class="percentage" style="{{ $isEmpty ? 'color: #667085;' : ($securityIndex < 80 ? 'color: #EF4444;' : '') }}">
                                {{ $securityIndex }}%
                            </span>
                            <span class="label">dari 100%</span>
                        </div>
                    </div>
                </div>

                {{-- Pesan Status Keamanan --}}
                <div class="security-message-box {{ $isExcellent ? 'box-excellent' : ($isEmpty ? 'box-empty' : 'box-warning') }}">
                    <div class="message-inner">
                        @if($isExcellent)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                <path d="M9 12l2 2 4-4"></path>
                            </svg>
                            <span>Kebiasaan akses anda sangat baik !!</span>
                        @elseif($isEmpty)
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span>Belum ada aktivitas akses terdeteksi</span>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span>{{ $securityMessage }}</span>
                        @endif
                    </div>
                </div>

                <p class="security-desc">
                    Skor berdasarkan keberhasilan akses ke brankas, dengan autentikasi Fingerprints dan PIN yang tidak pernah salah
                </p>
            </div>

            {{-- Kolom Status Kredensial --}}
            <div class="credential-column">

                {{-- Kartu Status: Fingerprints --}}
                <div class="cred-status-card">
                    <div class="cred-header">
                        <div class="cred-info-text">
                            <span class="cred-label">Fingerprints/Sidik Jari</span>
                            <span class="cred-sublabel">Metode Biometrik Akses</span>
                        </div>
                        <div class="cred-icon-box">
                            <svg viewBox="0 0 28 28" fill="none">
                                <path d="M14.0039 11.666C13.385 11.666 12.7915 11.9118 12.3539 12.3494C11.9164 12.787 11.6705 13.3805 11.6705 13.9993C11.6705 15.1893 11.5539 16.9277 11.3672 18.666" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16.3307 15.3066C16.3307 18.0833 16.3307 22.75 15.1641 25.6666" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20.1719 24.5233C20.3119 23.8233 20.6735 21.84 20.7552 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2.33594 14.0007C2.33594 11.552 3.10637 9.16546 4.53811 7.17903C5.96985 5.1926 7.99031 3.707 10.3133 2.93268C12.6363 2.15835 15.144 2.13456 17.4812 2.86466C19.8185 3.59476 21.8668 5.04175 23.3359 7.00065" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2.33594 18.666H2.34631" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M25.4297 18.666C25.663 16.3327 25.5825 12.4197 25.4297 11.666" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.83594 22.7493C6.41927 20.9993 7.0026 17.4993 7.0026 13.9993C7.00143 13.2047 7.13556 12.4156 7.39927 11.666" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.0938 25.6673C10.3387 24.8973 10.6188 24.1273 10.7588 23.334" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.5 7.93392C11.5645 7.31935 12.772 6.9959 14.0011 6.99609C15.2303 6.99629 16.4377 7.32013 17.5019 7.93504C18.5662 8.54995 19.4498 9.43425 20.0639 10.499C20.6779 11.5638 21.0008 12.7714 21 14.0006V16.3339" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="cred-body">
                        @if($user->fingerprint_id)
                            <span class="status-terdaftar">TERDAFTAR</span>
                        @else
                            <span class="status-belum-terdaftar">BELUM TERDAFTAR</span>
                        @endif
                    </div>
                    <div class="cred-footer">
                        <div class="divider"></div>
                        <span class="footer-msg">
                            {{ $user->fingerprint_id ? 'Autentikasi sidik jari aktif' : 'Sidik jari belum dikonfigurasi' }}
                        </span>
                    </div>
                </div>

                {{-- Kartu Status: PIN --}}
                <div class="cred-status-card">
                    <div class="cred-header">
                        <div class="cred-info-text">
                            <span class="cred-label">PIN Akses</span>
                            <span class="cred-sublabel">Kode Numerik Akses</span>
                        </div>
                        <div class="cred-icon-box">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                                <path d="M8.41711 25.9729C11.9594 25.9729 14.8342 22.9373 14.8342 19.1971C14.8342 17.8148 14.4364 16.5409 13.769 15.4704L18.0428 10.9577L20.3401 13.3834L22.1497 11.4726L19.8524 9.04687L21.2513 7.56973L24.1904 10.6731L26 8.76228L23.061 5.65895L24.7166 3.91078L22.907 2L11.9465 13.5731C10.9326 12.8684 9.71337 12.4483 8.41711 12.4483C4.87487 12.4483 2 15.4839 2 19.2242C2 22.9644 4.87487 26 8.41711 26V25.9729ZM8.41711 15.1316C10.5348 15.1316 12.2674 16.961 12.2674 19.1971C12.2674 21.4331 10.5348 23.2626 8.41711 23.2626C6.29946 23.2626 4.56684 21.4331 4.56684 19.1971C4.56684 16.961 6.29946 15.1316 8.41711 15.1316Z" fill="currentColor" />
                            </svg>
                        </div>
                    </div>
                    <div class="cred-body">
                        @if($user->pin)
                            <span class="status-terdaftar">TERDAFTAR</span>
                        @else
                            <span class="status-belum-terdaftar">BELUM TERDAFTAR</span>
                        @endif
                    </div>
                    <div class="cred-footer">
                        <div class="divider"></div>
                        <span class="footer-msg">
                            {{ $user->pin ? 'PIN aktif' : 'PIN belum dikonfigurasi' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- SECTION B: Tabel Detail Profil Saya --}}
        <div class="profile-table-card">
            <div class="table-card-header">
                <h3>Data Profile Saya</h3>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>NAMA</th>
                            <th>FINGERPRINTS ID</th>
                            <th>PIN</th>
                            <th>ROLE</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-nama">{{ $user->nama }}</td>
                            <td>
                                <div class="td-fp">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--C-Green, #00A63E)">
                                        <path d="M11.9983 10C11.4678 10 10.9591 10.2107 10.5841 10.5858C10.209 10.9609 9.99828 11.4696 9.99828 12C9.99828 13.02 9.89828 14.51 9.73828 16" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 13.1201C14 15.5001 14 19.5001 13 22.0001" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M17.2891 21.02C17.4091 20.42 17.7191 18.72 17.7891 18" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M2 12C2 9.90118 2.66037 7.85555 3.88758 6.1529C5.11478 4.45024 6.8466 3.17687 8.83772 2.51317C10.8288 1.84946 12.9783 1.82906 14.9817 2.45486C16.985 3.08067 18.7407 4.32094 20 6" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M2 16H2.01" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M21.8008 16C22.0008 14 21.9318 10.646 21.8008 10" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5 19.5C5.5 18 6 15 6 12C5.99899 11.3189 6.11397 10.6425 6.34 10" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M8.64844 22C8.85844 21.34 9.09844 20.68 9.21844 20" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9 6.79994C9.9124 6.27317 10.9474 5.99593 12.001 5.99609C13.0545 5.99626 14.0894 6.27384 15.0017 6.8009C15.9139 7.32797 16.6713 8.08594 17.1976 8.99859C17.7239 9.91124 18.0007 10.9464 18 11.9999V13.9999" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    @if($user->fingerprint_id)
                                        {{ $user->fingerprint_id }}
                                    @else
                                        <span style="color:var(--text-muted);font-style:italic;">Belum terdaftar</span>
                                    @endif
                                </div>
                            </td>
                             <td>
                                 <div class="td-pin">
                                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" fill="none">
                                        <path d="M7.72559 7.84355L15.5376 0L16.945 1.41302L15.5376 2.82703L18 5.29931L16.5927 6.71333L14.1293 4.24005L12.722 5.65307L14.833 7.77259L13.4256 9.18661L11.3146 7.06609L9.13293 9.25656C9.80918 10.2899 10.08 11.5382 9.89306 12.7603C9.70612 13.9823 9.0747 15.0915 8.12078 15.8734C7.16687 16.6554 5.95816 17.0546 4.72818 16.994C3.49821 16.9334 2.33426 16.4172 1.46121 15.5452C0.587591 14.6699 0.0691057 13.4998 0.00642622 12.2622C-0.0562532 11.0246 0.341345 9.8078 1.12203 8.84801C1.90271 7.88821 3.01076 7.25393 4.23105 7.06631C5.45134 6.88269 6.69679 7.15899 7.72559 7.84355ZM7.09159 14.1322C7.38153 13.8568 7.61355 13.5259 7.77394 13.159C7.93433 12.792 8.01982 12.3966 8.02537 11.9959C8.03092 11.5952 7.95641 11.1975 7.80625 10.8262C7.65609 10.455 7.43332 10.1177 7.15111 9.83435C6.86891 9.55101 6.53299 9.32734 6.16322 9.17657C5.79345 9.02581 5.39732 8.951 4.99826 8.95657C4.5992 8.96214 4.2053 9.04798 3.83984 9.20902C3.47439 9.37005 3.1448 9.60301 2.87055 9.89412C2.32665 10.4595 2.02569 11.2168 2.03249 12.0029C2.03929 12.7889 2.35331 13.5408 2.90692 14.0967C3.46053 14.6525 4.20942 14.9678 4.99231 14.9746C5.7752 14.9814 6.52944 14.6793 7.09258 14.1332" fill="#00A63E"/>
                                     </svg>
                                     {{ $user->pin_asli }}
                                 </div>
                            </td>
                            <td style="font-weight:500;">{{ ucfirst($user->role) }}</td>
                            <td>
                                <span class="badge-status {{ $user->aktif ? 'aktif' : 'nonaktif' }}">
                                    {{ $user->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    @vite(['resources/css/user/kredensial.css'])
@endpush