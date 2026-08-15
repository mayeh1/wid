<?php

namespace Database\Seeders;

use App\Models\MembershipLevel;
use App\Models\SuccessStory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EngagementSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMembershipLevels();
        $this->seedSuccessStories();
    }

    private function seedMembershipLevels(): void
    {
        MembershipLevel::query()->delete();

        $levels = [
            ['name' => 'Friend', 'price' => 25, 'perks' => ['Quarterly newsletter', 'Invitations to community events']],
            ['name' => 'Advocate', 'price' => 100, 'perks' => ['All Friend perks', 'Recognition on our supporter wall', 'Early event registration']],
            ['name' => 'Legacy Circle', 'price' => 500, 'perks' => ['All Advocate perks', 'Invitation to the Annual Legacy Gala', 'Direct program updates from leadership']],
        ];

        foreach ($levels as $i => $level) {
            MembershipLevel::create([
                'name' => $level['name'],
                'slug' => Str::slug($level['name']),
                'description' => "Support WID's mission year-round as a {$level['name']} member.",
                'annual_price' => $level['price'],
                'perks' => $level['perks'],
                'order' => $i,
            ]);
        }
    }

    private function seedSuccessStories(): void
    {
        SuccessStory::query()->delete();

        $stories = [
            ['title' => "From Job Seeker to Business Owner: Marisol's Story", 'category' => 'Entrepreneurship'],
            ['title' => "A Scholarship That Changed Everything: Keisha's Journey", 'category' => 'Scholarships'],
            ['title' => "Finding Financial Freedom: Fatima's Story", 'category' => 'Financial Literacy'],
            ['title' => "Leading Her Community: Angela's Path to Leadership", 'category' => 'Leadership'],
        ];

        foreach ($stories as $story) {
            SuccessStory::create([
                'title' => $story['title'],
                'slug' => Str::slug($story['title']),
                'category' => $story['category'],
                'excerpt' => 'A story of resilience, opportunity, and transformation through Women in Development programs.',
                'story' => '<p>'.$story['title'].'</p><p>Every success story at WID reflects the power of investing in women — not just for one life, but for generations to come.</p>',
                'is_featured' => true,
            ]);
        }
    }
}
