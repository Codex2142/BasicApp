@php
    $value = $value ?? '';
@endphp

<!-- Text -->
@if ($type == 'text')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="text" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $place }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Password -->
@if ($type == 'password')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="password" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $place }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Email -->
@if ($type == 'email')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="email" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $place }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Number -->
@if ($type == 'number')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="number" id="{{ $name }}" name="{{ $name }}" placeholder="{{ $place }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Date -->
@if ($type == 'date')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="date" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Time -->
@if ($type == 'time')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="time" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Month -->
@if ($type == 'month')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="month" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Week -->
@if ($type == 'week')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="week" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" class="form-control border rounded p-2 w-full" />
@endif

<!-- Radio -->
@if ($type == 'radio')
    <label class="block mb-1 font-medium">{{ $label }}</label>
    @foreach ($data as $key => $text)
        @php
            $id = $name . '_' . $key;
            $checked = ($key == $value) ? 'checked' : '';
        @endphp
        <div>
            <input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $key }}" class="form-check-input" {{ $checked }}>
            <label for="{{ $id }}" class="ms-1">{{ $text }}</label>
        </div>
    @endforeach
@endif

<!-- Checkbox -->
@if ($type == 'checkbox')
    <label class="block mb-1 font-medium">{{ $label }}</label>
    @php
        $valueSelected = is_array($value) ? $value : [];
    @endphp
    @foreach ($data as $val => $text)
        <div>
            <input
                type="checkbox"
                id="{{ $name . '_' . $val }}"
                name="{{ $name }}[]"
                value="{{ $val }}"
                class="form-check-input"
                {{ in_array($val, $valueSelected) ? 'checked' : '' }}
            >
            <label for="{{ $name . '_' . $val }}" class="ms-1">{{ $text }}</label>
        </div>
    @endforeach
@endif

<!-- File -->
@if ($type == 'file')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <input type="file" id="{{ $name }}" name="{{ $name }}" class="form-control form-control-sm border rounded p-1 w-full" />
@endif

<!-- Textarea -->
@if ($type == 'textarea')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="3" class="form-control border rounded p-2 w-full" placeholder="{{ $place }}">{{ $value }}</textarea>
@endif

<!-- Select -->
@if ($type == 'select')
    <label for="{{ $name }}" class="form-label block mb-1 font-medium">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" class="form-select border rounded p-2 w-full">
        <option value="">{{ $place ?? 'Pilih salah satu' }}</option>
        @foreach ($data as $key => $text)
            <option value="{{ $key }}" {{ $key == $value ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>
@endif

<!-- Submit -->
@if ($type == 'submit')
    <button type="submit" class="btn btn-primary px-4 py-2 rounded">{{ $label }}</button>
@endif

<!-- Reset -->
@if ($type == 'reset')
    <button type="reset" class="btn btn-warning px-4 py-2 rounded text-amber-50">{{ $label }}</button>
@endif
