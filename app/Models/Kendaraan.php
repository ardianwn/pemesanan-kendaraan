<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'nama',
        'nomor_plat',
        'kapasitas',
        'jenis',
        'status',
        'lokasi',
    ];

    // Relasi: Kendaraan memiliki banyak Driver
    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    // Relasi: Kendaraan memiliki banyak Pemesanan
    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }
}
