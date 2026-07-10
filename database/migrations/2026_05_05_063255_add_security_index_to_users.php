<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk menambahkan fitur Personal Security Index.
     * Kolom ini menyimpan skor reputasi keamanan user (0-100%).
     * Berkesinambungan dengan fitur auto-deaktivasi akun jika skor mencapai 0.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('security_index')->default(100)->after('is_superadmin'); 
            // Default 100% saat user baru dibuat
        });
    }

    /**
     * Membatalkan migration dengan menghapus kolom security_index.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('security_index');
        });
    }
};
