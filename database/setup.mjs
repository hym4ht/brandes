/**
 * Script untuk membuat dan mengisi database SQLite
 * Jalankan dengan: bun database/setup.mjs
 */

import { Database } from 'bun:sqlite';
import { createHash } from 'crypto';

// Bun memiliki bcrypt built-in via password API
const dbPath = new URL('./database.sqlite', import.meta.url).pathname;
const db = new Database(dbPath);

// Enable foreign keys
db.run('PRAGMA foreign_keys = ON');

console.log('🗄️  Membuat tabel database SQLite...\n');

// ─── MIGRATIONS TABLE ───────────────────────────────────────────────────────
db.run(`
  CREATE TABLE IF NOT EXISTS migrations (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    migration TEXT    NOT NULL,
    batch     INTEGER NOT NULL
  )
`);

// ─── USERS TABLE ────────────────────────────────────────────────────────────
// fingerprint_id: INTEGER — nomor slot sensor biometrik AS608/R307 pada ESP32 (1–127)
db.run(`
  CREATE TABLE IF NOT EXISTS users (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    nama           TEXT    NOT NULL,
    username       TEXT    NOT NULL UNIQUE,
    pin            TEXT    NOT NULL,
    fingerprint_id INTEGER UNIQUE,
    role           TEXT    NOT NULL DEFAULT 'user' CHECK(role IN ('admin','user')),
    aktif          INTEGER NOT NULL DEFAULT 1,
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);
console.log('✅ Tabel users dibuat');

// ─── CACHE TABLE ────────────────────────────────────────────────────────────
db.run(`
  CREATE TABLE IF NOT EXISTS cache (
    key        TEXT    PRIMARY KEY,
    value      TEXT    NOT NULL,
    expiration INTEGER NOT NULL
  )
`);
db.run(`
  CREATE TABLE IF NOT EXISTS cache_locks (
    key        TEXT    PRIMARY KEY,
    owner      TEXT    NOT NULL,
    expiration INTEGER NOT NULL
  )
`);
console.log('✅ Tabel cache & cache_locks dibuat');

// ─── JOBS TABLE ─────────────────────────────────────────────────────────────
db.run(`
  CREATE TABLE IF NOT EXISTS jobs (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    queue        TEXT    NOT NULL,
    payload      TEXT    NOT NULL,
    attempts     INTEGER NOT NULL DEFAULT 0,
    reserved_at  INTEGER,
    available_at INTEGER NOT NULL,
    created_at   INTEGER NOT NULL
  )
`);
db.run(`CREATE INDEX IF NOT EXISTS jobs_queue_index ON jobs(queue)`);
db.run(`
  CREATE TABLE IF NOT EXISTS job_batches (
    id             TEXT    PRIMARY KEY,
    name           TEXT    NOT NULL,
    total_jobs     INTEGER NOT NULL,
    pending_jobs   INTEGER NOT NULL,
    failed_jobs    INTEGER NOT NULL,
    failed_job_ids TEXT    NOT NULL,
    options        TEXT,
    cancelled_at   INTEGER,
    created_at     INTEGER NOT NULL,
    finished_at    INTEGER
  )
`);
db.run(`
  CREATE TABLE IF NOT EXISTS failed_jobs (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid       TEXT    NOT NULL UNIQUE,
    connection TEXT    NOT NULL,
    queue      TEXT    NOT NULL,
    payload    TEXT    NOT NULL,
    exception  TEXT    NOT NULL,
    failed_at  DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);
console.log('✅ Tabel jobs, job_batches, failed_jobs dibuat');

// ─── SESSIONS TABLE ──────────────────────────────────────────────────────────
db.run(`
  CREATE TABLE IF NOT EXISTS sessions (
    id            TEXT    PRIMARY KEY,
    user_id       INTEGER,
    ip_address    TEXT,
    user_agent    TEXT,
    payload       TEXT    NOT NULL,
    last_activity INTEGER NOT NULL
  )
`);
db.run(`CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions(user_id)`);
db.run(`CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions(last_activity)`);
console.log('✅ Tabel sessions dibuat');

// ─── HISTORY AKSES TABLE ─────────────────────────────────────────────────────
db.run(`
  CREATE TABLE IF NOT EXISTS history_akses (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id        INTEGER REFERENCES users(id) ON DELETE SET NULL,
    nama           TEXT,
    aktivitas      TEXT    NOT NULL,
    status         TEXT    NOT NULL DEFAULT 'Berhasil' CHECK(status IN ('Berhasil','Gagal')),
    fingerprint_id INTEGER,
    waktu          DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);
console.log('✅ Tabel history_akses dibuat');

// ─── NOTIFIKASI KEAMANAN TABLE ───────────────────────────────────────────────
db.run(`
  CREATE TABLE IF NOT EXISTS notifikasi_keamanan (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    judul      TEXT    NOT NULL,
    pesan      TEXT    NOT NULL,
    tipe       TEXT    NOT NULL DEFAULT 'info' CHECK(tipe IN ('warning','danger','info','success')),
    dibaca     INTEGER NOT NULL DEFAULT 0,
    user_id    INTEGER REFERENCES users(id) ON DELETE SET NULL,
    waktu      DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);
console.log('✅ Tabel notifikasi_keamanan dibuat');

// ─── LOKASI BRANKAS TABLE ────────────────────────────────────────────────────
// Kolom GPS Neo-6M: altitude, hdop, satellites, speed_kmh, fix_quality, last_gps_update
db.run(`
  CREATE TABLE IF NOT EXISTS lokasi_brankas (
    id               INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_brankas     TEXT    NOT NULL,
    lokasi           TEXT    NOT NULL,
    kode_brankas     TEXT    NOT NULL UNIQUE,
    status           TEXT    NOT NULL DEFAULT 'aman' CHECK(status IN ('aman','terbuka','peringatan')),
    latitude         REAL,
    longitude        REAL,
    altitude         REAL,
    hdop             REAL,
    satellites       INTEGER,
    speed_kmh        REAL,
    fix_quality      INTEGER NOT NULL DEFAULT 0,
    last_gps_update  DATETIME,
    keterangan       TEXT,
    aktif            INTEGER NOT NULL DEFAULT 1,
    created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);
console.log('✅ Tabel lokasi_brankas dibuat');

// ─── LOKASI HISTORY TABLE ────────────────────────────────────────────────────
// Riwayat posisi GPS yang dikirim ESP32 + GPS Neo-6M
db.run(`
  CREATE TABLE IF NOT EXISTS lokasi_history (
    id                 INTEGER PRIMARY KEY AUTOINCREMENT,
    lokasi_brankas_id  INTEGER NOT NULL REFERENCES lokasi_brankas(id) ON DELETE CASCADE,
    latitude           REAL    NOT NULL,
    longitude          REAL    NOT NULL,
    altitude           REAL,
    hdop               REAL,
    satellites         INTEGER,
    fix_quality        INTEGER NOT NULL DEFAULT 0,
    speed_kmh          REAL,
    getaran            REAL,
    status             TEXT    NOT NULL DEFAULT 'normal' CHECK(status IN ('normal','waspada','bahaya')),
    raw_nmea           TEXT,
    recorded_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at         DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at         DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);
db.run(`CREATE INDEX IF NOT EXISTS lokasi_history_brankas_recorded_idx ON lokasi_history(lokasi_brankas_id, recorded_at)`);
console.log('✅ Tabel lokasi_history dibuat');

// ─── SEED DATA ───────────────────────────────────────────────────────────────
console.log('\n🌱 Mengisi data awal (seeder)...\n');

// Hash PIN menggunakan bcrypt via Bun.password
// fingerprint_id: nomor slot INTEGER sensor biometrik (1–127), null jika belum terdaftar
const users = [
  { nama: 'Fitriyah',       username: 'fitriyah',    pin: '123456', fingerprint_id: null, role: 'admin', aktif: 1 },
  { nama: 'M Zaeni',        username: 'zaeni',        pin: '150804', fingerprint_id: null, role: 'admin', aktif: 1 },
  { nama: 'Ikhwanudin F',   username: 'ikhwanudin',   pin: '678926', fingerprint_id: 1,    role: 'user',  aktif: 1 },
  { nama: 'Akhmad Sodikin', username: 'sodikin',      pin: '783543', fingerprint_id: 2,    role: 'user',  aktif: 1 },
  { nama: 'Sri Endang',     username: 'sri_endang',   pin: '985638', fingerprint_id: 3,    role: 'user',  aktif: 1 },
  { nama: 'Sutrisno',       username: 'sutrisno',     pin: '159036', fingerprint_id: 4,    role: 'user',  aktif: 1 },
  { nama: 'M Wakhidin',     username: 'wakhidin',     pin: '387678', fingerprint_id: 5,    role: 'user',  aktif: 1 },
  { nama: 'Joko Udiono',    username: 'joko',         pin: '687257', fingerprint_id: 6,    role: 'user',  aktif: 1 },
  { nama: 'Mashruri',       username: 'mashruri',     pin: '865478', fingerprint_id: 7,    role: 'user',  aktif: 1 },
  { nama: 'Masroi',         username: 'masroi',       pin: '237893', fingerprint_id: 8,    role: 'user',  aktif: 1 },
  { nama: 'Moh Zaeni',      username: 'moh_zaeni',    pin: '857487', fingerprint_id: 9,    role: 'user',  aktif: 1 },
];

const insertUser = db.prepare(`
  INSERT OR IGNORE INTO users (nama, username, pin, fingerprint_id, role, aktif)
  VALUES ($nama, $username, $pin, $fingerprint_id, $role, $aktif)
`);

for (const user of users) {
  const hashedPin = await Bun.password.hash(user.pin, { algorithm: 'bcrypt', cost: 12 });
  insertUser.run({
    $nama:           user.nama,
    $username:       user.username,
    $pin:            hashedPin,
    $fingerprint_id: user.fingerprint_id,
    $role:           user.role,
    $aktif:          user.aktif,
  });
  const fpLabel = user.fingerprint_id ? `Slot #${user.fingerprint_id}` : 'Belum terdaftar';
  console.log(`  ✅ User [${user.role}] ${user.nama} (${user.username}) — PIN: ${user.pin} — FP: ${fpLabel}`);
}

// ─── SEED LOKASI BRANKAS ─────────────────────────────────────────────────────
const insertLokasi = db.prepare(`
  INSERT OR IGNORE INTO lokasi_brankas (nama_brankas, lokasi, kode_brankas, status, latitude, longitude, keterangan, aktif)
  VALUES ($nama_brankas, $lokasi, $kode_brankas, $status, $latitude, $longitude, $keterangan, $aktif)
`);

insertLokasi.run({
  $nama_brankas: 'Brankas Balai Desa Bengle',
  $lokasi:       'Jl. Projosumarto II No. 16, Bengledukuh, Desa Bengle, Kecamatan Talang, Kabupaten Tegal',
  $kode_brankas: 'BRK-001',
  $status:       'aman',
  $latitude:     -6.91050000,
  $longitude:    109.14790000,
  $keterangan:   'Brankas utama penyimpanan dokumen penting Balai Desa Bengle',
  $aktif:        1,
});
insertLokasi.run({
  $nama_brankas: 'Brankas Cadangan',
  $lokasi:       'Ruang Server Lt. 2, Balai Desa Bengle',
  $kode_brankas: 'BRK-002',
  $status:       'aman',
  $latitude:     null,
  $longitude:    null,
  $keterangan:   'Brankas cadangan untuk backup dokumen',
  $aktif:        1,
});
console.log('\n  ✅ Data lokasi brankas ditambahkan');

// ─── SEED HISTORY AKSES ──────────────────────────────────────────────────────
const insertHistory = db.prepare(`
  INSERT INTO history_akses (user_id, nama, aktivitas, status, fingerprint_id, waktu)
  VALUES ($user_id, $nama, $aktivitas, $status, $fingerprint_id, $waktu)
`);

const historyData = [
  { user_id: 3, nama: 'Ikhwanudin F',   aktivitas: 'Membuka Brankas',       status: 'Berhasil', fingerprint_id: 1,    waktu: '2026-01-31 14:25:30' },
  { user_id: 5, nama: 'Sri Endang',     aktivitas: 'Membuka Brankas',       status: 'Berhasil', fingerprint_id: 3,    waktu: '2026-01-31 14:15:12' },
  { user_id: null, nama: 'Unknown',     aktivitas: 'Percobaan Akses Gagal', status: 'Gagal',    fingerprint_id: null, waktu: '2026-01-31 13:30:45' },
  { user_id: 4, nama: 'Akhmad Sodikin', aktivitas: 'Membuka Brankas',       status: 'Berhasil', fingerprint_id: 2,    waktu: '2026-01-31 12:45:20' },
  { user_id: 3, nama: 'Ikhwanudin F',   aktivitas: 'Membuka Brankas',       status: 'Berhasil', fingerprint_id: 1,    waktu: '2026-01-31 11:30:15' },
  { user_id: 7, nama: 'M Wakhidin',     aktivitas: 'Percobaan Akses Gagal', status: 'Gagal',    fingerprint_id: 5,    waktu: '2026-01-31 13:30:45' },
];

for (const h of historyData) {
  insertHistory.run({
    $user_id:        h.user_id,
    $nama:           h.nama,
    $aktivitas:      h.aktivitas,
    $status:         h.status,
    $fingerprint_id: h.fingerprint_id,
    $waktu:          h.waktu,
  });
}
console.log('  ✅ Data history akses ditambahkan');

// ─── SEED NOTIFIKASI ─────────────────────────────────────────────────────────
const insertNotif = db.prepare(`
  INSERT INTO notifikasi_keamanan (judul, pesan, tipe, dibaca, user_id)
  VALUES ($judul, $pesan, $tipe, $dibaca, $user_id)
`);

insertNotif.run({ $judul: 'Percobaan Akses Gagal', $pesan: 'Terdeteksi percobaan akses tidak sah pada brankas utama.', $tipe: 'danger',  $dibaca: 0, $user_id: null });
insertNotif.run({ $judul: 'Brankas Dibuka',        $pesan: 'Brankas utama berhasil dibuka oleh Ikhwanudin F.',          $tipe: 'success', $dibaca: 1, $user_id: 3    });
insertNotif.run({ $judul: 'Peringatan Keamanan',   $pesan: 'Sensor mendeteksi getaran tidak normal pada brankas.',      $tipe: 'warning', $dibaca: 0, $user_id: null });
console.log('  ✅ Data notifikasi keamanan ditambahkan');

// ─── RECORD MIGRATIONS ───────────────────────────────────────────────────────
const migrations = [
  '0001_01_01_000000_create_users_table',
  '0001_01_01_000001_create_cache_table',
  '0001_01_01_000002_create_jobs_table',
  '2026_02_25_092132_add_fingerprint_to_users_table',
  '2026_02_28_000001_create_history_akses_table',
  '2026_02_28_000002_create_notifikasi_keamanan_table',
  '2026_02_28_000003_create_lokasi_brankas_table',
  '2026_02_28_000004_change_fingerprint_id_type_in_users_table',
  '2026_02_28_000005_add_gps_columns_to_lokasi_brankas_table',
  '2026_02_28_000006_create_lokasi_history_table',
];

const insertMigration = db.prepare(`INSERT OR IGNORE INTO migrations (migration, batch) VALUES ($migration, 1)`);
for (const m of migrations) {
  insertMigration.run({ $migration: m });
}

db.close();

console.log('\n✅ Database SQLite berhasil dibuat dan diisi!');
console.log(`📁 Lokasi: ${dbPath}`);
console.log('\n📋 Ringkasan:');
console.log('   - 2 admin (fitriyah, zaeni)');
console.log('   - 9 user biasa (slot fingerprint 1–9)');
console.log('   - 2 lokasi brankas (BRK-001 dengan koordinat GPS, BRK-002 tanpa GPS)');
console.log('   - 6 history akses');
console.log('   - 3 notifikasi keamanan');
console.log('   - Tabel lokasi_history siap menerima data GPS Neo-6M dari ESP32');
