/**
 * ==========================================================================
 * log-berkas.js
 * Deskripsi: Menangani fungsionalitas pencarian/filtering pada tabel log berkas.
 * ==========================================================================
 */

window.filterTable = function() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("logTable");
    let tbody = table.querySelector("tbody");
    let tr = tbody.querySelectorAll("tr.data-row");
    
    let visibleCount = 0;
    
    // Looping semua baris dan sembunyikan yang tidak sesuai query
    tr.forEach(row => {
        // Asumsi data yang dicari ada di Judul atau Tanggal atau Badge
        let textContent = row.textContent || row.innerText;
        
        if (textContent.toLowerCase().indexOf(filter) > -1) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    // Perbarui styling untuk baris terakhir
    updateLastRowStyle(tbody);
    
    // Tampilkan not-found state jika tidak ada yang cocok
    let emptyState = document.getElementById("emptyState");
    let searchNotFoundState = document.getElementById("searchNotFoundState");
    
    // Gunakan jumlah baris (tr.length) untuk menentukan kekosongan tabel, lebih stabil
    let totalCount = tr.length;
    
    if (totalCount === 0) {
        if(emptyState) emptyState.style.display = "";
        if(searchNotFoundState) searchNotFoundState.style.display = "none";
    } else {
        if(emptyState) emptyState.style.display = "none";
        
        if (visibleCount === 0 && filter !== "") {
            if(searchNotFoundState) searchNotFoundState.style.display = "";
        } else {
            if(searchNotFoundState) searchNotFoundState.style.display = "none";
        }
    }
};

// Fungsi helper untuk menghapus border-bottom pada baris terakhir yang visible
function updateLastRowStyle(tbody) {
    let trs = Array.from(tbody.querySelectorAll("tr.data-row"));
    
    // Reset semua class last-row
    trs.forEach(row => row.classList.remove("last-row"));
    
    // Cari row terakhir yang tidak disembunyikan
    let visibleRows = trs.filter(row => row.style.display !== "none");
    
    if (visibleRows.length > 0) {
        visibleRows[visibleRows.length - 1].classList.add("last-row");
    }
}

/**
 * ==========================================================================
 * REALTIME POLLING (AJAX)
 * Melakukan pengecekan data terbaru ke server setiap 3 detik
 * dan memperbarui tabel tanpa me-refresh halaman sepenuhnya.
 * ==========================================================================
 */
setInterval(() => {
    // Jangan lakukan polling jika ada modal (tambah/edit/delete) yang sedang terbuka
    if (document.querySelector('.modal-overlay.show')) {
        return; 
    }
    
    // Jangan lakukan polling jika Tour Guide sedang berjalan (mencegah kedipan dan reset DOM)
    if (document.querySelector('.tour-backdrop') && document.querySelector('.tour-backdrop').style.display === 'block') {
        return;
    }
    
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // CEK SEKALI LAGI SEBELUM UPDATE DOM (Mencegah Race Condition dengan Tour Guide)
        if (document.querySelector('.tour-backdrop') && document.querySelector('.tour-backdrop').style.display === 'block') {
            return;
        }

        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update tabel (hanya jika ada perubahan)
        const currentTbody = document.querySelector('#logTable tbody');
        const newTbody = doc.querySelector('#logTable tbody');
        if (currentTbody && newTbody) {
            if (currentTbody.innerHTML !== newTbody.innerHTML) {
                currentTbody.innerHTML = newTbody.innerHTML;
            }
        }
        
        // Update angka statistik satu per satu agar tidak memicu ulang animasi CSS
        ['totalBerkasCount', 'totalKkCount', 'totalKtpCount', 'totalAkteCount'].forEach(id => {
            let curr = document.getElementById(id);
            let newVal = doc.getElementById(id);
            if(curr && newVal && curr.innerHTML !== newVal.innerHTML) {
                curr.innerHTML = newVal.innerHTML;
            }
        });
        
        // Aplikasikan kembali filter pencarian jika ada input
        if (typeof window.filterTable === 'function') {
            window.filterTable();
        }
    })
    .catch(error => {
        console.error("Polling error:", error);
    });
}, 3000);
