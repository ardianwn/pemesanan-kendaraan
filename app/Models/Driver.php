<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'telepon',
        'kendaraan_id',
    ];

    // Relasi: Driver milik Kendaraan
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // Relasi: Driver memiliki banyak Pemesanan
    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }
}
