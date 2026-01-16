<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = 'Kighlander aus NRW: Amateur pokert sich mit Sachpreisen nach oben';
        $slug = News::generateSlug($title);

        News::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'slug' => $slug,
                'author' => 'Pokergiants',
                'category' => News::CATEGORY_INTERNAL,
                'content' => 'Der 50-jährige Pokerspieler aus Nordrhein-Westfalen, der unter dem Nickname „Kighlander“ antritt, sorgt derzeit in der regionalen Szene für Aufmerksamkeit. Obwohl er sich selbst weiterhin als Amateur bezeichnet, hat er sich in den vergangenen Monaten einen Namen an den Turniertischen gemacht. Seine bisherigen Live Earnings werden auf rund 6.000 Euro geschätzt – ein solider Betrag für einen Spieler, der das Kartenspiel nie als Hauptberuf geplant hatte.

Seinen Einstieg in die Pokerwelt fand Kighlander eher zufällig: Auf einer LAN-Party, eigentlich für Computerspiele gedacht, wurde spontan ein kleines Texas-Hold’em-Turnier organisiert. „Ich war sofort fasziniert von der Mischung aus Mathematik, Psychologie und Nervenkitzel“, erinnert er sich. Aus dem spontanen Spaß wurde schnell ein ernsthaftes Hobby.

Heute spielt Kighlander regelmäßig in lokalen Cardrooms und Vereinsstrukturen, vor allem Sachpreisturniere, bei denen es statt großer Geldsummen häufig um Technik-Gadgets, Gutscheine oder Eventtickets geht. Für ihn steht dabei weniger der finanzielle Gewinn im Vordergrund als die Möglichkeit, Erfahrung gegen unterschiedlichste Gegnertypen zu sammeln. Langfristig möchte der Amateur sein Spiel weiter verbessern und gelegentlich größere Turnierserien in Deutschland und dem benachbarten Ausland anvisieren.',
                'comments_allowed' => true,
                'published' => true,
                'tags' => ['Kighlander', 'NRW', 'Amateur', 'Sachpreise'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
