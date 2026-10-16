<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the `admin_users` table (same columns as the original CEAC database).
 * Skipped automatically if the table already exists, so you can also point
 * this project at your existing database without losing any data.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_users')) {
            return;
        }

        Schema::create('admin_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->increments('id');
            $table->string('username', 80)->unique();
            $table->string('email', 200)->unique();
            $table->string('password_hash', 255);
            $table->string('full_name', 150);
            $table->enum('role', ['super_admin', 'admin', 'viewer'])->default('admin');
            $table->boolean('is_active')->default(true);
            $table->dateTime('last_login')->nullable();
            $table->integer('login_attempts')->default(0);
            $table->dateTime('locked_until')->nullable();
            $table->string('reset_token', 100)->nullable();
            $table->dateTime('reset_token_expires')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
