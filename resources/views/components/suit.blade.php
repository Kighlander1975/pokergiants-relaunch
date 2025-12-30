<!-- resources/views/components/suit.blade.php -->
@props(['type', 'size' => 'inherit'])

@php
/** @var string $type */
/** @var string $size */
$symbols = [
'club' => '♣',
'spade' => '♠',
'heart' => '♥',
'diamond' => '♦',
];
/** @var string $symbol */
$symbol = $symbols[$type] ?? '';
/** @var string $color */
$color = in_array($type, ['heart', 'diamond']) ? 'red' : 'black';
@endphp

<span class="suit suit-{{ $type }} suit-{{ $color }}">
    {{ $symbol }}
</span>