<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #1F0F28; font-size: 12px; }
        .header { border-bottom: 3px solid #5B2C83; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { color: #5B2C83; font-size: 20px; margin: 0 0 4px; }
        .header p { color: #D4AF37; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
        h2 { color: #5B2C83; font-size: 15px; margin-top: 30px; border-bottom: 1px solid #eee; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 6px 4px; border-bottom: 1px solid #eee; }
        th { color: #666; font-size: 11px; text-transform: uppercase; }
        .stat-grid { display: table; width: 100%; margin-top: 10px; }
        .stat { display: table-cell; width: 25%; text-align: center; padding: 10px; }
        .stat .value { font-size: 20px; font-weight: bold; color: #5B2C83; }
        .stat .label { font-size: 10px; color: #888; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Women in Development, Inc.</h1>
        <p>Organizational Summary Report &mdash; Generated {{ $generatedAt->format('F j, Y g:i A') }}</p>
    </div>

    <h2>Donations</h2>
    <div class="stat-grid">
        <div class="stat"><div class="value">${{ number_format($totalRaised, 0) }}</div><div class="label">Total Raised</div></div>
        <div class="stat"><div class="value">{{ $totalDonations }}</div><div class="label">Completed Donations</div></div>
        <div class="stat"><div class="value">{{ $pendingDonations }}</div><div class="label">Pending Donations</div></div>
    </div>

    <h2>Fundraising Campaigns</h2>
    <table>
        <tr><th>Campaign</th><th>Goal</th><th>Raised</th><th>Progress</th></tr>
        @forelse ($campaigns as $campaign)
            <tr>
                <td>{{ $campaign->title }}</td>
                <td>${{ number_format($campaign->target_amount, 0) }}</td>
                <td>${{ number_format($campaign->raisedAmount(), 0) }}</td>
                <td>{{ $campaign->progressPercent() }}%</td>
            </tr>
        @empty
            <tr><td colspan="4">No active campaigns.</td></tr>
        @endforelse
    </table>

    <h2>Volunteers</h2>
    <div class="stat-grid">
        <div class="stat"><div class="value">{{ $volunteerApproved }}</div><div class="label">Approved Volunteers</div></div>
        <div class="stat"><div class="value">{{ $volunteerPending }}</div><div class="label">Pending Applications</div></div>
        <div class="stat"><div class="value">{{ number_format($volunteerHours, 1) }}</div><div class="label">Total Hours Logged</div></div>
    </div>
</body>
</html>
