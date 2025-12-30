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
        Schema::table('user_details', function (Blueprint $table) {
            $table->string('pending_email')->nullable();
            $table->string('email_change_token')->nullable();
            $table->timestamp('email_change_expires_at')->nullable();
            $table->timestamp('email_change_requested_at')->nullable();
            $table->boolean('password_change_blocked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn(['pending_email', 'email_change_token', 'email_change_expires_at', 'email_change_requested_at', 'password_change_blocked']);
        });
    }
};
