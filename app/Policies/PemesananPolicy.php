<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pemesanan;

class PemesananPolicy
{
    /**
     * Membolehkan semua pengguna untuk membuat pemesanan
     */
    public function create(User $user)
    {
        // Allow all authenticated users to create pemesanan
        return true;
    }

    /**
     * Membolehkan admin untuk melihat semua pemesanan
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan pengguna yang sama untuk melihat pemesanan mereka
     */
    public function view(User $user, Pemesanan $pemesanan)
    {
        return $user->id === $pemesanan->user_id || $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk mengupdate pemesanan
     */
    public function update(User $user, Pemesanan $pemesanan)
    {
        return $user->role === 'admin' || $user->id === $pemesanan->user_id;
    }

    /**
     * Membolehkan admin untuk menghapus pemesanan
     */
    public function delete(User $user, Pemesanan $pemesanan)
    {
        return $user->role === 'admin';
    }
}
