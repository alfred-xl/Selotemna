<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#28166B">

        <title>{{ $title ?? 'Selotemna | Real Estate Development, Engineering & Construction' }}</title>
        <meta name="description" content="{{ $description ?? 'Explore Selotemna’s Real Estate Development and Engineering & Construction divisions and the Omu Creek Upcoming Project.' }}">
        @if ($canonicalUrl ?? null)
            <link rel="canonical" href="{{ $canonicalUrl }}">
        @endif
        @stack('structured-data')
        @if (($isPreview ?? false) || ! app()->environment('production'))
            <meta name="robots" content="noindex, nofollow">
        @endif

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen overflow-x-hidden" data-motion-root>
        <a href="#main-content" class="fixed left-4 top-4 z-[100] -translate-y-24 rounded-xl bg-white px-4 py-3 font-semibold text-brand-700 shadow-lg transition-transform focus:translate-y-0">
            Skip to content
        </a>

        <x-site.header :contact="$contact" />
        <div class="h-px" aria-hidden="true" data-header-sentinel></div>

        <main id="main-content">
            @yield('content')
        </main>

        <x-site.footer :contact="$contact" />
        @unless (request()->routeIs('project-enquiries.create'))
            <x-site.project-enquiry-dialog />
        @endunless
        @unless (request()->routeIs('inspections.create'))
            <x-site.inspection-dialog />
        @endunless
    </body>
</html>
