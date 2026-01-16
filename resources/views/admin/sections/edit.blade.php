@extends('layouts.backend.main-layout-container.app')

@section('title', 'Section bearbeiten')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Section bearbeiten</h3>
                    <a href="{{ route('admin.views.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Zurück zur Liste</a>
                </div>

                <form method="POST" action="{{ route('admin.sections.update', $section) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="section_name" class="block text-sm font-medium text-gray-700">Section Name</label>
                        <input type="text" name="section_name" id="section_name" value="{{ old('section_name', $section->section_name) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        @error('section_name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.views.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Abbrechen</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Aktualisieren</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection