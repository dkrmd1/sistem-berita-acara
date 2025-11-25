<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use Carbon\Carbon;

class BackupController extends Controller
{
    // ==================== UPDATE BAGIAN INI ====================
    public function index()
    {
        $backups = $this->getBackupList();

        // 1. Hitung Ukuran Real Database (Query ke MySQL)
        try {
            $dbName = env('DB_DATABASE');
            $query = DB::select("SELECT sum(data_length + index_length) as size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
            $dbSizeBytes = $query[0]->size ?? 0;
            $dbSize = $this->formatBytes($dbSizeBytes);
        } catch (\Exception $e) {
            $dbSize = 'Error';
        }

        // 2. Hitung Ukuran Real File (TTD + Berita Acara)
        $filesSizeBytes = 0;
        // Hitung folder TTD
        $filesSizeBytes += $this->getFolderSize(storage_path('app/public/ttd'));
        // Hitung folder Berita Acara
        $filesSizeBytes += $this->getFolderSize(storage_path('app/public/berita_acara'));
        
        $fileSize = $this->formatBytes($filesSizeBytes);

        // 3. Ambil Tanggal Backup Terakhir
        // Karena $backups sudah disortir descending di getBackupList(), index 0 adalah yang terbaru
        $lastBackupDate = '-';
        if (count($backups) > 0) {
            $lastBackupDate = $backups[0]['date']->format('d M Y (H:i)');
        }

        // 4. Statistik count (Tetap dipertahankan)
        $stats = [
            'users' => DB::table('users')->count(),
            'nasabah' => DB::table('nasabahs')->count(),
            'berita_acara' => DB::table('berita_acaras')->count(),
            'notifications' => DB::table('notifications')->count(),
        ];

        // KIRIM SEMUA DATA KE VIEW
        return view('backup.index', compact('backups', 'stats', 'dbSize', 'fileSize', 'lastBackupDate'));
    }
    // ==================== END UPDATE ====================

    public function createBackup(Request $request)
    {
        // ... (KODE TETAP SAMA SEPERTI SEBELUMNYA) ...
        try {
            set_time_limit(0); 
            ini_set('memory_limit', '-1');
            
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $backupName = "backup_{$timestamp}";
            $backupPath = storage_path("app/backups/{$backupName}");

            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            $this->backupDatabase($backupPath);
            $this->backupFiles($backupPath);
            $zipPath = $this->createZipArchive($backupPath, $backupName);
            File::deleteDirectory($backupPath);

            return redirect()->back()->with('success', '✅ Backup berhasil dibuat! File: ' . basename($zipPath));

        } catch (\Exception $e) {
            \Log::error('Backup Error: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function uploadBackup(Request $request)
    {
        // ... (KODE TETAP SAMA) ...
        try {
            $validator = Validator::make($request->all(), [
                'backup_file' => 'required|file|mimes:zip|max:512000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $file = $request->file('backup_file');
            $originalName = $file->getClientOriginalName();
            
            if (!preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{6}\.zip$/', $originalName)) {
                return redirect()->back()->with('error', '❌ Format nama file tidak valid!');
            }

            if (File::exists(storage_path("app/backups/{$originalName}"))) {
                return redirect()->back()->with('error', '❌ File backup sudah ada!');
            }

            $file->move(storage_path('app/backups'), $originalName);

            return redirect()->back()->with('success', '✅ File backup berhasil diupload!');

        } catch (\Exception $e) {
            \Log::error('Upload Backup Error: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Gagal upload backup: ' . $e->getMessage());
        }
    }

    public function downloadBackup($filename)
    {
        // ... (KODE TETAP SAMA) ...
        $path = storage_path("app/backups/{$filename}");
        if (!File::exists($path)) return redirect()->back()->with('error', 'File tidak ditemukan!');
        return response()->download($path);
    }

    public function restore(Request $request, $filename)
    {
        // ... (KODE TETAP SAMA) ...
        try {
            set_time_limit(0);
            ini_set('memory_limit', '-1');
            
            $zipPath = storage_path("app/backups/{$filename}");

            if (!File::exists($zipPath)) {
                return redirect()->back()->with('error', 'File backup tidak ditemukan!');
            }

            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== TRUE) {
                return redirect()->back()->with('error', '❌ File backup corrupt!');
            }
            
            $extractPath = storage_path("app/backups/temp_restore_" . time());
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }
            
            $zip->extractTo($extractPath);
            $zip->close();

            if (!File::exists($extractPath . '/database.sql')) {
                File::deleteDirectory($extractPath);
                return redirect()->back()->with('error', '❌ File database.sql tidak ditemukan.');
            }

            $this->restoreDatabase($extractPath);
            $this->restoreFiles($extractPath);
            File::deleteDirectory($extractPath);

            return redirect()->back()->with('success', '✅ Restore berhasil! Data telah dipulihkan.');

        } catch (\Exception $e) {
            \Log::error('Restore Error: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Gagal restore: ' . $e->getMessage());
        }
    }

    public function deleteBackup($filename)
    {
        // ... (KODE TETAP SAMA) ...
        $path = storage_path("app/backups/{$filename}");
        if (File::exists($path)) {
            File::delete($path);
            return redirect()->back()->with('success', '✅ Backup dihapus!');
        }
        return redirect()->back()->with('error', 'File tidak ditemukan!');
    }

    // ==================== PRIVATE METHODS ====================

    // --- METHOD BARU: UNTUK MENGHITUNG FOLDER SIZE ---
    private function getFolderSize($path)
    {
        $size = 0;
        if (File::exists($path)) {
            foreach (File::allFiles($path) as $file) {
                $size += $file->getSize();
            }
        }
        return $size;
    }
    // ------------------------------------------------

    private function backupDatabase($backupPath)
    {
        // ... (KODE TETAP SAMA) ...
        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        $tableKey = "Tables_in_{$dbName}";

        $sql = "-- Backup generated: " . Carbon::now() . "\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
            $sql .= $createTable->{'Create Table'} . ";\n\n";
            
            DB::table($tableName)->orderByRaw('1')->chunk(100, function ($rows) use (&$sql, $tableName) {
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                }
            });
            $sql .= "\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        File::put($backupPath . '/database.sql', $sql);
    }

    private function restoreDatabase($extractPath)
    {
        // ... (KODE TETAP SAMA) ...
        $sqlFile = $extractPath . '/database.sql';
        if (!File::exists($sqlFile)) {
            throw new \Exception('File database.sql tidak ditemukan!');
        }
        DB::disableQueryLog();
        $sql = File::get($sqlFile);
        try {
            DB::unprepared($sql);
        } catch (\Exception $e) {
            throw new \Exception('Gagal eksekusi SQL: ' . $e->getMessage());
        }
    }

    private function backupFiles($backupPath)
    {
        // ... (KODE TETAP SAMA) ...
        $filesPath = $backupPath . '/files';
        File::makeDirectory($filesPath, 0755, true);

        $ttdPath = storage_path('app/public/ttd');
        if (File::exists($ttdPath)) File::copyDirectory($ttdPath, $filesPath . '/ttd');

        $pdfPath = storage_path('app/public/berita_acara');
        if (File::exists($pdfPath)) File::copyDirectory($pdfPath, $filesPath . '/berita_acara');
    }

    private function createZipArchive($sourcePath, $backupName)
    {
        // ... (KODE TETAP SAMA) ...
        $zipPath = storage_path("app/backups/{$backupName}.zip");
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sourcePath), \RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($sourcePath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
        return $zipPath;
    }

    private function restoreFiles($extractPath)
    {
        // ... (KODE TETAP SAMA) ...
        $filesPath = $extractPath . '/files';
        if (!File::exists($filesPath)) return;

        if (File::exists($filesPath . '/ttd')) {
            $target = storage_path('app/public/ttd');
            File::deleteDirectory($target);
            File::copyDirectory($filesPath . '/ttd', $target);
        }

        if (File::exists($filesPath . '/berita_acara')) {
            $target = storage_path('app/public/berita_acara');
            File::deleteDirectory($target);
            File::copyDirectory($filesPath . '/berita_acara', $target);
        }
    }

    private function getBackupList()
    {
        // ... (KODE TETAP SAMA) ...
        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
            return [];
        }
        $files = File::files($backupPath);
        $backups = [];
        foreach ($files as $file) {
            if ($file->getExtension() === 'zip') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => Carbon::createFromTimestamp($file->getMTime()),
                ];
            }
        }
        usort($backups, function($a, $b) { return $b['date']->timestamp - $a['date']->timestamp; });
        return $backups;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes == 0) return "0 B";
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) $bytes /= 1024;
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}