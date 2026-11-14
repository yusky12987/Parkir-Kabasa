<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tarif;
use App\Models\AreaParkir;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Data User Default (Password: password123)
        User::create([
            'nama_lengkap' => 'Administrator Parkir',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        User::create([
            'nama_lengkap' => 'Petugas Lapangan',
            'username' => 'petugas',
            'password' => Hash::make('password123'),
            'role' => 'petugas'
        ]);

        User::create([
            'nama_lengkap' => 'Owner / Manager',
            'username' => 'owner',
            'password' => Hash::make('password123'),
            'role' => 'owner'
        ]);

        // Data Tarif Awal
        Tarif::create(['jenis_kendaraan' => 'Motor', 'tarif_per_jam' => 2000]);
        Tarif::create(['jenis_kendaraan' => 'Mobil', 'tarif_per_jam' => 5000]);

        // Data Area Parkir Awal
        AreaParkir::create(['nama_area' => 'Zona A (Depan)', 'kapasitas' => 50, 'terisi' => 0]);
        AreaParkir::create(['nama_area' => 'Zona B (Gedung)', 'kapasitas' => 30, 'terisi' => 0]);
    }
}