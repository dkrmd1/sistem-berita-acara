<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBeritaAcaraNotification extends Notification
{
    use Queueable;

    protected $beritaAcara;

    public function __construct($beritaAcara)
    {
        $this->beritaAcara = $beritaAcara;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database agar muncul di lonceng
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => 'BA Baru Menunggu Approval',
            'message' => 'BA No: ' . $this->beritaAcara->nomor_ba . ' menunggu persetujuan Anda.',
            'url'     => route('berita-acara.show', $this->beritaAcara->id),
            'type'    => 'pending' // Untuk warna icon (pending/info)
        ];
    }
}