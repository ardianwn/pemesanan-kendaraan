<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use App\Models\Kendaraan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        // Get kendaraan IDs from the database
        $kendaraanIds = Kendaraan::pluck('id')->toArray();

        $driversData = [
            [
                'nama' => 'Budi Santoso',
                'schedule' => [
                    'nomor_telepon' => '08123456789',
                    'alamat' => 'Jl. Pahlawan No. 123, Jakarta Selatan',
                    'shift' => 'pagi',
                    'hari_libur' => ['Minggu'],
                    'jam_kerja' => '08:00 - 17:00'
                ]
            ],
            [
                'nama' => 'Agus Hermawan',
                'schedule' => [
                    'nomor_telepon' => '08234567890',
                    'alamat' => 'Jl. Raya Bogor Km. 25, Jakarta Timur',
                    'shift' => 'siang',
                    'hari_libur' => ['Sabtu'],
                    'jam_kerja' => '14:00 - 22:00'
                ]
            ],
            [
                'nama' => 'Dedi Kurniawan',
                'schedule' => [
                    'nomor_telepon' => '08345678901',
                    'alamat' => 'Jl. Gatot Subroto No. 45, Jakarta Pusat',
                    'shift' => 'malam',
                    'hari_libur' => ['Jumat'],
                    'jam_kerja' => '22:00 - 06:00'
                ]
            ],
            [
                'nama' => 'Eko Prasetyo',
                'schedule' => [
                    'nomor_telepon' => '08456789012',
                    'alamat' => 'Jl. Merdeka No. 78, Jakarta Barat',
                    'shift' => 'pagi',
                    'hari_libur' => ['Senin'],
                    'jam_kerja' => '08:00 - 17:00'
                ]
            ],
        ];

        foreach ($driversData as $index => $data) {
            Driver::create([
                'uuid' => Str::uuid(),
                'nama' => $data['nama'],
                // Assign each driver to a different kendaraan, cycling through available kendaraan
                'kendaraan_id' => $kendaraanIds[$index % count($kendaraanIds)],
                'schedule' => json_encode($data['schedule']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
