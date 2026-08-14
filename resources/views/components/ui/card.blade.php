@props([
    'as' => 'div',
    'padding' => true,
    'hover' => false,
])

@php
    $classes = [
        'rounded-xl border border-slate-200 bg-white shadow-card',
        $padding ? 'p-6' : '',
        $hover ? 'transition-shadow duration-200 hover:shadow-card-hover' : '',
        $attributes->get('class'),
    ];
@endphp

<{{ $as }} {{ $attributes->except('class')->class($classes) }}>
    {{ $slot }}
</{{ $as }}>
