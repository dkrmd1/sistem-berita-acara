<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat Tabel Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Insert Default Setting (Auto Generate ON by default)
        DB::table('settings')->insert([
            'key' => 'auto_generate_ba',
            'value' => '1', // 1 = ON, 0 = OFF
            'description' => 'Mengaktifkan fitur generate nomor BA otomatis',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Ubah nomor_ba jadi Nullable (Boleh Kosong)
        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->string('nomor_ba')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->string('nomor_ba')->nullable(false)->change();
        });
    }
};