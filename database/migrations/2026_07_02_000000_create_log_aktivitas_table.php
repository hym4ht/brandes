<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('ktp_nik')->nullable();
            $table->string('ktp_nama')->nullable();
            $table->string('kk_no')->nullable();
            $table->string('kk_nama_kepala')->nullable();
            $table->string('akte_no')->nullable();
            
            // Kolom untuk menyimpan nama file (atau path file di storage public)
            $table->string('file_ktp')->nullable();
            $table->string('file_kk')->nullable();
            $table->string('file_akte')->nullable();
            
            // Status dokumen independen
            $table->enum('status_ktp', ['Tersedia', 'Diambil'])->default('Tersedia');
            $table->enum('status_kk', ['Tersedia', 'Diambil'])->default('Tersedia');
            $table->enum('status_akte', ['Tersedia', 'Diambil'])->default('Tersedia');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
