{{-- ==========================================================================
KOMPONEN: Status Brankas (Shared)
Deskripsi: Menampilkan status realtime keadaan Pintu Brankas
(Terkunci, Terbuka, atau Offline).
========================================================================== --}}

@php
    // 1. LOGIKA STATUS (Integrated with IoT)
    // Tentukan status pintu (Default: TERKUNCI)
    $rawStatus = strtoupper($brankas->status_pintu ?? 'TERKUNCI');
    $isOpen = ($rawStatus === 'TERBUKA');

    // Tentukan label dan class berdasarkan state terbuka/terkunci saja (2 Kondisi)
    $currentStatusPintu = $isOpen ? 'TERBUKA' : 'TERKUNCI';
    $cardClass = $isOpen ? 'is-unlocked' : 'is-locked';

    // Metadata tambahan (Status online/offline tetap dicek untuk indikator LIVE/OFFLINE)
    $lastSeen = $brankas?->last_seen;
    $isRecentlySeen = !$lastSeen || $lastSeen->gt(now()->subSeconds(15));
    $isOffline = (!$brankas || !(bool) $brankas->is_online || !$isRecentlySeen); 
@endphp

<div class="main-status-card {{ $cardClass }} {{ $isOffline ? 'is-offline' : '' }}" id="brankas-card">

    {{-- BAGIAN HEADER: Judul & Indikator Live --}}
    <div class="status-header">
        <span class="status-title">Status Brankas saat ini</span>
        <div class="live-indicator">
            <span class="dot"></span>
            <span class="live-text" id="live-indicator-text">{{ $isOffline ? 'OFFLINE' : 'LIVE' }}</span>
        </div>
    </div>

    {{-- BAGIAN BODY: Ikon & Detail Status --}}
    <div class="status-body">

        {{-- Ikon Status Dinamis --}}
        <div class="status-icon-box" id="brankas-icon-box">
            {{-- Ikon Terkunci --}}
            <svg class="icon-locked" id="icon-locked" viewBox="0 0 24 24" fill="none"
                style="{{ $isOpen ? 'display: none;' : '' }}">
                <path
                    d="M17 10H19C19.5304 10 20.0391 10.2107 20.4142 10.5858C20.7893 10.9609 21 11.4696 21 12V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V12C3 11.4696 3.21071 10.9609 3.58579 10.5858C3.96086 10.2107 4.46957 10 5 10H7V7C7 5.67392 7.52678 4.40215 8.46447 3.46447C9.40215 2.52678 10.6739 2 12 2C13.3261 2 14.5979 2.52678 15.5355 3.46447C16.4732 4.40215 17 5.67392 17 7V10ZM15 10V7C15 6.20435 14.6839 5.44129 14.1213 4.87868C13.5587 4.31607 12.7956 4 12 4C11.2044 4 10.4413 4.31607 9.87868 4.87868C9.31607 5.44129 9 6.20435 9 7V10H15ZM5 12V20H19V12H5Z"
                    fill="var(--C-Green, #00A63E)" />
            </svg>

            {{-- Ikon Terbuka --}}
            <svg class="icon-unlocked" id="icon-unlocked" viewBox="0 0 24 24" fill="none"
                style="{{ $isOpen ? '' : 'display: none;' }}">
                <path
                    d="M7 10H18C18.5304 10 19.0391 10.2107 19.4142 10.5858C19.7893 10.9609 20 11.4696 20 12V20C20 20.5304 19.7893 21.0391 19.4142 21.4142C19.0391 21.7893 18.5304 22 18 22H6C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20V12C4 11.4696 4.21071 10.9609 4.58579 10.5858C4.96086 10.2107 5.46957 10 6 10H7V7C7 4.23858 9.23858 2 12 2C14.7614 2 17 4.23858 17 7V8H15V7C15 5.34315 13.6569 4 12 4C10.3431 4 9 5.34315 9 7V10H14V14H16V18H14V14H9V10Z"
                    fill="var(--C-Red, #E7000B)" />
            </svg>
        </div>

        {{-- Info Status Utama --}}
        <div class="status-info">
            <div class="status-label-big" id="brankas-status-text">
                {{ $currentStatusPintu }}
            </div>

            {{-- Metadata Row: Koneksi, Monitoring, & Waktu --}}
            <div class="status-meta-row">

                {{-- Item 1: Status Koneksi IoT --}}
                <div class="meta-item" id="koneksi-item">
                    <svg id="koneksi-off-icon" viewBox="0 0 24 24" fill="none"
                        style="{{ $isOffline ? '' : 'display: none;' }}">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 17C12.2652 17 12.5195 17.1055 12.7071 17.293C12.8946 17.4805 13 17.7348 13 18C13 18.2652 12.8946 18.5195 12.7071 18.707C12.5195 18.8946 12.2652 19 12 19C11.7349 19 11.4805 18.8946 11.293 18.707C11.1055 18.5195 11 18.2652 11 18C11 17.7348 11.1055 17.4805 11.293 17.293C11.4805 17.1055 11.7349 17 12 17ZM12 13C13.38 13 14.6322 13.5599 15.5362 14.4639C15.7238 14.6515 15.8291 14.9065 15.8291 15.1719C15.829 15.4371 15.7237 15.6914 15.5362 15.8789C15.3485 16.0666 15.0935 16.1719 14.8282 16.1719C14.5961 16.1718 14.3723 16.0912 14.1944 15.9453L13.9014 15.6797C13.3677 15.2422 12.6962 15.0002 12 15C11.1711 15 10.4229 15.3349 9.87894 15.8789C9.69137 16.0665 9.43716 16.1718 9.17191 16.1719C8.90655 16.1719 8.65155 16.0666 8.46391 15.8789C8.27645 15.6914 8.17103 15.437 8.17094 15.1719C8.17094 14.9065 8.27629 14.6515 8.46391 14.4639C8.92829 13.9996 9.48024 13.6311 10.087 13.3799C10.6935 13.1289 11.3436 12.9999 12 13ZM12.4893 11.0176C12.3267 11.0064 12.1635 10.9998 12 11C11.0806 10.9989 10.1698 11.1795 9.32035 11.5313C8.47097 11.8831 7.69918 12.399 7.04984 13.0498C6.86125 13.2319 6.60889 13.3333 6.34672 13.3311C6.08462 13.3288 5.83386 13.2234 5.64848 13.0381C5.46317 12.8528 5.35789 12.6019 5.35551 12.3399C5.35323 12.0777 5.45364 11.8244 5.63578 11.6358C6.47086 10.7991 7.46361 10.136 8.5557 9.6836C9.13425 9.44399 9.73504 9.26744 10.3477 9.15333L12.4893 11.0176ZM12.6377 9.02442C13.6015 9.09214 14.5501 9.3128 15.4454 9.6836C16.5373 10.1359 17.5293 10.7993 18.3643 11.6358C18.5465 11.8244 18.6468 12.0777 18.6446 12.3399C18.6422 12.6019 18.5369 12.8528 18.3516 13.0381C18.1662 13.2233 17.9154 13.3288 17.6534 13.3311C17.6282 13.3313 17.6031 13.3288 17.5782 13.3272L12.6377 9.02442ZM8.5225 7.56349C8.27558 7.6457 8.03023 7.73411 7.7891 7.83399C6.4543 8.38695 5.24178 9.19849 4.22172 10.2217C4.03312 10.4038 3.78077 10.5052 3.51859 10.5029C3.25648 10.5006 3.00572 10.3953 2.82035 10.21C2.63501 10.0246 2.52974 9.77382 2.52738 9.51173C2.5251 9.24955 2.62552 8.99623 2.80766 8.80763C3.95975 7.65248 5.31971 6.72646 6.81449 6.07618L8.5225 7.56349ZM12 5.00001C15.59 5.00001 18.8404 6.45566 21.1924 8.80763C21.3746 8.99623 21.475 9.24953 21.4727 9.51173C21.4703 9.77382 21.3651 10.0246 21.1797 10.21C20.9943 10.3953 20.7436 10.5007 20.4815 10.5029C20.2193 10.5052 19.9669 10.4038 19.7784 10.2217C18.7583 9.19849 17.5458 8.38694 16.211 7.83399C14.8761 7.28105 13.4449 6.99787 12 7.00001C11.4767 6.99924 10.9553 7.03694 10.4395 7.11036L8.54887 5.4629C9.67075 5.1545 10.8316 4.99859 12 5.00001Z"
                            fill="#64748B" />
                        <path d="M3.04297 3.95312L18.9595 18.0479" stroke="#64748B" stroke-width="2" />
                    </svg>
                    <svg id="koneksi-on-icon" viewBox="0 0 24 24" fill="none"
                        style="{{ $isOffline ? 'display: none;' : '' }}">
                        <path stroke="var(--C-Green, #00A63E)" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 12.55a11 11 0 0114.08 0M1.42 9a16 16 0 0121.16 0M8.53 16.11a6 6 0 016.95 0M12 20h.01" />
                    </svg>
                    <span id="koneksi-text">Koneksi IoT: {{ $isOffline ? 'Offline' : 'Online' }}</span>
                </div>

                {{-- Pemisah Visual (Titik) --}}
                <svg class="meta-dot" viewBox="0 0 8 8" fill="none">
                    <circle cx="4" cy="4" r="4" fill="#D1D5DB" />
                </svg>

                {{-- Item 2: Status Monitoring --}}
                <div class="meta-item" id="monitoring-item">
                    <svg id="monitoring-off-icon" viewBox="0 0 24 24" fill="none"
                        style="{{ $isOffline ? '' : 'display: none;' }}">
                        <path d="M7.5 11.5L11 15L17 9" stroke="#6A7282" stroke-width="1.9" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.28711 5.24201L3.95117 5.34064V10.0154C3.95128 12.5785 4.75794 15.077 6.25684 17.1561C7.69352 19.1486 9.69702 20.6578 12.002 21.493C14.1071 20.7298 15.9585 19.4024 17.3584 17.66L18.7402 18.9725C17.0572 21.0205 14.8289 22.5601 12.3027 23.4022C12.1079 23.4671 11.897 23.467 11.7021 23.4022C8.89201 22.4662 6.44802 20.669 4.71582 18.2664C2.9837 15.8639 2.05089 12.9773 2.05078 10.0154V4.62872C2.05081 4.22027 2.31228 3.86042 2.69629 3.73029L4.28711 5.24201ZM11.8711 1.06037C12.0045 1.0413 12.1411 1.05066 12.2715 1.08869L21.2676 3.71662C21.6726 3.83501 21.9511 4.20674 21.9512 4.62872V10.0184C21.9508 12.2544 21.4181 14.4473 20.4121 16.4217L18.9648 15.0467C19.6757 13.4745 20.0505 11.7612 20.0508 10.0174V5.34064L12.0049 2.99005L7.62012 4.26935L6.02637 2.75568L11.7393 1.08869L11.8711 1.06037Z"
                            fill="#6A7282" />
                        <path d="M1 2L21 21" stroke="#6A7282" stroke-width="2" />
                    </svg>
                    <svg id="monitoring-on-icon" viewBox="0 0 24 24" fill="none"
                        style="{{ $isOffline ? 'display: none;' : '' }}">
                        <path
                            d="M3 4.628L12.0045 2L21 4.628V10.017C20.9996 12.7788 20.1304 15.4704 18.5154 17.7108C16.9004 19.9512 14.6216 21.6267 12.0015 22.5C9.38048 21.627 7.10065 19.9514 5.48505 17.7105C3.86946 15.4696 3.00004 12.7771 3 10.0145V4.628Z"
                            stroke="#00A63E" stroke-width="1.9" stroke-linejoin="round" />
                        <path d="M7.5 11.5L11 15L17 9" stroke="#00A63E" stroke-width="1.9" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span id="monitoring-text">Monitoring: {{ $isOffline ? 'Non-Aktif' : 'Aktif' }}</span>
                </div>

                {{-- Pemisah Visual (Titik) --}}
                <svg class="meta-dot" viewBox="0 0 8 8" fill="none">
                    <circle cx="4" cy="4" r="4" fill="#D1D5DB" />
                </svg>

                {{-- Item 4: Tanggal & Jam Realtime --}}
                <div class="meta-item">
                    {{-- Ikon Kalender --}}
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M19 4H17V3C17 2.73478 16.8946 2.48043 16.7071 2.29289C16.5196 2.10536 16.2652 2 16 2C15.7348 2 15.4804 2.10536 15.2929 2.29289C15.1054 2.48043 15 2.73478 15 3V4H9V3C9 2.73478 8.89464 2.48043 8.70711 2.29289C8.51957 2.10536 8.26522 2 8 2C7.73478 2 7.48043 2.10536 7.29289 2.29289C7.10536 2.48043 7 2.73478 7 3V4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V19C2 19.7956 2.31607 20.5587 2.87868 21.1213C3.44129 21.6839 4.20435 22 5 22H19C19.7956 22 20.5587 21.6839 21.1213 21.1213C21.6839 20.5587 22 19.7956 22 19V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM20 19C20 19.2652 19.8946 19.5196 19.7071 19.7071C19.5196 19.8946 19.2652 20 19 20H5C4.73478 20 4.48043 19.8946 4.29289 19.7071C4.10536 19.5196 4 19.2652 4 19V12H20V19ZM20 10H4V7C4 6.73478 4.10536 6.48043 4.29289 6.29289C4.48043 6.10536 4.73478 6 5 6H7V7C7 7.26522 7.10536 7.51957 7.29289 7.70711C7.48043 7.89464 7.73478 8 8 8C8.26522 8 8.51957 7.89464 8.70711 7.70711C8.89464 7.51957 9 7.26522 9 7V6H15V7C15 7.26522 15.1054 7.51957 15.2929 7.70711C15.4804 7.89464 15.7348 8 16 8C16.2652 8 16.5196 7.89464 16.7071 7.70711C16.8946 7.51957 17 7.26522 17 7V6H19C19.2652 6 19.5196 6.10536 19.7071 6.29289C19.8946 6.48043 20 6.73478 20 7V10Z"
                            fill="#6A7282" />
                    </svg>
                    <span id="realtime-date">Loading...</span>

                    <span style="margin: 0 4px; color: #D1D5DB;">-</span>

                    {{-- Ikon Jam --}}
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 6V12L16 14" stroke="#6A7282" stroke-width="1.9" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                            stroke="#6A7282" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span id="realtime-clock">00:00:00</span>
                </div>

            </div>
        </div>

    </div>
</div>
