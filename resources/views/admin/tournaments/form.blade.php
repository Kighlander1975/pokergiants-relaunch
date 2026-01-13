@php
$tournament = $tournament ?? null;
@endphp
<div class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Turniername</label>
        <input required type="text" name="name" id="name" value="{{ old('name', $tournament->name ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
        <select required name="location_id" id="location_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Bitte wählen</option>
            @foreach($locations as $location)
            <option value="{{ $location->id }}" {{ old('location_id', $tournament->location_id ?? '') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
            @endforeach
        </select>
        @error('location_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="starts_at" class="block text-sm font-medium text-gray-700">Turnierzeitpunkt</label>
        <input required type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', isset($tournament->starts_at) ? $tournament->starts_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('starts_at') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="registration_info" class="block text-sm font-medium text-gray-700">Anmeldeinformationen</label>
        <textarea name="registration_info" id="registration_info" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('registration_info', $tournament->registration_info ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Preise</label>
        <p class="text-xs text-gray-500 mb-2">Fülle die Plätze ohne Lücken auf – es erscheint automatisch ein neues Feld.</p>
        <div id="prices-wrapper" class="space-y-2">
            @php
            $priceEntries = old('prices', $tournament->prices ?? ['']);
            if (count($priceEntries) === 0) {
            $priceEntries = [''];
            }
            @endphp
            @foreach($priceEntries as $index => $value)
            <div class="relative">
                <span class="absolute left-3 top-3 text-xs text-gray-500">Platz {{ $index + 1 }}</span>
                <input type="text" name="prices[]" value="{{ $value }}" class="mt-1 block w-full border border-gray-300 rounded-md px-12 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Preis für Platz {{ $index + 1 }}">
            </div>
            @endforeach
        </div>
        @error('prices') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Beschreibung</label>
        <textarea required name="description" id="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $tournament->description ?? '') }}</textarea>
        @error('description') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="flex items-center space-x-3">
            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $tournament->is_published ?? false) ? 'checked' : '' }}>
            <label for="is_published" class="text-sm text-gray-700">Veröffentlichen</label>
        </div>

        <div class="flex items-center space-x-3">
            <input type="checkbox" name="is_registration_open" id="is_registration_open" value="1" {{ old('is_registration_open', $tournament->is_registration_open ?? false) ? 'checked' : '' }}>
            <label for="is_registration_open" class="text-sm text-gray-700">Anmeldung freigegeben</label>
        </div>

        <div class="flex items-center space-x-3">
            <input type="checkbox" name="is_ranglistenturnier" id="is_ranglistenturnier" value="1" {{ old('is_ranglistenturnier', $tournament->is_ranglistenturnier ?? true) ? 'checked' : '' }}>
            <label for="is_ranglistenturnier" class="text-sm text-gray-700">Ranglistenturnier (aktiv)</label>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('prices-wrapper');

        function addPriceRow(value = '') {
            const index = wrapper.querySelectorAll('input[name="prices[]"]').length;
            const row = document.createElement('div');
            row.className = 'relative';
            row.innerHTML = `
                <span class="absolute left-3 top-3 text-xs text-gray-500">Platz ${index + 1}</span>
                <input type="text" name="prices[]" value="${value}" class="mt-1 block w-full border border-gray-300 rounded-md px-12 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Preis für Platz ${index + 1}">
            `;
            wrapper.appendChild(row);
            row.querySelector('input').addEventListener('input', onPriceInput);
        }

        function onPriceInput(event) {
            const inputs = Array.from(wrapper.querySelectorAll('input[name="prices[]"]'));
            const last = inputs[inputs.length - 1];
            if (last && last.value.trim() !== '') {
                addPriceRow('');
            }
        }

        wrapper.querySelectorAll('input[name="prices[]"]').forEach(input => {
            input.addEventListener('input', onPriceInput);
        });
    });
</script>
@endpush