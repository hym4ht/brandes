# 🔐 Brandes - IoT-Based Smart Safe (Brankas) Monitoring System

Sistem monitoring brankas pintar berbasis **IoT (ESP32)** dan **Laravel API Backend**. Sistem ini menggunakan autentikasi ganda (Sidik Jari + PIN), pemantauan lokasi real-time dengan GPS, sensor getaran untuk deteksi pembobolan, serta sinkronisasi database antara perangkat keras (hardware) dan server web.

---

## 🗺️ Arsitektur & Alur Status IoT

Sistem Brandes membagi status ke dalam beberapa kategori utama di database guna memberikan gambaran real-time mengenai kondisi keamanan brankas.

### 1. Status Konektivitas & Solenoid Pintu (`lokasi_brankas`)

Status ini disimpan pada model `LokasiBrankas` (`lokasi_brankas` table) dan mewakili kondisi fisik alat secara real-time.

| Kolom | Tipe Data | Nilai (Value) | Deskripsi |
| :--- | :--- | :--- | :--- |
| `is_online` | Boolean | `1` (Online) / `0` (Offline) | Dinyatakan **Online** jika server menerima sinyal *heartbeat* dalam **15 detik terakhir**. |
| `status` | Enum/String | `'aman'` / `'terbuka'` | `'aman'` jika pintu terkunci rapat. `'terbuka'` jika solenoid aktif (terbuka). |
| `status_pintu` | Enum/String | `'TERKUNCI'` / `'TERBUKA'` | Status fisik solenoid relay (dinormalisasi dari kiriman ESP32). |
| `last_seen` | Datetime | Timestamp | Waktu terakhir alat mengirimkan sinyal detak jantung (*heartbeat*). |

---

### 2. Status Deteksi Sensor & Lokasi (`lokasi_history`)

Model `LokasiHistory` (`lokasi_history` table) merekam metrik pergerakan, getaran, dan sinyal GPS secara berkala. Status dihitung secara dinamis pada backend menggunakan fungsi `determineStatus()` dengan aturan berikut:

```
                  ┌──────────────────────────────────────────┐
                  │            Data Sensor Masuk             │
                  └────────────────────┬─────────────────────┘
                                       │
                  ┌────────────────────▼─────────────────────┐
                  │ Getaran > 3.0 G ATAU Kecepatan > 5 km/h? │
                  └────────────────────┬─────────────────────┘
                                       │
                            ┌──────────┴──────────┐
                         YA │                  NO │
                            ▼                     ▼
                     ┌─────────────┐    ┌──────────────────────────────────────────┐
                     │ Status:     │    │ Getaran > 1.0 G ATAU Kecepatan > 1 km/h? │
                     │   bahaya    │    └────────────────────┬─────────────────────┘
                     └─────────────┘                         │
                                                  ┌──────────┴──────────┐
                                               YA │                  NO │
                                                  ▼                     ▼
                                           ┌─────────────┐       ┌─────────────┐
                                           │ Status:     │       │ Status:     │
                                           │   waspada   │       │   normal    │
                                           └─────────────┘       └─────────────┘
```

* **`normal`**: Kondisi brankas diam, tidak ada guncangan signifikan (Getaran $\le$ 1.0 G dan Kecepatan $\le$ 1.0 km/h).
* **`waspada`**: Terjadi guncangan pelan atau pergerakan lambat (Getaran > 1.0 G atau Kecepatan > 1.0 km/h).
* **`bahaya`**: Indikasi kuat brankas sedang dibobol secara paksa atau dibawa lari (Getaran > 3.0 G atau Kecepatan > 5.0 km/h).

---

### 3. Status Upaya Akses (`history_akses`)

Setiap kali pengguna mencoba membuka brankas melalui sidik jari atau tombol keypad, status dicatat pada model `HistoryAkses`.

* **`Berhasil`**: Sidik jari terdaftar cocok dengan database lokal ESP32, dan PIN 6-digit yang dimasukkan sesuai dengan dekripsi data PIN di server Laravel.
* **`Gagal`**: Terjadi kesalahan autentikasi yang dapat dipicu oleh:
  * **PIN Salah**: Sidik jari terverifikasi, tetapi input PIN 6-digit tidak cocok.
  * **Jari Tidak Dikenal**: Sidik jari ditempelkan namun ID template tidak terdaftar di sistem.
  * **Percobaan Akses Ilegal (3x Jari Salah)**: Pengguna gagal memverifikasi sidik jari sebanyak 3 kali berturut-turut pada alat (ID dikirim sebagai `0`).

---

### 4. Kategori Notifikasi Keamanan (`notifikasi_keamanan`)

Notifikasi diklasifikasikan berdasarkan tingkat urgensi keamanan:

* **🟢 `success`**: Aktivitas normal yang berhasil (contoh: Pintu brankas berhasil dibuka oleh pengguna terdaftar).
* **🟡 `warning`**: Aktivitas mencurigakan atau perubahan sistem (contoh: Jari tidak dikenal mencoba menempel, PIN salah dimasukkan, 3x gagal verifikasi jari, atau sistem memformat memori).
* **🔴 `danger`**: Ancaman keamanan aktif terdeteksi (contoh: Sensor getaran mendeteksi getaran keras / pembobolan).

---

## 🔌 API Gateway - IoT Endpoints

Backend menyediakan rute khusus komunikasi alat dengan prefiks `/api/iot` di [routes/api.php](file:///c:/Users/ASUS/Downloads/me/brandes/routes/api.php).

### 1. `POST /api/iot/heartbeat`
Mengirimkan detak jantung rutin dari ESP32 untuk mempertahankan status "Online".
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001",
    "status_pintu": "TERKUNCI"
  }
  ```
* **Response**:
  ```json
  {
    "success": true,
    "message": "Heartbeat OK",
    "is_online": 1,
    "status": "aman",
    "status_pintu": "TERKUNCI",
    "last_seen": "2026-05-24 19:15:00"
  }
  ```

### 2. `POST /api/iot/verify`
Memverifikasi sidik jari dan PIN saat proses buka brankas. Endpoint ini menangani berbagai skenario percobaan akses:

#### 🟢 Skenario A: Akses Diterima (Sukses)
*Terjadi jika Sidik Jari terdaftar dan PIN cocok.*
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001",
    "fingerprint_id": 1,
    "pin": "123456"
  }
  ```
* **Response (HTTP 200)**:
  ```json
  {
    "success": true,
    "message": "Akses Diberikan.",
    "user": "Nama Pengguna"
  }
  ```
* **Dampak Sistem**:
  * Menambah log `HistoryAkses` (`status`: 'Berhasil', `aktivitas`: 'Membuka Brankas (Sidik Jari + PIN)').
  * Menambah `NotifikasiKeamanan` (`tipe`: 'success', `judul`: 'Brankas Terbuka').

#### 🟡 Skenario B: Akses Ilegal (3x Gagal Jari Berturut-turut)
*Terjadi jika pengguna gagal memverifikasi sidik jari sebanyak 3 kali berturut-turut pada alat. Alat IoT secara otomatis mengirimkan `fingerprint_id` bernilai `0`.*
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001",
    "fingerprint_id": 0,
    "pin": "000000"
  }
  ```
* **Response (HTTP 401)**:
  ```json
  {
    "success": false,
    "message": "Ilegal Logged."
  }
  ```
* **Dampak Sistem**:
  * Menambah log `HistoryAkses` (`nama`: 'Tidak Dikenal', `status`: 'Gagal', `aktivitas`: 'Percobaan Akses Ilegal (3x Jari Salah)', `fingerprint_id`: 0).
  * Menambah `NotifikasiKeamanan` (`tipe`: 'warning', `judul`: 'Akses Ilegal (3x Gagal)', `fingerprint_id_attempt`: "0").

#### 🟡 Skenario C: Sidik Jari Tidak Dikenal
*Terjadi jika sidik jari ditempelkan tetapi ID template tersebut tidak terdaftar atau belum disetujui di web backend.*
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001",
    "fingerprint_id": 99,
    "pin": "123456"
  }
  ```
* **Response (HTTP 404)**:
  ```json
  {
    "success": false,
    "message": "User tidak ditemukan."
  }
  ```
* **Dampak Sistem**:
  * Menambah log `HistoryAkses` (`nama`: 'Tidak Dikenal', `status`: 'Gagal', `aktivitas`: 'Akses Gagal (Jari Tidak Terdaftar: 99)').
  * Menambah `NotifikasiKeamanan` (`tipe`: 'warning', `judul`: 'Jari Tidak Dikenal', `fingerprint_id_attempt`: "99").

#### 🟡 Skenario D: PIN Salah
*Terjadi jika sidik jari cocok dan terdaftar, namun PIN 6-digit yang dimasukkan di keypad salah.*
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001",
    "fingerprint_id": 1,
    "pin": "999999"
  }
  ```
* **Response (HTTP 401)**:
  ```json
  {
    "success": false,
    "message": "Akses Ditolak."
  }
  ```
* **Dampak Sistem**:
  * Menambah log `HistoryAkses` (`nama`: 'Nama Pengguna', `status`: 'Gagal', `aktivitas`: 'Gagal Membuka (PIN Salah)').
  * Menambah `NotifikasiKeamanan` (`tipe`: 'warning', `judul`: 'PIN Salah').

### 3. `POST /api/iot/gps`
Mengirimkan data telemetri koordinat GPS Neo-6M secara berkala.
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001",
    "gps_valid": true,
    "latitude": -6.200000,
    "longitude": 106.816666,
    "altitude": 15.2,
    "satellites": 8,
    "hdop": 1.2,
    "speed_kmh": 0.0,
    "fix_quality": 1
  }
  ```

### 4. `POST /api/iot/alert`
Mengirim sinyal peringatan getaran tinggi secara instan.
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001"
  }
  ```

### 5. `POST /api/iot/register`
Mendaftarkan sidik jari dan PIN baru dari alat ke antrean registrasi server.
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001",
    "fingerprint_id": 2,
    "pin": "654321"
  }
  ```

### 6. `POST /api/iot/reset`
Sinkronisasi ketika memori internal sidik jari pada ESP32 diformat ulang (semua pengguna reguler dihapus).
* **Payload**:
  ```json
  {
    "kode_brankas": "BRX-001"
  }
  ```

---

## 🛠️ Kontrol Menu Perangkat Keras (ESP32 Keypad)

Interaksi fisik pada modul brankas diatur melalui menu keypad 4x4 berikut:

* **Tombol `A` (Akses Brankas)**:
  1. Menempelkan Sidik Jari (dibatasi maks 3x percobaan).
  2. Jika berhasil, masukkan PIN 6-digit diakhiri tombol `#`.
  3. Relay solenoid aktif selama 5 detik lalu mengunci kembali secara otomatis.
* **Tombol `B` (Registrasi User Baru)**:
  1. Input ID Sidik Jari target (1-8).
  2. Tempel jari 2x untuk pembuatan template sidik jari lokal ESP32.
  3. Masukkan PIN baru 6-digit untuk didaftarkan ke server.
* **Tombol `C` (Hapus/Format Memori)**:
  1. Konfirmasi penghapusan database sidik jari dengan menekan `#`.
  2. Menghapus database internal sensor sidik jari ESP32.
  3. Mengirim permintaan sinkronisasi untuk menghapus semua data pengguna (role: 'user') di database web Laravel.
