<p>Dear <strong>{{ $fullName }}</strong>,</p>
<p>Thank you for your booking request at <strong>{{ $hallName }}</strong>.</p>
<table style='border-collapse:collapse;width:100%;max-width:500px;'>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Booking Reference</td><td style='padding:8px;border:1px solid #eee;font-weight:bold;color:#FF6B00;'>{{ $ref }}</td></tr>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Hall</td><td style='padding:8px;border:1px solid #eee;font-weight:bold;'>{{ $hallName }}</td></tr>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Event</td><td style='padding:8px;border:1px solid #eee;'>{{ $eventTitle }}</td></tr>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Event Type</td><td style='padding:8px;border:1px solid #eee;'>{{ $eventTypeLabel }}</td></tr>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Start</td><td style='padding:8px;border:1px solid #eee;'>{{ $startDisplay }}</td></tr>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>End</td><td style='padding:8px;border:1px solid #eee;'>{{ $endDisplay }}</td></tr>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Attendees</td><td style='padding:8px;border:1px solid #eee;'>{{ $attendees }} people</td></tr>
    <tr><td style='padding:8px;border:1px solid #eee;color:#666;'>Status</td><td style='padding:8px;border:1px solid #eee;'><span style='color:#F59E0B;font-weight:bold;'>PENDING REVIEW</span></td></tr>
    {!! $pricingRowsHtml !!}
</table>
<p>Your request is currently <strong>pending review</strong>. Our admin team will contact you within 24 hours to confirm your booking.</p>
<p>For enquiries: <strong>{{ config('ceac.phone') }}</strong> | <a href='mailto:{{ config('ceac.email') }}'>{{ config('ceac.email') }}</a></p>
<br>
<p>God bless you,<br><strong>CEAC Hall Administration</strong><br>Christ Embassy Airport City, Accra Ghana</p>
