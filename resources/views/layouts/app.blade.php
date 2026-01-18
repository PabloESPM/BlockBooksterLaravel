<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BlockBookster')</title>

    <!-- Fonts: Space Grotesk + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-body bg-gray-50 text-black antialiased flex flex-col min-h-screen">

    <header class="sticky top-0 z-50">
        @include('components.navbar')
    </header>

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        @yield('content')
    </main>

    <footer>
        @include('components.footer')
    </footer>

    <div id="mobile-menu-container"></div>

</body>

</html>