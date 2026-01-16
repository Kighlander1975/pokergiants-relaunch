@extends('layouts.backend.main-layout-container.app')

@section('title', 'Headline anzeigen')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Headline: {{ $headline->section }}</h3>
                    <a href="{{ route('admin.views.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Zurück zur Liste</a>
                </div>

                <div class="space-y-4">
                    <div>
                        <strong>Section:</strong> {{ $headline->section }}
                    </div>
                    <div>
                        <strong>Headline Text:</strong> {!! $headline->headline_text !!}
                    </div>
                    <div>
                        <strong>Subline Text:</strong> {!! $headline->subline_text !!}
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.headlines.edit', $headline) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Bearbeiten</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection