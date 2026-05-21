<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Daftar semua akun yang kamu butuhkan
        $users = [
            [
                'name'     => 'Admin Pusat',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('password'), // Kita buat seragam: password
                'role'     => 'admin',
            ],
            [
                'name'     => 'Pimpinan Bulog',
                'email'    => 'pimpinan@bulog.co.id',
                'password' => Hash::make('password'), // Diubah dari bulog123 jadi password
                'role'     => 'pimpinan_cabang',
            ],
            [
                'name'     => 'Pinwil',
                'email'    => 'pinwil@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'pinwil',
            ],
        ];

        // 2. Daftar 8 Kantor Cabang (Pinca)
        $cabangs = ['Bogor', 'Cianjur', 'Bandung', 'Ciamis', 'Cirebon', 'Indramayu', 'Subang', 'Karawang'];
        foreach ($cabangs as $kota) {
            $users[] = [
                'name'     => 'Pinca ' . $kota,
                'email'    => 'pinca.' . strtolower($kota) . '@gmail.com',
                'password' => Hash::make('password'),
                'role'     => 'pinca',
            ];
        }

        // 3. Proses masukkan ke Database
        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // Cek email agar tidak double
                [
                    'name'     => $userData['name'],
                    'password' => $userData['password'],
                    'role'     => $userData['role'],
                ]
            );
        }

        $this->command->info('Semua akun (Admin & 8 Cabang) berhasil diperbarui!');
    }
}