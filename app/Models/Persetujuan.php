<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesanans_id',
        'approver_id',
        'level',
        'next_approver_id',
        'is_final_approval',
        'status',
        'catatan',
    ];

    // Relasi: Persetujuan milik Pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanans_id');
    }

    // Relasi: Persetujuan dibuat oleh User (Approver)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
    
    // Relasi: Persetujuan akan diteruskan ke approver berikutnya
    public function nextApprover()
    {
        return $this->belongsTo(User::class, 'next_approver_id');
    }
}
