@props([
    'head' => [],
    'empty' => null,
    'striped' => false,
])

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-card">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-slate-200 text-sm']) }}>
        @if (count($head) > 0)
            <thead class="bg-slate-50">
                <tr>
                    @foreach ($head as $heading)
                        <th
                            scope="col"
                            class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500"
                        >{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif

        <tbody class="divide-y divide-slate-100">
            {{ $slot }}
        </tbody>
    </table>

    @if ($empty)
        {{ $empty }}
    @endif
</div>
