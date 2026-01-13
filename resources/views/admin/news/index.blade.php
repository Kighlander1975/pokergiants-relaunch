@php
use App\Models\News;
@endphp

@extends('layouts.backend.main-layout-container.app')

@section('title', 'News')

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">News-Verwaltung</h3>
                        <p class="text-sm text-gray-500">Erstelle, bearbeite und veröffentliche News-Artikel.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.news.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">Neue News</a>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span>Status:</span>
                    <a href="{{ route('admin.news.index') }}" class="{{ $status ? 'text-gray-500 hover:text-gray-700' : 'font-semibold text-gray-900' }}">Alle</a>
                    <a href="{{ route('admin.news.index', ['status' => 'published']) }}" class="{{ $status === 'published' ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">Veröffentlichte</a>
                    <a href="{{ route('admin.news.index', ['status' => 'draft']) }}" class="{{ $status === 'draft' ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">Entwürfe</a>
                    <a href="{{ route('admin.news.index', ['status' => 'scheduled']) }}" class="{{ $status === 'scheduled' ? 'font-semibold text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">Geplant</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kommentare</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auto-Veröffentlichung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($news as $item)
                            <tr class="hover:bg-indigo-50">
                                <td class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-900">
                                    <div class="flex flex-col gap-1">
                                        <span>{{ $item->title }}</span>
                                        <small class="text-xs text-gray-500">{{ $item->slug }}</small>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ News::categories()[$item->category] ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($item->published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Veröffentlicht</span>
                                    @elseif($item->auto_publish_at && $item->auto_publish_at > now())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-800">Geplant</span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Entwurf</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->comments()->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ optional($item->auto_publish_at)->format('d.m.Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm space-y-2">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.news.edit', $item) }}" class="inline-flex items-center px-3 py-2 text-xs font-semibold uppercase tracking-wide rounded-md border border-gray-200 text-indigo-600 hover:bg-gray-50">Bearbeiten</a>
                                        @if($item->published)
                                        <form method="POST" action="{{ route('admin.news.unpublish', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold uppercase tracking-wide rounded-md border border-gray-200 text-gray-600 hover:bg-gray-50">Zurückziehen</button>
                                        </form>
                                        @else
                                        <form method="POST" action="{{ route('admin.news.publish', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold uppercase tracking-wide rounded-md border border-gray-200 text-emerald-600 hover:bg-emerald-50">Veröffentlichen</button>
                                        </form>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('admin.news.destroy', $item) }}" onsubmit="return confirm('News wirklich löschen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold uppercase tracking-wide rounded-md border border-red-200 text-red-600 hover:bg-red-50">Löschen</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-sm text-gray-500">Keine News gefunden.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ $news->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection