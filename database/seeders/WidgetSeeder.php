<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Widget;
use App\Models\Section;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $homeSection = Section::where('section_name', 'home')->first();

        if (!$homeSection) {
            return; // Skip if home section doesn't exist
        }

        $widgets = [
            [
                'internal_name' => 'news',
                'widget_type' => 'card',
                'width_percentage' => '100',
                'center_on_small' => false,
                'content_html' => '<h2 class="text-center">Neuigkeiten</h2>
    <p>
        Hier findest du die neuesten Updates und Ankündigungen rund um
        Pokergiants.de.
    </p>
    <ul>
        <li>Feature 1: Beschreibung des Features 1.</li>
        <li>Feature 2: Beschreibung des Features 2.</li>
        <li>Feature 3: Beschreibung des Features 3.</li>
    </ul>',
                'sort_order' => 1,
            ],
            [
                'internal_name' => 'events',
                'widget_type' => 'card',
                'width_percentage' => '100',
                'center_on_small' => false,
                'content_html' => '<h2 class="text-center">Termine</h2>
    <p>Hier findest du die neuesten Termine der Pokergiants.de Turniere.</p>
    <ul>
        <li>Feature 1: Beschreibung des Features 1.</li>
        <li>Feature 2: Beschreibung des Features 2.</li>
        <li>Feature 3: Beschreibung des Features 3.</li>
    </ul>',
                'sort_order' => 2,
            ],
            [
                'internal_name' => 'second-card',
                'widget_type' => 'one-card',
                'width_percentage' => '75',
                'center_on_small' => false,
                'content_html' => '<h2 class="text-center">Zweite Karte</h2>
    <p>Test für Breite.</p>',
                'sort_order' => 3,
            ],
            [
                'internal_name' => 'third-card',
                'widget_type' => 'card',
                'width_percentage' => '66',
                'center_on_small' => true,
                'content_html' => '<h2>Dritte Card</h2>
    <p>
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aut deserunt,
        blanditiis reprehenderit cum asperiores esse vitae in debitis pariatur,
        voluptatem aspernatur eum nesciunt. Cum modi odit, tempora
        exercitationem atque quos. Adipisci dolorum doloribus tempora cum illo
        veniam commodi deserunt ea impedit ad aliquam unde culpa consectetur
        numquam, qui cumque provident.
    </p>',
                'sort_order' => 4,
            ],
            [
                'internal_name' => 'fourth-card',
                'widget_type' => 'card',
                'width_percentage' => '33',
                'center_on_small' => false,
                'content_html' => '<h3>Vierte Card</h3>',
                'sort_order' => 5,
            ],
        ];

        foreach ($widgets as $widgetData) {
            Widget::create(array_merge($widgetData, [
                'section_id' => $homeSection->id,
            ]));
        }
    }
}
