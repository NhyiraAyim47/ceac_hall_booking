<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `sent_messages` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sent_messages')) {
            return;
        }

        Schema::create('sent_messages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('sent_to_email', 255);
            $table->string('sent_to_name', 150)->nullable();
            $table->string('subject', 255);
            $table->text('body');
            $table->unsignedInteger('sent_by')->nullable()->index();
            $table->dateTime('sent_at')->nullable()->useCurrent();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sent_messages');
    }
};
