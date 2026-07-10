{{-- ======================================================================
     KOMPONEN: Modal Logout (Shared)
     Deskripsi: Komponen modal konfirmasi untuk keluar dari sistem.
                Digunakan secara global untuk Admin maupun User.
     ====================================================================== --}}


{{-- 2. STRUKTUR HTML MODAL --}}
<div class="logout-overlay" id="logoutOverlay" onclick="closeLogoutOutside(event)">
    <div class="logout-modal">
        
        {{-- Bagian Header: Ikon Logout & Judul --}}
        <div class="logout-modal-header">
            <div class="logout-icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="logout-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 17L21 12L16 7" />
                    <path d="M21 12H9" />
                    <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" />
                </svg>
            </div>
            <span class="logout-modal-title">Konfirmasi Logout {{ ucfirst(session('user.role', 'User')) }}</span>
        </div>

        {{-- Bagian Konten: Pesan Penjelasan --}}
        <p class="logout-modal-desc">
            Apakah Anda yakin ingin keluar dari sistem? Anda perlu login kembali untuk mengakses dashboard di sesi berikutnya.
        </p>

        {{-- Bagian Aksi: Tombol Batal & Form Logout --}}
        <div class="logout-modal-actions">
            {{-- Tombol Batal (Menutup Modal) --}}
            <button type="button" class="btn-batal" onclick="closeLogoutModal()">Batal</button>
            
            {{-- Form Logout (Metode POST untuk keamanan) --}}
            <form action="{{ route('logout') }}" method="POST" style="display:contents;">
                @csrf
                <button type="submit" class="btn-logout-confirm">Logout</button>
            </form>
        </div>

    </div>
</div>