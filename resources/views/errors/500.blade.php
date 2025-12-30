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
    <div style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin-top: 20px; border-radius: 5px;">
        <h4 style="color: #dc3545; margin-bottom: 10px;">🐛 Debug-Modus aktiv - Fehlerdetails:</h4>
        <p><strong>Fehlermeldung:</strong> {{ $exception->getMessage() }}</p>
        <p><strong>Datei:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
        <details style="margin-top: 10px;">
            <summary style="cursor: pointer; font-weight: bold;">Stack Trace anzeigen</summary>
            <pre style="background: #f1f3f4; padding: 10px; margin-top: 10px; border-radius: 3px; font-size: 12px; overflow-x: auto;">{{ $exception->getTraceAsString() }}</pre>
        </details>
    </div>
    @endif
    <p><a href="{{ route('home') }}">Zurück zur Startseite</a></p>
</div>
@endsection