<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CleanupTempAvatars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan avatars:cleanup-temp
     */
    protected $signature = 'avatars:cleanup-temp {--days=1 : Dateien löschen, die älter als X Tage sind}';

    /**
     * The console command description.
     */
    protected $description = 'Löscht alte temporäre Avatar-Bilder aus storage/app/public/temp/avatars';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');

        $this->info("Temp-Avatar-Cleanup gestartet (älter als {$days} Tage)...");

        $disk = Storage::disk('public');
        $path = 'temp/avatars';

        if (!$disk->exists($path)) {
            $this->info('Kein temp/avatars-Verzeichnis vorhanden – nichts zu tun.');
            return self::SUCCESS;
        }

        $files = $disk->files($path);
        $now   = Carbon::now();
        $deletedCount = 0;

        foreach ($files as $file) {
            $basename = basename($file);

            // Nur Dateien, die wirklich von uns stammen
            if (!Str::startsWith($basename, 'temp_')) {
                // z.B. test.jpg oder andere manuell abgelegte Dateien werden übersprungen
                $this->line("Überspringe {$file} (kein temp_*)");
                continue;
            }

            $fullPath = storage_path('app/public/' . $file);

            if (!file_exists($fullPath)) {
                $this->line("Datei existiert nicht mehr: {$fullPath}");
                continue;
            }

            $lastModified = Carbon::createFromTimestamp(filemtime($fullPath));

            if ($lastModified->lt($now->copy()->subDays($days))) {
                $this->info("Lösche alte Temp-Datei: {$file} (letzte Änderung: {$lastModified})");
                $disk->delete($file);
                $deletedCount++;
            }
        }

        $this->info("Cleanup fertig. Gelöschte Dateien: {$deletedCount}");

        return self::SUCCESS;
    }
}
