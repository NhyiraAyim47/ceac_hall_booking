<?php

namespace App\Providers;

use App\Support\Sql;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Older MySQL / MariaDB versions limit index key length with utf8mb4.
        Schema::defaultStringLength(191);

        // Data needed by the public layout (booking modal + footer)
        View::composer('layouts.app', function ($view) {
            $view->with('activeHalls', Sql::fetchAll(
                "SELECT id, name, capacity, seating_style, pricing_json
                 FROM halls WHERE status = 'active' ORDER BY name ASC"
            ));
            $view->with('footerHall', Sql::fetch("SELECT * FROM halls WHERE slug = 'ceac-hall' LIMIT 1"));
        });

        // Counters shown in the admin sidebar / top bar
        View::composer(['layouts.admin', 'admin.*'], function ($view) {
            $stats = getSiteStats();
            $view->with('pending', $stats['pending']);
            $view->with('unread', $stats['messages_unread']);
        });
    }
}
