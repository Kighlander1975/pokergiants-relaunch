@extends('layouts.backend.main-layout-container.app')

@section('title', 'Dashboard')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in as " . (auth()->user()->userDetail->role ?? 'player')) }}
            </div>
        </div>
    </div>
</div>
@endsection