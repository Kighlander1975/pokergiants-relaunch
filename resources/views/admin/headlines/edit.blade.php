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
                        <textarea name="headline_text" id="headline_text" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('headline_text', $headline->headline_text) }}</textarea>
                        @error('headline_text') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="subline_text" class="block text-sm font-medium text-gray-700">Subline Text</label>
                        <textarea name="subline_text" id="subline_text" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('subline_text', $headline->subline_text) }}</textarea>
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