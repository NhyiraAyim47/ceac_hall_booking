<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `halls` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('halls')) {
            return;
        }

        Schema::create('halls', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->text('about_extended')->nullable();
            $table->text('seating_info')->nullable();
            $table->text('features_json')->nullable();
            $table->text('ideal_for_json')->nullable();
            $table->text('pricing_json')->nullable();
            $table->integer('capacity')->default(50);
            $table->string('seating_style', 100)->nullable()->default('Theatre Style');
            $table->boolean('has_projector')->default(true);
            $table->boolean('has_ac')->default(true);
            $table->boolean('has_pa_system')->default(true);
            $table->boolean('has_wifi')->default(true);
            $table->boolean('has_whiteboard')->default(true);
            $table->boolean('has_parking')->default(true);
            $table->string('image', 255)->nullable();
            $table->text('images_json')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('halls');
    }
};
