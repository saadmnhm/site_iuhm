@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'errorKey' => null,
    'placeholder' => null,
    'rows' => 4,
])

@php
    $resolvedErrorKey = $errorKey ?? str_replace(['][', '[', ']'], ['.', '.', ''], $name);
    $fieldId = 'field-'.str_replace(['.', '[', ']'], '-', $resolvedErrorKey).'-'.substr(md5($name), 0, 6);
    $fieldValue = old($resolvedErrorKey, $value);
@endphp

<div class="space-y-2">
    @if ($label)
        <label for="{{ $fieldId }}" class="form-label">{{ $label }}</label>
    @endif

    @if ($type === 'textarea')
        <textarea
            id="{{ $fieldId }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            @required($required)
            {{ $attributes->merge(['class' => 'form-input']) }}
        >{{ $fieldValue }}</textarea>
    @else
        <input
            id="{{ $fieldId }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ $fieldValue }}"
            placeholder="{{ $placeholder }}"
            @required($required)
            {{ $attributes->merge(['class' => 'form-input']) }}
        >
    @endif

    @error($resolvedErrorKey)
        <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>
