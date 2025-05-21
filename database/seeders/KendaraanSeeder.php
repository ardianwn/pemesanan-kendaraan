<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kendaraan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $kendaraanData = [
            [
                'nama' => 'Toyota Avanza',
                'jenis' => 'angkutan_orang',
                'status' => 'milik',
                'lokasi' => 'Kantor Pusat Jakarta',
                'specifications' => [
                    'nomor_plat' => 'B 1234 CD',
                    'tahun' => '2023',
                    'warna' => 'Putih',
                    'transmisi' => 'Manual',
                    'bahan_bakar' => 'Bensin',
                    'kapasitas_penumpang' => 7,
                    'fitur' => ['AC', 'Power Steering', 'Electric Window']
                ]
            ],
            [
                'nama' => 'Honda HR-V',
                'jenis' => 'angkutan_orang',
                'status' => 'milik',
                'lokasi' => 'Kantor Cabang Bandung',
                'specifications' => [
                    'nomor_plat' => 'B 2345 EF',
                    'tahun' => '2023',
                    'warna' => 'Hitam',
                    'transmisi' => 'Automatic',
                    'bahan_bakar' => 'Bensin',
                    'kapasitas_penumpang' => 5,
                    'fitur' => ['AC', 'Power Steering', 'Electric Window', 'Sunroof']
                ]
            ],
            [
                'nama' => 'Toyota Hiace',
                'jenis' => 'angkutan_orang',
                'status' => 'sewa',
                'lokasi' => 'Kantor Pusat Jakarta',
                'specifications' => [
                    'nomor_plat' => 'B 3456 GH',
                    'tahun' => '2022',
                    'warna' => 'Silver',
                    'transmisi' => 'Manual',
                    'bahan_bakar' => 'Solar',
                    'kapasitas_penumpang' => 16,
                    'fitur' => ['AC', 'Power Steering', 'TV', 'Audio System']
                ]
            ],
            [
                'nama' => 'Mitsubishi L300',
                'jenis' => 'angkutan_barang',
                'status' => 'milik',
                'lokasi' => 'Gudang Bekasi',
                'specifications' => [
                    'nomor_plat' => 'B 4567 IJ',
                    'tahun' => '2022',
                    'warna' => 'Putih',
                    'transmisi' => 'Manual',
                    'bahan_bakar' => 'Solar',
                    'kapasitas_muatan' => '1 ton',
                    'fitur' => ['AC', 'Power Steering']
                ]
            ],
        ];

        foreach ($kendaraanData as $data) {
            Kendaraan::create([
                'uuid' => Str::uuid(),
                'nama' => $data['nama'],
                'nomor_plat' => $data['specifications']['nomor_plat'],
                'kapasitas' => $data['specifications']['kapasitas_penumpang'] ?? ($data['specifications']['kapasitas_muatan'] ? intval($data['specifications']['kapasitas_muatan']) : 0),
                'jenis' => $data['jenis'],
                'status' => $data['status'],
                'lokasi' => $data['lokasi'],
                'specifications' => json_encode($data['specifications']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
