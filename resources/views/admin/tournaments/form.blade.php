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
                $priceEntries = old('prices', $tournament->prices ?? []);
                if (count($priceEntries) === 0) {
                    $priceEntries = [''];
                }
                $lastPrice = trim((string) end($priceEntries));
                if ($lastPrice !== '') {
                    $priceEntries[] = '';
                }
                reset($priceEntries);
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
        const dirtyTrackers = [];

        const serializeForm = (form) => {
            const entries = [];
            const radioGroups = new Set();

            form.querySelectorAll('input, select, textarea').forEach((element) => {
                if (!element.name) return;

                if (element.type === 'radio') {
                    if (radioGroups.has(element.name)) {
                        return;
                    }
                    radioGroups.add(element.name);
                    const selected = form.querySelector(`input[name="${element.name}"]:checked`);
                    entries.push(`${element.name}:${selected ? selected.value : ''}`);
                    return;
                }

                if (element.type === 'checkbox') {
                    entries.push(`${element.name}:${element.checked ? '1' : '0'}:${element.value}`);
                    return;
                }

                if (element.tagName === 'SELECT') {
                    if (element.multiple) {
                        const values = Array.from(element.selectedOptions).map((option) => option.value).join(',');
                        entries.push(`${element.name}:${values}`);
                    } else {
                        entries.push(`${element.name}:${element.value}`);
                    }
                    return;
                }

                entries.push(`${element.name}:${element.value}`);
            });

            return entries.join('|');
        };

        const createDirtyTracker = (form) => {
            const buttonSelector = form.dataset.dirtyButton || 'button[type="submit"]';
            const submitButton = form.querySelector(buttonSelector);

            if (!submitButton) {
                return null;
            }

            const tracker = {
                form,
                button: submitButton,
                initialState: '',
                serialize() {
                    return serializeForm(this.form);
                },
                check() {
                    const currentState = this.serialize();
                    const dirty = currentState !== this.initialState;
                    this.button.disabled = !dirty;
                },
                attach(element) {
                    if (!element) {
                        return;
                    }

                    ['input', 'change'].forEach((eventName) => {
                        element.addEventListener(eventName, () => this.check());
                    });
                },
            };

            tracker.initialState = tracker.serialize();
            tracker.button.disabled = true;

            return tracker;
        };

        const findTrackerForElement = (element) => {
            const form = element.closest('form');
            return dirtyTrackers.find((tracker) => tracker.form === form);
        };

        const registerDirtyForms = () => {
            document.querySelectorAll('[data-dirty-enabled="true"]').forEach((form) => {
                const tracker = createDirtyTracker(form);
                if (!tracker) {
                    return;
                }

                form.querySelectorAll('input, select, textarea').forEach((element) => tracker.attach(element));
                tracker.check();
                dirtyTrackers.push(tracker);
            });
        };

        function addPriceRow(value = '') {
            const index = wrapper.querySelectorAll('input[name="prices[]"]').length;
            const row = document.createElement('div');
            row.className = 'relative';
            row.innerHTML = `
                <span class="absolute left-3 top-3 text-xs text-gray-500">Platz ${index + 1}</span>
                <input type="text" name="prices[]" value="${value}" class="mt-1 block w-full border border-gray-300 rounded-md px-12 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Preis für Platz ${index + 1}">
            `;
            wrapper.appendChild(row);
            const input = row.querySelector('input');
            input.addEventListener('input', onPriceInput);

            const tracker = findTrackerForElement(input);
            if (tracker) {
                tracker.attach(input);
                tracker.check();
            }
        }

        function onPriceInput(event) {
            const inputs = Array.from(wrapper.querySelectorAll('input[name="prices[]"]'));
            const last = inputs[inputs.length - 1];
            if (last && last.value.trim() !== '') {
                addPriceRow('');
            }

            const tracker = findTrackerForElement(event.target);
            if (tracker) {
                tracker.check();
            }
        }

        if (wrapper) {
            wrapper.querySelectorAll('input[name="prices[]"]').forEach((input) => {
                input.addEventListener('input', onPriceInput);
            });
        }

        registerDirtyForms();
    });
</script>
@endpush