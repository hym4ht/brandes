<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk menambahkan metrik GPS tingkat lanjut.
     *
     * Migration ini bertujuan melengkapi tabel 'lokasi_brankas' dengan 
     * data pelacakan presisi tinggi dari modul GPS (ketinggian, sinyal satelit, dll).
     */
    public function up(): void
    {
        Schema::table('lokasi_brankas', function (Blueprint $table) {
            // 1. Data Elevasi & Presisi Koordinat
            $table->decimal('altitude', 8, 2)->nullable()->after('longitude');       // Ketinggian dari permukaan laut (dalam meter)
            $table->decimal('hdop', 5, 2)->nullable()->after('altitude');            // Horizontal Dilution of Precision (tingkat akurasi GPS)

            // 2. Kualitas Sinyal & Sensor
            $table->unsignedTinyInteger('satellites')->nullable()->after('hdop');    // Jumlah satelit yang terkunci/berhasil terhubung oleh antena GPS
            $table->decimal('speed_kmh', 6, 2)->nullable()->after('satellites');     // Kecepatan pergerakan fisik brankas (jika brankas berpindah lokasi)
            $table->tinyInteger('fix_quality')->default(0)->after('speed_kmh');      // Indikator status kualitas sinyal (0 = Invalid, 1 = GPS fix, 2 = DGPS)

            // 3. Sinkronisasi Waktu Perangkat
            $table->timestamp('last_gps_update')->nullable()->after('fix_quality');  // Waktu aktual pembaruan data koordinat terakhir langsung dari modul GPS
        });
    }

    /**
     * Membatalkan migration dengan menghapus seluruh kolom metrik GPS tambahan.
     */
    public function down(): void
    {
        Schema::table('lokasi_brankas', function (Blueprint $table) {
            // Menghapus kolom yang ditambahkan pada method up()
            $table->dropColumn([
                'altitude',
                'hdop',
                'satellites',
                'speed_kmh',
                'fix_quality',
                'last_gps_update',
            ]);
        });
    }
};
