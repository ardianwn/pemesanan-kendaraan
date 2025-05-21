<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogAplikasi;
use App\Models\User;
use App\Models\Pemesanan;
use App\Models\Kendaraan;
use App\Models\Driver;
use Carbon\Carbon;

class LogAplikasiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Tidak dapat membuat log, data users tidak tersedia');
            return;
        }

        // Login/Logout logs
        foreach ($users as $user) {
            for ($i = 1; $i <= 3; $i++) {
                // Login log
                LogAplikasi::create([
                    'user_id' => $user->id,
                    'aktivitas' => 'login',
                    'tabel' => 'users',
                    'id_data' => $user->id,
                    'deskripsi' => "User melakukan login ke sistem",
                    'created_at' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 12)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 12))
                ]);

                // Logout log (beberapa jam setelah login)
                LogAplikasi::create([
                    'user_id' => $user->id,
                    'aktivitas' => 'logout',
                    'tabel' => 'users',
                    'id_data' => $user->id,
                    'deskripsi' => "User keluar dari sistem",
                    'created_at' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 12)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 12))
                ]);
            }
        }

        // CRUD operation logs
        $this->createCrudLogs('pemesanans', Pemesanan::all());
        $this->createCrudLogs('kendaraan', Kendaraan::all());
        $this->createCrudLogs('drivers', Driver::all());
    }

    private function createCrudLogs(string $tabel, $records): void
    {
        if ($records->isEmpty()) {
            return;
        }

        $users = User::all();
        $actions = [
            'create' => 'membuat',
            'update' => 'mengupdate',
            'view' => 'melihat',
            'delete' => 'menghapus'
        ];

        foreach ($records as $record) {
            foreach ($actions as $aktivitas => $verb) {
                // Skip some actions randomly to make logs more realistic
                if (rand(0, 1)) {
                    continue;
                }

                LogAplikasi::create([
                    'user_id' => $users->random()->id,
                    'aktivitas' => $aktivitas,
                    'tabel' => $tabel,
                    'id_data' => $record->id,
                    'deskripsi' => "User {$verb} data {$tabel} #" . $record->id,
                    'created_at' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 23)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(1, 23))
                ]);
            }
        }
    }
}
