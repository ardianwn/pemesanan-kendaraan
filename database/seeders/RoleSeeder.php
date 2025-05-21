<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Administrator sistem dengan akses penuh',
                'permissions' => json_encode(['*']),
                'is_active' => true,
            ],
            [
                'name' => 'Approver',
                'slug' => 'approver',
                'description' => 'Pengguna dengan akses persetujuan pemesanan',
                'permissions' => json_encode(['approve_pemesanan', 'view_pemesanan']),
                'is_active' => true,
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Pengguna biasa yang dapat mengajukan pemesanan',
                'permissions' => json_encode(['create_pemesanan', 'view_own_pemesanan']),
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create(array_merge($role, [
                'uuid' => Str::uuid(),
            ]));
        }
    }
}
