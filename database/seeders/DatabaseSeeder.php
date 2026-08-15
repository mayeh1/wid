<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Deliberately does NOT use WithoutModelEvents: several models rely on
     * model events for business logic during seeding (Donation generates
     * its own receipt_number on creating(), and LogsActivity-enabled
     * models record their audit trail via created/updated events).
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
        $this->call(DemoActivitySeeder::class);
    }
}
