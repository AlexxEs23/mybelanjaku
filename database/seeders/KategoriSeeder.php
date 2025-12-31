<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Fashion'],
            ['nama_kategori' => 'Makanan'],
            ['nama_kategori' => 'Kerajinan'],
            ['nama_kategori' => 'Kecantikan'],
            ['nama_kategori' => 'Buku'],
            ['nama_kategori' => 'Elektronik'],
            ['nama_kategori' => 'Rumah Tangga'],
            ['nama_kategori' => 'Olahraga'],
            ['nama_kategori' => 'Hobi'],
            ['nama_kategori' => 'Otomotif'],
        ];

        foreach ($categories as $category) {
            Kategori::create($category);
        }
    }
}
