<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    use Illuminate\Support\Facades\DB;

    public function up(): void
    {
        // Erstelle sections für bestehende headlines
        $sections = \DB::table('headlines')->distinct()->pluck('section');
        foreach ($sections as $section) {
            \DB::table('sections')->insert(['section_name' => $section, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Drop alte Spalte
        Schema::table('headlines', function (Blueprint $table) {
            $table->dropColumn('section');
        });

        // Neue Spalte
        Schema::table('headlines', function (Blueprint $table) {
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
        });

        // Setze section_id
        $headlines = \DB::table('headlines')->get();
        foreach ($headlines as $headline) {
            $sectionId = \DB::table('sections')->where('section_name', $headline->section)->value('id');
            \DB::table('headlines')->where('id', $headline->id)->update(['section_id' => $sectionId]);
        }
    }

    public function down(): void
    {
        // Rekonstruiere section aus sections
        Schema::table('headlines', function (Blueprint $table) {
            $table->string('section')->nullable();
        });

        $headlines = \DB::table('headlines')->get();
        foreach ($headlines as $headline) {
            $sectionName = \DB::table('sections')->where('id', $headline->section_id)->value('section_name');
            \DB::table('headlines')->where('id', $headline->id)->update(['section' => $sectionName]);
        }

        Schema::table('headlines', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });
    }
};
