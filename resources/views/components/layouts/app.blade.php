@props([
    'title' => null,
    'description' => null,
    'bodyClass' => null,
])

@php
    $siteName = config('app.name', 'SMK Tahfizh Al-Fatih');
    $pageTitle = $title ? "{$title} — {$siteName}" : $siteName;
    $metaDescription = $description ?? 'Website resmi SMK Tahfizh Al-Fatih. Informasi sekolah, program keahlian, berita, dan pendaftaran peserta didik baru (PPDB).';
    $currentUrl = url()->current();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ $currentUrl }}">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $currentUrl }}">

        <meta name="theme-color" content="#047857">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>

    <body class="flex min-h-screen flex-col {{ $bodyClass }}">
        @include('partials.navbar')

        <main id="main-content" class="flex-1">
            {{ $slot }}
        </main>

        @include('partials.footer')

        <x-ui.toast />
        @stack('scripts')
    </body>
</html>
