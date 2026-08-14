@php
    $navigation = [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Profil', 'url' => '#'],
        ['label' => 'Program Keahlian', 'url' => '#'],
        ['label' => 'Berita', 'url' => '#'],
        ['label' => 'Galeri', 'url' => '#'],
        ['label' => 'Kontak', 'url' => '#'],
    ];

    $active = collect($navigation)->first(fn ($item) => request()->url() === $item['url'])['label'] ?? null;
@endphp

<header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/90 backdrop-blur-md">
    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8" aria-label="Navigasi utama">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <span class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-primary-800 text-white shadow-sm" aria-hidden="true">
                <svg class="size-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2.5l1.8 5.2 5.2 1.8-5.2 1.8L12 16.5l-1.8-5.2L5 9.5l5.2-1.8L12 2.5z" opacity="0.9" />
                    <path d="M18 14l.9 2.6 2.6.9-2.6.9L18 21l-.9-2.6-2.6-.9 2.6-.9L18 14z" opacity="0.7" />
                </svg>
            </span>
            <span class="leading-tight">
                <span class="block text-sm font-extrabold tracking-tight text-slate-900">SMK TAHFIZH</span>
                <span class="block text-[11px] font-semibold uppercase tracking-widest text-primary-700">Al-Fatih</span>
            </span>
        </a>

        <div class="hidden items-center gap-1 lg:flex">
            @foreach ($navigation as $item)
                <a
                    href="{{ $item['url'] }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $active === $item['label'] ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                >{{ $item['label'] }}</a>
            @endforeach
        </div>

        <div class="flex items-center gap-3">
            <a href="#" class="hidden sm:inline-flex">
                <x-ui.button size="sm">Daftar PPDB</x-ui.button>
            </a>

            <button
                type="button"
                data-nav-toggle
                class="inline-flex size-10 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100 lg:hidden"
                aria-label="Buka menu navigasi"
                aria-expanded="false"
            >
                <svg data-nav-icon-open class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>
        </div>
    </nav>

    <div data-nav-menu class="hidden border-t border-slate-200 bg-white lg:hidden">
        <div class="space-y-1 px-4 py-3">
            @foreach ($navigation as $item)
                <a
                    href="{{ $item['url'] }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $active === $item['label'] ? 'bg-primary-50 text-primary-700' : 'text-slate-600 hover:bg-slate-100' }}"
                >{{ $item['label'] }}</a>
            @endforeach
            <a href="#" class="block px-3 py-2.5 sm:hidden">
                <x-ui.button variant="primary" size="sm" full="true">Daftar PPDB</x-ui.button>
            </a>
        </div>
    </div>
</header>
