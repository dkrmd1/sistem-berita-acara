<?php

// File: app/Http/Middleware/CheckRole.php
// BUAT FILE BARU

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Ambil role user yang login
        $userRole = auth()->user()->role;
        
        // Cek apakah role user ada di daftar role yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }
        
        // Kalau tidak punya akses
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}