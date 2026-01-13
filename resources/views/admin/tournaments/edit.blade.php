@extends('layouts.backend.main-layout-container.app')

@section('title', 'Turnier bearbeiten')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Turnier bearbeiten</h3>
                    <a href="{{ route('admin.tournaments.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Zurück zur Liste</a>
                </div>

                <form method="POST" action="{{ route('admin.tournaments.update', $tournament) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    @include('admin.tournaments.form')

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.tournaments.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Abbrechen</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection