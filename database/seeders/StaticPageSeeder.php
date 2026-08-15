<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'excerpt' => 'How Women in Development, Inc. collects, uses, and protects your information.',
                'content' => $this->privacyPolicyContent(),
                'is_published' => true,
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'terms-and-conditions'],
            [
                'title' => 'Terms & Conditions',
                'excerpt' => 'The terms governing your use of the Women in Development, Inc. website.',
                'content' => $this->termsContent(),
                'is_published' => true,
            ]
        );
    }

    private function privacyPolicyContent(): string
    {
        return <<<'HTML'
            <p>Women in Development, Inc. ("WID," "we," "us") respects your privacy. This policy explains what
            information we collect through this website, how we use it, and the choices available to you.</p>
            <h2>Information We Collect</h2>
            <p>We collect information you voluntarily provide, such as your name, email address, and phone number,
            when you donate, volunteer, become a member, RSVP for an event, comment on a blog post, or contact us.
            We also collect standard technical information (such as IP address and browser type) for security and
            analytics purposes.</p>
            <h2>How We Use Information</h2>
            <p>We use your information to process donations and issue receipts, manage volunteer and membership
            relationships, respond to inquiries, send newsletters and program updates (which you may unsubscribe
            from at any time), and improve our programs and website.</p>
            <h2>Donor Privacy</h2>
            <p>Donor information is protected and used consistently with law, donor expectations, and this policy,
            in accordance with WID's Governance & Policy Manual. We do not sell donor information to third parties.</p>
            <h2>Data Security</h2>
            <p>We use reasonable administrative, technical, and physical safeguards to protect information collected
            through this site, including encrypted storage of sensitive payment gateway credentials.</p>
            <h2>Contact</h2>
            <p>Questions about this policy may be directed to hello@womenindevelopmentempire.org.</p>
        HTML;
    }

    private function termsContent(): string
    {
        return <<<'HTML'
            <p>These Terms & Conditions govern your use of the Women in Development, Inc. website. By using this
            site, you agree to these terms.</p>
            <h2>Use of Content</h2>
            <p>Content on this site is provided for informational purposes about WID's mission and programs. You
            may not reproduce, distribute, or use WID's name, logo, or content for commercial purposes without
            written permission.</p>
            <h2>Donations</h2>
            <p>Donations made through this site are voluntary contributions to Women in Development, Inc., a
            nonprofit corporation intended to qualify under Internal Revenue Code &sect; 501(c)(3). Donations are
            generally non-refundable except as required by law or at WID's discretion.</p>
            <h2>User Conduct</h2>
            <p>You agree not to misuse this site, including submitting false information, attempting unauthorized
            access, or posting unlawful, abusive, or infringing content through comments or forms.</p>
            <h2>Limitation of Liability</h2>
            <p>This site is provided "as is" without warranties of any kind. WID is not liable for any damages
            arising from your use of this site to the fullest extent permitted by law.</p>
            <h2>Changes</h2>
            <p>WID may update these terms from time to time. Continued use of the site after changes constitutes
            acceptance of the revised terms.</p>
        HTML;
    }
}
