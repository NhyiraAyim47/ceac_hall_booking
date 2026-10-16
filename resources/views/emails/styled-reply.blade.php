<!DOCTYPE html>
<html><head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#F3F4F6;font-family:Inter,Arial,sans-serif;'>
<div style='max-width:600px;margin:32px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);'>
    <div style='background:linear-gradient(135deg,#0D1117,#1A2E4A);padding:28px 32px;border-bottom:3px solid #FF6B00;'>
        <img src='{{ base_url() }}/assets/images/logo.png' alt='CEAC Logo'
             style='height:44px;object-fit:contain;margin-bottom:12px;display:block;'>
        <h2 style='color:white;margin:0;font-size:1.2rem;'>CEAC Hall Booking System</h2>
        <p style='color:rgba(255,255,255,0.5);margin:4px 0 0;font-size:0.82rem;'>Christ Embassy Airport City &bull; Accra, Ghana</p>
    </div>
    <div style='background:#FF6B00;padding:12px 32px;'>
        <span style='color:white;font-weight:700;font-size:0.9rem;letter-spacing:1px;'>MESSAGE FROM OUR TEAM</span>
    </div>
    <div style='padding:32px;'>
        <p style='margin-top:0;'>Dear <strong>{{ $recipientName }}</strong>,</p>
        <div style='background:#F9FAFB;border-radius:10px;padding:20px;margin:20px 0;border:1px solid #E5E7EB;color:#374151;font-size:0.92rem;line-height:1.8;'>
            {!! nl2br(e($messageBody)) !!}
        </div>
        <div style='background:#FFF7ED;border:1px solid #FED7AA;border-radius:10px;padding:16px;margin-top:24px;'>
            <p style='margin:0 0 10px;font-size:0.85rem;color:#92400E;'>
                <strong>Need further assistance?</strong><br>
                📞 <a href='tel:{{ config('ceac.phone') }}' style='color:#C2410C;'>{{ config('ceac.phone') }}</a>
                &nbsp;|&nbsp;
                ✉️ <a href='https://mail.google.com/mail/?view=cm&to={{ urlencode(config('ceac.email')) }}' target='_blank' style='color:#C2410C;'>{{ config('ceac.email') }}</a>
            </p>
            <a href='{{ base_url() }}/contact' target='_blank'
               style='display:inline-block;background:#FF6B00;color:white;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:700;font-size:0.85rem;'>
                Visit Our Contact Page →
            </a>
        </div>
    </div>
    <div style='background:#F9FAFB;padding:16px 32px;border-top:1px solid #E5E7EB;text-align:center;'>
        <p style='margin:0;font-size:0.75rem;color:#9CA3AF;'>
            &copy; {{ date('Y') }} Christ Embassy Airport City &bull; Airport City Zone, Accra Ghana
        </p>
    </div>
</div>
</body></html>
