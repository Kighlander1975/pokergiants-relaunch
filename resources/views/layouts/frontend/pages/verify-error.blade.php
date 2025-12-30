@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />Verifizierung fehlgeschlagen<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Fehler bei der E-Mail-Verifikation<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card">
    <p>Die Verifizierung deiner E-Mail-Adresse ist fehlgeschlagen.</p>
    <p><strong>Fehlerdetails (für Entwickler):</strong> {{ $error }}</p>
    <p>Bitte versuche es erneut oder kontaktiere den Support.</p>
    <p><a href="{{ route('login') }}">Zurück zum Login</a></p>
</div>
@endsection