@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />500 - Serverfehler<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Es ist ein Fehler aufgetreten<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card one-card one-card-75">
    <p>Es ist ein unerwarteter Fehler aufgetreten. Bitte versuche es später erneut.</p>
    @if(config('app.debug'))
    <p><strong>Fehlermeldung (DEV Modus):</strong> {{ $exception->getMessage() }}</p>
    <p><strong>Stack Trace:</strong></p>
    <pre>{{ $exception->getTraceAsString() }}</pre>
    @endif
    <p><a href="{{ route('home') }}">Zurück zur Startseite</a></p>
</div>
@endsection