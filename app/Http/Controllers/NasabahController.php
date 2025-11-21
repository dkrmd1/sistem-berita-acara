<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        $query = Nasabah::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('ktp', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'belum') {
                $query->where('has_berita_acara', false);
            } elseif ($request->status == 'sudah') {
                $query->where('has_berita_acara', true);
            }
        }

        $nasabahs = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('nasabah.index', compact('nasabahs'));
    }

    public function show($id)
    {
        $nasabah = Nasabah::with('beritaAcaras.creator')->findOrFail($id);
        return view('nasabah.show', compact('nasabah'));
    }

    public function showImport()
    {
        return view('nasabah.import');
    }

    public function downloadTemplate()
    {
        // Cegah output buffer yang merusak file Excel
        if (ob_get_contents()) ob_end_clean();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // === HEADER ===
        $headers = ['Nama', 'KTP', 'NPWP', 'Tanggal Lahir', 'Negara'];
        $sheet->fromArray($headers, null, 'A1');
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '165581']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]]
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // === FORMAT KOLOM SEBAGAI TEXT (PENTING!) ===
        // Kolom B (KTP) & C (NPWP) diformat sebagai TEXT agar tidak jadi scientific notation
        $sheet->getStyle('B2:B1000')->getNumberFormat()->setFormatCode('@'); // @ = TEXT
        $sheet->getStyle('C2:C1000')->getNumberFormat()->setFormatCode('@'); // @ = TEXT
        
        // Kolom D (Tanggal Lahir) juga diformat sebagai TEXT untuk copy-paste mudah
        $sheet->getStyle('D2:D1000')->getNumberFormat()->setFormatCode('@'); // @ = TEXT
        
        // === DATA CONTOH (SEMUA SEBAGAI TEXT) ===
        $exampleData = [
            ['RUDI RUCHBANSAH', '3203112509720000', '273483404429000', '25-Sep-72', 'INDONESIA'],
            ['BUDI SANTOSO', '3201011234560001', '123456789012000', '15-Jan-85', 'INDONESIA'],
            ['SITI NURHALIZA', '3301025678900002', '987654321098000', '10-Mar-90', 'INDONESIA'],
        ];
        
        // Insert data dengan FORCE TEXT untuk SEMUA KOLOM
        $row = 2;
        foreach ($exampleData as $data) {
            // SEMUA kolom di-force sebagai TEXT
            $sheet->setCellValueExplicit('A' . $row, $data[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // Nama
            $sheet->setCellValueExplicit('B' . $row, $data[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // KTP
            $sheet->setCellValueExplicit('C' . $row, $data[2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // NPWP
            $sheet->setCellValueExplicit('D' . $row, $data[3], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // Tanggal
            $sheet->setCellValueExplicit('E' . $row, $data[4], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); // Negara
            
            $row++;
        }
        
        // === STYLING BODY ===
        $bodyStyle = [
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
            'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]
        ];
        $sheet->getStyle('A2:E4')->applyFromArray($bodyStyle);
        
        // === WIDTH KOLOM ===
        $sheet->getColumnDimension('A')->setWidth(25); // Nama
        $sheet->getColumnDimension('B')->setWidth(20); // KTP
        $sheet->getColumnDimension('C')->setWidth(20); // NPWP
        $sheet->getColumnDimension('D')->setWidth(15); // Tanggal Lahir
        $sheet->getColumnDimension('E')->setWidth(15); // Negara
        
        // === INSTRUKSI DI BAWAH ===
        $sheet->setCellValue('A6', '📌 CARA PENGGUNAAN:');
        $sheet->setCellValue('A7', '1. Hapus 3 baris contoh di atas (Baris 2-4)');
        $sheet->setCellValue('A8', '2. Copy-Paste data Anda LANGSUNG di baris 2 dan seterusnya');
        $sheet->setCellValue('A9', '3. JANGAN ubah format kolom! SEMUA kolom sudah diset sebagai TEXT');
        $sheet->setCellValue('A10', '4. Format Tanggal: 25-Sep-72, 15-Jan-85, 29-Des-99 (support Indonesia!)');
        $sheet->setCellValue('A11', '5. Tanggal boleh kosong jika tidak diketahui');
        $sheet->setCellValue('A12', '6. Upload file ini ke sistem');
        
        $sheet->getStyle('A6:A12')->getFont()->setBold(true)->setSize(10)->getColor()->setRGB('165581');
        $sheet->mergeCells('A6:E6');
        $sheet->mergeCells('A7:E7');
        $sheet->mergeCells('A8:E8');
        $sheet->mergeCells('A9:E9');
        $sheet->mergeCells('A10:E10');
        $sheet->mergeCells('A11:E11');
        $sheet->mergeCells('A12:E12');
        
        // === FREEZE HEADER ===
        $sheet->freezePane('A2');
        
        // === SAVE & DOWNLOAD ===
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Nasabah_' . date('Y-m-d_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib diupload',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (count($rows) < 2) {
                return redirect()->back()
                    ->with('error', 'File Excel kosong atau tidak memiliki data.')
                    ->with('warning', 'Pastikan file Excel memiliki minimal 1 baris header dan 1 baris data.');
            }

            $header = array_map('trim', array_map('strtolower', $rows[0]));
            
            $colMap = [
                'nama' => array_search('nama', $header),
                'ktp' => array_search('ktp', $header),
                'npwp' => array_search('npwp', $header),
                'tanggal_lahir' => array_search('tanggal lahir', $header),
                'negara' => array_search('negara', $header),
            ];

            if ($colMap['nama'] === false || $colMap['ktp'] === false) {
                return redirect()->back()
                    ->with('error', 'Format Header Excel Salah!')
                    ->with('warning', 'Pastikan header: Nama, KTP, NPWP, Tanggal Lahir, Negara ada.');
            }

            $successCount = 0;
            $skipCount = 0;
            $errorCount = 0;
            $errors = [];

            $processedKTPs = []; 
            $processedNPWPs = [];

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                $rowNumber = $i + 1;

                if (empty(array_filter($row))) continue;

                try {
                    $nama = isset($row[$colMap['nama']]) ? strtoupper(trim($row[$colMap['nama']])) : null;
                    $ktp = isset($row[$colMap['ktp']]) ? preg_replace('/\D/', '', $row[$colMap['ktp']]) : null;
                    $npwp = isset($row[$colMap['npwp']]) ? preg_replace('/\D/', '', $row[$colMap['npwp']]) : null;
                    $negara = isset($row[$colMap['negara']]) ? strtoupper(trim($row[$colMap['negara']])) : 'INDONESIA';
                    $tanggalLahirRaw = isset($row[$colMap['tanggal_lahir']]) ? $row[$colMap['tanggal_lahir']] : null;

                    $rowErrors = [];
                    if (empty($nama)) $rowErrors[] = "Nama kosong";
                    if (empty($ktp)) $rowErrors[] = "KTP kosong";
                    elseif (strlen($ktp) != 16) $rowErrors[] = "KTP harus 16 digit";

                    if (!empty($rowErrors)) {
                        $errorCount++;
                        $errors[] = "Baris {$rowNumber}: " . implode(', ', $rowErrors);
                        continue;
                    }

                    // Cek Duplikat Internal
                    if (in_array($ktp, $processedKTPs)) {
                        $skipCount++;
                        $errors[] = "Baris {$rowNumber} ({$nama}): SKIP - KTP ganda di file Excel.";
                        continue;
                    }
                    if (!empty($npwp) && in_array($npwp, $processedNPWPs)) {
                        $skipCount++;
                        $errors[] = "Baris {$rowNumber} ({$nama}): SKIP - NPWP ganda di file Excel.";
                        continue;
                    }

                    // Cek Duplikat Database
                    $queryCheck = Nasabah::where('ktp', $ktp);
                    if (!empty($npwp)) $queryCheck->orWhere('npwp', $npwp);
                    if (!empty($nama)) $queryCheck->orWhere('nama', $nama);

                    $existingData = $queryCheck->first();

                    if ($existingData) {
                        $skipCount++;
                        $reason = [];
                        if ($existingData->ktp == $ktp) $reason[] = "KTP sama";
                        if (!empty($npwp) && $existingData->npwp == $npwp) $reason[] = "NPWP sama";
                        if ($existingData->nama == $nama) $reason[] = "Nama sama";
                        
                        $errors[] = "Baris {$rowNumber} ({$nama}): DILEWATI - Data sudah ada (" . implode(' & ', $reason) . ").";
                        
                        $processedKTPs[] = $ktp; 
                        if(!empty($npwp)) $processedNPWPs[] = $npwp;
                        continue;
                    }

                    // Parse Tanggal Lahir
                    $tanggalLahir = null;
                    if ($tanggalLahirRaw) {
                        try {
                            if (is_numeric($tanggalLahirRaw)) {
                                // Format Excel Serial Number
                                $tanggalLahir = Date::excelToDateTimeObject($tanggalLahirRaw)->format('Y-m-d');
                            } else {
                                // Format Text - Support bahasa Indonesia
                                $tanggalLahirRaw = trim($tanggalLahirRaw);
                                
                                // Konversi bulan Indonesia ke Inggris
                                $bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Dec', 'Des'];
                                $bulanEng = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Dec'];
                                $tanggalLahirRaw = str_replace($bulanIndo, $bulanEng, $tanggalLahirRaw);
                                
                                // Try multiple formats
                                $possibleFormats = ['d-M-y', 'd-M-Y', 'd/m/Y', 'Y-m-d', 'd-m-Y', 'd/M/Y', 'd/M/y'];
                                foreach ($possibleFormats as $fmt) {
                                    try {
                                        $parsedDate = Carbon::createFromFormat($fmt, $tanggalLahirRaw);
                                        if ($parsedDate) {
                                            $tanggalLahir = $parsedDate->format('Y-m-d');
                                            break;
                                        }
                                    } catch (\Exception $e) { continue; }
                                }
                                
                                // Last attempt: Carbon auto-parse
                                if (!$tanggalLahir) {
                                    $tanggalLahir = Carbon::parse($tanggalLahirRaw)->format('Y-m-d');
                                }
                            }
                        } catch (\Exception $e) {
                            // Jika gagal parse, biarkan null (jika nullable)
                            $tanggalLahir = null;
                        }
                    }

                    Nasabah::create([
                        'nama' => $nama,
                        'ktp' => $ktp,
                        'npwp' => $npwp,
                        'tanggal_lahir' => $tanggalLahir,
                        'negara' => $negara,
                        'has_berita_acara' => false,
                    ]);

                    $successCount++;
                    $processedKTPs[] = $ktp;
                    if(!empty($npwp)) $processedNPWPs[] = $npwp;

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Baris {$rowNumber}: Error Sistem - " . $e->getMessage();
                }
            }

            $message = "Proses Import Selesai. ";
            $message .= "✅ Berhasil: {$successCount} Data. ";
            $message .= "⛔ Dilewati (Sudah Ada): {$skipCount} Data.";

            if ($skipCount > 0 || $errorCount > 0) {
                if ($errorCount > 0) $message .= " ❌ Gagal: {$errorCount} Data.";
                return redirect()->route('nasabah.import.form')
                    ->with('warning', $message)
                    ->with('errors_detail', $errors);
            }

            return redirect()->route('nasabah.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        if ($nasabah->has_berita_acara) {
            return redirect()->back()->with('error', 'Nasabah tidak dapat dihapus karena sudah memiliki Berita Acara!');
        }
        $nasabah->delete();
        return redirect()->route('nasabah.index')->with('success', 'Data nasabah berhasil dihapus!');
    }
}