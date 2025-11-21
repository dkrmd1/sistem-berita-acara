<?php
// File: app/Http/Controllers/DashboardController.php
// UPDATE ISI FILE

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\BeritaAcara;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistik umum
        $totalNasabah = Nasabah::count();
        $nasabahBelumBA = Nasabah::belumPunyaBeritaAcara()->count();
        $nasabahSudahBA = Nasabah::sudahPunyaBeritaAcara()->count();
        
        // Statistik Berita Acara
        $totalBA = BeritaAcara::count();
        $baPending = BeritaAcara::pending()->count();
        $baApproved = BeritaAcara::approved()->count();
        $baRejected = BeritaAcara::rejected()->count();
        
        // Berita Acara terbaru
        $recentBA = BeritaAcara::with(['nasabah', 'creator', 'approver'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Data spesifik berdasarkan role
        $myData = null;
        
        if ($user->isCS()) {
            // CS: Lihat BA yang dia buat
            $myData = BeritaAcara::where('created_by', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        if ($user->isApprover()) {
            // Approver: Lihat BA yang menunggu approval dari dia
            $myData = BeritaAcara::where('approver_id', $user->id)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('dashboard.index', compact(
            'totalNasabah',
            'nasabahBelumBA',
            'nasabahSudahBA',
            'totalBA',
            'baPending',
            'baApproved',
            'baRejected',
            'recentBA',
            'myData'
        ));
    }
}
?>