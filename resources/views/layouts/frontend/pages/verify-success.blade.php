@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" />E-Mail verifiziert!<x-suit type="spade" /></h1>
    <p class="text-center home-size"><x-suit type="club" />Willkommen bei Pokergiants.de<x-suit type="diamond" /></p>
</div>
@endsection

@section('content-body')
<div class="glass-card">
    <p>Deine E-Mail-Adresse wurde erfolgreich verifiziert. Du kannst dich jetzt <a href="{{ route('login') }}">einloggen</a>.</p>
    <p>Weiterleitung zur Login-Seite in <span id="countdown">15</span> Sekunden...</p>
</div>

<script>
    let countdown = 15;
    const countdownElement = document.getElementById('countdown');
    const interval = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = '{{ route("login") }}';
        }
    }, 1000);
</script>
@endsection