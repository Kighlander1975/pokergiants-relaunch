@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />Registrierung erfolgreich!<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Willkommen bei Pokergiants.de<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card">
    <p>Deine Registrierung war erfolgreich! Wir haben dir eine E-Mail mit einem Verifizierungslink geschickt.</p>
    <p>Bitte klicke auf den Link in der E-Mail, um dein Konto zu aktivieren.</p>
    <p>Falls du die E-Mail nicht erhalten hast, kannst du dich <a href="{{ route('login') }}">einloggen</a> und eine neue Verifizierungs-E-Mail anfordern.</p>
</div>
@endsection