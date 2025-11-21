<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeritaAcara;
use App\Models\Nasabah;
use App\Models\User;
use App\Notifications\NewBeritaAcaraNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;

class BeritaAcaraController extends Controller
{
    // ... (index dan create method tetap sama) ...
    
    public function index(Request $request)
    {
        $query = BeritaAcara::with(['nasabah', 'creator', 'approver', 'approvedBy']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $user = Auth::user();
        
        if ($user->isApprover() && $request->filter == 'my') {
            $query->where('approver_id', $user->id)->where('status', 'pending');
        }
        
        if ($user->isCS() && $request->filter == 'my') {
            $query->where('created_by', $user->id);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_ba', 'like', "%{$search}%")
                  ->orWhereHas('nasabah', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        
        $beritaAcaras = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('berita-acara.index', compact('beritaAcaras'));
    }

    public function create(Request $request)
    {
        if (!Auth::user()->isCS()) {
            return redirect()->route('berita-acara.index')
                ->with('error', 'Hanya Customer Service yang dapat membuat Berita Acara');
        }
        
        $query = Nasabah::belumPunyaBeritaAcara();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('ktp', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%");
            });
        }
        
        $nasabahs = $query->orderBy('nama')->paginate(20);
        
        return view('berita-acara.create', compact('nasabahs'));
    }

    /**
     * Halaman form isi data BA
     * UPDATE: Cek TTD CS Disini
     */
    public function createForm($nasabahId)
    {
        if (!Auth::user()->isCS()) {
            return redirect()->route('berita-acara.index');
        }

        // === CEK TTD CS ===
        // Pastikan kolom di tabel users adalah 'ttd_path' atau sesuaikan
        if (empty(Auth::user()->ttd_path)) {
            return redirect()->route('berita-acara.create')
                ->with('ttd_missing', 'Anda belum mengupload tanda tangan digital.');
        }
        
        $nasabah = Nasabah::findOrFail($nasabahId);
        
        if ($nasabah->has_berita_acara) {
            return redirect()->route('berita-acara.create')
                ->with('error', 'Nasabah ini sudah memiliki Berita Acara!');
        }
        
        $approvers = User::approvers()->get();
        
        return view('berita-acara.form', compact('nasabah', 'approvers'));
    }

    /**
     * Simpan BA baru ke database
     * UPDATE: Double Check TTD CS
     */
    public function store(Request $request)
    {
        // === CEK TTD CS ===
        if (empty(Auth::user()->ttd_path)) {
            return redirect()->route('berita-acara.create')
                ->with('ttd_missing', 'Anda belum mengupload tanda tangan digital.');
        }

        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'tanggal_ba' => 'required|date',
            'approver_id' => 'required|exists:users,id',
            'watchlist_match' => 'required|boolean',
            'existing_match' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        try {
            $nasabah = Nasabah::findOrFail($request->nasabah_id);
            
            if ($nasabah->has_berita_acara) {
                return back()->with('error', 'Nasabah ini sudah memiliki Berita Acara!');
            }
            
            $nomorBA = BeritaAcara::generateNomorBA();
            
            $beritaAcara = BeritaAcara::create([
                'nasabah_id' => $nasabah->id,
                'nomor_ba' => $nomorBA,
                'tanggal_ba' => $request->tanggal_ba,
                'status' => 'pending',
                'created_by' => Auth::id(),
                'approver_id' => $request->approver_id,
                'watchlist_match' => $request->watchlist_match,
                'existing_match' => $request->existing_match,
                'notes' => $request->notes,
            ]);
            
            $nasabah->markHasBeritaAcara();
            $this->generatePDF($beritaAcara);
            
            $approver = User::find($request->approver_id);
            if ($approver) {
                $approver->notify(new NewBeritaAcaraNotification($beritaAcara));
            }
            
            return redirect()->route('berita-acara.show', $beritaAcara->id)
                ->with('success', 'Berita Acara berhasil dibuat dan dikirim ke Approver!');
            
        } catch (\Exception $e) {
            Log::error('Error creating BA: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat Berita Acara: ' . $e->getMessage());
        }
    }

    /**
     * Approve BA
     * UPDATE: Cek TTD Approver
     */
    public function approve($id)
    {
        $user = Auth::user();
        
        if (!$user->isApprover()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk approve!');
        }

        // === CEK TTD APPROVER ===
        if (empty($user->ttd_path)) {
            return back()->with('ttd_missing', 'Anda harus mengupload Tanda Tangan sebelum melakukan Approval.');
        }
        
        try {
            $beritaAcara = BeritaAcara::findOrFail($id);
            
            if (!$beritaAcara->canBeApprovedBy($user->id)) {
                return back()->with('error', 'Berita Acara ini tidak dapat di-approve oleh Anda!');
            }
            
            $beritaAcara->approve($user->id);
            
            // Regenerate PDF (memasukkan TTD ke dalam PDF)
            $this->generatePDF($beritaAcara, true);

            $creator = User::find($beritaAcara->created_by);
            // if ($creator) $creator->notify(new BeritaAcaraApprovedNotification($beritaAcara));
            
            return back()->with('success', 'Berita Acara berhasil di-approve!');
            
        } catch (\Exception $e) {
            Log::error('Error approving BA: ' . $e->getMessage());
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }
    }

    // ... (Method show, reject, viewPDF, downloadPDF, ensureFreshPDF, generatePDF tetap sama) ...
    public function show($id)
    {
        $beritaAcara = BeritaAcara::with(['nasabah', 'creator', 'approver', 'approvedBy'])
            ->findOrFail($id);
        return view('berita-acara.show', compact('beritaAcara'));
    }

    public function reject(Request $request, $id)
    {
        // Reject tidak perlu TTD, jadi tidak perlu validasi TTD disini
        $request->validate(['notes' => 'required|string']);
        $user = Auth::user();
        // ... (kode reject tetap sama) ...
        if (!$user->isApprover()) return back()->with('error', 'Akses ditolak');
        $beritaAcara = BeritaAcara::findOrFail($id);
        $beritaAcara->reject($request->notes);
        $beritaAcara->nasabah->markBelumPunyaBeritaAcara();
        return back()->with('success', 'Berita Acara berhasil ditolak.');
    }
    
    public function viewPDF($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $this->ensureFreshPDF($beritaAcara);
        return response()->file(storage_path('app/' . $beritaAcara->pdf_path));
    }
    
    public function downloadPDF($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $this->ensureFreshPDF($beritaAcara);
        return response()->download(storage_path('app/' . $beritaAcara->pdf_path), 'BA_' . str_replace('/', '_', $beritaAcara->nomor_ba) . '.pdf');
    }

    private function ensureFreshPDF($beritaAcara, $forceRegenerate = false) {
        if (!$beritaAcara->pdf_path || !Storage::exists($beritaAcara->pdf_path) || $forceRegenerate) {
            $this->generatePDF($beritaAcara, true);
        }
    }

    private function generatePDF($beritaAcara, $forceRegenerate = false) {
         // ... (kode generate PDF tetap sama, pastikan passing data TTD ke view PDF) ...
         // Pastikan view 'berita-acara.pdf' Anda mengambil gambar TTD dari user->ttd_path
         // Contoh di controller: 
         $beritaAcara->load(['nasabah', 'creator', 'approver', 'approvedBy']);
         $html = view('berita-acara.pdf', compact('beritaAcara'))->render();
         // ... logic mpdf simpan file ...
         // Mockup simple return untuk contoh controller ini:
         $path = 'berita-acara/BA_' . $beritaAcara->id . '.pdf';
         // Asumsi storage logic...
         $beritaAcara->update(['pdf_path' => $path]);
         return $path;
    }
}