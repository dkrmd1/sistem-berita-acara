<?php

// File: database/migrations/2014_10_12_000000_create_users_table.php
// GANTI SEMUA ISI FILE - SUDAH FIX!

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
            // ✅ FIX: Tambahkan 'admin' dan 'nip' di enum
            $table->enum('role', ['admin', 'cs', 'group_head', 'direktur_utama', 'direktur'])->default('cs');
            
            $table->string('nip')->nullable()->unique(); // ✅ Tambah field NIP yang kurang
            $table->string('jabatan')->nullable();
            $table->text('ttd_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};