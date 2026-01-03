{{-- resources/views/components/statistic-card.blade.php --}}
@props(['bgColor' => 'bg-blue-500', 'icon' => 'users', 'label' => 'Statistik', 'value' => 0])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-32">
    <div class="p-6 h-full flex flex-col justify-between">
        <div class="flex items-center">
            <div class="w-12 h-12 {{ $bgColor }} rounded-md flex">
                <x-icon name="{{ $icon }}" class="text-white m-auto" />
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-600 leading-tight">{{ $label }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-2xl font-semibold text-gray-900 count-up">{{ $value }}</p>
        </div>
    </div>
</div>