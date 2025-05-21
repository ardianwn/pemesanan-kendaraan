<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persetujuan;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PersetujuanSeeder extends Seeder
{
    public function run(): void
    {
        // Get approvers and pemesanans
        $approvers = User::where('role', 'approver')->get();
        
        if ($approvers->isEmpty()) {
            $this->command->warn('Tidak dapat membuat persetujuan, data approver tidak tersedia');
            return;
        }
        
        // Get approved pemesanans
        $approvedPemesanans = Pemesanan::where('status', 'approved')->get();
        
        // Create approvals for approved bookings
        foreach($approvedPemesanans as $pemesanan) {
            // First level approval
            Persetujuan::create([
                'uuid' => Str::uuid(),
                'pemesanans_id' => $pemesanan->id,
                'approver_id' => $approvers->first()->id,
                'level_approval' => 1,
                'status' => 'approved',
                'catatan' => 'Disetujui sesuai prosedur',
                'metadata' => json_encode([
                    'waktu_persetujuan' => Carbon::now()->subDays(rand(5, 8))->format('Y-m-d H:i:s'),
                    'device' => 'Web Browser',
                    'ip_address' => '192.168.1.'.rand(2, 254)
                ]),
                'created_at' => Carbon::now()->subDays(rand(5, 8)),
                'updated_at' => Carbon::now()->subDays(rand(5, 8)),
            ]);
            
            // Second level approval if there are enough approvers
            if ($approvers->count() > 1) {
                Persetujuan::create([
                    'uuid' => Str::uuid(),
                    'pemesanans_id' => $pemesanan->id,
                    'approver_id' => $approvers->skip(1)->first()->id,
                    'level_approval' => 2,
                    'status' => 'approved',
                    'catatan' => 'Telah diverifikasi dan disetujui',
                    'metadata' => json_encode([
                        'waktu_persetujuan' => Carbon::now()->subDays(rand(4, 7))->format('Y-m-d H:i:s'),
                        'device' => 'Mobile App',
                        'ip_address' => '192.168.1.'.rand(2, 254)
                    ]),
                    'created_at' => Carbon::now()->subDays(rand(4, 7)),
                    'updated_at' => Carbon::now()->subDays(rand(4, 7)),
                ]);
            }
        }
        
        // Get pending pemesanans
        $pendingPemesanans = Pemesanan::where('status', 'pending')->get();
        
        // Create partial approvals for pending bookings (only first level)
        foreach($pendingPemesanans as $pemesanan) {
            Persetujuan::create([
                'uuid' => Str::uuid(),
                'pemesanans_id' => $pemesanan->id,
                'approver_id' => $approvers->first()->id,
                'level_approval' => 1,
                'status' => 'pending',
                'catatan' => 'Menunggu persetujuan',
                'metadata' => json_encode([
                    'logged_at' => Carbon::now()->subHours(rand(2, 24))->format('Y-m-d H:i:s'),
                    'device' => 'Web Browser',
                    'ip_address' => '192.168.1.'.rand(2, 254)
                ]),
                'created_at' => Carbon::now()->subHours(rand(2, 24)),
                'updated_at' => Carbon::now()->subHours(rand(2, 24)),
            ]);
        }
        
        // Get rejected pemesanans
        $rejectedPemesanans = Pemesanan::where('status', 'rejected')->get();
        
        // Create rejection records
        foreach($rejectedPemesanans as $pemesanan) {
            Persetujuan::create([
                'uuid' => Str::uuid(),
                'pemesanans_id' => $pemesanan->id,
                'approver_id' => $approvers->first()->id,
                'level_approval' => 1,
                'status' => 'rejected',
                'catatan' => 'Ditolak karena jadwal bentrok',
                'metadata' => json_encode([
                    'waktu_penolakan' => Carbon::now()->subDays(rand(1, 3))->format('Y-m-d H:i:s'),
                    'device' => 'Web Browser',
                    'ip_address' => '192.168.1.'.rand(2, 254),
                    'alasan_lengkap' => 'Jadwal penggunaan kendaraan bentrok dengan kegiatan prioritas lain'
                ]),
                'created_at' => Carbon::now()->subDays(rand(1, 3)),
                'updated_at' => Carbon::now()->subDays(rand(1, 3)),
            ]);
        }
    }
}