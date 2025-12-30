@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />403 - Zugriff verweigert<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Du hast keine Berechtigung für diese Seite<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card">
    <p>Du hast nicht die erforderlichen Berechtigungen, um auf diese Seite zuzugreifen.</p>
    <p><a href="{{ route('home') }}">Zurück zur Startseite</a></p>
</div>
@endsection