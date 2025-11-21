<?php

// File: app/Models/Nasabah.php
// BUAT FILE BARU

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Nasabah extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'ktp',
        'npwp',
        'tanggal_lahir',
        'negara',
        'has_berita_acara',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'has_berita_acara' => 'boolean',
    ];

    /**
     * Relasi: Berita Acara yang dimiliki nasabah ini
     */
    public function beritaAcaras()
    {
        return $this->hasMany(BeritaAcara::class);
    }

    /**
     * Relasi: Berita Acara terbaru
     */
    public function latestBeritaAcara()
    {
        return $this->hasOne(BeritaAcara::class)->latestOfMany();
    }

    /**
     * Scope: Nasabah yang belum punya berita acara
     */
    public function scopeBelumPunyaBeritaAcara($query)
    {
        return $query->where('has_berita_acara', false);
    }

    /**
     * Scope: Nasabah yang sudah punya berita acara
     */
    public function scopeSudahPunyaBeritaAcara($query)
    {
        return $query->where('has_berita_acara', true);
    }

    /**
     * Get tanggal lahir dalam format Indonesia (dd-MM-yyyy)
     */
    public function getTanggalLahirFormatted()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d-M-Y') : '-';
    }

    /**
     * Get umur nasabah
     */
    public function getUmur()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    /**
     * Format KTP dengan spasi untuk tampilan
     */
    public function getKtpFormatted()
    {
        // Format: 3203 1125 0972 0003
        if (strlen($this->ktp) == 16) {
            return substr($this->ktp, 0, 4) . ' ' . 
                   substr($this->ktp, 4, 4) . ' ' . 
                   substr($this->ktp, 8, 4) . ' ' . 
                   substr($this->ktp, 12, 4);
        }
        return $this->ktp;
    }

    /**
     * Format NPWP dengan spasi untuk tampilan
     */
    public function getNpwpFormatted()
    {
        // Format: 27.348.340.4-429.000
        if (strlen($this->npwp) == 15) {
            return substr($this->npwp, 0, 2) . '.' . 
                   substr($this->npwp, 2, 3) . '.' . 
                   substr($this->npwp, 5, 3) . '.' . 
                   substr($this->npwp, 8, 1) . '-' . 
                   substr($this->npwp, 9, 3) . '.' . 
                   substr($this->npwp, 12, 3);
        }
        return $this->npwp;
    }

    /**
     * Mark nasabah sudah punya berita acara
     */
    public function markHasBeritaAcara()
    {
        $this->update(['has_berita_acara' => true]);
    }

    /**
     * Mark nasabah belum punya berita acara
     */
    public function markBelumPunyaBeritaAcara()
    {
        $this->update(['has_berita_acara' => false]);
    }
}