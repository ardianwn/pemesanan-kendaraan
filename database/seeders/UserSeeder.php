<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    private array $users = [
        // Admin Users
        [
            'name' => 'Admin Utama',
            'username' => 'admin1',
            'email' => 'admin1@example.com',
            'password' => 'admin123',
            'role' => 'admin',
            'preferences' => [
                'theme' => 'light',
                'dashboard_layout' => 'compact',
                'notifications' => true,
                'language' => 'id'
            ]
        ],
        [
            'name' => 'Admin Cadangan',
            'username' => 'admin2',
            'email' => 'admin2@example.com',
            'password' => 'admin456',
            'role' => 'admin',
            'preferences' => [
                'theme' => 'dark',
                'dashboard_layout' => 'full',
                'notifications' => true,
                'language' => 'en'
            ]
        ],
        
        // Approver Users
        [
            'name' => 'Approver Satu',
            'username' => 'approver1',
            'email' => 'approver1@example.com',
            'password' => 'approve123',
            'role' => 'approver',
            'preferences' => [
                'theme' => 'light',
                'notification_channels' => ['email', 'app'],
                'language' => 'id'
            ]
        ],
        [
            'name' => 'Approver Dua',
            'username' => 'approver2',
            'email' => 'approver2@example.com',
            'password' => 'approve456',
            'role' => 'approver',
            'preferences' => [
                'theme' => 'system',
                'notification_channels' => ['email'],
                'language' => 'id'
            ]
        ],
        
        // Regular Users
        [
            'name' => 'Pengguna Satu',
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => 'user123',
            'role' => 'user',
            'preferences' => [
                'theme' => 'light',
                'notifications' => true,
                'language' => 'id'
            ]
        ],
        [
            'name' => 'Pengguna Dua',
            'username' => 'user2',
            'email' => 'user2@example.com',
            'password' => 'user456',
            'role' => 'user',
            'preferences' => [
                'theme' => 'dark',
                'notifications' => false,
                'language' => 'id'
            ]
        ]
    ];

    public function run()
    {
        // Get roles created by RoleSeeder
        $adminRole = Role::where('slug', 'admin')->first();
        $approverRole = Role::where('slug', 'approver')->first();
        $userRole = Role::where('slug', 'user')->first();

        if (!$adminRole || !$approverRole || !$userRole) {
            $this->command->error('Required roles not found. Please run role seeder first.');
            return;
        }

        // Create users with role associations
        foreach ($this->users as $userData) {
            $role = match ($userData['role']) {
                'admin' => $adminRole,
                'approver' => $approverRole,
                'user' => $userRole,
                default => null
            };

            if (!$role) {
                $this->command->warn("Invalid role '{$userData['role']}' for user {$userData['email']}");
                continue;
            }

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'uuid' => Str::uuid(),
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'password' => Hash::make($userData['password']),
                    'role_id' => $role->id,
                    'preferences' => json_encode($userData['preferences']),
                    'email_verified_at' => Carbon::now(),
                    'is_active' => true,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now()
                ]
            );
            
            $this->command->info("User {$userData['email']} created/updated");
        }
    }
}