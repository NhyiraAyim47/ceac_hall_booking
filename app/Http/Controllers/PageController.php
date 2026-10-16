<?php

namespace App\Http\Controllers;

use App\Support\Sql;

/** Static-ish public pages: About, Contact, Policy. */
class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', [
            'metaTitle'       => 'About Us | CEAC Hall – Christ Embassy Airport City',
            'metaDescription' => 'Learn about the history, mission, vision and values of Christ Embassy Airport City and the CEAC Hall in Accra, Ghana.',
            'stats'           => getSiteStats(),
            'hall'            => Sql::fetch("SELECT * FROM halls WHERE slug = 'ceac-hall' LIMIT 1"),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'metaTitle'       => 'Contact Us | CEAC Hall Booking – Christ Embassy Airport City',
            'metaDescription' => 'Contact the CEAC Hall booking team at Christ Embassy Airport City. Call 024 403 6129 or email hallbookingceac@gmail.com. Located at 10 Blackberry Street, Estate Legon, Accra.',
        ]);
    }

    public function policy()
    {
        return view('pages.policy', [
            'metaTitle'       => 'Policy & Guidelines | CEAC Hall – Christ Embassy Airport City',
            'metaDescription' => 'Read the booking policies and hall usage guidelines for the CEAC Hall at Christ Embassy Airport City, Accra Ghana.',
        ]);
    }
}
