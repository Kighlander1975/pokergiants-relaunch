<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

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
        $userDetail = $user->userDetail;

        if (!$userDetail) {
            return response()->json(['success' => false, 'message' => 'UserDetail nicht gefunden']);
        }

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
        Log::info('Avatar crop request', $request->all());

        $request->validate([
            'avatar'    => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'temp_path' => 'required|string',
        ]);

        $user = Auth::user();
        $userDetail = $user->userDetail;

        if (!$userDetail) {
            Log::error('UserDetail not found for user', ['user_id' => $user->id]);
            return response()->json(['success' => false, 'message' => 'UserDetail nicht gefunden']);
        }

        $tempPath = $request->temp_path;
        Log::info('Temp path', ['temp_path' => $tempPath]);

        // Sicherheitscheck: gehört diese temp-Datei dem Nutzer?
        $filename = basename($tempPath);
        if (!str_starts_with($filename, 'temp_' . $user->id . '_')) {
            Log::error('Unauthorized access to temp file', ['filename' => $filename, 'user_id' => $user->id]);
            return response()->json(['success' => false, 'message' => 'Nicht autorisiert']);
        }

        try {
            // fertiges Canvas-Bild (Blob) aus Request holen
            $file = $request->file('avatar');

            $manager = new ImageManager('Intervention\Image\Drivers\Gd\Driver');
            $image = $manager->read($file->getPathname());
            Log::info('Canvas image loaded', ['width' => $image->width(), 'height' => $image->height()]);

            // auf 300x300 normalisieren (falls der Canvas doch anders groß ist)
            $image->resize(300, 300);
            Log::info('Resize applied');

            // als JPEG encoden und in Media Library speichern
            $encodedImage = $image->encode(new \Intervention\Image\Encoders\JpegEncoder(90));
            $mediaFile    = $encodedImage->toString();
            $finalFilename = 'avatar_' . $user->id . '_' . time() . '.jpg';
            Log::info('Media file prepared', ['filename' => $finalFilename]);

            // alte Avatare löschen
            $userDetail->clearMediaCollection('avatar');
            Log::info('Old avatars cleared');

            // neuen Avatar speichern
            $media = $userDetail->addMediaFromString($mediaFile)
                ->setName('Avatar')
                ->setFileName($finalFilename)
                ->toMediaCollection('avatar');

            Log::info('New avatar saved', ['media_id' => $media->id]);

            // temp-Datei aufräumen (optional, aber sinnvoll)
            Storage::disk('public')->delete($tempPath);
            Log::info('Temp file deleted');
            /** @var \App\Models\User $user */
            $avatarUrl = $user && method_exists($user, 'getAvatarUrl') ? $user->getAvatarUrl('thumb') : asset('images/default-avatar.png');
            Log::info('Avatar URL', ['avatar_url' => $avatarUrl]);

            return response()->json([
                'success'    => true,
                'message'    => 'Avatar erfolgreich aktualisiert',
                'avatar_url' => $avatarUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing avatar', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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
        $userDetail = $user->userDetail;

        if (!$userDetail) {
            return response()->json(['success' => false, 'message' => 'UserDetail nicht gefunden']);
        }

        $userDetail->clearMediaCollection('avatar');
        /** @var \App\Models\User $user */
        return response()->json([
            'success' => true,
            'message' => 'Avatar entfernt',
            'avatar_url' => $user && method_exists($user, 'getAvatarUrl') ? $user->getAvatarUrl() : asset('images/default-avatar.png')
        ]);
    }

    /**
     * Avatar-Display-Modus aktualisieren
     */
    public function updateDisplayMode(Request $request): JsonResponse
    {
        $request->validate([
            'display_mode' => 'required|in:nickname,initials',
        ]);

        $user = Auth::user();
        $userDetail = $user->userDetail;

        if (!$userDetail) {
            return response()->json(['success' => false, 'message' => 'UserDetail nicht gefunden']);
        }

        $userDetail->update(['avatar_display_mode' => $request->display_mode]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar-Display-Modus aktualisiert',
            'display_mode' => $request->display_mode
        ]);
    }
}
