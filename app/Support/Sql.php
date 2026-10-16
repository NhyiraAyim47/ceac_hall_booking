<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Thin wrapper around Laravel's database layer.
 *
 * It keeps the parameterised SQL of the original project but returns plain
 * arrays (like the old DB class did) so the page templates keep working.
 * Every value is bound as a parameter - never concatenated into the SQL.
 */
class Sql
{
    public static function fetchAll(string $sql, array $params = []): array
    {
        return array_map(fn ($row) => (array) $row, DB::select($sql, $params));
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $rows = self::fetchAll($sql, $params);
        return $rows[0] ?? null;
    }

    public static function insert(string $sql, array $params = []): int
    {
        DB::insert($sql, $params);
        return (int) DB::getPdo()->lastInsertId();
    }

    public static function execute(string $sql, array $params = []): int
    {
        return DB::affectingStatement($sql, $params);
    }
}
