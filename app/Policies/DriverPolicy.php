<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Driver;

class DriverPolicy
{
    /**
     * Membolehkan admin untuk membuat driver
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk melihat semua driver
     */
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk melihat detail driver
     */
    public function view(User $user, Driver $driver)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk mengupdate driver
     */
    public function update(User $user, Driver $driver)
    {
        return $user->role === 'admin';
    }

    /**
     * Membolehkan admin untuk menghapus driver
     */
    public function delete(User $user, Driver $driver)
    {
        return $user->role === 'admin';
    }
}
