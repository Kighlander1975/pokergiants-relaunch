<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            // Setze alle bestehenden Werte auf NULL
            DB::table('user_details')->update(['avatar_image_filename' => null]);

            // Stelle sicher, dass das Feld nullable ist
            $table->string('avatar_image_filename', 200)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            // Optional: Feld wieder non-nullable machen (aber da es nullable sein soll, lassen wir das)
            // $table->string('avatar_image_filename', 200)->nullable(false)->change();
        });
    }
};
