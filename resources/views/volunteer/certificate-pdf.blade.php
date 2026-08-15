<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #1F0F28; text-align: center; padding: 60px; }
        .border { border: 6px double #D4AF37; padding: 60px 40px; }
        h1 { color: #5B2C83; font-size: 14px; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 30px; }
        h2 { font-size: 32px; color: #5B2C83; margin: 20px 0; }
        .name { font-size: 28px; color: #D4AF37; font-weight: bold; margin: 30px 0; border-bottom: 2px solid #5B2C83; display: inline-block; padding-bottom: 10px; }
        p { font-size: 14px; color: #444; line-height: 1.8; }
        .hours { font-size: 20px; color: #5B2C83; font-weight: bold; margin: 20px 0; }
        .footer { margin-top: 60px; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="border">
        <h1>Women in Development, Inc.</h1>
        <h2>Certificate of Volunteer Service</h2>
        <p>This certificate is proudly presented to</p>
        <div class="name">{{ $volunteer->user->name }}</div>
        <p>in recognition of dedicated volunteer service to Women in Development, Inc.,<br>
        contributing to our mission of empowering women and girls through employment pathways,<br>
        entrepreneurship, financial literacy, leadership development, and community transformation.</p>
        <div class="hours">{{ number_format($volunteer->totalHours(), 1) }} Volunteer Hours</div>
        <p>Issued {{ now()->format('F j, Y') }}</p>
        <div class="footer">
            <p>Women in Development, Inc. &bull; An Indiana 501(c)(3) Nonprofit &bull; EIN: 42-4104600</p>
        </div>
    </div>
</body>
</html>
