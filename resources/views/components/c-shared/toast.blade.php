{{-- ======================================================================
     KOMPONEN: Toast Notification (Shared)
     Deskripsi: Sistem notifikasi melayang (toast) yang muncul sebentar 
                untuk memberikan umpan balik sukses/error.
     Parameter:
     - id: (string) ID unik untuk template default
     - title: (string) Judul pesan
     - message: (string) Isi pesan detail
     ====================================================================== --}}

@props([
    'id'      => 'toast_default',
    'title'   => 'Rekap berhasil diunduh',
    'message' => 'File rekap akses telah disimpan ke perangkat anda',
])

{{-- 1. KONTAINER UTAMA (Tempat Injeksi Toast) --}}
<div class="toast-container" id="toastContainer">
    {{-- Toast akan dimasukkan ke sini melalui JavaScript --}}
</div>

{{-- 2. TEMPLATE HTML (Digunakan sebagai cetakan oleh JS) --}}
<template id="toastTemplate">
    <div class="toast" id="{{ $id }}">
        
        {{-- Ikon Centang --}}
        <div class="toast-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 13" fill="none">
                <path d="M15.5 1.5L6.69355 11.5L1.5 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        
        {{-- Konten Teks --}}
        <div class="toast-body">
            <div class="toast-title">{{ $title }}</div>
            <div class="toast-text">{{ $message }}</div>
        </div>

    </div>
</template>