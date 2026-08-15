<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        // Dev-only credentials (password: "password"). Change before any
        // non-local deployment.
        $founder = User::factory()->create([
            'name' => 'Carmella Maduekwe',
            'email' => 'admin@womenindevelopmentempire.org',
        ]);
        $founder->assignRole('Super Admin');

        $this->call(ContentSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(EngagementSeeder::class);
        $this->call(StaticPageSeeder::class);
    }
}
