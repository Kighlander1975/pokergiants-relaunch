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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['player', 'floorman', 'admin'])->default('player');
            $table->string('firstname', 200)->nullable();
            $table->string('lastname', 200)->nullable();
            $table->string('street_number', 200)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('city', 200)->nullable();
            $table->enum('country', ['DE', 'AT', 'CH', 'Other'])->default('DE');
            $table->string('country_flag', 5)->default('de_DE');
            $table->string('avatar_image_filename', 200)->nullable();
            $table->text('bio')->nullable();
            $table->date('dob')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
