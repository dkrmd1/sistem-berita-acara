<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }
    
    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:cs,group_head,direktur_utama,direktur',
            'nip' => 'nullable|string|max:50|unique:users,nip',
            'jabatan' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'nip.unique' => 'NIP sudah terdaftar',
            'is_active.required' => 'Status akun wajib dipilih',
        ]);
        
        try {
            $validated['password'] = Hash::make($validated['password']);
            
            User::create($validated);
            
            \Log::info('User created by admin', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'new_user_email' => $validated['email'],
                'new_user_role' => $validated['role']
            ]);
            
            return redirect()->route('users.index')
                           ->with('success', 'User berhasil ditambahkan!');
                           
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return back()->withInput()
                        ->with('error', 'Gagal menambah user: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::with(['beritaAcarasCreated', 'beritaAcarasApproved'])->findOrFail($id);
        return view('users.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from editing their own account via this menu
        if ($user->id === auth()->id()) {
            return redirect()->route('profile.index')
                           ->with('info', 'Gunakan menu Profil untuk mengedit akun Anda sendiri.');
        }
        
        return view('users.edit', compact('user'));
    }
    
    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from updating their own account via this menu
        if ($user->id === auth()->id()) {
            return redirect()->route('profile.index')
                           ->with('error', 'Tidak dapat mengedit akun sendiri dari menu ini. Gunakan menu Profil.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:cs,group_head,direktur_utama,direktur',
            'nip' => 'nullable|string|max:50|unique:users,nip,' . $user->id,
            'jabatan' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'nip.unique' => 'NIP sudah terdaftar',
            'is_active.required' => 'Status akun wajib dipilih',
        ]);
        
        try {
            $oldData = $user->only(['name', 'email', 'role', 'is_active']);
            $user->update($validated);
            
            \Log::info('User updated by admin', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'updated_user_id' => $user->id,
                'updated_user_name' => $user->name,
                'old_data' => $oldData,
                'new_data' => $validated
            ]);
            
            return redirect()->route('users.index')
                           ->with('success', 'Data user berhasil diperbarui!');
                           
        } catch (\Exception $e) {
            \Log::error('User update failed: ' . $e->getMessage());
            return back()->withInput()
                        ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }
    
    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from resetting their own password via this menu
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat reset password akun sendiri. Gunakan menu Profil.');
        }
        
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
        ]);
        
        try {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);
            
            \Log::info('Password reset by admin', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);
            
            return back()->with('success', 'Password berhasil direset!');
            
        } catch (\Exception $e) {
            \Log::error('Password reset failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deactivating their own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah status akun sendiri.');
        }
        
        try {
            $oldStatus = $user->is_active;
            $user->update([
                'is_active' => !$user->is_active
            ]);
            
            \Log::info('User status toggled', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_status' => $oldStatus ? 'active' : 'inactive',
                'new_status' => $user->is_active ? 'active' : 'inactive'
            ]);
            
            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "User berhasil {$status}!");
            
        } catch (\Exception $e) {
            \Log::error('Toggle status failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting their own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        
        // Check if user has related data
        $hasCreatedBA = $user->beritaAcarasCreated()->count() > 0;
        $hasApprovedBA = $user->beritaAcarasApproved()->count() > 0;
        
        if ($hasCreatedBA || $hasApprovedBA) {
            return back()->with('error', 
                'User tidak dapat dihapus karena memiliki data Berita Acara terkait. ' .
                'Nonaktifkan user saja jika diperlukan.');
        }
        
        try {
            // Delete TTD if exists
            if ($user->ttd_path) {
                Storage::delete($user->ttd_path);
            }
            
            $userName = $user->name;
            $userEmail = $user->email;
            
            $user->delete();
            
            \Log::info('User deleted by admin', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'deleted_user_name' => $userName,
                'deleted_user_email' => $userEmail
            ]);
            
            return redirect()->route('users.index')
                           ->with('success', 'User berhasil dihapus!');
                           
        } catch (\Exception $e) {
            \Log::error('User deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}