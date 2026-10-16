<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#F3F4F6;font-family:Inter,Arial,sans-serif;'>
<div style='max-width:600px;margin:32px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);'>

    <!-- Header -->
    <div style='background:linear-gradient(135deg,#0D1117,#1A2E4A);padding:28px 32px;border-bottom:3px solid #FF6B00;'>
        <img src='{{ base_url() }}/assets/images/logo.png' alt='CEAC Logo'
             style='height:44px;object-fit:contain;margin-bottom:12px;display:block;'>
        <h2 style='color:white;margin:0;font-size:1.2rem;'>CEAC Hall Booking System</h2>
        <p style='color:rgba(255,255,255,0.5);margin:4px 0 0;font-size:0.82rem;'>Christ Embassy Airport City &bull; Accra, Ghana</p>
    </div>

    <!-- Status Banner -->
    <div style='background:{{ $statusColor }};padding:16px 32px;text-align:center;'>
        <span style='color:white;font-weight:800;font-size:1rem;letter-spacing:1px;'>{{ $statusLabel }}</span>
    </div>

    <!-- Body -->
    <div style='padding:32px;'>
        <p style='margin-top:0;'>Dear <strong>{{ $fullName }}</strong>,</p>

@if ($action === 'approve')
        <p>We are pleased to inform you that your booking request for <strong>{{ $hallName }}</strong> has been <strong style='color:#10B981;'>APPROVED</strong>.</p>
        <p>Please take note of the following:</p>
        <ul>
            <li>Arrive at least <strong>15 minutes before</strong> your event start time.</li>
            <li>Present your booking reference <strong>{{ $ref }}</strong> upon arrival.</li>
            <li>Ensure all attendees comply with the CEAC Hall usage policy.</li>
            <li>The hall must be vacated by your stated end time.</li>
        </ul>
        <div style='background:#EFF6FF;border-left:4px solid #3B82F6;padding:16px;border-radius:8px;margin:16px 0;'>
            <strong style='color:#1E40AF;'>Payment Instructions</strong>
            <p style='color:#374151;margin:8px 0 0;font-size:0.9rem;'>
                If paying by bank transfer or Mobile Money, please reference your payment with your
                booking reference number, <strong>{{ $ref }}</strong>, so we can promptly match and confirm your payment.
            </p>
        </div>
        <p>We look forward to hosting your event. God bless you!</p>
@elseif ($action === 'reject')
        <p>We regret to inform you that your booking request for <strong>{{ $hallName }}</strong> on <strong>{{ $formattedDate }}</strong> could not be approved at this time.</p>
@if (!empty($reason))
        <div style='background:#FEF2F2;border-left:4px solid #EF4444;padding:16px;border-radius:8px;margin:16px 0;'>
            <strong style='color:#991B1B;'>Reason for Rejection:</strong><br>
            <p style='color:#374151;margin:8px 0 0;'>{{ $reason }}</p>
        </div>
@endif
        <p>We encourage you to:</p>
        <ul>
            <li>Choose a different date or time and submit a new booking request.</li>
            <li>Contact us directly at <strong>{{ config('ceac.phone') }}</strong> to discuss alternative arrangements.</li>
        </ul>
        <p>We apologise for any inconvenience caused.</p>
@elseif ($action === 'cancel')
        <p>Please be informed that your approved booking for <strong>{{ $hallName }}</strong> on <strong>{{ $formattedDate }}</strong> has been <strong style='color:#F59E0B;'>CANCELLED</strong>.</p>
@if (!empty($reason))
        <div style='background:#FFFBEB;border-left:4px solid #F59E0B;padding:16px;border-radius:8px;margin:16px 0;'>
            <strong style='color:#92400E;'>Reason for Cancellation:</strong><br>
            <p style='color:#374151;margin:8px 0 0;'>{{ $reason }}</p>
        </div>
@endif
        <p>If you did not request this cancellation or need assistance, please contact us immediately at:</p>
        <ul>
            <li>Phone: <strong>{{ config('ceac.phone') }}</strong></li>
            <li>Email: <a href='mailto:{{ config('ceac.email') }}'>{{ config('ceac.email') }}</a></li>
        </ul>
        <p>We apologise for any inconvenience and hope to serve you again soon.</p>
@elseif ($action === 'complete')
        <p>Your event at the <strong>{{ $hallName }}</strong> on <strong>{{ $formattedDate }}</strong> has been marked as <strong style='color:#6366F1;'>COMPLETED</strong>.</p>
        <p>We hope your event was a great success and a blessing to all who attended. It was a pleasure hosting you at Christ Embassy Airport City.</p>
        <p>We look forward to welcoming you again soon. God bless you!</p>
@else
        <p>Your booking (Ref: <strong>{{ $ref }}</strong>) has been updated to <strong>{{ $newStatus }}</strong>.</p>
@endif

        <!-- Booking Details Table -->
        <div style='background:#F9FAFB;border-radius:10px;padding:20px;margin:20px 0;border:1px solid #E5E7EB;'>
            <h4 style='margin:0 0 12px;font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;color:#6B7280;'>Booking Details</h4>
            <table style='width:100%;border-collapse:collapse;'>
                <tr>
                    <td style='padding:7px 0;color:#6B7280;font-size:0.88rem;width:40%;'>Reference</td>
                    <td style='padding:7px 0;font-weight:700;color:#FF6B00;font-size:0.88rem;'>{{ $ref }}</td>
                </tr>
                <tr>
                    <td style='padding:7px 0;color:#6B7280;font-size:0.88rem;border-top:1px solid #E5E7EB;'>Hall</td>
                    <td style='padding:7px 0;font-weight:600;font-size:0.88rem;border-top:1px solid #E5E7EB;'>{{ $hallName }}</td>
                </tr>
                <tr>
                    <td style='padding:7px 0;color:#6B7280;font-size:0.88rem;border-top:1px solid #E5E7EB;'>Event</td>
                    <td style='padding:7px 0;font-weight:600;font-size:0.88rem;border-top:1px solid #E5E7EB;'>{{ $eventTitle }}</td>
                </tr>
                <tr>
                    <td style='padding:7px 0;color:#6B7280;font-size:0.88rem;border-top:1px solid #E5E7EB;'>Date</td>
                    <td style='padding:7px 0;font-size:0.88rem;border-top:1px solid #E5E7EB;'>{{ $formattedDate }}</td>
                </tr>
                <tr>
                    <td style='padding:7px 0;color:#6B7280;font-size:0.88rem;border-top:1px solid #E5E7EB;'>Time</td>
                    <td style='padding:7px 0;font-size:0.88rem;border-top:1px solid #E5E7EB;'>{{ $formattedStart }} – {{ $formattedEnd }}</td>
                </tr>
                <tr>
                    <td style='padding:7px 0;color:#6B7280;font-size:0.88rem;border-top:1px solid #E5E7EB;'>Status</td>
                    <td style='padding:7px 0;border-top:1px solid #E5E7EB;'>
                        <span style='background:{{ $statusColor }};color:white;padding:3px 12px;border-radius:100px;font-size:0.75rem;font-weight:700;'>{{ $statusLabel }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Contact Info -->
        <div style='background:#FFF7ED;border:1px solid #FED7AA;border-radius:10px;padding:16px;margin-top:20px;'>
            <p style='margin:0;font-size:0.85rem;color:#92400E;'>
                <strong>Need help?</strong> Contact our hall administration team:<br>
                📞 <a href='tel:{{ config('ceac.phone') }}' style='color:#C2410C;'>{{ config('ceac.phone') }}</a> &nbsp;|&nbsp;
                ✉️ <a href='mailto:{{ config('ceac.email') }}' style='color:#C2410C;'>{{ config('ceac.email') }}</a>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div style='background:#F9FAFB;padding:20px 32px;border-top:1px solid #E5E7EB;text-align:center;'>
        <p style='margin:0;font-size:0.78rem;color:#9CA3AF;'>
            &copy; {{ date('Y') }} Christ Embassy Airport City &bull; Airport City Zone, Accra Ghana<br>
            This is an automated message from the CEAC Hall Booking System.
        </p>
    </div>
</div>
</body>
</html>
