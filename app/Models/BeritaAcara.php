<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $fillable = [
        'nasabah_id',
        'nomor_ba',
        'tanggal_ba',
        'status',
        'created_by',
        'approver_id',
        'approved_by',
        'approved_at',
        'watchlist_match',
        'existing_match',
        'pdf_path',
        'notes',
    ];

    protected $casts = [
        'tanggal_ba' => 'date',
        'approved_at' => 'datetime',
        'watchlist_match' => 'boolean',
        'existing_match' => 'boolean',
    ];

    // Relasi
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeWaitingApproval($query, $approverId)
    {
        return $query->where('status', 'pending')
                     ->where('approver_id', $approverId);
    }

    // Status Methods
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Menunggu Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => $this->status,
        };
    }

    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function canBeApprovedBy($userId)
    {
        return $this->status === 'pending' && $this->approver_id == $userId;
    }

    // Actions
    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    public function reject($notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'notes' => $notes,
        ]);
    }

    // Helper Methods - DIPERBAIKI
    public function getWatchlistResult()
    {
        // Pengecekan null-safe
        return ($this->watchlist_match ?? false) ? 'TERDAPAT' : 'TIDAK TERDAPAT';
    }

    public function getExistingResult()
    {
        // Pengecekan null-safe
        return ($this->existing_match ?? false) ? 'TERDAPAT' : 'TIDAK TERDAPAT';
    }

    public function getTanggalBaFormatted()
    {
        return $this->tanggal_ba ? $this->tanggal_ba->format('d-M-Y') : '-';
    }

    // Generate Nomor BA
    public static function generateNomorBA()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastBA = self::whereYear('tanggal_ba', $year)
                      ->whereMonth('tanggal_ba', $month)
                      ->orderBy('id', 'desc')
                      ->first();
        
        $nextNumber = $lastBA ? (intval(substr($lastBA->nomor_ba, -4)) + 1) : 1;
        
        return sprintf('BA/%s/%s/%04d', $year, $month, $nextNumber);
    }
}