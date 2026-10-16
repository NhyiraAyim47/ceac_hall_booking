<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `admin_logs` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_logs')) {
            return;
        }

        Schema::create('admin_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->unsignedInteger('admin_id')->index();
            $table->string('action', 100);
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('admin_id')->references('id')->on('admin_users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
