<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `events` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            return;
        }

        Schema::create('events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->unsignedInteger('hall_id')->index();
            $table->string('title', 200);
            $table->string('slug', 200);
            $table->text('description')->nullable();
            $table->enum('event_type', ['cell_meeting', 'fellowship_meeting', 'executive_meeting', 'all_night', 'special', 'other'])->default('other')->index('idx_event_type');
            $table->string('other_event_type', 100)->nullable();
            $table->dateTime('start_datetime')->index('idx_start_datetime');
            $table->dateTime('end_datetime');
            $table->string('organizer', 150)->nullable();
            $table->integer('max_attendees')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming')->index('idx_events_status');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern', 50)->nullable()->comment('weekly, monthly, etc');
            $table->string('image', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('hall_id')->references('id')->on('halls')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
