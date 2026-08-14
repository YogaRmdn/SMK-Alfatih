@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'value' => null,
    'placeholder' => null,
    'help' => null,
    'required' => false,
    'autofocus' => false,
])

@php
    $id = $id ?? $name;
    $error = $name ? $errors->first($name) : null;
    $base = 'block w-full rounded-lg border bg-white text-sm text-slate-900 shadow-sm transition-colors';
    $base .= ' placeholder:text-slate-400 disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed';
    $base .= ' focus:outline-none focus:ring-2';
    $base .= $error
        ? ' border-red-300 focus:border-red-500 focus:ring-red-200'
        : ' border-slate-300 focus:border-primary-500 focus:ring-primary-200';
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows ?? 3 }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        @autofocus($autofocus)
        @if ($error) aria-invalid="true" @endif
        {{ $attributes->class([$base, $attributes->get('class')]) }}
    >{{ $slot }}{{ $value }}</textarea>

    @if ($error)
        <p id="{{ $id }}-error" class="mt-1.5 text-sm text-red-600">{{ $error }}</p>
    @elseif ($help)
        <p id="{{ $id }}-help" class="mt-1.5 text-sm text-slate-500">{{ $help }}</p>
    @endif
</div>
