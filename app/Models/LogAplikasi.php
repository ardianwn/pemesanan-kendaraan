<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAplikasi extends Model
{
    use HasFactory;

    protected $table = 'log_aplikasi';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'tabel',
        'id_data',
        'deskripsi',
    ];

    // Relasi: Log dibuat oleh User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
