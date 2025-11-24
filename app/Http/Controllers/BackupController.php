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
    // ... method index, uploadBackup, downloadBackup, deleteBackup TETAP SAMA ...
    
    public function index()
    {
        $backups = $this->getBackupList();
        $stats = [
            'users' => DB::table('users')->count(),
            'nasabah' => DB::table('nasabahs')->count(),
            'berita_acara' => DB::table('berita_acaras')->count(),
            'notifications' => DB::table('notifications')->count(),
        ];
        return view('backup.index', compact('backups', 'stats'));
    }

    public function createBackup(Request $request)
    {
        try {
            set_time_limit(0); // Unlimited time untuk backup besar
            ini_set('memory_limit', '-1'); // Unlimited memory
            
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $backupName = "backup_{$timestamp}";
            $backupPath = storage_path("app/backups/{$backupName}");

            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // 1. Backup Database
            $this->backupDatabase($backupPath);

            // 2. Backup Files (TTD & PDF)
            $this->backupFiles($backupPath);

            // 3. Compress menjadi ZIP
            $zipPath = $this->createZipArchive($backupPath, $backupName);

            // 4. Hapus folder temporary
            File::deleteDirectory($backupPath);

            return redirect()->back()->with('success', '✅ Backup berhasil dibuat! File: ' . basename($zipPath));

        } catch (\Exception $e) {
            \Log::error('Backup Error: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Gagal membuat backup: ' . $e->getMessage());
        }
    }

    public function uploadBackup(Request $request)
    {
        // ... (Kode sama seperti sebelumnya) ...
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
        $path = storage_path("app/backups/{$filename}");
        if (!File::exists($path)) return redirect()->back()->with('error', 'File tidak ditemukan!');
        return response()->download($path);
    }

    public function restore(Request $request, $filename)
    {
        try {
            set_time_limit(0); // Unlimited time
            ini_set('memory_limit', '-1'); // Unlimited memory
            
            $zipPath = storage_path("app/backups/{$filename}");

            if (!File::exists($zipPath)) {
                return redirect()->back()->with('error', 'File backup tidak ditemukan!');
            }

            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== TRUE) {
                return redirect()->back()->with('error', '❌ File backup corrupt!');
            }
            
            // Extract ZIP
            $extractPath = storage_path("app/backups/temp_restore_" . time());
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }
            
            $zip->extractTo($extractPath);
            $zip->close();

            // Validasi struktur
            if (!File::exists($extractPath . '/database.sql')) {
                File::deleteDirectory($extractPath);
                return redirect()->back()->with('error', '❌ File database.sql tidak ditemukan.');
            }

            // 1. Restore Database (INI YANG DIPERBAIKI)
            $this->restoreDatabase($extractPath);

            // 2. Restore Files
            $this->restoreFiles($extractPath);

            // 3. Cleanup
            File::deleteDirectory($extractPath);

            return redirect()->back()->with('success', '✅ Restore berhasil! Data telah dipulihkan.');

        } catch (\Exception $e) {
            \Log::error('Restore Error: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Gagal restore: ' . $e->getMessage());
        }
    }

    public function deleteBackup($filename)
    {
        $path = storage_path("app/backups/{$filename}");
        if (File::exists($path)) {
            File::delete($path);
            return redirect()->back()->with('success', '✅ Backup dihapus!');
        }
        return redirect()->back()->with('error', 'File tidak ditemukan!');
    }

    // ==================== PRIVATE METHODS ====================

    private function backupDatabase($backupPath)
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        $tableKey = "Tables_in_{$dbName}";

        $sql = "-- Backup generated: " . Carbon::now() . "\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;
            
            // DROP TABLE
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            
            // CREATE TABLE
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
            $sql .= $createTable->{'Create Table'} . ";\n\n";
            
            // INSERT DATA
            // Menggunakan chunking untuk hemat memori saat backup tabel besar
            DB::table($tableName)->orderByRaw('1')->chunk(100, function ($rows) use (&$sql, $tableName) {
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if (is_null($value)) {
                            $values[] = 'NULL';
                        } else {
                            // addslashes penting untuk handle quote
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

    /**
     * Restore database dari SQL file (DIPERBAIKI)
     */
    private function restoreDatabase($extractPath)
    {
        $sqlFile = $extractPath . '/database.sql';

        if (!File::exists($sqlFile)) {
            throw new \Exception('File database.sql tidak ditemukan!');
        }

        // Matikan query log agar RAM tidak penuh
        DB::disableQueryLog();
        
        // Baca seluruh isi file
        $sql = File::get($sqlFile);

        // FIX: Jangan di-explode berdasarkan ';'. 
        // Langsung eksekusi seluruh perintah SQL secara raw (unprepared).
        // Ini akan menangani data yang mengandung ';' dengan benar.
        
        try {
            DB::unprepared($sql);
        } catch (\Exception $e) {
            // Jika error packet too large, baru kita coba split cara aman (per baris insert)
            // Tapi DB::unprepared biasanya sudah handle multi-statements.
            throw new \Exception('Gagal eksekusi SQL: ' . $e->getMessage());
        }
    }

    private function backupFiles($backupPath)
    {
        // ... (Tetap sama) ...
        $filesPath = $backupPath . '/files';
        File::makeDirectory($filesPath, 0755, true);

        $ttdPath = storage_path('app/public/ttd');
        if (File::exists($ttdPath)) File::copyDirectory($ttdPath, $filesPath . '/ttd');

        $pdfPath = storage_path('app/public/berita_acara');
        if (File::exists($pdfPath)) File::copyDirectory($pdfPath, $filesPath . '/berita_acara');
    }

    private function createZipArchive($sourcePath, $backupName)
    {
        // ... (Tetap sama) ...
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
        // ... (Tetap sama) ...
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
        // ... (Tetap sama) ...
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
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) $bytes /= 1024;
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}