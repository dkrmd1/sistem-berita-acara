<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tandai semua notifikasi sebagai sudah dibaca (Mark All Read)
     */
    public function markAllRead()
    {
        // Update semua notifikasi user yang belum dibaca menjadi dibaca
        Auth::user()->unreadNotifications->markAsRead();

        // Kembali ke halaman sebelumnya
        return back();
    }

    /**
     * Tandai satu notifikasi sebagai dibaca saat diklik, lalu redirect ke URL tujuannya
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Tandai sebagai dibaca
        $notification->markAsRead();

        // Ambil URL tujuan dari data notifikasi, atau default ke dashboard
        $url = $notification->data['url'] ?? route('dashboard');

        // Redirect user ke halaman detail
        return redirect($url);
    }
}