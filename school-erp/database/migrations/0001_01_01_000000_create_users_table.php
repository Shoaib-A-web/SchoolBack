<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Automatically serves as the unique ID


            // Relationship to Schools Table
            $table->foreignId('school_id')
                  ->nullable() // Set to nullable if a user can exist without a school initially
                  ->constrained('schools') // Explicitly reference the 'schools' table
                  ->onDelete('cascade'); // If a school is deleted, delete its users

            // Names
            $table->string('first_name');
            $table->string('last_name');

            // Authentication & Contact
            $table->string('email')->unique();
            $table->string('contact')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();

            // Profile Details
            $table->date('age'); // Stores the birthdate string (e.g., "2013-02-06")
            $table->string('gender')->nullable();
            $table->string('aadhar', 12)->unique()->nullable(); // 12-digit unique ID
            $table->string('userType')->default('Student'); // e.g., Student, Teacher, Admin

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
