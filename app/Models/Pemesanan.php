<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemesanan extends Model
{
    use HasFactory, HasUuid, SoftDeletes, Auditable;

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'driver_id',
        'tujuan',
        'tanggal_pemesanan',
        'jam_pemesanan',
        'durasi_pemesanan',
        'status',
        'catatan',
        'additional_info',
        'document_path',
        'document_name',
        'document_size',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'jam_pemesanan' => 'datetime',
        'durasi_pemesanan' => 'integer',
        'additional_info' => 'json',
    ];

    // Relasi: Pemesanan dibuat oleh User (Admin)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Pemesanan untuk Kendaraan tertentu
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // Relasi: Pemesanan menggunakan Driver tertentu
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Relasi: Pemesanan memiliki banyak Persetujuan
    public function persetujuans()
    {
        return $this->hasMany(Persetujuan::class, 'pemesanans_id');
    }
}
