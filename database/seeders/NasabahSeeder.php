<?php

// File: database/seeders/NasabahSeeder.php
// BUAT FILE BARU

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
        // Data nasabah dari file PDF yang Anda kirim
        $nasabahs = [
            [
                'nama' => 'RUDI RUCHBANSAH',
                'ktp' => '3203112509720003',
                'npwp' => '273483404429000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '25-Sep-72'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'BILAL MAKARIM',
                'ktp' => '3273133005960001',
                'npwp' => '922419262424000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '30-May-96'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'JOKO HADI SUSILO',
                'ktp' => '3273220404840001',
                'npwp' => '261784318429000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '04-Apr-84'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'YOGI HEDITIA PERMADI',
                'ktp' => '3273210504770005',
                'npwp' => '274893338424000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '05-Apr-77'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'BAMBANG SETIADI',
                'ktp' => '3277032302760003',
                'npwp' => '079402988423000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '23-Feb-76'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'DUDDY NUGRAHA',
                'ktp' => '3273202911740003',
                'npwp' => '079463428425000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '29-Nov-74'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'BUDI NUGRAHA',
                'ktp' => '3273182302760004',
                'npwp' => '093505154423000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '23-Feb-76'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'DESTRI OKSA VIALI',
                'ktp' => '3273254312930004',
                'npwp' => '806451902429000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '03-Dec-93'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'ARIEF SETYAHADI',
                'ktp' => '3273201908690005',
                'npwp' => '003474146429000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '19-Aug-69'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
            [
                'nama' => 'DIKI RAMDANI',
                'ktp' => '3273202912990001',
                'npwp' => '418370169429000',
                'tanggal_lahir' => Carbon::createFromFormat('d-M-y', '29-Dec-99'),
                'negara' => 'INDONESIA',
                'has_berita_acara' => false,
            ],
        ];

        // Insert data ke database
        foreach ($nasabahs as $nasabah) {
            Nasabah::create($nasabah);
        }

        $this->command->info('✅ Nasabah seeder berhasil! 10 nasabah telah dibuat.');
        $this->command->info('');
        $this->command->info('📋 DATA NASABAH:');
        $this->command->info('-----------------------------------');
        foreach ($nasabahs as $index => $nasabah) {
            $this->command->info(($index + 1) . '. ' . $nasabah['nama']);
        }
        $this->command->info('-----------------------------------');
    }
}