/**
 * ==========================================================================
 * user-tambah.js
 * Deskripsi: Mengelola logika modal Tambah User.
 * ==========================================================================
 */

/**
 * Polling variable
 */
let registrationPollingInterval = null;

/**
 * Membuka modal Tambah User
 */
window.openTambahModalUser = function() {
    console.log("Membuka modal tambah user...");
    const modal = document.getElementById('userTambahModalOverlay');
    if (modal) {
        modal.classList.add('show');
        document.body.classList.add('no-scroll');
        
        // Reset form inputs
        document.getElementById('displayFingerprintId').value = '';
        document.getElementById('hiddenFingerprintId').value = '';
        document.getElementById('inputUserPin').value = '';

        // Mulai polling data dari alat IoT
        startPollingRegistration();

        // Trigger tour untuk elemen di dalam modal (jika ada)
        setTimeout(() => {
            if (window.TourGuideInstance) {
                window.TourGuideInstance.start();
            }
        }, 300);
    } else {
        console.error("Modal overlay tidak ditemukan!");
    }
};

/**
 * Menutup modal Tambah User
 */
window.closeTambahModalUser = function() {
    console.log("Menutup modal tambah user...");
    const modal = document.getElementById('userTambahModalOverlay');
    if (modal) {
        modal.classList.remove('show');
        document.body.classList.remove('no-scroll');

        // Berhenti polling data
        stopPollingRegistration();
    }
};

/**
 * Mengambil data registrasi terbaru dari API
 */
function fetchLatestRegistration() {
    fetch('/api/iot/latest-registration')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const reg = data.data;
                const displayId = `FP-${reg.fingerprint_id.toString().padStart(3, '0')}`;
                
                const displayInput = document.getElementById('displayFingerprintId');
                const hiddenInput  = document.getElementById('hiddenFingerprintId');
                const pinInput     = document.getElementById('inputUserPin');

                if (displayInput) displayInput.value = displayId;
                if (hiddenInput)  hiddenInput.value  = reg.fingerprint_id;
                if (pinInput)     pinInput.value     = reg.pin;
                
                // Tambahkan efek visual jika data baru masuk
                [displayInput, pinInput].forEach(el => {
                    if (el) {
                        el.style.backgroundColor = 'rgba(0, 166, 62, 0.1)';
                        el.style.borderColor = 'var(--C-Green-Primary)';
                    }
                });
            }
        })
        .catch(err => console.error('Error fetching IoT data:', err));
}

/**
 * Menjalankan interval polling
 */
function startPollingRegistration() {
    if (registrationPollingInterval) return;
    
    // Jalankan sekali di awal
    fetchLatestRegistration();
    
    // Set interval setiap 3 detik
    registrationPollingInterval = setInterval(fetchLatestRegistration, 3000);
}

/**
 * Menghentikan interval polling
 */
function stopPollingRegistration() {
    if (registrationPollingInterval) {
        clearInterval(registrationPollingInterval);
        registrationPollingInterval = null;
    }
}

/**
 * Menutup modal saat area background (overlay) diklik
 */
window.closeTambahModalUserOutside = function(e) {
    if (e.target.id === 'userTambahModalOverlay') {
        window.closeTambahModalUser();
    }
};

// ==========================================================================
// 3. EVENT LISTENERS
// ==========================================================================
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleUserPin');
    const pinInput  = document.getElementById('inputUserPin');
    
    if (toggleBtn && pinInput) {
        toggleBtn.addEventListener('click', function() {
            const isPassword = pinInput.getAttribute('type') === 'password';
            
            // Toggle input type
            pinInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Toggle icons
            const eyeOpen = toggleBtn.querySelector('.eye-open');
            const eyeClosed = toggleBtn.querySelector('.eye-closed');
            
            if (isPassword) {
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        });
    }
});