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
        Schema::table('widgets', function (Blueprint $table) {
            // Drop the enum constraint and make it nullable
            $table->dropColumn('width_percentage');
            $table->string('width_percentage', 3)->nullable()->after('widget_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('widgets', function (Blueprint $table) {
            // Revert back to enum
            $table->dropColumn('width_percentage');
            $table->enum('width_percentage', ['10', '25', '33', '50', '66', '75', '100'])->after('widget_type');
        });
    }
};
