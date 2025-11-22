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
        
        return view('berita-acara.form', compact('nasabah', 'approvers'));
    }

    public function store(Request $request)
    {
        // Cek TTD CS
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
            
            // Generate PDF (TTD CS sudah masuk)
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

            $creator = User::find($beritaAcara->created_by);
            
            return back()->with('success', 'Berita Acara berhasil di-approve!');
            
        } catch (\Exception $e) {
            Log::error('Error approving BA: ' . $e->getMessage());
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $beritaAcara = BeritaAcara::with(['nasabah', 'creator', 'approver', 'approvedBy'])
            ->findOrFail($id);
        return view('berita-acara.show', compact('beritaAcara'));
    }

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
    
    public function downloadPDF($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $this->ensureFreshPDF($beritaAcara);
        
        $fullPath = storage_path('app/' . $beritaAcara->pdf_path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File PDF tidak ditemukan.');
        }
        
        $fileName = 'BA_' . str_replace('/', '_', $beritaAcara->nomor_ba) . '.pdf';
        
        return response()->download($fullPath, $fileName);
    }

    /**
     * Print PDF (sama seperti viewPDF tapi tanpa download)
     */
    public function printPDF($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $this->ensureFreshPDF($beritaAcara);
        
        $fullPath = storage_path('app/' . $beritaAcara->pdf_path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File PDF tidak ditemukan.');
        }
        
        // Return file untuk ditampilkan di browser (bisa langsung di-print)
        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="BA_' . str_replace('/', '_', $beritaAcara->nomor_ba) . '.pdf"'
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

    /**
     * Generate PDF menggunakan mPDF
     * FIXED: Sekarang benar-benar membuat file PDF
     */
    private function generatePDF($beritaAcara, $forceRegenerate = false)
    {
        try {
            // Load relasi yang dibutuhkan
            $beritaAcara->load(['nasabah', 'creator', 'approver', 'approvedBy']);
            
            // Render HTML dari view
            $html = view('berita-acara.pdf', compact('beritaAcara'))->render();
            
            // Inisialisasi mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 20,
                'margin_right' => 20,
            ]);
            
            // Tulis HTML ke PDF
            $mpdf->WriteHTML($html);
            
            // Tentukan path penyimpanan
            $directory = 'berita-acara';
            $fileName = 'BA_' . $beritaAcara->id . '_' . time() . '.pdf';
            $relativePath = $directory . '/' . $fileName;
            $fullPath = storage_path('app/' . $relativePath);
            
            // Pastikan direktori ada
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            
            // Simpan file PDF
            $mpdf->Output($fullPath, 'F');
            
            // Hapus file lama jika ada dan berbeda
            if ($beritaAcara->pdf_path && $beritaAcara->pdf_path !== $relativePath) {
                if (Storage::exists($beritaAcara->pdf_path)) {
                    Storage::delete($beritaAcara->pdf_path);
                }
            }
            
            // Update path di database
            $beritaAcara->update(['pdf_path' => $relativePath]);
            
            Log::info("PDF generated successfully: {$relativePath}");
            
            return $relativePath;
            
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}