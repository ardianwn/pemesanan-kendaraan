<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Persetujuan;

class PersetujuanPolicy
{
    /**
     * Membolehkan approver untuk melakukan persetujuan
     */
    public function approve(User $user, Persetujuan $persetujuan)
    {
        return $user->role === 'approver';
    }

    /**
     * Membolehkan approver untuk melihat daftar persetujuan
     */
    public function viewAny(User $user)
    {
        return $user->role === 'approver' || $user->role === 'admin';
    }

    /**
     * Membolehkan approver untuk melihat detail persetujuan
     */
    public function view(User $user, Persetujuan $persetujuan)
    {
        return $user->role === 'approver' || $user->role === 'admin';
    }
}
