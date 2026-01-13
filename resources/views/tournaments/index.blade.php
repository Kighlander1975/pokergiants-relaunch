@extends('layouts.frontend.main')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto space-y-6">
        <h1 class="text-3xl font-semibold">Kommende Turniere</h1>
        @forelse($tournaments as $tournament)
        <div class="bg-white rounded-lg shadow p-6 space-y-3">
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold">{{ $tournament->name }}</h2>
                <span class="text-sm text-gray-500">{{ $tournament->starts_at->format('d.m.Y H:i') }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $tournament->location->name ?? 'Ort nicht verfügbar' }}</p>
            <p class="text-sm text-gray-600">{{ $tournament->registration_info }}</p>
            <p class="text-sm text-gray-600">{{ $tournament->description }}</p>
        </div>
        @empty
        <p class="text-gray-500">Momentan sind keine Turniere geplant.</p>
        @endforelse
        <div>{{ $tournaments->links() }}</div>
    </div>
</div>
@endsection