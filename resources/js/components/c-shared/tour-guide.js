/**
 * ==========================================================================
 * tour-guide.js
 * Deskripsi: Mengelola alur (sequence) tooltip dan overlay gelap dengan 
 *            efek lubang (hole) yang menyorot area target tanpa 
 *            menutupnya.
 * ==========================================================================
 */
class TourGuide {
    constructor() {
        // Buat elemen overlay yang akan menjadi "lubang" (hole)
        this.overlay = document.createElement('div');
        this.overlay.className = 'tour-overlay-hole';
        this.overlay.style.cssText = `
            position: fixed;
            box-shadow: 0 0 0 9999px rgba(0,0,0,0.6);
            z-index: 2147483647;
            border-radius: 8px;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: none;
        `;
        document.body.appendChild(this.overlay);

        // Buat backdrop penangkap klik (agar user bisa klik di area gelap untuk lanjut)
        this.backdrop = document.createElement('div');
        this.backdrop.className = 'tour-backdrop';
        this.backdrop.style.cssText = `
            position: fixed; inset: 0; z-index: 2147483646; cursor: pointer; display: none;
        `;
        this.backdrop.addEventListener('click', () => this.next());
        document.body.appendChild(this.backdrop);

        this.visibleSteps = [];
        this.currentIndex = -1;
        this.originalParents = new Map(); // Untuk mengembalikan tooltip ke tempat asal
    }

    start() {
        // Matikan fitur Tour Guide jika diakses dari perangkat mobile (layar kecil)
        if (window.innerWidth <= 768) {
            return;
        }

        // Pastikan overlay dan backdrop ada di body yang aktif (Penting untuk SPA/Turbo)
        if (!document.body.contains(this.overlay)) {
            document.body.appendChild(this.overlay);
        }
        if (!document.body.contains(this.backdrop)) {
            document.body.appendChild(this.backdrop);
        }

        // Pastikan tracking state global ada
        window.completedTourSteps = window.completedTourSteps || new Set();

        // Cari semua tooltip, filter yang sudah selesai berdasarkan global state
        const allTooltips = Array.from(document.querySelectorAll('.custom-tooltip-info'))
            .filter(t => !window.completedTourSteps.has(t.dataset.step));

        // Cek apakah ada modal yang sedang terbuka
        const activeModal = document.querySelector('.modal-overlay.show');

        this.visibleSteps = allTooltips.filter(t => {
            // Jika ada modal yang aktif, HANYA proses tooltip yang berada DI DALAM modal tersebut
            if (activeModal && !activeModal.contains(t)) {
                return false;
            }
            // Jika TIDAK ADA modal yang aktif, ABAIKAN semua tooltip yang berada di dalam modal
            if (!activeModal && t.closest('.modal-overlay')) {
                return false;
            }

            // Cek apakah elemen induk (target) sedang terlihat di layar
            const wrapper = t.closest('.tooltip-target-wrapper') || t.parentElement;
            if (!wrapper) return false;

            const rect = wrapper.getBoundingClientRect();
            // Elemen dianggap terlihat jika memiliki ukuran
            return rect.width > 0 && rect.height > 0;
        }).sort((a, b) => parseInt(a.dataset.step) - parseInt(b.dataset.step));

        // Jika tidak ada tooltip yang terlihat, hentikan
        if (this.visibleSteps.length === 0) return;

        this.currentIndex = 0;
        this.backdrop.style.display = 'block';
        this.overlay.style.display = 'block';
        this.showCurrent();
    }

    showCurrent() {
        // Sembunyikan semua tooltip sementara
        document.querySelectorAll('.custom-tooltip-info').forEach(t => t.style.display = 'none');

        if (this.currentIndex >= this.visibleSteps.length) {
            this.end();
            return;
        }

        const tooltip = this.visibleSteps[this.currentIndex];

        // Cari parent/target asli
        let wrapper = null;
        let isGroup = false;
        const targetGroupSelector = tooltip.getAttribute('data-target-group');
        const targetSelector = tooltip.getAttribute('data-target');

        if (targetGroupSelector) {
            const elements = document.querySelectorAll(targetGroupSelector);
            if (elements.length > 0) {
                wrapper = elements;
                isGroup = true;
            }
        }

        if (!isGroup && targetSelector) {
            wrapper = document.querySelector(targetSelector);
        }

        // Fallback ke wrapper terdekat jika tidak ada target eksplisit
        if (!wrapper) {
            wrapper = tooltip.closest('.tooltip-target-wrapper');
        }

        // Simpan parent elemen tooltip asli untuk dikembalikan nanti
        let originalParent = this.originalParents.get(tooltip);
        if (!originalParent) {
            originalParent = tooltip.parentElement;
            this.originalParents.set(tooltip, originalParent);
        }

        // Pindahkan tooltip ke dalam elemen "lubang" 
        this.overlay.appendChild(tooltip);
        tooltip.style.display = 'flex';

        // Hentikan tracking animasi frame sebelumnya jika ada
        if (this.trackingFrame) {
            cancelAnimationFrame(this.trackingFrame);
        }

        // Gunakan requestAnimationFrame agar lubang secara real-time mengikuti pergerakan elemen
        const updatePosition = () => {
            // Jika tour sudah selesai/berpindah, hentikan loop
            if (this.currentIndex >= this.visibleSteps.length) return;
            if (this.visibleSteps[this.currentIndex] !== tooltip) return;

            let currentRect;

            if (isGroup) {
                // Kalkulasi bounding box yang mencakup seluruh elemen di dalam grup
                let minTop = Infinity, minLeft = Infinity, maxBottom = -Infinity, maxRight = -Infinity;
                wrapper.forEach(el => {
                    const r = el.getBoundingClientRect();
                    if (r.top < minTop) minTop = r.top;
                    if (r.left < minLeft) minLeft = r.left;
                    if (r.bottom > maxBottom) maxBottom = r.bottom;
                    if (r.right > maxRight) maxRight = r.right;
                });

                // Jika elemen group hilang/tidak valid, berikan fallback
                if (minTop === Infinity) {
                    currentRect = { top: 0, left: 0, width: 0, height: 0 };
                } else {
                    currentRect = {
                        top: minTop,
                        left: minLeft,
                        width: maxRight - minLeft,
                        height: maxBottom - minTop
                    };
                }
            } else if (wrapper) {
                currentRect = wrapper.getBoundingClientRect();
            } else {
                currentRect = { top: 0, left: 0, width: 0, height: 0 };
            }

            // Hitung posisi dan ukuran lubang (tambah sedikit padding jika grup agar lebih luas)
            const padding = isGroup ? 8 : 0;
            this.overlay.style.top = (currentRect.top - padding) + 'px';
            this.overlay.style.left = (currentRect.left - padding) + 'px';
            this.overlay.style.width = (currentRect.width + padding * 2) + 'px';
            this.overlay.style.height = (currentRect.height + padding * 2) + 'px';

            this.trackingFrame = requestAnimationFrame(updatePosition);
        };

        // Mulai tracking
        updatePosition();

        // Tandai sebagai selesai agar tidak muncul ulang di sesi tour yang sama
        tooltip.classList.add('tour-completed');
        window.completedTourSteps.add(tooltip.dataset.step);

        // Ambil timestamp dari meta tag (diinjeksi dari sesi login)
        const metaTag = document.querySelector('meta[name="user-updated-at"]');
        const updatedAt = metaTag ? metaTag.content : '0';

        // Simpan ke localStorage agar permanen (hanya muncul sekali seumur hidup untuk rute ini pada versi profil ini)
        const storageKey = `tour_completed_${updatedAt}_${window.location.pathname}`;
        localStorage.setItem(storageKey, JSON.stringify(Array.from(window.completedTourSteps)));
    }

    next() {
        this.currentIndex++;
        this.showCurrent();
    }

    end() {
        this.backdrop.style.display = 'none';
        this.overlay.style.display = 'none';

        // Kembalikan semua tooltip yang dipinjam ke rumah aslinya
        this.visibleSteps.forEach(tooltip => {
            tooltip.style.display = 'none';
            const parent = this.originalParents.get(tooltip);
            if (parent) {
                parent.appendChild(tooltip);
            }
        });

        this.visibleSteps = [];
    }
}

// Inisialisasi secara Global agar bisa dipanggil dari mana saja (misal: saat modal dibuka)
if (!window.TourGuideInstance) {
    window.TourGuideInstance = new TourGuide();
}

// Fungsi untuk memuat state selesai dari localStorage
const loadCompletedSteps = () => {
    // Ambil timestamp dari meta tag (diinjeksi dari sesi login)
    const metaTag = document.querySelector('meta[name="user-updated-at"]');
    const updatedAt = metaTag ? metaTag.content : '0';

    const storageKey = `tour_completed_${updatedAt}_${window.location.pathname}`;
    const saved = localStorage.getItem(storageKey);
    window.completedTourSteps = saved ? new Set(JSON.parse(saved)) : new Set();
};

// Reset state global ketika halaman berubah (Turbo SPA navigation)
// State akan dimuat ulang dari localStorage berdasarkan rute baru
document.addEventListener('turbo:before-visit', () => {
    window.completedTourSteps = new Set();
});

// Jalankan otomatis saat halaman dimuat (Support Turbo/SPA)
const initTour = () => {
    if (window.tourInitTimeout) clearTimeout(window.tourInitTimeout);
    window.tourInitTimeout = setTimeout(() => {
        if (window.TourGuideInstance) {
            window.TourGuideInstance.start();
        }
    }, 3000);
};

// Pastikan memuat state dari localStorage saat normal full reload
document.addEventListener('DOMContentLoaded', () => {
    loadCompletedSteps();
    initTour();
});

// Pastikan memuat state dari localStorage saat Turbo navigasi
document.addEventListener('turbo:load', () => {
    loadCompletedSteps();
    initTour();
});
