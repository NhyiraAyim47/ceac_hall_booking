<?php

namespace App\Http\Controllers;

use App\Support\Security;
use App\Support\Sql;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/** POST /contact/submit  (used by the contact form via assets/js/main.js) */
class ContactController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        $ip = Security::getClientIp($request);

        if (Security::isIpBlocked($ip)) {
            return response()->json(['success' => false, 'message' => 'Access denied.']);
        }
        if (!Security::checkRateLimit($ip, 'contact', config('ceac.rate_limit_contact'), config('ceac.rate_limit_window'))) {
            return response()->json(['success' => false, 'message' => 'Too many messages from your network. Please wait a few minutes and try again.']);
        }

        $fullName = Security::sanitize($request->input('full_name'));
        $email    = Security::sanitize($request->input('email'));
        $phone    = Security::sanitize($request->input('phone'));
        $subject  = Security::sanitize($request->input('subject'));
        $message  = Security::sanitize($request->input('message'));

        $errors = [];
        if (strlen($fullName) < 2)            $errors[] = 'Full name is required.';
        if (!Security::validateEmail($email)) $errors[] = 'A valid email address is required.';
        if (strlen($subject) < 2)             $errors[] = 'Subject is required.';
        if (strlen($message) < 10)            $errors[] = 'Please provide a message of at least 10 characters.';

        if (!empty($errors)) {
            return response()->json(['success' => false, 'message' => implode(' ', $errors)]);
        }

        Sql::execute(
            "INSERT INTO contact_messages (full_name, email, phone, subject, message, ip_address) VALUES (?,?,?,?,?,?)",
            [$fullName, $email, $phone, $subject, $message, $ip]
        );

        // Auto-reply to the sender
        try {
            $body = view('emails.contact-autoreply', [
                'fullName'    => $fullName,
                'subject'     => $subject,
                'messageText' => $message,
            ])->render();

            Mail::html($body, function ($m) use ($email, $fullName, $subject) {
                $m->to($email, $fullName)
                  ->subject('We received your message — CEAC Hall Booking')
                  ->text("Dear {$fullName}, thank you for contacting CEAC Hall Booking. We have received your message about \"{$subject}\" and will respond within 24 hours. For urgent matters call " . config('ceac.phone'));
            });
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['success' => true, 'message' => 'Your message has been sent successfully! We will respond within 24 hours.']);
    }
}
