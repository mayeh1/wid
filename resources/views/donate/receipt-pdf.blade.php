<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #1F0F28; font-size: 13px; }
        .header { border-bottom: 3px solid #5B2C83; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { color: #5B2C83; font-size: 20px; margin: 0 0 4px; }
        .header p { color: #D4AF37; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 8px 0; border-bottom: 1px solid #eee; }
        td.label { color: #666; width: 40%; }
        td.value { font-weight: bold; }
        .amount { font-size: 24px; color: #5B2C83; font-weight: bold; margin: 20px 0; }
        .footer { margin-top: 40px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Women in Development, Inc.</h1>
        <p>Official Donation Receipt</p>
    </div>

    <p>Receipt #: <strong>{{ $donation->receipt_number }}</strong></p>
    <p>Date: {{ $donation->created_at->format('F j, Y') }}</p>

    <div class="amount">${{ number_format($donation->amount, 2) }}</div>

    <table>
        <tr><td class="label">Donor</td><td class="value">{{ $donation->displayName() }}</td></tr>
        <tr><td class="label">Email</td><td class="value">{{ $donation->donor_email }}</td></tr>
        <tr><td class="label">Frequency</td><td class="value">{{ ucfirst(str_replace('_', ' ', $donation->frequency)) }}</td></tr>
        <tr><td class="label">Payment Method</td><td class="value">{{ $donation->paymentMethod?->name ?? 'N/A' }}</td></tr>
        @if ($donation->campaign)
            <tr><td class="label">Campaign</td><td class="value">{{ $donation->campaign->title }}</td></tr>
        @endif
        @if ($donation->project)
            <tr><td class="label">Project</td><td class="value">{{ $donation->project->title }}</td></tr>
        @endif
    </table>

    <p style="margin-top: 24px;">
        Thank you for your generous support of Women in Development, Inc. Your contribution directly
        funds employment pathways, entrepreneurship, financial literacy, leadership development,
        mentorship, scholarships, and humanitarian support for women and girls.
    </p>

    <div class="footer">
        <p>Women in Development, Inc. is a nonprofit corporation organized under Indiana law and intended
        to qualify under Internal Revenue Code &sect; 501(c)(3). EIN: 42-4104600. No goods or services
        were provided in exchange for this contribution unless otherwise noted. Please retain this
        receipt for your tax records.</p>
    </div>
</body>
</html>
