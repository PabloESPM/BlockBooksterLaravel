<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - @yield('title', 'BlockBookster')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-body bg-gray-100 text-black antialiased flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-black text-white flex-shrink-0 flex flex-col">
        <div class="p-6 border-b border-gray-800">
            <a href="/"
                class="text-2xl font-black font-display uppercase tracking-tighter text-white hover:text-brand-yellow transition-colors">
                BlockBookster
                <span class="text-xs block font-bold text-brand-blue">Admin Panel</span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="block px-6 py-3 font-bold uppercase hover:bg-gray-900 {{ request()->routeIs('admin.dashboard') ? 'bg-brand-blue text-white' : '' }}">
                Dashboard
            </a>

            <div class="px-6 py-2 text-xs font-bold text-gray-500 uppercase mt-4">Content</div>
            <a href="{{ route('admin.books.index') }}"
                class="block px-6 py-3 font-bold uppercase hover:bg-gray-900 {{ request()->routeIs('admin.books.*') ? 'bg-gray-900 border-l-4 border-brand-yellow' : '' }}">
                Books
            </a>
            <a href="{{ route('admin.authors.index') }}"
                class="block px-6 py-3 font-bold uppercase hover:bg-gray-900 {{ request()->routeIs('admin.authors.*') ? 'bg-gray-900 border-l-4 border-brand-yellow' : '' }}">
                Authors
            </a>

            <div class="px-6 py-2 text-xs font-bold text-gray-500 uppercase mt-4">Community</div>
            <a href="{{ route('admin.users.index') }}"
                class="block px-6 py-3 font-bold uppercase hover:bg-gray-900 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900 border-l-4 border-brand-yellow' : '' }}">
                Users
            </a>
            <a href="{{ route('admin.reviews.moderation') }}"
                class="block px-6 py-3 font-bold uppercase hover:bg-gray-900 {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-900 border-l-4 border-brand-yellow' : '' }}">
                Moderation
            </a>
            <a href="{{ route('admin.lists.reports') }}"
                class="block px-6 py-3 font-bold uppercase hover:bg-gray-900 {{ request()->routeIs('admin.lists.*') ? 'bg-gray-900 border-l-4 border-brand-yellow' : '' }}">
                Reports
            </a>
            <a href="{{ route('home') }}"
               class="block px-6 py-3 font-bold uppercase hover:bg-gray-900 {{ request()->routeIs('home.*') ? 'bg-gray-900 border-l-4 border-brand-yellow' : '' }}">
                WEB
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-yellow rounded-full border border-white"></div>
                <div>
                    <div class="text-sm font-bold">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <a href="/" class="text-xs text-brand-blue hover:underline">Back to Site -></a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-8">
        @yield('content')
    </main>

</body>

</html>
