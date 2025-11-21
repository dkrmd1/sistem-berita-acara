<?php

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_nasabahs_table.php
// BUAT FILE BARU, kemudian GANTI ISI FILE dengan kode ini

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
        Schema::create('nasabahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('ktp', 16)->unique();
            $table->string('npwp', 15);
            $table->date('tanggal_lahir');
            $table->string('negara')->default('INDONESIA');
            $table->boolean('has_berita_acara')->default(false);
            $table->timestamps();
            
            $table->index('has_berita_acara');
            $table->index('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabahs');
    }
};