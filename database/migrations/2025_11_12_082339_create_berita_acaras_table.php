<?php

// File: database/migrations/xxxx_create_berita_acaras_table.php
// GANTI ISI FILE dengan kode ini

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->string('nomor_ba')->unique();
            $table->date('tanggal_ba');
            
            // Status: pending -> approved
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Data Checker (CS)
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('created_at')->nullable();
            
            // Data Approver (Group Head / Direktur Utama / Direktur)
            $table->foreignId('approver_id')->nullable()->constrained('users'); // Yang dipilih CS untuk approve
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Yang sudah approve
            $table->timestamp('approved_at')->nullable();
            
            // Hasil Pengecekan
            $table->boolean('watchlist_match')->default(false);
            $table->boolean('existing_match')->default(false);
            
            // File PDF
            $table->text('pdf_path')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->timestamp('updated_at')->nullable();
            
            $table->index('status');
            $table->index('tanggal_ba');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};