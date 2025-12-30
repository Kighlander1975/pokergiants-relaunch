@if($imageFilename)
    <!-- Image Avatar -->
    <img src="{{ asset('storage/avatars/' . $imageFilename) }}"
         alt="Avatar"
         class="w-{{ $size / 4 }} h-{{ $size / 4 }} rounded-full object-cover border-2 border-gray-200">
@else
    <!-- Text Avatar -->
    <div class="w-{{ $size / 4 }} h-{{ $size / 4 }} rounded-full flex items-center justify-center text-white font-bold text-lg border-2 border-gray-200"
         style="background-color: {{ $backgroundColor }};">
        {{ $initials }}
    </div>
@endif