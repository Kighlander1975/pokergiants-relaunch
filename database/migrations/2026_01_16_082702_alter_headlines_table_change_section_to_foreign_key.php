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
        if (Schema::hasColumn('headlines', 'section')) {
            // Erstelle sections für bestehende headlines
            $sections = \Illuminate\Support\Facades\DB::table('headlines')->distinct()->pluck('section');
            foreach ($sections as $section) {
                \Illuminate\Support\Facades\DB::table('sections')->insert(['section_name' => $section, 'created_at' => now(), 'updated_at' => now()]);
            }

            // Neue Spalte hinzufügen
            Schema::table('headlines', function (Blueprint $table) {
                $table->unsignedBigInteger('section_id')->nullable();
            });

            // Setze section_id
            $headlines = \Illuminate\Support\Facades\DB::table('headlines')->get();
            foreach ($headlines as $headline) {
                $sectionId = \Illuminate\Support\Facades\DB::table('sections')->where('section_name', $headline->section)->value('id');
                \Illuminate\Support\Facades\DB::table('headlines')->where('id', $headline->id)->update(['section_id' => $sectionId]);
            }

            // Drop alte Spalte
            Schema::table('headlines', function (Blueprint $table) {
                $table->dropColumn('section');
            });
        }

        // Foreign key hinzufügen, falls nicht vorhanden
        if (!Schema::hasColumn('headlines', 'section_id')) {
            Schema::table('headlines', function (Blueprint $table) {
                $table->unsignedBigInteger('section_id');
            });
        }

        // Prüfe, ob foreign key existiert
        $foreignKeys = \Illuminate\Support\Facades\DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'headlines' AND COLUMN_NAME = 'section_id' AND REFERENCED_TABLE_NAME = 'sections'");
        if (empty($foreignKeys)) {
            Schema::table('headlines', function (Blueprint $table) {
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // Rekonstruiere section aus sections
        Schema::table('headlines', function (Blueprint $table) {
            $table->string('section')->nullable();
        });

        $headlines = \Illuminate\Support\Facades\DB::table('headlines')->get();
        foreach ($headlines as $headline) {
            $sectionName = \Illuminate\Support\Facades\DB::table('sections')->where('id', $headline->section_id)->value('section_name');
            \Illuminate\Support\Facades\DB::table('headlines')->where('id', $headline->id)->update(['section' => $sectionName]);
        }

        Schema::table('headlines', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });
    }
};
