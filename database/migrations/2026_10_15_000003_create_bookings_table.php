<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `bookings` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            return;
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('booking_ref', 20)->unique();
            $table->unsignedInteger('hall_id')->index();
            $table->string('full_name', 150);
            $table->string('email', 200)->index('idx_email');
            $table->string('phone', 20);
            $table->string('organization', 150)->nullable();
            $table->string('event_title', 200);
            $table->string('event_type', 50)->default('meetings');
            $table->string('other_event_type', 100)->nullable();
            $table->string('booking_duration_type', 10)->nullable()->default('half');
            $table->date('event_date')->nullable()->index('idx_event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('expected_attendees');
            $table->text('special_requirements')->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('currency', 5)->nullable()->default('GHS');
            $table->boolean('needs_projector')->default(false);
            $table->boolean('needs_pa_system')->default(false);
            $table->boolean('needs_ac')->default(true);
            $table->boolean('needs_whiteboard')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('pending')->index('idx_status');
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('hall_id')->references('id')->on('halls')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
