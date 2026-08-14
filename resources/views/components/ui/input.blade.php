@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'help' => null,
    'required' => false,
    'autofocus' => false,
    'readonly' => false,
    'prefix' => null,
    'suffix' => null,
])

@php
    $id = $id ?? $name;
    $error = $name ? $errors->first($name) : null;
    $base = 'block w-full rounded-lg border bg-white text-sm text-slate-900 shadow-sm transition-colors';
    $base .= ' placeholder:text-slate-400 disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed';
    $base .= ' focus:outline-none focus:ring-2 focus:ring-offset-0';
    $base .= $prefix || $suffix ? ' px-3' : '';
    $base .= $prefix ? ' rounded-l-none border-l-0' : '';
    $base .= $suffix ? ' rounded-r-none border-r-0' : '';

    $hasError = $error ? true : false;
    if ($hasError) {
        $base .= ' border-red-300 focus:border-red-500 focus:ring-red-200';
    } else {
        $base .= ' border-slate-300 focus:border-primary-500 focus:ring-primary-200';
    }
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

    <div class="flex items-stretch">
        @if ($prefix)
            <span
                class="inline-flex items-center rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-500"
            >{{ $prefix }}</span>
        @endif

        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            @required($required)
            @autofocus($autofocus)
            @readonly($readonly)
            {{ $hasError ? 'aria-invalid="true"' : '' }}
            @if ($name) aria-describedby="{{ $hasError ? $id.'-error' : ($help ? $id.'-help' : null) }}" @endif
            {{ $attributes->class([$base, $attributes->get('class')])->merge([]) }}
        />

        @if ($suffix)
            <span
                class="inline-flex items-center rounded-r-lg border border-l-0 border-slate-300 bg-slate-50 px-3 text-sm text-slate-500"
            >{{ $suffix }}</span>
        @endif
    </div>

    @if ($error)
        <p id="{{ $id }}-error" class="mt-1.5 text-sm text-red-600">{{ $error }}</p>
    @elseif ($help)
        <p id="{{ $id }}-help" class="mt-1.5 text-sm text-slate-500">{{ $help }}</p>
    @endif
</div>
