<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil penjual yang sudah approved
        $penjual1 = User::where('email', 'penjual1@umkm.com')->first();
        $penjual2 = User::where('email', 'penjual2@umkm.com')->first();
        
        // Data produk per kategori
        $produkData = [
            'Fashion' => [
                ['nama' => 'Kaos Batik Modern', 'deskripsi' => 'Kaos batik dengan desain modern dan nyaman dipakai', 'harga' => 85000, 'stok' => 50],
                ['nama' => 'Kemeja Flanel Premium', 'deskripsi' => 'Kemeja flanel berkualitas tinggi, cocok untuk casual', 'harga' => 125000, 'stok' => 30],
                ['nama' => 'Celana Jeans Slim Fit', 'deskripsi' => 'Celana jeans dengan potongan slim fit yang stylish', 'harga' => 175000, 'stok' => 40],
                ['nama' => 'Jaket Hoodie Distro', 'deskripsi' => 'Jaket hoodie distro dengan bahan fleece lembut', 'harga' => 150000, 'stok' => 25],
                ['nama' => 'Dress Casual Wanita', 'deskripsi' => 'Dress casual dengan bahan katun yang adem', 'harga' => 135000, 'stok' => 35],
            ],
            'Makanan' => [
                ['nama' => 'Keripik Singkong Pedas', 'deskripsi' => 'Keripik singkong renyah dengan bumbu pedas khas', 'harga' => 25000, 'stok' => 100],
                ['nama' => 'Dodol Durian Khas', 'deskripsi' => 'Dodol durian asli dengan rasa yang legit', 'harga' => 45000, 'stok' => 75],
                ['nama' => 'Kue Kering Nastar', 'deskripsi' => 'Kue nastar homemade dengan selai nanas segar', 'harga' => 55000, 'stok' => 60],
                ['nama' => 'Sambal Matah Bali', 'deskripsi' => 'Sambal matah khas Bali yang segar dan pedas', 'harga' => 35000, 'stok' => 80],
                ['nama' => 'Abon Sapi Original', 'deskripsi' => 'Abon sapi berkualitas dengan bumbu tradisional', 'harga' => 65000, 'stok' => 50],
            ],
            'Kerajinan' => [
                ['nama' => 'Tas Anyaman Rotan', 'deskripsi' => 'Tas anyaman rotan handmade dengan desain etnik', 'harga' => 185000, 'stok' => 20],
                ['nama' => 'Gelang Etnik Handmade', 'deskripsi' => 'Gelang etnik dengan manik-manik pilihan', 'harga' => 45000, 'stok' => 100],
                ['nama' => 'Lukisan Kanvas Abstrak', 'deskripsi' => 'Lukisan kanvas abstrak karya seniman lokal', 'harga' => 275000, 'stok' => 15],
                ['nama' => 'Patung Kayu Ukir', 'deskripsi' => 'Patung kayu ukir dengan motif tradisional', 'harga' => 320000, 'stok' => 10],
                ['nama' => 'Tempat Pensil Bambu', 'deskripsi' => 'Tempat pensil dari bambu dengan ukiran cantik', 'harga' => 35000, 'stok' => 50],
            ],
            'Kecantikan' => [
                ['nama' => 'Masker Wajah Alami', 'deskripsi' => 'Masker wajah dari bahan alami tanpa kimia', 'harga' => 75000, 'stok' => 60],
                ['nama' => 'Lip Balm Madu Organik', 'deskripsi' => 'Lip balm dari madu organik untuk bibir lembut', 'harga' => 35000, 'stok' => 80],
                ['nama' => 'Sabun Herbal Tradisional', 'deskripsi' => 'Sabun herbal dengan bahan alami dari alam', 'harga' => 25000, 'stok' => 100],
                ['nama' => 'Serum Wajah Vitamin C', 'deskripsi' => 'Serum wajah dengan kandungan vitamin C alami', 'harga' => 95000, 'stok' => 45],
                ['nama' => 'Lulur Badan Tradisional', 'deskripsi' => 'Lulur badan dengan resep tradisional warisan', 'harga' => 55000, 'stok' => 70],
            ],
            'Buku' => [
                ['nama' => 'Novel Romantis Lokal', 'deskripsi' => 'Novel karya penulis lokal dengan cerita menarik', 'harga' => 75000, 'stok' => 40],
                ['nama' => 'Buku Resep Masakan Nusantara', 'deskripsi' => 'Kumpulan resep masakan tradisional Indonesia', 'harga' => 85000, 'stok' => 30],
                ['nama' => 'Komik Petualangan Anak', 'deskripsi' => 'Komik petualangan edukatif untuk anak-anak', 'harga' => 45000, 'stok' => 50],
                ['nama' => 'Buku Motivasi Entrepreneur', 'deskripsi' => 'Buku motivasi untuk para pengusaha muda', 'harga' => 95000, 'stok' => 35],
                ['nama' => 'Atlas Indonesia Lengkap', 'deskripsi' => 'Atlas Indonesia dengan peta lengkap 34 provinsi', 'harga' => 125000, 'stok' => 25],
            ],
            'Elektronik' => [
                ['nama' => 'Power Bank 10000mAh', 'deskripsi' => 'Power bank fast charging kapasitas 10000mAh', 'harga' => 175000, 'stok' => 40],
                ['nama' => 'Speaker Bluetooth Mini', 'deskripsi' => 'Speaker bluetooth portable dengan suara jernih', 'harga' => 145000, 'stok' => 35],
                ['nama' => 'Lampu LED USB Fleksibel', 'deskripsi' => 'Lampu LED USB dengan lengan fleksibel', 'harga' => 35000, 'stok' => 60],
                ['nama' => 'Kabel Data Type-C Premium', 'deskripsi' => 'Kabel data type-C fast charging berkualitas', 'harga' => 45000, 'stok' => 100],
                ['nama' => 'Headset Gaming RGB', 'deskripsi' => 'Headset gaming dengan lampu RGB dan mic', 'harga' => 225000, 'stok' => 25],
            ],
            'Rumah Tangga' => [
                ['nama' => 'Set Piring Keramik 6pcs', 'deskripsi' => 'Set piring keramik cantik isi 6 pieces', 'harga' => 185000, 'stok' => 30],
                ['nama' => 'Gelas Kaca Premium 12pcs', 'deskripsi' => 'Gelas kaca berkualitas tinggi isi 1 lusin', 'harga' => 125000, 'stok' => 40],
                ['nama' => 'Rak Bumbu Dapur Kayu', 'deskripsi' => 'Rak bumbu dapur dari kayu solid praktis', 'harga' => 95000, 'stok' => 35],
                ['nama' => 'Tempat Bumbu Set 6pcs', 'deskripsi' => 'Tempat bumbu stainless steel set lengkap', 'harga' => 75000, 'stok' => 50],
                ['nama' => 'Tatakan Gelas Rotan', 'deskripsi' => 'Tatakan gelas dari rotan set 6 pieces', 'harga' => 35000, 'stok' => 80],
            ],
            'Olahraga' => [
                ['nama' => 'Matras Yoga Anti Slip', 'deskripsi' => 'Matras yoga berkualitas dengan permukaan anti slip', 'harga' => 185000, 'stok' => 30],
                ['nama' => 'Resistance Band Set', 'deskripsi' => 'Set resistance band untuk home workout', 'harga' => 95000, 'stok' => 45],
                ['nama' => 'Dumbbell 2kg Pair', 'deskripsi' => 'Dumbbell vinyl 2kg sepasang untuk fitness', 'harga' => 125000, 'stok' => 35],
                ['nama' => 'Botol Minum Olahraga 1L', 'deskripsi' => 'Botol minum sport 1 liter BPA free', 'harga' => 55000, 'stok' => 60],
                ['nama' => 'Skipping Rope Digital', 'deskripsi' => 'Tali skipping dengan counter digital', 'harga' => 75000, 'stok' => 50],
            ],
            'Hobi' => [
                ['nama' => 'Set Alat Lukis Lengkap', 'deskripsi' => 'Set alat lukis cat air lengkap untuk pemula', 'harga' => 165000, 'stok' => 25],
                ['nama' => 'Puzzle 1000 Pieces', 'deskripsi' => 'Puzzle 1000 keping dengan gambar pemandangan', 'harga' => 85000, 'stok' => 40],
                ['nama' => 'Gitar Akustik Mini', 'deskripsi' => 'Gitar akustik ukuran mini cocok untuk belajar', 'harga' => 385000, 'stok' => 15],
                ['nama' => 'Set Alat Menjahit Portable', 'deskripsi' => 'Set alat menjahit lengkap dalam tas portable', 'harga' => 125000, 'stok' => 30],
                ['nama' => 'Board Game Keluarga', 'deskripsi' => 'Board game seru untuk dimainkan bersama keluarga', 'harga' => 145000, 'stok' => 35],
            ],
            'Otomotif' => [
                ['nama' => 'Kaca Spion Motor Universal', 'deskripsi' => 'Kaca spion motor universal berkualitas tinggi', 'harga' => 65000, 'stok' => 50],
                ['nama' => 'Sarung Jok Motor Anti Air', 'deskripsi' => 'Sarung jok motor waterproof berbagai ukuran', 'harga' => 85000, 'stok' => 40],
                ['nama' => 'Helm Half Face SNI', 'deskripsi' => 'Helm half face standar SNI dengan visor', 'harga' => 185000, 'stok' => 30],
                ['nama' => 'Jas Hujan Motor Premium', 'deskripsi' => 'Jas hujan motor berkualitas tidak tembus air', 'harga' => 95000, 'stok' => 45],
                ['nama' => 'Lampu LED Motor 12V', 'deskripsi' => 'Lampu LED motor 12V terang dan hemat listrik', 'harga' => 55000, 'stok' => 60],
            ],
        ];

        // Insert produk untuk setiap kategori
        foreach ($produkData as $kategoriNama => $produks) {
            $kategori = Kategori::where('nama_kategori', $kategoriNama)->first();
            
            if ($kategori) {
                foreach ($produks as $index => $produk) {
                    // Alternatif antara penjual 1 dan 2
                    $penjual = ($index % 2 == 0) ? $penjual1 : $penjual2;
                    
                    Produk::create([
                        'user_id' => $penjual->id,
                        'kategori_id' => $kategori->id,
                        'nama_produk' => $produk['nama'],
                        'slug' => Str::slug($produk['nama']),
                        'deskripsi' => $produk['deskripsi'],
                        'harga' => $produk['harga'],
                        'stok' => $produk['stok'],
                        'gambar' => null, // Bisa diisi nanti dengan path gambar
                        'status' => true,
                        'nomor_whatsapp' => $penjual->no_hp, // Ambil dari nomor HP penjual
                    ]);
                }
            }
        }
    }
}
