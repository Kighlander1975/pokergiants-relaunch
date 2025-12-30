@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />404 - Seite nicht gefunden<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Die gesuchte Seite existiert nicht<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card one-card one-card-33">
    <p>Die von dir gesuchte Seite konnte nicht gefunden werden.</p>
    <p><a href="{{ route('home') }}">Zurück zur Startseite</a></p>
</div>
@endsection