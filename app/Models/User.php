<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // PENTING: Untuk fitur notifikasi
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         // admin, cs, group_head, direktur_utama, direktur
        'nip',
        'jabatan',      // Nama jabatan lengkap untuk ditampilkan di BA
        'ttd_path',     // Path file tanda tangan (opsional)
        'is_active',    // Status aktif user
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * =========================================
     * RELASI DATABASE
     * =========================================
     */

    // User (CS) yang membuat Berita Acara
    public function beritaAcarasCreated()
    {
        return $this->hasMany(\App\Models\BeritaAcara::class, 'created_by');
    }

    // User (Pejabat) yang ditunjuk sebagai Approver saat pembuatan
    public function beritaAcarasAsApprover()
    {
        return $this->hasMany(\App\Models\BeritaAcara::class, 'approver_id');
    }

    // User (Pejabat) yang benar-benar melakukan Approval (bisa jadi beda jika ada delegasi)
    public function beritaAcarasApproved()
    {
        return $this->hasMany(\App\Models\BeritaAcara::class, 'approved_by');
    }

    /**
     * =========================================
     * CHECK ROLE & PERMISSIONS
     * =========================================
     */

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCS()
    {
        return $this->role === 'cs';
    }

    public function isGroupHead()
    {
        return $this->role === 'group_head';
    }

    public function isDirekturUtama()
    {
        return $this->role === 'direktur_utama';
    }

    public function isDirektur()
    {
        return $this->role === 'direktur';
    }

    // Cek apakah user termasuk jajaran yang boleh Approve dokumen
    public function isApprover()
    {
        return in_array($this->role, ['group_head', 'direktur_utama', 'direktur']);
    }

    /**
     * =========================================
     * HELPERS & ACCESSORS
     * =========================================
     */

    // Mendapatkan URL Tanda Tangan (Untuk ditampilkan di <img src="">)
    // Cara pakai: $user->ttd_url
    public function getTtdUrlAttribute()
    {
        if ($this->ttd_path && Storage::disk('public')->exists($this->ttd_path)) {
            return asset('storage/' . $this->ttd_path);
        }
        
        // Return null atau placeholder jika tidak ada TTD
        return null; 
    }

    // Label Role yang enak dibaca manusia
    public function getRoleLabel()
    {
        return match($this->role) {
            'admin' => 'Administrator (IT)',
            'cs' => 'Customer Service (Checker)',
            'group_head' => 'Group Head (Approver Utama)',
            'direktur_utama' => 'Direktur Utama',
            'direktur' => 'Direktur',
            default => ucfirst($this->role),
        };
    }

    /**
     * =========================================
     * QUERY SCOPES
     * =========================================
     */

    // Scope untuk mengambil hanya user yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk mengambil list Approver (Untuk Dropdown di Form BA)
    // Diurutkan berdasarkan prioritas: GH -> Dirut -> Direktur
    public static function scopeApprovers($query)
    {
        return $query->whereIn('role', ['group_head', 'direktur_utama', 'direktur'])
                     ->where('is_active', true)
                     ->orderByRaw("FIELD(role, 'group_head', 'direktur_utama', 'direktur')");
    }
}