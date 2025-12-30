@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-2">Avatar bearbeiten</h1>
    <p class="text-gray-300">Lade ein Bild hoch und passe es an</p>
</div>
@endsection

@section('content-body')
<div class="container mx-auto px-4 py-4">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Indicator -->
        <div class="mb-6">
            <div class="flex items-center justify-center space-x-4">
                <div id="step-1" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-bold text-lg">
                    1
                </div>
                <div class="flex-1 h-1 bg-gray-300 rounded">
                    <div id="progress-bar" class="h-1 bg-blue-600 rounded transition-all duration-300" style="width: 0%"></div>
                </div>
                <div id="step-2" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold text-lg">
                    2
                </div>
                <div class="flex-1 h-1 bg-gray-300 rounded">
                    <div id="progress-bar-2" class="h-1 bg-gray-300 rounded transition-all duration-300" style="width: 0%"></div>
                </div>
                <div id="step-3" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold text-lg">
                    3
                </div>
            </div>
            <div class="flex justify-between mt-2 text-sm text-gray-600">
                <span id="step-1-text" class="text-blue-600 font-medium">Bild auswählen</span>
                <span id="step-2-text">Zuschneiden</span>
                <span id="step-3-text">Fertig</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <!-- Upload Step -->
                <div id="upload-step" class="text-center">
                    <div class="mb-6">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">Wähle dein Profilbild</h2>
                        <p class="text-gray-600 mb-6 text-lg">Wähle ein JPG oder PNG Bild aus (max. 2MB)</p>
                    </div>

                    <div class="space-y-6">
                        <input type="file" id="image-input" accept="image/jpeg,image/jpg,image/png" class="hidden">
                        <label for="image-input" class="group inline-flex items-center px-8 py-4 border-2 border-dashed border-blue-300 text-lg font-medium rounded-xl text-blue-700 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 cursor-pointer transition-all duration-200 transform hover:scale-105">
                            <svg class="w-6 h-6 mr-3 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Bild auswählen
                        </label>
                        <p class="text-sm text-gray-500">oder ziehe ein Bild hierher</p>
                    </div>
                </div>

                <!-- Crop Step -->
                <div id="crop-step" class="hidden">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center">Passe dein Bild an</h2>
                        <div class="bg-gray-50 rounded-xl p-6 mb-6">
                            <div class="relative flex items-center justify-center">
                                <div class="relative w-64 h-64 rounded-full overflow-hidden shadow-inner border-2 border-gray-200 bg-white">
                                    <img id="cropper-image" src="" alt="Bild zum Zuschneiden" class="w-full h-full object-cover">
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 text-center mt-2">
                                Bewege und zoome das Bild, bis der gewünschte ausschnitt im Kreis liegt
                            </p>
                        </div>
                    </div>

                    <!-- Control Panel -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bearbeitungswerkzeuge</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                            <button id="rotate-left" class="flex flex-col items-center p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Links drehen</span>
                            </button>

                            <button id="rotate-right" class="flex flex-col items-center p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 4v5h-.582m0 0a8.003 8.003 0 00-15.357 2m15.357-2H15m-11 11v-5h.581m0 0a8.001 8.001 0 0115.356-2M4 20h5"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Rechts drehen</span>
                            </button>

                            <button id="flip-horizontal" class="flex flex-col items-center p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Horizontal spiegeln</span>
                            </button>

                            <button id="flip-vertical" class="flex flex-col items-center p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l-4-4m-4 4l-4-4"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Vertikal spiegeln</span>
                            </button>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button id="back-to-upload" class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Zurück
                            </button>

                            <button id="save-avatar" class="inline-flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Avatar speichern
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="success-message" class="hidden text-center py-12">
                    <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">Avatar erfolgreich gespeichert!</h2>
                    <p class="text-gray-600 text-lg mb-6">Dein neues Profilbild wurde hochgeladen und ist sofort aktiv.</p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200">
                            Zum Profil
                        </a>
                        <button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-lg font-medium transition-all duration-200">
                            Weiter bearbeiten
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 flex flex-col items-center space-y-4 shadow-2xl max-w-sm mx-4">
            <div class="relative">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-200"></div>
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent absolute top-0 left-0"></div>
            </div>
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2" id="loading-title">Verarbeitung...</h3>
                <p class="text-gray-600 text-sm" id="loading-message">Dein Bild wird hochgeladen</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cropper = null;
            let tempPath = null;
            let eventsBound = false;
            let currentScaleX = 1;
            let currentScaleY = 1;

            // File Input Handler
            const imageInput = document.getElementById('image-input');
            if (!imageInput) {
                console.error('Image input element not found!');
                return;
            }

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File is too large. Max size: 2MB');
                    return;
                }

                // Validate file type
                if (!file.type.match('image/(jpeg|jpg|png)')) {
                    alert('Only JPG and PNG files are allowed.');
                    return;
                }

                showLoading('Bild wird hochgeladen...', 'Bitte warte einen Moment');

                const formData = new FormData();
                formData.append('avatar', file);

                fetch('/avatar/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        hideLoading();
                        if (data.success) {
                            tempPath = data.temp_path;
                            showCropper(data.temp_url);
                        } else {
                            alert('Upload failed: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        hideLoading();
                        alert('Image upload failed: ' + error.message);
                    });
            });

            function showCropper(imageUrl) {
                const image = document.getElementById('cropper-image');
                image.src = imageUrl;

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: false,
                    center: true,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                    responsive: true,
                    checkCrossOrigin: false,
                    checkOrientation: true,
                    modal: false,
                    background: false,
                    zoomable: true,
                    zoomOnWheel: true,
                    zoomOnTouch: true,
                    wheelZoomRatio: 0.1,
                    ready() {
                        const container = cropper.getContainerData();
                        const imageData = cropper.getImageData();

                        const circleSize = 256;

                        const boxLeft = (container.width - circleSize) / 2;
                        const boxTop = (container.height - circleSize) / 2;

                        cropper.setCropBoxData({
                            left: boxLeft,
                            top: boxTop,
                            width: circleSize,
                            height: circleSize
                        });

                        const sX = circleSize / imageData.naturalWidth;
                        const sY = circleSize / imageData.naturalHeight;
                        const baseZoom = Math.max(sX, sY);

                        cropper.zoomTo(baseZoom * 1.05);
                    }
                });

                currentScaleX = 1;
                currentScaleY = 1;

                updateProgress(2);
                document.getElementById('upload-step').classList.add('hidden');
                document.getElementById('crop-step').classList.remove('hidden');

                if (!eventsBound) {
                    bindCropperEvents();
                    eventsBound = true;
                }
            }

            function bindCropperEvents() {
                const rotateLeftBtn = document.getElementById('rotate-left');
                const rotateRightBtn = document.getElementById('rotate-right');
                const flipHorizontalBtn = document.getElementById('flip-horizontal');
                const flipVerticalBtn = document.getElementById('flip-vertical');
                const backToUploadBtn = document.getElementById('back-to-upload');
                const saveAvatarBtn = document.getElementById('save-avatar');

                if (!rotateLeftBtn || !rotateRightBtn || !flipHorizontalBtn || !flipVerticalBtn || !backToUploadBtn || !saveAvatarBtn) {
                    console.error('Some buttons not found!');
                    return;
                }

                rotateLeftBtn.addEventListener('click', function() {
                    if (cropper) {
                        cropper.rotate(-90);
                    }
                });

                rotateRightBtn.addEventListener('click', function() {
                    if (cropper) {
                        cropper.rotate(90);
                    }
                });

                flipHorizontalBtn.addEventListener('click', function() {
                    if (cropper) {
                        currentScaleX = currentScaleX === 1 ? -1 : 1;
                        cropper.scaleX(currentScaleX);
                    }
                });

                flipVerticalBtn.addEventListener('click', function() {
                    if (cropper) {
                        currentScaleY = currentScaleY === 1 ? -1 : 1;
                        cropper.scaleY(currentScaleY);
                    }
                });

                backToUploadBtn.addEventListener('click', function() {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                    updateProgress(1);
                    document.getElementById('crop-step').classList.add('hidden');
                    document.getElementById('upload-step').classList.remove('hidden');
                });

                // WICHTIG: hier die neue Version
                saveAvatarBtn.addEventListener('click', function() {
                    if (!cropper) {
                        alert('No image to crop.');
                        return;
                    }

                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    });

                    if (!canvas) {
                        alert('Error creating cropped image.');
                        return;
                    }

                    showLoading('Avatar wird gespeichert...', 'Dein Bild wird verarbeitet und gespeichert');

                    canvas.toBlob(function(blob) {
                        if (!blob) {
                            hideLoading();
                            alert('Fehler beim Erstellen des Bildes.');
                            return;
                        }

                        const formData = new FormData();
                        formData.append('avatar', blob, 'avatar.png');
                        formData.append('temp_path', tempPath);

                        fetch('/avatar/crop', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                hideLoading();
                                if (data.success) {
                                    showSuccess();
                                } else {
                                    alert('Save failed: ' + (data.message || 'Unknown error'));
                                }
                            })
                            .catch(error => {
                                hideLoading();
                                alert('Avatar save failed.');
                                console.error(error);
                            });
                    }, 'image/png', 0.92);
                });
            }

            // Helper wie bisher
            function updateProgress(step) {
                document.getElementById('step-1').className = 'flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold text-lg';
                document.getElementById('step-2').className = 'flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold text-lg';
                document.getElementById('step-3').className = 'flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold text-lg';

                document.getElementById('step-1-text').className = 'text-gray-600';
                document.getElementById('step-2-text').className = 'text-gray-600';
                document.getElementById('step-3-text').className = 'text-gray-600';

                document.getElementById('progress-bar').style.width = '0%';
                document.getElementById('progress-bar-2').style.width = '0%';

                if (step >= 1) {
                    document.getElementById('step-1').className = 'flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-bold text-lg';
                    document.getElementById('step-1-text').className = 'text-blue-600 font-medium';
                    document.getElementById('progress-bar').style.width = '100%';
                }
                if (step >= 2) {
                    document.getElementById('step-2').className = 'flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white font-bold text-lg';
                    document.getElementById('step-2-text').className = 'text-blue-600 font-medium';
                    document.getElementById('progress-bar-2').style.width = '100%';
                }
                if (step >= 3) {
                    document.getElementById('step-3').className = 'flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white font-bold text-lg';
                    document.getElementById('step-3-text').className = 'text-green-600 font-medium';
                }
            }

            function showLoading(title = 'Verarbeitung...', message = 'Dein Bild wird hochgeladen') {
                document.getElementById('loading-title').textContent = title;
                document.getElementById('loading-message').textContent = message;
                document.getElementById('loading').classList.remove('hidden');
            }

            function hideLoading() {
                document.getElementById('loading').classList.add('hidden');
            }

            function showSuccess() {
                updateProgress(3);
                document.getElementById('crop-step').classList.add('hidden');
                document.getElementById('success-message').classList.remove('hidden');
            }
        });
    </script>
    @endpush
</div>
@endsection