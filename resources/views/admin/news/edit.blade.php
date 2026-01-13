@extends('layouts.backend.main-layout-container.app')

@section('title', 'News bearbeiten')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">News bearbeiten</h3>
                    <a href="{{ route('admin.news.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Zurück zur Liste</a>
                </div>

                @include('admin.news.form', ['news' => $news])
            </div>
        </div>
    </div>
</div>
@endsection