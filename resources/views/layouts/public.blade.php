<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 antialiased" x-data="{ mobileMenu: false }">
        {{-- Top Navigation --}}
        @include('partials.public-nav')

        {{-- Page Content --}}
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        @include('partials.footer')

        @fluxScripts
        @stack('scripts')
    </body>
</html>
