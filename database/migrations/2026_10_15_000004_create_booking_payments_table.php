<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `booking_payments` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_payments')) {
            return;
        }

        Schema::create('booking_payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->unsignedInteger('booking_id')->index();
            $table->decimal('amount', 10, 2);
            $table->string('method', 50);
            $table->string('reference_note', 255)->nullable();
            $table->unsignedInteger('recorded_by')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};
