<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Seeder;

class DemoActivitySeeder extends Seeder
{
    public function run(): void
    {
        $this->seedVolunteers();
        $this->seedDonations();
    }

    private function seedVolunteers(): void
    {
        $volunteers = [
            ['name' => 'Renata Solis', 'status' => 'approved', 'skills' => ['Event Planning', 'Mentoring'], 'hours' => [3.5, 2, 4]],
            ['name' => 'Naomi Ferris', 'status' => 'approved', 'skills' => ['Marketing', 'Photography'], 'hours' => [2, 2, 2, 1.5]],
            ['name' => 'Priya Desai', 'status' => 'pending', 'skills' => ['Financial Literacy Coaching'], 'hours' => []],
            ['name' => 'Grace Adeyemi', 'status' => 'approved', 'skills' => ['Translation', 'Community Outreach'], 'hours' => [5, 3]],
        ];

        foreach ($volunteers as $data) {
            $user = User::firstOrCreate(
                ['email' => str($data['name'])->slug('.').'@example.com'],
                ['name' => $data['name'], 'password' => bcrypt('password'), 'status' => 'approved']
            );

            $volunteer = Volunteer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => '555-'.random_int(1000, 9999),
                    'skills' => $data['skills'],
                    'availability' => 'Weekday evenings and weekends',
                    'bio' => "{$data['name']} is passionate about empowering women in the community.",
                    'status' => $data['status'],
                    'approved_at' => $data['status'] === 'approved' ? now()->subWeeks(random_int(1, 8)) : null,
                ]
            );

            foreach ($data['hours'] as $i => $hours) {
                $volunteer->hourLogs()->create([
                    'date' => now()->subWeeks($i + 1),
                    'hours' => $hours,
                    'activity' => 'Community program support',
                ]);
            }
        }
    }

    private function seedDonations(): void
    {
        $bankTransfer = PaymentMethod::where('slug', 'bank-transfer')->first();
        $campaign = Campaign::first();
        $project = Project::where('status', 'current')->first();

        $donors = [
            ['name' => 'Elena Marsh', 'email' => 'elena.marsh@example.com', 'amount' => 250, 'status' => 'completed', 'anonymous' => false],
            ['name' => 'James Okoye', 'email' => 'james.okoye@example.com', 'amount' => 100, 'status' => 'completed', 'anonymous' => false],
            ['name' => 'Anonymous', 'email' => 'donor.anon@example.com', 'amount' => 500, 'status' => 'completed', 'anonymous' => true],
            ['name' => 'Sophia Chen', 'email' => 'sophia.chen@example.com', 'amount' => 75, 'status' => 'completed', 'anonymous' => false],
            ['name' => 'Marcus Webb', 'email' => 'marcus.webb@example.com', 'amount' => 1000, 'status' => 'completed', 'anonymous' => false],
            ['name' => 'Ade Bello', 'email' => 'ade.bello@example.com', 'amount' => 50, 'status' => 'pending', 'anonymous' => false],
            ['name' => 'Linda Park', 'email' => 'linda.park@example.com', 'amount' => 150, 'status' => 'pending', 'anonymous' => false],
        ];

        foreach ($donors as $i => $donor) {
            $donation = Donation::create([
                'payment_method_id' => $bankTransfer?->id,
                'campaign_id' => $i % 2 === 0 ? $campaign?->id : null,
                'project_id' => $i % 2 === 1 ? $project?->id : null,
                'donor_name' => $donor['name'],
                'donor_email' => $donor['email'],
                'is_anonymous' => $donor['anonymous'],
                'amount' => $donor['amount'],
                'frequency' => $i % 3 === 0 ? 'monthly' : 'one_time',
                'status' => $donor['status'],
                'approved_at' => $donor['status'] === 'completed' ? now()->subDays(random_int(1, 60)) : null,
            ]);

            $backdated = now()->subDays(random_int(1, 90));
            $donation->forceFill(['created_at' => $backdated, 'updated_at' => $backdated])->save();
        }
    }
}
