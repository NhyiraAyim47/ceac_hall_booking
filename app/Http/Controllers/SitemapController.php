<?php

namespace App\Http\Controllers;

use App\Support\Sql;

/** /sitemap.xml (replaces the old sitemap.php). */
class SitemapController extends Controller
{
    public function __invoke()
    {
        $halls  = Sql::fetchAll("SELECT id, updated_at FROM halls WHERE status = 'active'");
        $events = Sql::fetchAll("SELECT id, updated_at FROM events WHERE deleted_at IS NULL AND status != 'completed'");
        $base   = base_url();

        $entry = function (string $loc, string $priority, ?string $lastmod = null): string {
            $out = "    <url>\n        <loc>{$loc}</loc>\n";
            if ($lastmod) {
                $out .= "        <lastmod>" . date('Y-m-d', strtotime($lastmod)) . "</lastmod>\n";
            }
            return $out . "        <priority>{$priority}</priority>\n    </url>\n";
        };

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= $entry($base . '/', '1.0');
        $xml .= $entry($base . '/halls', '0.9');
        $xml .= $entry($base . '/events', '0.8');
        $xml .= $entry($base . '/about', '0.6');
        $xml .= $entry($base . '/contact', '0.6');
        $xml .= $entry($base . '/policy', '0.4');
        foreach ($halls as $h) {
            $xml .= $entry($base . '/halls?id=' . $h['id'], '0.8', $h['updated_at'] ?? null);
        }
        foreach ($events as $e) {
            $xml .= $entry($base . '/events?id=' . $e['id'], '0.5', $e['updated_at'] ?? null);
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }
}
