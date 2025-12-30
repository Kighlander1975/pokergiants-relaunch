<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AvatarController extends Controller
{
    /**
     * Zeige Avatar-Upload-Formular
     */
    public function edit()
    {
        return view('avatar.edit');
    }

    /**
     * Upload temporäres Bild für Cropping
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $file = $request->file('avatar');

        // Temporären Dateinamen generieren
        $tempFilename = 'temp_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Bild in temporären Ordner speichern
        $path = $file->storeAs('temp/avatars', $tempFilename, 'public');

        return response()->json([
            'success' => true,
            'temp_path' => $path,
            'temp_url' => Storage::url($path)
        ]);
    }

    /**
     * Crop und speichere Avatar
     */
    public function crop(Request $request): JsonResponse
    {
        $request->validate([
            'temp_path' => 'required|string',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric|min:50|max:500',
            'height' => 'required|numeric|min:50|max:500',
            'rotate' => 'nullable|numeric|min:-180|max:180',
            'scaleX' => 'nullable|numeric|min:-1|max:1',
            'scaleY' => 'nullable|numeric|min:-1|max:1',
        ]);

        $user = Auth::user();
        $tempPath = $request->temp_path;

        // Prüfe ob temp-Datei existiert und dem User gehört
        if (!Storage::disk('public')->exists($tempPath)) {
            return response()->json(['success' => false, 'message' => 'Bild nicht gefunden']);
        }

        // Prüfe ob der Dateiname dem User gehört (Sicherheit)
        $filename = basename($tempPath);
        if (!str_starts_with($filename, 'temp_' . $user->id . '_')) {
            return response()->json(['success' => false, 'message' => 'Nicht autorisiert']);
        }

        try {
            $fullTempPath = Storage::disk('public')->path($tempPath);

            // Intervention Image für Cropping verwenden
            $image = Image::make($fullTempPath);

            // EXIF-Rotation automatisch korrigieren
            $image->orientate();

            // Rotation anwenden
            if ($request->rotate && $request->rotate != 0) {
                $image->rotate(-$request->rotate); // Negativ weil Cropper.js gegen Uhrzeigersinn rotiert
            }

            // Flip anwenden
            if ($request->scaleX && $request->scaleX == -1) {
                $image->flip('h');
            }
            if ($request->scaleY && $request->scaleY == -1) {
                $image->flip('v');
            }

            // Crop anwenden (als Quadrat für Avatar)
            $cropSize = min($request->width, $request->height);
            $image->crop(
                $cropSize,
                $cropSize,
                $request->x,
                $request->y
            );

            // Größe auf 300x300 für beste Qualität setzen
            $image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Als Media in Laravel Media Library speichern
            $mediaFile = $image->encode('jpg', 90);
            $filename = 'avatar_' . $user->id . '_' . time() . '.jpg';

            // Alte Avatare löschen
            $user->clearMediaCollection('avatar');

            // Neuen Avatar hinzufügen
            $user->addMediaFromString($mediaFile)
                ->setName('Avatar')
                ->setFileName($filename)
                ->toMediaCollection('avatar');

            // Temp-Datei löschen
            Storage::disk('public')->delete($tempPath);

            return response()->json([
                'success' => true,
                'message' => 'Avatar erfolgreich aktualisiert',
                'avatar_url' => $user->getAvatarUrl('thumb')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fehler beim Verarbeiten des Bildes: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Avatar löschen
     */
    public function destroy(): JsonResponse
    {
        $user = Auth::user();
        $user->clearMediaCollection('avatar');

        return response()->json([
            'success' => true,
            'message' => 'Avatar entfernt',
            'avatar_url' => $user->getAvatarUrl()
        ]);
    }
}
