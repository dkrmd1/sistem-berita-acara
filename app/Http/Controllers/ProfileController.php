<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update Profile Information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
        ]);

        try {
            $user->update($validated);
            
            \Log::info("Profile updated", [
                'user_id' => $user->id,
                'name' => $user->name
            ]);
            
            return back()->with('success', 'Profil berhasil diperbarui!');
            
        } catch (\Exception $e) {
            \Log::error("Profile update failed: " . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        try {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
            
            \Log::info("Password changed", ['user_id' => $user->id]);
            
            return back()->with('success', 'Password berhasil diubah!');
            
        } catch (\Exception $e) {
            \Log::error("Password update failed: " . $e->getMessage());
            return back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }

    /**
     * Upload TTD - AUTO CONVERT TO JPG
     */
    public function uploadTTD(Request $request)
    {
        $request->validate([
            'ttd' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'ttd.required' => 'File tanda tangan harus diupload',
            'ttd.image' => 'File harus berupa gambar',
            'ttd.mimes' => 'Format file harus PNG, JPG, atau JPEG',
            'ttd.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            $user = Auth::user();
            
            // Hapus TTD lama
            if ($user->ttd_path) {
                Storage::delete($user->ttd_path);
            }
            
            $file = $request->file('ttd');
            $filename = 'ttd_' . $user->id . '_' . time() . '.jpg';
            $storagePath = storage_path('app/public/ttd/' . $filename);
            
            // Create directory if not exists
            if (!file_exists(storage_path('app/public/ttd'))) {
                mkdir(storage_path('app/public/ttd'), 0755, true);
            }
            
            // Get image info
            $imageInfo = getimagesize($file->getPathname());
            $mimeType = $imageInfo['mime'];
            
            // Konversi ke JPG
            if ($mimeType === 'image/png') {
                $img = imagecreatefrompng($file->getPathname());
                $width = imagesx($img);
                $height = imagesy($img);
                $newImg = imagecreatetruecolor($width, $height);
                $white = imagecolorallocate($newImg, 255, 255, 255);
                imagefill($newImg, 0, 0, $white);
                imagecopy($newImg, $img, 0, 0, 0, 0, $width, $height);
                imagejpeg($newImg, $storagePath, 90);
                imagedestroy($img);
                imagedestroy($newImg);
            } else {
                move_uploaded_file($file->getPathname(), $storagePath);
            }
            
            // Update database
            $path = 'public/ttd/' . $filename;
            $user->update(['ttd_path' => $path]);
            
            \Log::info("TTD uploaded", ['user_id' => $user->id]);
            
            return back()->with('success', 'Tanda tangan berhasil diupload!');
            
        } catch (\Exception $e) {
            \Log::error("TTD upload failed: " . $e->getMessage());
            return back()->with('error', 'Gagal upload tanda tangan: ' . $e->getMessage());
        }
    }

    public function deleteTTD()
    {
        try {
            $user = Auth::user();
            
            if ($user->ttd_path) {
                Storage::delete($user->ttd_path);
                \Log::info("TTD deleted", ['user_id' => $user->id]);
            }
            
            $user->update(['ttd_path' => null]);
            
            return back()->with('success', 'Tanda tangan berhasil dihapus!');
            
        } catch (\Exception $e) {
            \Log::error("TTD delete failed: " . $e->getMessage());
            return back()->with('error', 'Gagal hapus tanda tangan: ' . $e->getMessage());
        }
    }
}