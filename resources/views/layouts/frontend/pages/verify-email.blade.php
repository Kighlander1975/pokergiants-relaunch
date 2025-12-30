@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" /> E-Mail-Verifikation<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" /> Bitte verifiziere deine E-Mail<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card one-card one-card-33">
    @if(session('status') == 'verification-link-sent')
    <div class="alert alert-success">
        Ein neuer Verifizierungslink wurde an deine E-Mail-Adresse gesendet.
    </div>
    @elseif(session('status') == 'email-already-verified')
    <div class="alert alert-info">
        Deine E-Mail-Adresse ist bereits verifiziert. Du kannst dich <a href="{{ route('login') }}">hier einloggen</a>.
    </div>
    @endif
    <h2 class="text-center">Email Verifikation</h2>
    <p>Wir haben dir eine E-Mail mit einem Verifizierungslink geschickt. Bitte klicke auf den Link, um deine E-Mail-Adresse zu bestätigen.</p>
    <p>Falls du die E-Mail nicht erhalten hast, gib deine E-Mail-Adresse ein, um einen neuen Link zu erhalten:</p>
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="my-auth-forms">
            <label for="email">E-Mail-Adresse</label>
            <input type="email" name="email" id="email" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Neuen Verifizierungs-Link senden</button>
    </form>
</div>
@endsection