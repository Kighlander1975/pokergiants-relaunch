@extends('layouts.backend.main-layout-container.app')

@section('title', 'Headline bearbeiten')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Headline bearbeiten</h3>
                    <a href="{{ route('admin.headlines.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Zurück zur Liste</a>
                </div>

                <form method="POST" action="{{ route('admin.headlines.update', $headline) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="headline_text" class="block text-sm font-medium text-gray-700">Headline Text</label>
                        <div class="headline-editor-toolbar" role="toolbar" aria-label="BB-Code-Werkzeugleiste">
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" data-bb-undo aria-label="Rückgängig">
                                    <i class="fas fa-undo" aria-hidden="true"></i>
                                    <span class="sr-only">Rückgängig</span>
                                </button>
                                <button type="button" class="toolbar-btn" data-bb-redo aria-label="Wiederholen">
                                    <i class="fas fa-redo" aria-hidden="true"></i>
                                    <span class="sr-only">Wiederholen</span>
                                </button>
                            </div>
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" data-bb-tag="b" aria-label="Fett">
                                    <i class="fas fa-bold" aria-hidden="true"></i>
                                    <span class="sr-only">Fett</span>
                                </button>
                                <button type="button" class="toolbar-btn" data-bb-tag="i" aria-label="Kursiv">
                                    <i class="fas fa-italic" aria-hidden="true"></i>
                                    <span class="sr-only">Kursiv</span>
                                </button>
                            </div>
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="heart" aria-label="Herz">
                                    <span class="suit-symbol suit-heart suit-red">♥</span>
                                    <span class="sr-only">Herz</span>
                                </button>
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="spade" aria-label="Pik">
                                    <span class="suit-symbol suit-spade suit-black">♠</span>
                                    <span class="sr-only">Pik</span>
                                </button>
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="diamond" aria-label="Karo">
                                    <span class="suit-symbol suit-diamond suit-red">♦</span>
                                    <span class="sr-only">Karo</span>
                                </button>
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="club" aria-label="Kreuz">
                                    <span class="suit-symbol suit-club suit-black">♣</span>
                                    <span class="sr-only">Kreuz</span>
                                </button>
                            </div>
                        </div>
                        <textarea name="headline_text" id="headline_text" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono" required>{{ old('headline_text', $headline->headline_text) }}</textarea>
                        @error('headline_text') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="subline_text" class="block text-sm font-medium text-gray-700">Subline Text</label>
                        <div class="headline-editor-toolbar" role="toolbar" aria-label="BB-Code-Werkzeugleiste">
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" data-bb-undo aria-label="Rückgängig">
                                    <i class="fas fa-undo" aria-hidden="true"></i>
                                    <span class="sr-only">Rückgängig</span>
                                </button>
                                <button type="button" class="toolbar-btn" data-bb-redo aria-label="Wiederholen">
                                    <i class="fas fa-redo" aria-hidden="true"></i>
                                    <span class="sr-only">Wiederholen</span>
                                </button>
                            </div>
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn" data-bb-tag="b" aria-label="Fett">
                                    <i class="fas fa-bold" aria-hidden="true"></i>
                                    <span class="sr-only">Fett</span>
                                </button>
                                <button type="button" class="toolbar-btn" data-bb-tag="i" aria-label="Kursiv">
                                    <i class="fas fa-italic" aria-hidden="true"></i>
                                    <span class="sr-only">Kursiv</span>
                                </button>
                            </div>
                            <div class="toolbar-group">
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="heart" aria-label="Herz">
                                    <span class="suit-symbol suit-heart suit-red">♥</span>
                                    <span class="sr-only">Herz</span>
                                </button>
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="spade" aria-label="Pik">
                                    <span class="suit-symbol suit-spade suit-black">♠</span>
                                    <span class="sr-only">Pik</span>
                                </button>
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="diamond" aria-label="Karo">
                                    <span class="suit-symbol suit-diamond suit-red">♦</span>
                                    <span class="sr-only">Karo</span>
                                </button>
                                <button type="button" class="toolbar-btn suit-btn" data-bb-suit="club" aria-label="Kreuz">
                                    <span class="suit-symbol suit-club suit-black">♣</span>
                                    <span class="sr-only">Kreuz</span>
                                </button>
                            </div>
                        </div>
                        <textarea name="subline_text" id="subline_text" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono" required>{{ old('subline_text', $headline->subline_text) }}</textarea>
                        @error('subline_text') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.views.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Zurück</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Aktualisieren</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .headline-editor-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.5rem;
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
    }

    .headline-editor-toolbar .toolbar-group {
        display: flex;
        gap: 0.25rem;
    }

    .headline-editor-toolbar .toolbar-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.375rem;
        background-color: white;
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
        color: #374151;
        font-size: 0.875rem;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
    }

    .headline-editor-toolbar .toolbar-btn:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
    }

    .headline-editor-toolbar .toolbar-btn:focus {
        outline: 2px solid #6366f1;
        outline-offset: 2px;
    }

    .headline-editor-toolbar .suit-btn {
        font-size: 1rem;
        min-width: 2rem;
    }

    .headline-editor-toolbar .suit-symbol {
        font-size: 1.25rem;
        line-height: 1;
    }

    .headline-editor-toolbar .suit-red {
        color: #dc2626;
    }

    .headline-editor-toolbar .suit-black {
        color: #000000;
    }

    .headline-editor-toolbar .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
</style>
@endpush