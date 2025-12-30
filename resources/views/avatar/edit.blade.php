@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />Avatar bearbeiten<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Wähle dein perfektes Profilbild<x-suit type="diamond" /></p>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" integrity="sha512-hvNR0F/e2J7zPPfLC9UNEO9zHk6Xw7jOwKrHq2wxY2L7KjBarz9ASGQNQgK0IP8E1gq6fJiWqJy8HqGJBNjLjw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content-body')
<div class="glass-card one-card one-card-75">
    <div id="avatar-editor">
        <!-- Schritt 1: Bild Upload -->
        <div id="upload-step" class="text-center">
            <h3>Bild hochladen</h3>
            <p>Wähle ein Bild von deinem Computer aus (max. 2MB, JPG/PNG)</p>

            <div class="mt-4">
                <input type="file" id="image-input" accept="image/*" class="hidden">
                <label for="image-input" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 cursor-pointer">
                    <x-icon name="upload" type="fas" class="mr-2" />
                    Bild auswählen
                </label>
            </div>

            @if(Auth::user()->hasMedia('avatar'))
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Aktuelles Avatar:</p>
                    <img src="{{ Auth::user()->getAvatarUrl('thumb') }}" alt="Current Avatar" class="w-20 h-20 rounded-full mx-auto mt-2 border-2 border-gray-300">
                </div>
            @endif
        </div>

        <!-- Schritt 2: Bild Croppen -->
        <div id="crop-step" class="hidden">
            <h3 class="text-center">Bild zuschneiden</h3>
            <p class="text-center text-sm text-gray-600">Ziehe den Rahmen, um den gewünschten Bereich auszuwählen</p>

            <div class="mt-4 flex justify-center">
                <div class="relative">
                    <img id="cropper-image" src="" alt="Bild zum Bearbeiten" class="max-w-full">
                </div>
            </div>

            <div class="mt-6 flex justify-center space-x-4">
                <button id="rotate-left" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <x-icon name="undo" type="fas" class="mr-1" />
                    Links drehen
                </button>
                <button id="rotate-right" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <x-icon name="redo" type="fas" class="mr-1" />
                    Rechts drehen
                </button>
                <button id="flip-horizontal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <x-icon name="arrows-alt-h" type="fas" class="mr-1" />
                    Horizontal spiegeln
                </button>
                <button id="flip-vertical" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <x-icon name="arrows-alt-v" type="fas" class="mr-1" />
                    Vertikal spiegeln
                </button>
            </div>

            <div class="mt-6 flex justify-center space-x-4">
                <button id="back-to-upload" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <x-icon name="arrow-left" type="fas" class="mr-1" />
                    Anderes Bild
                </button>
                <button id="save-avatar" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    <x-icon name="save" type="fas" class="mr-1" />
                    Avatar speichern
                </button>
            </div>
        </div>

        <!-- Lade-Indikator -->
        <div id="loading" class="hidden text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Bild wird verarbeitet...</p>
        </div>

        <!-- Erfolgsmeldung -->
        <div id="success-message" class="hidden text-center py-8">
            <div class="text-green-600 text-4xl mb-4">
                <x-icon name="check-circle" type="fas" />
            </div>
            <h3 class="text-green-600 font-bold">Avatar erfolgreich aktualisiert!</h3>
            <p class="text-gray-600 mt-2">Du wirst in wenigen Sekunden weitergeleitet...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js" integrity="sha512-9+7dGtfF1+kvPRF5F0FDzUoq5EFp5ZL7J5fn1h9jZJyFkqUUqHdIGoOqlXmKr8gV7D8CGSyDd+5LcE7QPPPB/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper = null;
    let tempPath = null;

    // File Input Handler
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Dateigröße prüfen (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Die Datei ist zu groß. Maximale Größe: 2MB');
            return;
        }

        // Dateityp prüfen
        if (!file.type.match('image/(jpeg|jpg|png)')) {
            alert('Nur JPG und PNG Dateien sind erlaubt.');
            return;
        }

        showLoading();

        // Bild hochladen
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch('/avatar/upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                tempPath = data.temp_path;
                showCropper(data.temp_url);
            } else {
                alert('Fehler beim Hochladen: ' + (data.message || 'Unbekannter Fehler'));
            }
        })
        .catch(error => {
            hideLoading();
            alert('Fehler beim Hochladen des Bildes.');
            console.error(error);
        });
    });

    // Cropper initialisieren
    function showCropper(imageUrl) {
        document.getElementById('upload-step').classList.add('hidden');
        document.getElementById('crop-step').classList.remove('hidden');

        const image = document.getElementById('cropper-image');
        image.src = imageUrl;

        image.onload = function() {
            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(image, {
                aspectRatio: 1, // Quadratisch für Avatar
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                responsive: true,
                checkCrossOrigin: false,
                checkOrientation: true, // EXIF-Orientierung beachten
                modal: true,
                background: false,
            });
        };
    }

    // Rotation Buttons
    document.getElementById('rotate-left').addEventListener('click', function() {
        if (cropper) cropper.rotate(-90);
    });

    document.getElementById('rotate-right').addEventListener('click', function() {
        if (cropper) cropper.rotate(90);
    });

    // Flip Buttons
    document.getElementById('flip-horizontal').addEventListener('click', function() {
        if (cropper) {
            const data = cropper.getData();
            cropper.scaleX(data.scaleX === 1 ? -1 : 1);
        }
    });

    document.getElementById('flip-vertical').addEventListener('click', function() {
        if (cropper) {
            const data = cropper.getData();
            cropper.scaleY(data.scaleY === 1 ? -1 : 1);
        }
    });

    // Zurück zum Upload
    document.getElementById('back-to-upload').addEventListener('click', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        document.getElementById('crop-step').classList.add('hidden');
        document.getElementById('upload-step').classList.remove('hidden');
    });

    // Avatar speichern
    document.getElementById('save-avatar').addEventListener('click', function() {
        if (!cropper || !tempPath) {
            alert('Kein Bild zum Speichern vorhanden.');
            return;
        }

        const cropData = cropper.getData();
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            alert('Fehler beim Erstellen des zugeschnittenen Bildes.');
            return;
        }

        showLoading();

        // Crop-Daten an Server senden
        fetch('/avatar/crop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                temp_path: tempPath,
                x: Math.round(cropData.x),
                y: Math.round(cropData.y),
                width: Math.round(cropData.width),
                height: Math.round(cropData.height),
                rotate: cropData.rotate || 0,
                scaleX: cropData.scaleX || 1,
                scaleY: cropData.scaleY || 1,
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccess();
                // Nach 3 Sekunden zur Profil-Seite weiterleiten
                setTimeout(function() {
                    window.location.href = '/profile';
                }, 3000);
            } else {
                alert('Fehler beim Speichern: ' + (data.message || 'Unbekannter Fehler'));
            }
        })
        .catch(error => {
            hideLoading();
            alert('Fehler beim Speichern des Avatars.');
            console.error(error);
        });
    });

    // Hilfsfunktionen
    function showLoading() {
        document.getElementById('loading').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loading').classList.add('hidden');
    }

    function showSuccess() {
        document.getElementById('crop-step').classList.add('hidden');
        document.getElementById('success-message').classList.remove('hidden');
    }
});
</script>
@endpush