<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel 'lokasi_history'.
     * Tabel ini digunakan untuk menyimpan seluruh riwayat pelacakan posisi (GPS tracking) 
     * dan metrik guncangan/pergerakan fisik (movement metrics) brankas secara detail.
     */
    public function up(): void
    {
        Schema::create('lokasi_history', function (Blueprint $table) {
            // 1. Primary Key
            $table->id();

            // 2. Relasi ke Master Data Brankas
            $table->foreignId('lokasi_brankas_id')
                  ->constrained('lokasi_brankas')
                  ->onDelete('cascade');                                             // Relasi brankas (jika master brankas dihapus, seluruh histori pelacakannya ikut terhapus)

            // 3. Titik Koordinat & Elevasi (GPS)
            $table->decimal('latitude', 10, 8);                                      // Titik koordinat garis lintang
            $table->decimal('longitude', 11, 8);                                     // Titik koordinat garis bujur
            $table->decimal('altitude', 8, 2)->nullable();                           // Ketinggian dari permukaan laut (dalam meter)

            // 4. Kualitas Sinyal & Sensor Satelit
            $table->decimal('hdop', 5, 2)->nullable();                               // Tingkat akurasi pembacaan GPS (Horizontal Dilution of Precision)
            $table->unsignedTinyInteger('satellites')->nullable();                   // Jumlah satelit yang berhasil terkunci oleh antena perangkat
            $table->tinyInteger('fix_quality')->default(0);                          // Indikator kualitas sinyal (0 = Invalid, 1 = GPS fix, 2 = DGPS)

            // 5. Metrik Pergerakan Fisik (Physical Movement Metrics)
            $table->decimal('speed_kmh', 6, 2)->nullable();                          // Physical movement speed (km/h)
            $table->decimal('getaran', 5, 2)->nullable();                            // Vibration/Impact sensor value
            $table->enum('status', ['normal', 'waspada', 'bahaya'])->default('normal'); // Security status based on movement/vibration reading

            // 6. Data Log Diagnostik
            $table->text('raw_nmea')->nullable();                                    // Menyimpan string mentah (raw data) NMEA langsung dari modul GPS untuk debugging

            // 7. Metadata Waktu & Indexing
            $table->timestamp('recorded_at')->useCurrent();                          // Waktu aktual saat data posisi ini direkam oleh sensor
            $table->timestamps();                                                    // Waktu data masuk/diupdate di database server (created_at & updated_at)

            // Index tambahan untuk mengoptimalkan performa kueri/pencarian riwayat lokasi brankas
            $table->index(['lokasi_brankas_id', 'recorded_at']);
        });
    }

    /**
     * Membatalkan migration dengan menghapus tabel 'lokasi_history'.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_history');
    }
};
