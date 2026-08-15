<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPaymentMethods();
        $this->seedCampaigns();
    }

    private function seedPaymentMethods(): void
    {
        PaymentMethod::query()->delete();

        $manualMethods = [
            ['name' => 'Bank Transfer', 'instructions' => "Bank: Chase Bank\nAccount Name: Women in Development, Inc.\nRouting Number: 021000021\nAccount Number: Contact us for details\n\nPlease include your name and \"Donation\" in the memo."],
            ['name' => 'CashApp', 'instructions' => "Send your donation to \$WIDEmpowers on CashApp.\nPlease include your name in the payment note."],
            ['name' => 'Zelle', 'instructions' => "Send your donation via Zelle to donate@womenindevelopmentempire.org.\nPlease include your name in the memo."],
            ['name' => 'MTN Mobile Money', 'instructions' => "Send your donation via MTN Mobile Money to +234 800 000 0000.\nPlease include your name as the reference."],
            ['name' => 'Orange Money', 'instructions' => "Send your donation via Orange Money to +234 800 000 0001.\nPlease include your name as the reference."],
            ['name' => 'Crypto Wallet', 'instructions' => "BTC: bc1q000000000000000000000000000000000\nETH: 0x0000000000000000000000000000000000\n\nPlease email your transaction ID to donate@womenindevelopmentempire.org so we can send your receipt."],
        ];

        foreach ($manualMethods as $i => $method) {
            PaymentMethod::create([
                'name' => $method['name'],
                'slug' => Str::slug($method['name']),
                'type' => 'manual',
                'instructions' => $method['instructions'],
                'is_enabled' => true,
                'order' => $i,
            ]);
        }

        // Gateway methods ship disabled until an admin adds real API credentials
        // in Payment Methods — the driver code is fully wired either way.
        $gatewayMethods = [
            ['name' => 'Credit / Debit Card (Stripe)', 'driver' => 'stripe'],
            ['name' => 'PayPal', 'driver' => 'paypal'],
            ['name' => 'Paystack', 'driver' => 'paystack'],
            ['name' => 'Flutterwave', 'driver' => 'flutterwave'],
        ];

        foreach ($gatewayMethods as $i => $method) {
            PaymentMethod::create([
                'name' => $method['name'],
                'slug' => Str::slug($method['name']),
                'type' => 'gateway',
                'driver' => $method['driver'],
                'config' => [],
                'is_enabled' => false,
                'order' => count($manualMethods) + $i,
            ]);
        }
    }

    private function seedCampaigns(): void
    {
        Campaign::query()->delete();

        $campaigns = [
            ['title' => '2026 Scholarship Fund Drive', 'category' => 'Scholarships', 'target' => 100000],
            ['title' => 'Skills Center Equipment Upgrade', 'category' => 'Community Development', 'target' => 45000],
            ['title' => 'Emergency Relief Reserve', 'category' => 'Emergency Assistance', 'target' => 30000],
        ];

        foreach ($campaigns as $i => $campaign) {
            Campaign::create([
                'title' => $campaign['title'],
                'slug' => Str::slug($campaign['title']),
                'category' => $campaign['category'],
                'excerpt' => 'Help us reach our goal and expand our impact for women and girls in our community.',
                'description' => '<p>This campaign directly funds WID programs, with all contributions tracked separately in accordance with our Financial Controls Policy.</p>',
                'target_amount' => $campaign['target'],
                'start_date' => now()->subMonths(2),
                'updates' => [
                    ['date' => now()->subMonth()->format('M j, Y'), 'text' => 'Campaign launched with strong early community support.'],
                ],
                'is_featured' => $i === 0,
            ]);
        }
    }
}
