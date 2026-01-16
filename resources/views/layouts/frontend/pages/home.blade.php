@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    @if($headline)
    <h1 class="text-center home-size">{!! $headline->headline_text !!}</h1>
    <p class="text-center home-size">{!! $headline->subline_text !!}</p>
    @else
    <h1 class="text-center home-size"><span class="suit suit-heart suit-red">♥</span> Willkommen bei Pokergiants.de <span class="suit suit-spade suit-black">♠</span></h1>
    <p class="text-center home-size"><span class="suit suit-diamond suit-red">♦</span> Deine Poker-Community <span class="suit suit-club suit-black">♣</span></p>
    @endif
</div>
@endsection

@section('content-body')
<div class="glass-card">
    <h2 class="text-center">Neuigkeiten</h2>
    <p>Hier findest du die neuesten Updates und Ankündigungen rund um Pokergiants.de.</p>
    <ul>
        <li>Feature 1: Beschreibung des Features 1.</li>
        <li>Feature 2: Beschreibung des Features 2.</li>
        <li>Feature 3: Beschreibung des Features 3.</li>
    </ul>
</div>
<div class="glass-card">
    <h2 class="text-center">Termine</h2>
    <p>Hier findest du die neuesten Termine der Pokergiants.de Turniere.</p>
        <ul>
            <li>Feature 1: Beschreibung des Features 1.</li>
            <li>Feature 2: Beschreibung des Features 2.</li>
            <li>Feature 3: Beschreibung des Features 3.</li>
        </ul>
</div>
<div class="glass-card one-card one-card-75">
    <h2 class="text-center">Zweite Karte</h2>
    <p>Test für Breite.</p>
</div>
<div class="glass-card card-66 center-on-small">
    <h2>Dritte Card</h2>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aut deserunt, blanditiis reprehenderit cum asperiores esse vitae in debitis pariatur, voluptatem aspernatur eum nesciunt. Cum modi odit, tempora exercitationem atque quos. Adipisci dolorum doloribus tempora cum illo veniam commodi deserunt ea impedit ad aliquam unde culpa consectetur numquam, qui cumque provident.</p>
</div>
<div class="glass-card card-33">
    <h3>Vierte Card</h3>
</div>
@endsection