<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Driver;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing data from database
        $admin = User::whereHas('role', function($q) { 
            $q->where('slug', 'admin');
        })->first();
        
        $regularUser = User::whereHas('role', function($q) { 
            $q->where('slug', 'user');
        })->first();
        
        $approver = User::whereHas('role', function($q) { 
            $q->where('slug', 'approver');
        })->first();
        
        $kendaraan = Kendaraan::all();
        $drivers = Driver::all();
        
        if (!$admin || !$regularUser || !$approver || $kendaraan->isEmpty() || $drivers->isEmpty()) {
            $this->command->warn('Tidak dapat membuat pemesanan, data users/kendaraan/drivers tidak tersedia');
            return;
        }
        
        $pemesananData = [
            // Pemesanan dengan status approved
            [
                'user_id' => $admin->id,
                'kendaraan_id' => $kendaraan->where('jenis', 'angkutan_orang')->first()->id,
                'driver_id' => $drivers->first()->id,
                'tujuan' => 'Kantor Cabang Bandung',
                'tanggal_pemesanan' => Carbon::now()->subDays(5),
                'status' => 'approved',
                'additional_info' => [
                    'keperluan' => 'Rapat Koordinasi Tim Sales',
                    'jumlah_penumpang' => 4,
                    'durasi' => '2 hari',
                    'prioritas' => 'tinggi'
                ]
            ],
            
            // Pemesanan dengan status pending
            [
                'user_id' => $regularUser->id,
                'kendaraan_id' => $kendaraan->where('jenis', 'angkutan_barang')->first()->id,
                'driver_id' => $drivers->skip(1)->first()->id,
                'tujuan' => 'Gudang Bekasi',
                'tanggal_pemesanan' => Carbon::now()->addDays(3),
                'status' => 'pending',
                'additional_info' => [
                    'keperluan' => 'Pengiriman Dokumen',
                    'jenis_barang' => 'Dokumen Penting',
                    'berat' => '5 kg',
                    'prioritas' => 'normal'
                ]
            ],
            
            // Pemesanan dengan status rejected
            [
                'user_id' => $approver->id,
                'kendaraan_id' => $kendaraan->where('status', 'sewa')->first()->id,
                'driver_id' => $drivers->skip(2)->first()->id,
                'tujuan' => 'Bandara Soekarno-Hatta',
                'tanggal_pemesanan' => Carbon::now()->addDay(),
                'status' => 'rejected',
                'additional_info' => [
                    'keperluan' => 'Penjemputan Tamu',
                    'jumlah_penumpang' => 3,
                    'alasan_penolakan' => 'Kendaraan sedang dalam perawatan'
                ]
            ],
        ];
        
        foreach ($pemesananData as $data) {
            // Extract durasi from additional_info if provided, or default to 1
            $durasi = 1;
            if (isset($data['additional_info']['durasi'])) {
                // Try to extract numeric value from durasi string like "2 hari"
                preg_match('/(\d+)/', $data['additional_info']['durasi'], $matches);
                if (!empty($matches)) {
                    $durasi = intval($matches[1]) * 24; // Convert days to hours
                }
            }
            
            Pemesanan::create([
                'uuid' => Str::uuid(),
                'user_id' => $data['user_id'],
                'kendaraan_id' => $data['kendaraan_id'],
                'driver_id' => $data['driver_id'],
                'tujuan' => $data['tujuan'],
                'tanggal_pemesanan' => $data['tanggal_pemesanan'],
                'jam_pemesanan' => '09:00:00', // Default to 9 AM
                'durasi_pemesanan' => $durasi,
                'status' => $data['status'],
                'additional_info' => json_encode($data['additional_info']),
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
                'updated_at' => Carbon::now()->subDays(rand(0, 3)),
            ]);
        }
    }
}
