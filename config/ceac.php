<?php

/*
|--------------------------------------------------------------------------
| CEAC Hall Booking - site settings
|--------------------------------------------------------------------------
| These replace the constants that used to live in includes/config.php.
| Secrets (database password, mail password) are NOT stored here; they live
| in the .env file, which is never committed or uploaded to the web root.
*/

return [
    'version'   => '1.0.0',
    'site_name' => 'CEAC Hall Booking',
    'site_full' => 'Christ Embassy Airport City – Hall Booking',
    'tagline'   => 'Book Your Space for Kingdom Impact',
    'address'   => '10 Blackberry Street, Estate Legon, Accra, Ghana',
    'phone'     => '024 403 6129',
    'email'     => env('CEAC_EMAIL', 'hallbookingceac@gmail.com'),
    'zone'      => 'Airport City Zone, Accra Ghana',
    'map_link'  => 'https://maps.app.goo.gl/5u64GQH2mHqKvsP6A?g_st=aw',

    // Security
    'max_login_attempts' => 5,
    'lockout_minutes'    => 3,
    'rate_limit_booking' => 3,   // max bookings per IP per window
    'rate_limit_contact' => 5,   // max contact messages per IP per window
    'rate_limit_window'  => 5,   // minutes

    // Email
    'mail_admin'     => env('CEAC_EMAIL', 'hallbookingceac@gmail.com'),
    'mail_from'      => env('MAIL_FROM_ADDRESS', 'hallbookingceac@gmail.com'),
    'mail_from_name' => env('MAIL_FROM_NAME', 'CEAC Hall Booking System'),

    // Overrun buffer (minutes) used by the dashboard / payments page
    'overrun_buffer' => 15,
];
