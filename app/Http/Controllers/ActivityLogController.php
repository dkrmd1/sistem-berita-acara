<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Mulai Query
        $query = LoginLog::with('user');

        // 1. Filter Pencarian (Nama User atau IP)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Role
        if ($request->has('role') && $request->role != '') {
            $role = $request->role;
            $query->whereHas('user', function($q) use ($role) {
                $q->where('role', $role);
            });
        }

        // Urutkan terbaru & Paginate
        $logs = $query->latest('login_at')->paginate(20)->withQueryString();
        
        return view('admin.logs.index', compact('logs'));
    }
}