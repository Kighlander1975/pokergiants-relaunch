{{-- resources/views/components/icon.blade.php --}}
@props(['name', 'type' => 'fas', 'class' => ''])

<i class="{{ $type }} fa-{{ $name }} {{ $class }}"></i>