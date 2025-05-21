<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all seeders in the correct order to maintain referential integrity
        $this->call([
            // Roles should be seeded first as users depend on them
            RoleSeeder::class,
            
            // Users next as other tables reference them
            UserSeeder::class,
            
            // Independent entities next
            KendaraanSeeder::class,
            DriverSeeder::class,
            
            // Dependent entities last
            PemesananSeeder::class,
            PersetujuanSeeder::class,
            
            // Logs should be seeded last as they depend on all other entities
            LogAplikasiSeeder::class,
        ]);
        
        // Uncomment to add additional random users for testing
        // User::factory(5)->create();
    }
}
