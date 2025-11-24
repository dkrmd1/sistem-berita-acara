<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// ============================================
// ROUTES UNTUK GUEST (BELUM LOGIN)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ============================================
// ROUTES UNTUK USER YANG SUDAH LOGIN
// ============================================
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ============================================
    // NOTIFICATION ROUTES
    // ============================================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
        Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    });

    // ============================================
    // PROFILE ROUTES (Semua user bisa akses)
    // ============================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'updateProfile'])->name('update');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/upload-ttd', [ProfileController::class, 'uploadTTD'])->name('upload-ttd');
        Route::delete('/delete-ttd', [ProfileController::class, 'deleteTTD'])->name('delete-ttd');
    });
    
    // ============================================
    // ADMIN ROUTES (User Management, Backup, Logs, SETTINGS)
    // ============================================
    Route::middleware('role:admin')->group(function () {
        
        // 1. User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::patch('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
            Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        // 2. Backup & Restore
        Route::prefix('backup')->name('backup.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::post('/create', [BackupController::class, 'createBackup'])->name('create');
            Route::post('/upload', [BackupController::class, 'uploadBackup'])->name('upload');
            Route::get('/download/{filename}', [BackupController::class, 'downloadBackup'])->name('download');
            Route::post('/restore/{filename}', [BackupController::class, 'restore'])->name('restore');
            Route::delete('/delete/{filename}', [BackupController::class, 'deleteBackup'])->name('delete');
        });

        // 3. Activity Logs (Riwayat Login)
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // 4. System Settings (Pengaturan Nomor BA)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/', [SettingController::class, 'update'])->name('update');
        });
    });
    
    // ============================================
    // NASABAH ROUTES (UPDATED)
    // ============================================
    Route::prefix('nasabah')->name('nasabah.')->group(function () {
        Route::get('/', [NasabahController::class, 'index'])->name('index');
        Route::get('/{id}', [NasabahController::class, 'show'])->name('show');
        
        // Hanya CS yang boleh Import, Edit, Update, dan Delete
        Route::middleware('role:cs')->group(function () {
            Route::get('/import/form', [NasabahController::class, 'showImport'])->name('import.form');
            Route::post('/import', [NasabahController::class, 'import'])->name('import');
            Route::get('/template/download', [NasabahController::class, 'downloadTemplate'])->name('template.download');
            
            // === ROUTE EDIT & UPDATE DITAMBAHKAN DI SINI ===
            Route::get('/{id}/edit', [NasabahController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NasabahController::class, 'update'])->name('update');
            // ===============================================

            Route::delete('/{id}', [NasabahController::class, 'destroy'])->name('destroy');
        });
    });
    
    // ============================================
    // BERITA ACARA ROUTES
    // ============================================
    Route::prefix('berita-acara')->name('berita-acara.')->group(function () {
        Route::get('/', [BeritaAcaraController::class, 'index'])->name('index');
        Route::get('/{id}', [BeritaAcaraController::class, 'show'])->name('show');
        Route::get('/{id}/view-pdf', [BeritaAcaraController::class, 'viewPDF'])->name('view-pdf');
        Route::get('/{id}/print', [BeritaAcaraController::class, 'printPDF'])->name('print');
        Route::get('/{id}/download', [BeritaAcaraController::class, 'downloadPDF'])->name('download');
        
        Route::middleware('role:cs')->group(function () {
            Route::get('/create/pilih-nasabah', [BeritaAcaraController::class, 'create'])->name('create');
            Route::get('/create/form/{nasabahId}', [BeritaAcaraController::class, 'createForm'])->name('create.form');
            Route::post('/store', [BeritaAcaraController::class, 'store'])->name('store');
        });
        
        Route::middleware('role:group_head,direktur_utama,direktur')->group(function () {
            Route::post('/{id}/approve', [BeritaAcaraController::class, 'approve'])->name('approve');
        });
    });
});

Route::fallback(function () {
    return view('errors.404');
});