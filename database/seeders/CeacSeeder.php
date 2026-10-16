<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Starter data for a brand-new (empty) database: the default CEAC hall that
 * the home page, About page and footer look up by its slug "ceac-hall".
 * Edit its details afterwards under Admin > Halls. Safe to run more than once.
 */
class CeacSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('halls')->where('slug', 'ceac-hall')->exists()) {
            return;
        }

        DB::table('halls')->insert([
            'name'           => 'CEAC Hall',
            'slug'           => 'ceac-hall',
            'description'    => 'A comfortable, air-conditioned hall at Christ Embassy Airport City for cell meetings, fellowship meetings, executive meetings and all-night services.',
            'capacity'       => 50,
            'seating_style'  => 'Theatre Style',
            'has_projector'  => 1,
            'has_ac'         => 1,
            'has_pa_system'  => 1,
            'has_wifi'       => 1,
            'has_whiteboard' => 1,
            'has_parking'    => 1,
            'status'         => 'active',
        ]);
    }
}
