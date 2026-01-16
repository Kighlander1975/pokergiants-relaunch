@extends('layouts.backend.main-layout-container.app')

@section('title', 'Views Übersicht')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Views Übersicht</h3>
                    <a href="{{ route('admin.sections.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Neue Section</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Headline Text</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subline Text</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sections as $section)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ ucfirst($section->section_name) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{!! $section->headline ? $section->headline->headline_text : 'Keine Headline' !!}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{!! $section->headline ? $section->headline->subline_text : 'Keine Subline' !!}</td>
                                <td class="px-6 py-4 text-sm font-medium space-x-2">
                                    @if($section->headline)
                                    <a href="{{ route('admin.headlines.edit', $section->headline) }}" class="text-blue-600 hover:text-blue-900">Headlines</a>
                                    @endif
                                    <a href="{{ route('admin.views.' . $section->section_name) }}" class="text-green-600 hover:text-green-900">Widgets</a>
                                    @if($section->section_name !== 'home')
                                    <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Sicher löschen?')">Löschen</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-sm text-gray-500 text-center">Keine Sections vorhanden.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection