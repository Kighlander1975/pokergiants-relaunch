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
@foreach($widgets as $widget)
<div class="{{ $widget->css_classes }}">
{!! $widget->content_html !!}
</div>
@endforeach
@endsection