<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#F3F4F6;font-family:Inter,Arial,sans-serif;'>
<div style='max-width:600px;margin:32px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);'>

    <div style='background:linear-gradient(135deg,#0D1117,#1A2E4A);padding:24px 32px;border-bottom:3px solid #FF6B00;'>
        <h2 style='color:white;margin:0;font-size:1.1rem;'>CEAC Hall Booking</h2>
        <p style='color:rgba(255,255,255,0.5);margin:4px 0 0;font-size:0.8rem;'>
            Christ Embassy Airport City &bull; Accra, Ghana
        </p>
    </div>

    <div style='background:#10B981;padding:12px 32px;'>
        <span style='color:white;font-weight:700;font-size:0.9rem;letter-spacing:1px;'>
            MESSAGE RECEIVED
        </span>
    </div>

    <div style='padding:32px;'>
        <p style='margin-top:0;'>Dear <strong>{{ $fullName }}</strong>,</p>
        <p style='color:#374151;line-height:1.7;'>
            Thank you for reaching out to us. We have received your message and our team will
            respond to you within <strong>24 hours</strong>.
        </p>

        <div style='background:#F9FAFB;border-radius:10px;padding:20px;margin:20px 0;border:1px solid #E5E7EB;'>
            <h4 style='margin:0 0 10px;font-size:0.82rem;text-transform:uppercase;letter-spacing:1px;color:#6B7280;'>
                Your Message Summary
            </h4>
            <p style='margin:0 0 6px;'><strong>Subject:</strong> {{ $subject }}</p>
            <p style='margin:0;color:#6B7280;font-size:0.88rem;white-space:pre-wrap;'>{{ $messageText }}</p>
        </div>

        <div style='background:#FFF7ED;border:1px solid #FED7AA;border-radius:10px;padding:16px;'>
            <p style='margin:0;font-size:0.85rem;color:#92400E;'>
                <strong>Need urgent assistance?</strong><br>
                📞 <a href='tel:{{ config('ceac.phone') }}' style='color:#C2410C;'>{{ config('ceac.phone') }}</a>
                &nbsp;|&nbsp;
                ✉️ <a href='mailto:{{ config('ceac.email') }}' style='color:#C2410C;'>{{ config('ceac.email') }}</a>
            </p>
        </div>
    </div>

    <div style='background:#F9FAFB;padding:16px 32px;border-top:1px solid #E5E7EB;text-align:center;'>
        <p style='margin:0;font-size:0.75rem;color:#9CA3AF;'>
            &copy; {{ date('Y') }} Christ Embassy Airport City &bull; Airport City Zone, Accra Ghana
        </p>
    </div>
</div>
</body>
</html>
