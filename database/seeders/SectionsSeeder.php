<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = ['home', 'news', 'tournaments'];

        foreach ($sections as $sectionName) {
            $section = \App\Models\Section::firstOrCreate(['section_name' => $sectionName]);

            // Erstelle Headline, falls nicht vorhanden
            if (!$section->headline) {
                \App\Models\Headline::create([
                    'section_id' => $section->id,
                    'headline_text' => '',
                    'subline_text' => '',
                ]);
            }
        }
    }
}
