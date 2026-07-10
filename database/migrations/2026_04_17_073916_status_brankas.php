<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk menambahkan indikator status real-time.
     *
     * Migration ini bertujuan melengkapi tabel 'lokasi_brankas' dengan 
     * informasi status fisik (pintu) dan status konektivitas perangkat IoT (ESP32).
     */
    public function up(): void
    {
        Schema::table('lokasi_brankas', function (Blueprint $table) {
            // 1. Status Perangkat Keras Fisik
            $table->enum('status_pintu', ['TERKUNCI', 'TERBUKA'])->default('TERKUNCI')->after('status'); // Mendeteksi apakah pintu brankas secara fisik sedang terbuka/terkunci

            // 2. Status Jaringan & Konektivitas
            $table->boolean('is_online')->default(false)->after('status_pintu');                         // Indikator aktif/tidaknya alat terhubung ke server/WiFi
            $table->timestamp('last_seen')->nullable()->after('is_online');                              // Waktu terakhir kali alat mengirimkan data (heartbeat) ke server
        });
    }

    /**
     * Membatalkan migration dengan menghapus kolom indikator status real-time.
     */
    public function down(): void
    {
        Schema::table('lokasi_brankas', function (Blueprint $table) {
            // Menghapus kolom yang ditambahkan pada method up()
            $table->dropColumn([
                'status_pintu', 
                'is_online', 
                'last_seen'
            ]);
        });
    }
};
