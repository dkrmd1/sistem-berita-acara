<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeritaAcara;
use App\Models\Nasabah;
use App\Models\User;
use App\Models\Setting; // Pastikan Model Setting ada
use App\Notifications\NewBeritaAcaraNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;

class BeritaAcaraController extends Controller
{
    /**
     * Menampilkan daftar Berita Acara
     */
    public function index(Request $request)
    {
        $query = BeritaAcara::with(['nasabah', 'creator', 'approver', 'approvedBy']);
        
        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $user = Auth::user();
        
        // Filter My Tasks (Approver)
        if ($user->isApprover() && $request->filter == 'my') {
            $query->where('approver_id', $user->id)->where('status', 'pending');
        }
        
        // Filter My Created (CS)
        if ($user->isCS() && $request->filter == 'my') {
            $query->where('created_by', $user->id);
        }
        
        // Search
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

    /**
     * Halaman pilih nasabah untuk buat BA baru
     */
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
     * Form pengisian data BA
     */
    public function createForm($nasabahId)
    {
        if (!Auth::user()->isCS()) {
            return redirect()->route('berita-acara.index');
        }

        // Cek TTD CS
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

        // AMBIL SETTING DARI DATABASE (Default ON/1 jika tidak ada)
        $isAutoEnabled = Setting::getValue('auto_generate_ba', '1') == '1';
        
        return view('berita-acara.form', compact('nasabah', 'approvers', 'isAutoEnabled'));
    }

    /**
     * Simpan data BA ke Database
     */
    public function store(Request $request)
    {
        // Cek TTD CS lagi
        if (empty(Auth::user()->ttd_path)) {
            return redirect()->route('berita-acara.create')->with('ttd_missing', 'Upload TTD dulu.');
        }

        // AMBIL SETTING
        $isAutoEnabled = Setting::getValue('auto_generate_ba', '1') == '1';

        // 1. Validasi
        $rules = [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'tanggal_ba' => 'required|date',
            'approver_id' => 'required|exists:users,id',
            'watchlist_match' => 'required|boolean',
            'existing_match' => 'required|boolean',
            'notes' => 'nullable|string',
        ];

        // Validasi Manual Input (Hanya jika diisi, cek unique)
        if ($request->filled('manual_nomor_ba')) {
            $rules['manual_nomor_ba'] = 'string|unique:berita_acaras,nomor_ba';
        }

        $request->validate($rules, [
            'manual_nomor_ba.unique' => 'Nomor BA tersebut sudah digunakan.',
        ]);

        try {
            $nasabah = Nasabah::findOrFail($request->nasabah_id);
            if ($nasabah->has_berita_acara) return back()->with('error', 'Nasabah sudah punya BA!');
            
            // 2. LOGIC PENENTUAN NOMOR
            $nomorBA = null; // Default NULL (Kosong)

            if ($isAutoEnabled) {
                // KONDISI: AUTO GENERATE AKTIF (ON)
                // Cek apakah user mencentang "Pakai Manual"?
                if ($request->has('use_manual_ba') && $request->use_manual_ba == '1') {
                    // User pilih manual saat Auto ON
                    $nomorBA = $request->filled('manual_nomor_ba') ? strtoupper(trim($request->manual_nomor_ba)) : null;
                } else {
                    // User pilih Otomatis
                    $nomorBA = BeritaAcara::generateNomorBA();
                }
            } else {
                // KONDISI: AUTO GENERATE MATI (OFF)
                // Paksa ambil dari input manual (meskipun kosong)
                $nomorBA = $request->filled('manual_nomor_ba') ? strtoupper(trim($request->manual_nomor_ba)) : null;
            }
            
            // 3. Simpan
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
            
            // Generate PDF awal
            $this->generatePDF($beritaAcara);
            
            $approver = User::find($request->approver_id);
            if ($approver) {
                $approver->notify(new NewBeritaAcaraNotification($beritaAcara));
            }
            
            return redirect()->route('berita-acara.show', $beritaAcara->id)
                ->with('success', 'Berita Acara berhasil dibuat dan dikirim ke Approver!');
            
        } catch (\Exception $e) {
            Log::error('Error creating BA: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat Berita Acara: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan Detail BA
     */
    public function show($id)
    {
        $beritaAcara = BeritaAcara::with(['nasabah', 'creator', 'approver', 'approvedBy'])
            ->findOrFail($id);
        return view('berita-acara.show', compact('beritaAcara'));
    }

    /**
     * Proses Approval oleh Approver
     */
    public function approve($id)
    {
        $user = Auth::user();
        
        if (!$user->isApprover()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk approve!');
        }

        // Cek TTD Approver
        if (empty($user->ttd_path)) {
            return back()->with('ttd_missing', 'Anda harus mengupload Tanda Tangan sebelum melakukan Approval.');
        }
        
        try {
            $beritaAcara = BeritaAcara::findOrFail($id);
            
            if (!$beritaAcara->canBeApprovedBy($user->id)) {
                return back()->with('error', 'Berita Acara ini tidak dapat di-approve oleh Anda!');
            }
            
            $beritaAcara->approve($user->id);
            
            // Regenerate PDF dengan TTD Approver
            $this->generatePDF($beritaAcara, true);

            return back()->with('success', 'Berita Acara berhasil di-approve!');
            
        } catch (\Exception $e) {
            Log::error('Error approving BA: ' . $e->getMessage());
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }
    }

    /**
     * Proses Reject oleh Approver
     */
    public function reject(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string']);
        $user = Auth::user();
        
        if (!$user->isApprover()) {
            return back()->with('error', 'Akses ditolak');
        }
        
        $beritaAcara = BeritaAcara::findOrFail($id);
        $beritaAcara->reject($request->notes);
        $beritaAcara->nasabah->markBelumPunyaBeritaAcara();
        
        return back()->with('success', 'Berita Acara berhasil ditolak.');
    }
    
    /**
     * View PDF di browser
     */
    public function viewPDF($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $this->ensureFreshPDF($beritaAcara);
        
        $fullPath = storage_path('app/' . $beritaAcara->pdf_path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File PDF tidak ditemukan.');
        }
        
        return response()->file($fullPath);
    }
    
    /**
     * Download PDF
     */
    public function downloadPDF($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $this->ensureFreshPDF($beritaAcara);
        
        $fullPath = storage_path('app/' . $beritaAcara->pdf_path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File PDF tidak ditemukan.');
        }
        
        // Handle nama file jika nomor_ba NULL
        $nomor = $beritaAcara->nomor_ba ? str_replace('/', '_', $beritaAcara->nomor_ba) : 'TANPA_NOMOR_' . $beritaAcara->id;
        $fileName = 'BA_' . $nomor . '.pdf';
        
        return response()->download($fullPath, $fileName);
    }

    /**
     * Print PDF (Inline)
     */
    public function printPDF($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $this->ensureFreshPDF($beritaAcara);
        
        $fullPath = storage_path('app/' . $beritaAcara->pdf_path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File PDF tidak ditemukan.');
        }
        
        $nomor = $beritaAcara->nomor_ba ? str_replace('/', '_', $beritaAcara->nomor_ba) : 'TANPA_NOMOR_' . $beritaAcara->id;

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="BA_' . $nomor . '.pdf"'
        ]);
    }

    private function ensureFreshPDF($beritaAcara, $forceRegenerate = false)
    {
        $needsRegenerate = false;
        
        if (!$beritaAcara->pdf_path) {
            $needsRegenerate = true;
        } elseif (!Storage::exists($beritaAcara->pdf_path)) {
            $needsRegenerate = true;
        } elseif ($forceRegenerate) {
            $needsRegenerate = true;
        }
        
        if ($needsRegenerate) {
            $this->generatePDF($beritaAcara, true);
        }
    }

    private function generatePDF($beritaAcara, $forceRegenerate = false)
    {
        try {
            $beritaAcara->load(['nasabah', 'creator', 'approver', 'approvedBy']);
            
            $html = view('berita-acara.pdf', compact('beritaAcara'))->render();
            
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 20,
                'margin_right' => 20,
            ]);
            
            $mpdf->WriteHTML($html);
            
            $directory = 'berita-acara';
            $fileName = 'BA_' . $beritaAcara->id . '_' . time() . '.pdf';
            $relativePath = $directory . '/' . $fileName;
            $fullPath = storage_path('app/' . $relativePath);
            
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            
            $mpdf->Output($fullPath, 'F');
            
            if ($beritaAcara->pdf_path && $beritaAcara->pdf_path !== $relativePath) {
                if (Storage::exists($beritaAcara->pdf_path)) {
                    Storage::delete($beritaAcara->pdf_path);
                }
            }
            
            $beritaAcara->update(['pdf_path' => $relativePath]);
            
            return $relativePath;
            
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            throw $e;
        }
    }
}