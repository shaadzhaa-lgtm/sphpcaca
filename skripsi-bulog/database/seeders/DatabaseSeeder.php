<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run() {
        // Akun Admin
        User::create(['name' => 'Admin Pusat', 'email' => 'admin@gmail.com', 'password' => Hash::make('admin123'), 'role' => 'admin']);
        
        // Akun Pinwil
        User::create(['name' => 'Pimpinan Wilayah', 'email' => 'pinwil@gmail.com', 'password' => Hash::make('pinwil123'), 'role' => 'pinwil']);

        // Akun 8 Pinca
        $cabangs = ['Bogor', 'Cianjur', 'Bandung', 'Ciamis', 'Cirebon', 'Indramayu', 'Subang', 'Karawang'];
        foreach ($cabangs as $c) {
            User::create([
                'name' => 'Pinca ' . $c,
                'email' => 'pinca.' . strtolower($c) . '@gmail.com',
                'password' => Hash::make('pinca123'),
                'role' => 'pinca'
            ]);
        }
    }
}