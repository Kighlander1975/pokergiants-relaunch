@extends('layouts.backend.main-layout-container.app')

@section('title', 'Views - Home')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <h3 class="text-lg font-medium text-gray-900">Home View - Headline bearbeiten</h3>

                @if($headline)
                <form method="POST" action="{{ route('admin.headlines.update', $headline) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')
                @else
                <form method="POST" action="{{ route('admin.headlines.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="section" value="home">
                @endif

                    <div>
                        <label for="headline_text" class="block text-sm font-medium text-gray-700">Headline Text (H1)</label>
                        <textarea name="headline_text" id="headline_text" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('headline_text', $headline->headline_text ?? '') }}</textarea>
                        @error('headline_text') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="subline_text" class="block text-sm font-medium text-gray-700">Subline Text (P)</label>
                        <textarea name="subline_text" id="subline_text" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('subline_text', $headline->subline_text ?? '') }}</textarea>
                        @error('subline_text') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            {{ $headline ? 'Aktualisieren' : 'Erstellen' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection