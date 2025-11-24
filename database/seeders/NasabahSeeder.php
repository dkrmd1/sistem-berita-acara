<?php

// File: database/seeders/NasabahSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nasabah;
use Carbon\Carbon;

class NasabahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array dikosongkan agar tidak ada data dummy yang masuk
        $nasabahs = [
            // Silakan isi data real di sini jika diperlukan, formatnya seperti di bawah ini:
            /*
            [
                'nama' => 'NAMA NASABAH',
                'ktp' => 'NOMOR KTP',
                'npwp' => 'NOMOR NPWP',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', 'DD-MMM-YY'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            */
        ];

        // Proses Insert (hanya akan jalan jika array di atas diisi)
        foreach ($nasabahs as $nasabah) {
            Nasabah::create($nasabah);
        }

        if (empty($nasabahs)) {
            $this->command->info('ℹ️ Data nasabah kosong. Tidak ada data yang di-seed.');
        } else {
            $this->command->info('✅ Nasabah seeder berhasil dijalankan.');
        }
    }
}