@if($imageFilename)
<!-- Image Avatar -->
<img src="{{ asset('storage/avatars/' . $imageFilename) }}"
    alt="Avatar"
    class="avatar-image {{ $sizeClass }}">
@else
<!-- Text Avatar -->
<div class="avatar {{ $sizeClass }}"
    style="--avatar-bg-color: {{ $backgroundColor }};">
    <span class="avatar-initials">{{ $initials }}</span>
</div>
@endif