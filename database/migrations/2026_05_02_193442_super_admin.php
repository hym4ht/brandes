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
        // Set M Zaeni sebagai Super Admin secara otomatis
        \App\Models\User::where('username', 'zaeni')
            ->orWhere('username', 'zaenii')
            ->orWhere('nama', 'M Zaeni')
            ->update(['is_superadmin' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
