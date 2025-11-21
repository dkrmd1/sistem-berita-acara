<?php

// File: database/seeders/UserSeeder.php
// GANTI ISI FILE

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@bjbsekuritas.co.id',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'nip' => 'ADM001',
                'jabatan' => 'Administrator Sistem',
                'is_active' => true,
            ],
            [
                'name' => 'R. A. Sukma Ayu H.',
                'email' => 'rasukma@bjbsekuritas.co.id',
                'password' => Hash::make('12345678'),
                'role' => 'cs',
                'nip' => 'CS001',
                'jabatan' => 'Customer Service',
                'is_active' => true,
            ],
            [
                'name' => 'Group Head Sales',
                'email' => 'grouphead@bjbsekuritas.co.id',
                'password' => Hash::make('12345678'),
                'role' => 'group_head',
                'nip' => 'GH001',
                'jabatan' => 'Group Head Sales & Marketing',
                'is_active' => true,
            ],
            [
                'name' => 'Maryadi Suwondo',
                'email' => 'msuwondo@bjbsekuritas.co.id',
                'password' => Hash::make('12345678'),
                'role' => 'direktur_utama',
                'nip' => 'DIRUT001',
                'jabatan' => 'Direktur Utama',
                'is_active' => true,
            ],
            [
                'name' => 'Yogi Heditia Permadi',
                'email' => 'ypermadi@bjbsekuritas.co.id',
                'password' => Hash::make('12345678'),
                'role' => 'direktur',
                'nip' => 'DIR001',
                'jabatan' => 'Direktur',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('✅ User seeder berhasil! 5 user telah dibuat.');
        $this->command->info('');
        $this->command->info('📋 DATA LOGIN (Password semua: 12345678):');
        $this->command->info('-------------------------------------------');
        $this->command->info('ADMIN: admin@bjbsekuritas.co.id');
        $this->command->info('  → Hanya bisa monitoring, tidak bisa buat BA');
        $this->command->info('');
        $this->command->info('CS: rasukma@bjbsekuritas.co.id');
        $this->command->info('  → Bisa buat Berita Acara & import nasabah');
        $this->command->info('');
        $this->command->info('GROUP HEAD: grouphead@bjbsekuritas.co.id');
        $this->command->info('DIREKTUR UTAMA: msuwondo@bjbsekuritas.co.id');
        $this->command->info('DIREKTUR: ypermadi@bjbsekuritas.co.id');
        $this->command->info('  → Approver untuk BA');
        $this->command->info('-------------------------------------------');
    }
}