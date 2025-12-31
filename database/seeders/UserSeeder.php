<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin UMKM',
            'email' => 'admin@umkm.com',
            'password' => Hash::make('admin123'),
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Admin No. 1, Jakarta',
            'role' => 'admin',
            'status' => 'aktif',
            'status_approval' => 'approved',
        ]);

        // Penjual yang sudah approved
        User::create([
            'name' => 'Toko Berkah Jaya',
            'email' => 'penjual1@umkm.com',
            'password' => Hash::make('penjual123'),
            'no_hp' => '082345678901',
            'alamat' => 'Jl. Penjual No. 10, Bandung',
            'role' => 'penjual',
            'status' => 'aktif',
            'status_approval' => 'approved',
        ]);

        User::create([
            'name' => 'Warung Maju Mandiri',
            'email' => 'penjual2@umkm.com',
            'password' => Hash::make('penjual123'),
            'no_hp' => '083456789012',
            'alamat' => 'Jl. Makmur No. 15, Surabaya',
            'role' => 'penjual',
            'status' => 'aktif',
            'status_approval' => 'approved',
        ]);

        // Penjual yang masih pending
        User::create([
            'name' => 'Toko Harapan Baru',
            'email' => 'penjual3@umkm.com',
            'password' => Hash::make('penjual123'),
            'no_hp' => '084567890123',
            'alamat' => 'Jl. Pending No. 5, Semarang',
            'role' => 'penjual',
            'status' => 'aktif',
            'status_approval' => 'pending',
        ]);

        User::create([
            'name' => 'UMKM Sejahtera',
            'email' => 'penjual4@umkm.com',
            'password' => Hash::make('penjual123'),
            'no_hp' => '085678901234',
            'alamat' => 'Jl. Menunggu No. 20, Yogyakarta',
            'role' => 'penjual',
            'status' => 'aktif',
            'status_approval' => 'pending',
        ]);

        // Penjual yang ditolak
        User::create([
            'name' => 'Toko Tidak Lolos',
            'email' => 'penjual5@umkm.com',
            'password' => Hash::make('penjual123'),
            'no_hp' => '086789012345',
            'alamat' => 'Jl. Ditolak No. 3, Malang',
            'role' => 'penjual',
            'status' => 'aktif',
            'status_approval' => 'rejected',
        ]);

        // User/Pembeli
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('user123'),
            'no_hp' => '087890123456',
            'alamat' => 'Jl. Pembeli No. 25, Jakarta Selatan',
            'role' => 'user',
            'status' => 'aktif',
            'status_approval' => 'approved',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('user123'),
            'no_hp' => '088901234567',
            'alamat' => 'Jl. Customer No. 30, Bekasi',
            'role' => 'user',
            'status' => 'aktif',
            'status_approval' => 'approved',
        ]);

        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'user3@gmail.com',
            'password' => Hash::make('user123'),
            'no_hp' => '089012345678',
            'alamat' => 'Jl. Pelanggan No. 12, Tangerang',
            'role' => 'user',
            'status' => 'aktif',
            'status_approval' => 'approved',
        ]);
    }
}
