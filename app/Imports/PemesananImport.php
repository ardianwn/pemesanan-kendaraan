<?php

namespace App\Imports;

use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Driver;
use App\Models\Persetujuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PemesananImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithChunkReading
{
    use Importable;
    
    /**
     * @var Collection
     */
    protected $failures;
    
    public function __construct()
    {
        $this->failures = collect();
    }
    
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Validasi data yang diperlukan
            if (empty($row['email_pengguna']) || 
                empty($row['kendaraan']) || 
                empty($row['tujuan']) || 
                empty($row['tanggal_mulai'])) {
                return null;
            }
            
            // Temukan user berdasarkan email
            $user = User::where('email', $row['email_pengguna'])->first();
            if (!$user) {
                $this->failures->push(new Failure(
                    $this->getRowNumber($row),
                    'email_pengguna',
                    ["User dengan email {$row['email_pengguna']} tidak ditemukan"]
                ));
                return null;
            }
            
            // Temukan kendaraan berdasarkan plat nomor
            $kendaraan = Kendaraan::where('nomor_plat', $row['kendaraan'])->first();
            if (!$kendaraan) {
                $this->failures->push(new Failure(
                    $this->getRowNumber($row),
                    'kendaraan',
                    ["Kendaraan dengan plat nomor {$row['kendaraan']} tidak ditemukan"]
                ));
                return null;
            }
            
            // Temukan driver jika ada
            $driver = null;
            if (!empty($row['nama_driver'])) {
                $driver = Driver::where('nama', 'LIKE', "%{$row['nama_driver']}%")->first();
            }
            
            // Parse tanggal
            $tanggal_mulai = Carbon::parse($row['tanggal_mulai']);
            $tanggal_selesai = !empty($row['tanggal_selesai']) 
                ? Carbon::parse($row['tanggal_selesai']) 
                : $tanggal_mulai->copy()->addDays(1);
                
            // Buat pemesanan
            return DB::transaction(function () use ($user, $kendaraan, $driver, $row, $tanggal_mulai, $tanggal_selesai) {
                $pemesanan = Pemesanan::create([
                    'user_id' => $user->id,
                    'kendaraan_id' => $kendaraan->id,
                    'driver_id' => $driver ? $driver->id : null,
                    'tujuan' => $row['tujuan'],
                    'tanggal_pemesanan' => now(),
                    'tanggal_mulai' => $tanggal_mulai,
                    'tanggal_selesai' => $tanggal_selesai,
                    'status' => 'pending',
                    'catatan' => $row['catatan'] ?? null,
                ]);
                
                // Jika ada approver yang disebutkan, buat persetujuan
                if (!empty($row['approver_email'])) {
                    $approver = User::where('email', $row['approver_email'])->first();
                    if ($approver) {
                        Persetujuan::create([
                            'pemesanans_id' => $pemesanan->id,
                            'approver_id' => $approver->id,
                            'level' => 1,
                            'status' => 'pending',
                            'is_final_approval' => true
                        ]);
                    }
                }
                
                return $pemesanan;
            });
            
        } catch (\Exception $e) {
            Log::error('Error importing pemesanan: ' . $e->getMessage());
            $this->failures->push(new Failure(
                $this->getRowNumber($row),
                'general',
                [$e->getMessage()]
            ));
            return null;
        }
    }
    
    /**
     * Get the validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email_pengguna' => 'required|email',
            'kendaraan' => 'required|string',
            'tujuan' => 'required|string',
            'tanggal_mulai' => 'required',
        ];
    }
    
    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failures->push($failure);
        }
    }
    
    /**
     * Get all failures
     *
     * @return Collection
     */
    public function failures(): Collection
    {
        return $this->failures;
    }
    
    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
    
    /**
     * Get the row number
     *
     * @param array $row
     * @return int
     */
    protected function getRowNumber(array $row): int
    {
        return $row['rowIndex'] ?? 0;
    }
}
