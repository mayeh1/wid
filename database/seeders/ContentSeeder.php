<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Download;
use App\Models\Event;
use App\Models\Faq;
use App\Models\GalleryAlbum;
use App\Models\HeroSlide;
use App\Models\Menu;
use App\Models\NewsPost;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSiteSettings();
        $this->seedTeam();
        $this->seedPrograms();
        $this->seedProjects();
        $this->seedEvents();
        $this->seedBlog();
        $this->seedNews();
        $this->seedGallery();
        $this->seedTestimonials();
        $this->seedPartners();
        $this->seedFaqs();
        $this->seedDownloads();
        $this->seedHeroSlides();
        $this->seedMenu();
    }

    private function seedSiteSettings(): void
    {
        SiteSetting::current()->update([
            'site_name' => 'Women in Development',
            'tagline' => 'Where Women Become Legends',
            'mission_statement' => 'Women in Development exists to restore dignity, create opportunity, and unlock the potential of women and girls through employment pathways, entrepreneurship, financial education, leadership development, mentorship, scholarships, humanitarian support, and community transformation.',
            'ein' => '42-4104600',
            'founder_name' => 'Carmella Maduekwe',
            'contact_email' => 'hello@womenindevelopmentempire.org',
            'contact_phone' => '(317) 555-0142',
            'address' => 'Indianapolis, Indiana, USA',
            'office_hours' => 'Monday - Friday, 9:00 AM - 5:00 PM EST',
            'facebook_url' => 'https://facebook.com/womenindevelopment',
            'instagram_url' => 'https://instagram.com/womenindevelopment',
            'linkedin_url' => 'https://linkedin.com/company/womenindevelopment',
            'women_empowered_count' => 3200,
            'scholarships_awarded_count' => 185,
            'communities_reached_count' => 42,
            'projects_completed_count' => 67,
            'meta_title' => 'Women in Development, Inc. — Empowering Women, Transforming Lives',
            'meta_description' => 'Women in Development, Inc. is an Indiana 501(c)(3) nonprofit empowering women and girls through entrepreneurship, financial literacy, leadership development, scholarships, and humanitarian support.',
        ]);
    }

    private function seedTeam(): void
    {
        TeamMember::query()->delete();

        TeamMember::create([
            'name' => 'Carmella Maduekwe',
            'role_title' => 'Founder & Chairperson',
            'category' => 'founder',
            'bio' => 'Carmella founded Women in Development to restore dignity and create opportunity for women and girls facing barriers to economic self-sufficiency. Under her leadership, WID has grown into a multi-program organization spanning entrepreneurship, financial literacy, and scholarships.',
            'order' => 1,
        ]);

        TeamMember::create([
            'name' => 'Charles Maduekwe',
            'role_title' => 'Vice Chairperson',
            'category' => 'board_member',
            'bio' => 'Charles brings governance and operational oversight experience to the board, supporting WID\'s strategic direction and fiduciary responsibilities.',
            'order' => 2,
        ]);

        $boardNames = ['Angela Whitfield', 'Deborah Okonkwo', 'Priya Ramanathan', 'Monica Fields'];
        foreach ($boardNames as $i => $name) {
            TeamMember::create([
                'name' => $name,
                'role_title' => 'Board Member',
                'category' => 'board_member',
                'bio' => 'A dedicated board member supporting WID\'s mission through governance, fundraising, and community engagement.',
                'order' => 3 + $i,
            ]);
        }

        $staffNames = ['Renee Castillo' => 'Program Director', 'Talia Brooks' => 'Development Manager', 'Simone Achebe' => 'Volunteer Coordinator'];
        $i = 0;
        foreach ($staffNames as $name => $title) {
            TeamMember::create([
                'name' => $name,
                'role_title' => $title,
                'category' => 'staff',
                'bio' => "As {$title}, {$name} works directly with participants to deliver WID's programs with excellence and care.",
                'order' => $i++,
            ]);
        }
    }

    private function seedPrograms(): void
    {
        Program::query()->delete();

        $programs = [
            ['title' => 'Employment Readiness Program', 'category' => 'employment', 'excerpt' => 'Job-readiness training, resume support, and interview coaching that opens doors to stable employment.'],
            ['title' => 'Women\'s Entrepreneurship Academy', 'category' => 'entrepreneurship', 'excerpt' => 'Business plan development, startup capital access, and mentorship for women launching their own ventures.'],
            ['title' => 'Financial Literacy & Intelligence', 'category' => 'financial_literacy', 'excerpt' => 'Practical budgeting, credit-building, and long-term wealth strategies tailored to real-life circumstances.'],
            ['title' => 'Legacy Leadership Development', 'category' => 'leadership_development', 'excerpt' => 'Leadership training that multiplies community capacity and prepares women to lead in their organizations.'],
            ['title' => 'Mentorship Circles', 'category' => 'mentorship', 'excerpt' => 'One-on-one and group mentorship pairing participants with experienced professionals and community leaders.'],
            ['title' => 'WID Scholars Scholarship Fund', 'category' => 'scholarships', 'excerpt' => 'Need-based scholarships that open doors to higher education for women and girls in our communities.'],
            ['title' => 'Community Development Initiative', 'category' => 'community_development', 'excerpt' => 'Neighborhood-level projects that build shared infrastructure and economic opportunity.'],
            ['title' => 'Emergency Assistance Fund', 'category' => 'emergency_assistance', 'excerpt' => 'Rapid humanitarian support for women and families facing crisis, from housing insecurity to disaster recovery.'],
            ['title' => "Women's Empowerment Circles", 'category' => 'womens_empowerment', 'excerpt' => 'Peer support groups building confidence, connection, and shared advocacy among participants.'],
        ];

        foreach ($programs as $i => $program) {
            Program::create([
                'title' => $program['title'],
                'slug' => Str::slug($program['title']),
                'category' => $program['category'],
                'excerpt' => $program['excerpt'],
                'description' => '<p>'.$program['excerpt'].'</p><p>This program is delivered by trained WID staff and volunteers, with eligibility criteria that are objective, documented, and consistently administered in line with our governance policies.</p>',
                'success_stories' => '<p>Graduates of this program have gone on to launch businesses, secure stable employment, and become mentors themselves — creating a ripple effect of generational impact.</p>',
                'is_featured' => $i < 4,
                'order' => $i,
            ]);
        }
    }

    private function seedProjects(): void
    {
        Project::query()->delete();

        $projects = [
            ['title' => 'Indianapolis Skills Center Renovation', 'status' => 'current', 'budget' => 250000, 'raised' => 168000, 'progress' => 67, 'location' => 'Indianapolis, IN'],
            ['title' => 'Rural Microloan Fund Expansion', 'status' => 'current', 'budget' => 120000, 'raised' => 54000, 'progress' => 45, 'location' => 'Central Indiana'],
            ['title' => 'Digital Literacy Lab', 'status' => 'upcoming', 'budget' => 80000, 'raised' => 12000, 'progress' => 15, 'location' => 'Gary, IN'],
            ['title' => '2025 Scholarship Cohort', 'status' => 'completed', 'budget' => 95000, 'raised' => 95000, 'progress' => 100, 'location' => 'Statewide'],
            ['title' => 'Emergency Housing Relief Drive', 'status' => 'completed', 'budget' => 60000, 'raised' => 61500, 'progress' => 100, 'location' => 'Marion County, IN'],
        ];

        foreach ($projects as $i => $project) {
            Project::create([
                'title' => $project['title'],
                'slug' => Str::slug($project['title']),
                'status' => $project['status'],
                'excerpt' => 'A WID initiative advancing community transformation through direct investment and hands-on support.',
                'description' => '<p>This project reflects WID\'s commitment to measurable, community-driven impact. Funds are tracked separately in accordance with our Financial Controls Policy, and progress is reported to the Board at regular intervals.</p>',
                'budget' => $project['budget'],
                'raised' => $project['raised'],
                'progress_percent' => $project['progress'],
                'location' => $project['location'],
                'start_date' => now()->subMonths(6 + $i),
                'timeline' => [
                    ['date' => now()->subMonths(6 + $i)->format('M Y'), 'label' => 'Project launched'],
                    ['date' => now()->subMonths(3 + $i)->format('M Y'), 'label' => 'Mid-point review completed'],
                ],
                'is_featured' => $i < 3,
            ]);
        }
    }

    private function seedEvents(): void
    {
        Event::query()->delete();

        $upcoming = [
            ['title' => 'Annual Legacy Gala', 'days' => 21, 'location' => 'JW Marriott, Indianapolis, IN'],
            ['title' => "Women's Entrepreneurship Summit", 'days' => 45, 'location' => 'WID Skills Center, Indianapolis, IN'],
            ['title' => 'Volunteer Orientation Workshop', 'days' => 10, 'location' => 'Virtual (Zoom)'],
        ];

        foreach ($upcoming as $event) {
            Event::create([
                'title' => $event['title'],
                'slug' => Str::slug($event['title']),
                'excerpt' => 'Join Women in Development for an evening celebrating our mission and community.',
                'description' => '<p>This event brings together supporters, alumnae, and partners to celebrate the impact of WID\'s programs. Light refreshments will be provided.</p>',
                'starts_at' => now()->addDays($event['days'])->setTime(18, 0),
                'location' => $event['location'],
                'capacity' => 150,
            ]);
        }

        $past = [
            ['title' => '2025 Scholarship Awards Ceremony', 'days' => 90],
            ['title' => 'Financial Literacy Bootcamp', 'days' => 150],
        ];

        foreach ($past as $event) {
            Event::create([
                'title' => $event['title'],
                'slug' => Str::slug($event['title']),
                'excerpt' => 'A past WID event celebrating our community.',
                'description' => '<p>This past event was a wonderful success thanks to our supporters and volunteers.</p>',
                'starts_at' => now()->subDays($event['days']),
                'location' => 'Indianapolis, IN',
            ]);
        }
    }

    private function seedBlog(): void
    {
        BlogCategory::query()->delete();
        BlogPost::query()->delete();

        $categories = ['Entrepreneurship', 'Financial Literacy', 'Leadership', 'Community Impact'];
        $categoryModels = collect($categories)->map(fn ($name) => BlogCategory::create(['name' => $name, 'slug' => Str::slug($name)]));

        $author = User::first();

        $posts = [
            'Five Lessons from Our First Cohort of Women Entrepreneurs',
            'Why Financial Literacy Changes Everything',
            'Building Leaders Who Multiply Impact',
            'How a $500 Microloan Became a Thriving Bakery',
            'The Power of Mentorship in Career Transitions',
            'Behind the Scenes: Our Scholarship Selection Process',
        ];

        foreach ($posts as $i => $title) {
            BlogPost::create([
                'blog_category_id' => $categoryModels[$i % $categoryModels->count()]->id,
                'author_id' => $author?->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'excerpt' => 'A look at the real, on-the-ground impact of WID\'s programs — told through the stories of the women who lived them.',
                'body' => '<p>'.$title.'</p><p>Every program at Women in Development is grounded in the belief that opportunity, when paired with dignity and support, transforms not just one life but generations to come.</p>',
                'tags' => ['empowerment', 'community'],
                'is_featured' => $i === 0,
                'published_at' => now()->subDays($i * 5),
            ]);
        }
    }

    private function seedNews(): void
    {
        NewsPost::query()->delete();

        $items = [
            ['title' => 'WID Announces Expansion of Scholarship Fund for 2026', 'type' => 'press_release'],
            ['title' => 'New Partnership with Central Indiana Community Foundation', 'type' => 'announcement'],
            ['title' => 'Women in Development Named Nonprofit of the Year', 'type' => 'press_release'],
            ['title' => 'Registration Now Open for the Annual Legacy Gala', 'type' => 'announcement'],
        ];

        foreach ($items as $i => $item) {
            NewsPost::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'type' => $item['type'],
                'excerpt' => 'Read the latest update from Women in Development, Inc.',
                'body' => '<p>'.$item['title'].'</p><p>This announcement reflects our ongoing commitment to transparency and community engagement.</p>',
                'published_at' => now()->subDays($i * 7),
            ]);
        }
    }

    private function seedGallery(): void
    {
        GalleryAlbum::query()->delete();

        $albums = ['2025 Legacy Gala', 'Entrepreneurship Academy Graduation', 'Community Outreach Day'];
        foreach ($albums as $i => $title) {
            GalleryAlbum::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => 'Photos from this WID event.',
                'type' => 'photos',
                'order' => $i,
            ]);
        }
    }

    private function seedTestimonials(): void
    {
        Testimonial::query()->delete();

        $testimonials = [
            ['name' => 'Marisol Vega', 'role' => 'Entrepreneurship Academy Graduate', 'quote' => 'WID gave me the tools and the confidence to open my own business. I went from unemployed to employing three other women in my community.'],
            ['name' => 'Keisha Johnson', 'role' => 'Scholarship Recipient', 'quote' => 'The scholarship didn\'t just pay for tuition — it told me someone believed I was worth investing in.'],
            ['name' => 'Fatima Al-Rashid', 'role' => 'Financial Literacy Program', 'quote' => 'I finally understand my finances. For the first time, I have a savings plan and a path forward.'],
            ['name' => 'Angela Brooks', 'role' => 'Mentorship Circle Participant', 'quote' => 'My mentor became someone I could call for anything — career advice, encouragement, or just a listening ear.'],
        ];

        foreach ($testimonials as $i => $t) {
            Testimonial::create([
                'name' => $t['name'],
                'role' => $t['role'],
                'quote' => $t['quote'],
                'rating' => 5,
                'is_featured' => true,
                'order' => $i,
            ]);
        }
    }

    private function seedPartners(): void
    {
        Partner::query()->delete();

        $partners = ['Central Indiana Community Foundation', 'United Way of Central Indiana', 'Indiana Women\'s Business Center', 'Lilly Endowment'];
        foreach ($partners as $i => $name) {
            Partner::create(['name' => $name, 'order' => $i]);
        }
    }

    private function seedFaqs(): void
    {
        Faq::query()->delete();

        $faqs = [
            ['category' => 'Donations', 'question' => 'Is my donation tax-deductible?', 'answer' => 'Yes. Women in Development, Inc. is a 501(c)(3) nonprofit, and donations are tax-deductible to the extent allowed by law.'],
            ['category' => 'Donations', 'question' => 'Can I donate to a specific project?', 'answer' => 'Yes, you can direct your donation to a specific project or campaign during checkout.'],
            ['category' => 'Programs', 'question' => 'Who is eligible for WID programs?', 'answer' => 'Eligibility varies by program and is based on objective, documented criteria such as financial need or geography. Contact us to learn more about a specific program.'],
            ['category' => 'Programs', 'question' => 'How do I apply for a scholarship?', 'answer' => 'Visit our Programs page and select the Scholarships program to view current application details.'],
            ['category' => 'Volunteering', 'question' => 'How can I volunteer?', 'answer' => 'Visit our Volunteer page to register your interest, skills, and availability. Our Volunteer Manager will follow up with next steps.'],
            ['category' => 'General', 'question' => 'Where is Women in Development located?', 'answer' => 'Our principal office is located in Indiana. Many of our programs are also delivered virtually or at community partner sites.'],
        ];

        foreach ($faqs as $i => $faq) {
            Faq::create([...$faq, 'order' => $i]);
        }
    }

    private function seedDownloads(): void
    {
        Download::query()->delete();
    }

    private function seedHeroSlides(): void
    {
        HeroSlide::query()->delete();
    }

    private function seedMenu(): void
    {
        Menu::query()->delete();

        $menu = Menu::create(['name' => 'Primary Navigation', 'slug' => 'primary']);

        $items = [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Programs', 'url' => '/programs'],
            ['label' => 'Projects', 'url' => '/projects'],
            ['label' => 'Events', 'url' => '/events'],
            ['label' => 'Blog', 'url' => '/blog'],
            ['label' => 'Contact', 'url' => '/contact'],
        ];

        foreach ($items as $i => $item) {
            $menu->items()->create([...$item, 'order' => $i]);
        }
    }
}
