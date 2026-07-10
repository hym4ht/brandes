<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'lokasi_brankas'.
     * Tabel ini digunakan untuk menyimpan data master unit brankas, 
     * mencakup informasi lokasi fisik, GPS coordinates, dan real-time status.
     */
    public function up(): void
    {
        Schema::create('lokasi_brankas', function (Blueprint $table) {
            // 1. Primary Key
            $table->id();

            // 2. Identitas Brankas (Device Identity)
            $table->string('nama_brankas');                                               // Nama unit (contoh: "Brankas Utama")
            $table->string('kode_brankas')->unique();                                     // Unique code for synchronization with IoT Device (ESP32)

            // 3. Informasi Lokasi (Geographic & Physical Info)
            $table->string('lokasi');                                                     // Detail fisik (contoh: "Ruang Direktur Lantai 3")
            $table->decimal('latitude', 10, 8)->nullable();                               // Latitude coordinate
            $table->decimal('longitude', 11, 8)->nullable();                              // Longitude coordinate

            // 4. Status Operasional (Operational & Security Status)
            $table->enum('status', ['aman', 'terbuka', 'peringatan'])->default('aman');   // Real-time security status (auto-detected by system)
            $table->text('keterangan')->nullable();                                       // Additional notes or descriptions
            $table->boolean('aktif')->default(true);                                      // Flag status: true (Active), false (Maintenance/Inactive)

            // 5. Metadata Waktu
            $table->timestamps();                                                         // Waktu record dibuat (created_at) dan diupdate (updated_at)
        });
    }

    /**
     * Membatalkan migration dengan menghapus tabel 'lokasi_brankas'.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_brankas');
    }
};
