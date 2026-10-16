<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `rate_limits` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rate_limits')) {
            return;
        }

        Schema::create('rate_limits', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('ip_address', 45);
            $table->string('action', 80);
            $table->integer('attempts')->default(1);
            $table->timestamp('window_start')->useCurrent();

            $table->index(['ip_address', 'action'], 'idx_ip_action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};
