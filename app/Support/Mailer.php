<?php

namespace App\Support;

use Illuminate\Support\Facades\Mail;

/**
 * Small helpers around Laravel's mailer.
 * SMTP details (host, port, username, app password) come from the .env file.
 */
class Mailer
{
    /**
     * Send an HTML email. Throws on failure - callers decide whether to catch.
     */
    public static function sendHtml(
        string $to,
        ?string $toName,
        string $subject,
        string $html,
        string $alt = '',
        bool $replyToSite = false
    ): void {
        Mail::html($html, function ($message) use ($to, $toName, $subject, $alt, $replyToSite) {
            $message->to($to, $toName ?: null)->subject($subject);
            if ($alt !== '') {
                $message->text($alt);
            }
            if ($replyToSite) {
                $message->replyTo(config('ceac.mail_from'), 'CEAC Hall Booking');
            }
        });
    }

    /** The branded "message from our team" wrapper used for admin replies. */
    public static function styledReply(string $recipientName, string $messageBody): string
    {
        return view('emails.styled-reply', [
            'recipientName' => $recipientName,
            'messageBody'   => $messageBody,
        ])->render();
    }
}
