<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kendaraan;

class KendaraanPolicy
{
    /**
     * Membolehkan admin untuk membuat kendaraan
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk melihat semua kendaraan
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk melihat detail kendaraan
     */
    public function view(User $user, Kendaraan $kendaraan)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk mengupdate kendaraan
     */
    public function update(User $user, Kendaraan $kendaraan)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk menghapus kendaraan
     */
    public function delete(User $user, Kendaraan $kendaraan)
    {
        return $user->role === 'admin';
    }
}
