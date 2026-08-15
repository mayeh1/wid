<x-mail::message>
# Thank You, {{ $donation->displayName() }}!

Your donation of **${{ number_format($donation->amount, 2) }}** to Women in Development, Inc. has been received.

Receipt #: {{ $donation->receipt_number }}
Date: {{ $donation->created_at->format('F j, Y') }}

Your official PDF receipt is attached to this email for your records.

Your generosity directly funds employment pathways, entrepreneurship, financial literacy, leadership
development, mentorship, scholarships, and humanitarian support for women and girls.

<x-mail::button :url="url('/')">
Visit Our Website
</x-mail::button>

With gratitude,<br>
Women in Development, Inc.
</x-mail::message>
