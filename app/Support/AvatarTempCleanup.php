<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AvatarTempCleanup
{
    /**
     * Löscht temporäre Avatar-Dateien unter storage/app/public/temp/avatars,
     * die älter als $days Tage sind.
     *
     * @return array{deleted:int, message:string}
     */
    public static function cleanup(int $days = 1): array
    {
        $disk = Storage::disk('public');
        $path = 'temp/avatars';

        if (!$disk->exists($path)) {
            return [
                'deleted' => 0,
                'message' => 'Kein temp/avatars-Verzeichnis vorhanden – nichts zu tun.',
            ];
        }

        $files = $disk->files($path);
        $now   = Carbon::now();
        $deletedCount = 0;

        foreach ($files as $file) {
            $basename = basename($file);

            // Nur unsere temp-Dateien löschen, alles andere (z.B. test.jpg) ignorieren
            if (!Str::startsWith($basename, 'temp_')) {
                continue;
            }

            $fullPath = storage_path('app/public/' . $file);

            if (!file_exists($fullPath)) {
                continue;
            }

            $lastModified = Carbon::createFromTimestamp(filemtime($fullPath));

            // älter als X Tage?
            if ($lastModified->lt($now->copy()->subDays($days))) {
                $disk->delete($file);
                $deletedCount++;
            }
        }

        return [
            'deleted' => $deletedCount,
            'message' => "Cleanup fertig. Gelöschte Dateien: {$deletedCount}",
        ];
    }
}
