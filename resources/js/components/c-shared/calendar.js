/**
 * ==========================================================================
 * calendar.js
 * Deskripsi: Mengelola logika komponen kalender kustom (dropdown), termasuk
 *            navigasi bulan, pemilihan tanggal, dan integrasi dengan input.
 * ==========================================================================
 */

(function () {

    /**
     * Inisialisasi seluruh dropdown kalender yang ada di halaman
     */
    const initCalendars = () => {
        const dropdowns = document.querySelectorAll('.calendar-dropdown');

        dropdowns.forEach(dropdown => {
            // Proteksi agar tidak terjadi inisialisasi ganda (Turbo/FOUC)
            if (dropdown.dataset.initialized === 'true') return;
            dropdown.dataset.initialized = 'true';

            // ==================================================================
            // 1. REFERENSI DOM & STATE INTERNAL
            // ==================================================================

            const daysContainer = dropdown.querySelector('.calendar-days-container');
            const monthHeader = dropdown.querySelector('.month-year-label');
            const prevBtn = dropdown.querySelector('.prev-month');
            const nextBtn = dropdown.querySelector('.next-month');
            const todayBtn = dropdown.querySelector('.btn-today');
            const clearBtn = dropdown.querySelector('.btn-clear');
            const targetInputId = dropdown.dataset.target;

            // State Kalender
            let currentDate = new Date();
            let selectedDate = null;

            const months = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            // ==================================================================
            // 2. RENDERING ENGINE
            // ==================================================================

            /**
             * Merender tampilan hari dan header bulan/tahun
             */
            function renderCalendar() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();

                // 2.1 Perbarui Header (Bulan & Tahun)
                if (monthHeader) {
                    monthHeader.innerText = `${months[month]} ${year}`;
                }

                // 2.2 Hitung logika tanggal (Padding & Total Hari)
                const firstDayOfMonth = new Date(year, month, 1).getDay();
                const lastDateOfMonth = new Date(year, month + 1, 0).getDate();
                const lastDateOfPrevMonth = new Date(year, month, 0).getDate();

                if (daysContainer) {
                    daysContainer.innerHTML = "";

                    // A. Isi hari padding dari bulan sebelumnya (Muted)
                    for (let i = firstDayOfMonth; i > 0; i--) {
                        const dayDiv = document.createElement("div");
                        dayDiv.className = "calendar-day muted";
                        dayDiv.innerText = lastDateOfPrevMonth - i + 1;
                        daysContainer.appendChild(dayDiv);
                    }

                    // B. Isi hari pada bulan berjalan
                    for (let i = 1; i <= lastDateOfMonth; i++) {
                        const dayDiv = document.createElement("div");
                        dayDiv.className = "calendar-day";
                        dayDiv.innerText = i;

                        // Cek status 'Active' (Dipilih atau Hari Ini)
                        const isSelected = selectedDate &&
                            i === selectedDate.getDate() &&
                            month === selectedDate.getMonth() &&
                            year === selectedDate.getFullYear();

                        const today = new Date();
                        const isToday = !selectedDate &&
                            i === today.getDate() &&
                            month === today.getMonth() &&
                            year === today.getFullYear();

                        if (isSelected || isToday) {
                            dayDiv.classList.add("active");
                        }

                        // Event Klik Tanggal
                        dayDiv.onclick = (e) => {
                            e.stopPropagation();
                            selectedDate = new Date(year, month, i);
                            updateTarget(selectedDate);
                            dropdown.classList.remove('show');
                            renderCalendar();
                        };

                        daysContainer.appendChild(dayDiv);
                    }

                    // C. Isi hari padding untuk bulan berikutnya (hingga total 42 slot)
                    const remainingSlots = 42 - daysContainer.children.length;
                    for (let i = 1; i <= remainingSlots; i++) {
                        const dayDiv = document.createElement("div");
                        dayDiv.className = "calendar-day muted";
                        dayDiv.innerText = i;
                        daysContainer.appendChild(dayDiv);
                    }
                }
            }


            // ==================================================================
            // 3. UTILITAS DATA
            // ==================================================================

            /**
             * Menulis nilai tanggal ke input target dan memicu event 'change'
             */
            function updateTarget(date) {
                if (!targetInputId) return;
                const targetInput = document.getElementById(targetInputId);
                if (!targetInput) return;

                if (date) {
                    const yyyy = date.getFullYear();
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');
                    targetInput.value = `${yyyy}-${mm}-${dd}`;
                } else {
                    targetInput.value = "";
                }

                // Trigger event change agar filter tabel/pencarian menyadari perubahan
                targetInput.dispatchEvent(new Event('change'));
            }


            // ==================================================================
            // 4. KONTROL & EVENT LISTENERS
            // ==================================================================

            // Navigasi Bulan Sebelumnya
            if (prevBtn) {
                prevBtn.onclick = (e) => {
                    e.stopPropagation();
                    currentDate.setMonth(currentDate.getMonth() - 1);
                    renderCalendar();
                };
            }

            // Navigasi Bulan Berikutnya
            if (nextBtn) {
                nextBtn.onclick = (e) => {
                    e.stopPropagation();
                    currentDate.setMonth(currentDate.getMonth() + 1);
                    renderCalendar();
                };
            }

            // Tombol Kembali ke 'Hari Ini'
            if (todayBtn) {
                todayBtn.onclick = (e) => {
                    e.stopPropagation();
                    currentDate = new Date();
                    selectedDate = new Date();
                    updateTarget(selectedDate);
                    dropdown.classList.remove('show');
                    renderCalendar();
                };
            }

            // Tombol Bersihkan Filter (Clear)
            if (clearBtn) {
                clearBtn.onclick = (e) => {
                    e.stopPropagation();
                    selectedDate = null;
                    updateTarget(null);
                    dropdown.classList.remove('show');
                    renderCalendar();
                };
            }

            // Klik di luar dropdown untuk menutup kalender
            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });

            // Render awal saat inisialisasi
            renderCalendar();
        });
    };

    // ==========================================================================
    // 5. LIFECYCLE LISTENERS (TURBO SUPPORT)
    // ==========================================================================

    document.addEventListener('turbo:load', initCalendars);
    document.addEventListener('DOMContentLoaded', initCalendars);

    // Inisialisasi jika halaman sudah siap (fallback)
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        initCalendars();
    }

})();
