<!-- resources/views/components/suit.blade.php -->
@props(['type', 'size' => 'inherit'])

@php
$symbols = [
'club' => '♣',
'spade' => '♠',
'heart' => '♥',
'diamond' => '♦',
];
$symbol = $symbols[$type] ?? '';
@endphp

<style>
    .suit {
        font-size: {
                {
                $size ?: 'inherit'
            }
        }

        ;
        font-weight: bold;
    }

    .suit-heart,
    .suit-diamond {
        color: red;
    }

    .suit-club,
    .suit-spade {
        color: black;
    }
</style>

<span class="suit suit-{{ $type }}">{{ $symbol }}</span>