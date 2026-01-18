<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - BlockBookster</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full font-body">

    <div class="min-h-full" x-data="{ sidebarOpen: false }">

        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex lg:hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>

            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-brand-blue pt-5 pb-4">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        @click="sidebarOpen = false">
                        <span class="sr-only">Close sidebar</span>
                        <!-- X Icon -->
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Sidebar Content -->
                <div class="flex-shrink-0 flex items-center px-4">
                    <span class="font-display font-bold text-white text-xl">BLOCKBOOKSTER ADMIN</span>
                </div>
                <nav class="mt-5 flex-1 px-2 space-y-1">
                    @include('components.admin-nav-links')
                </nav>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
            <div class="flex-1 flex flex-col min-h-0 bg-brand-blue">
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <div class="flex items-center h-16 flex-shrink-0 px-4 bg-blue-900/50">
                        <span class="font-display font-bold text-white text-xl">BLOCKBOOKSTER</span>
                    </div>
                    <nav class="mt-5 flex-1 px-2 space-y-1">
                        @include('components.admin-nav-links')
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Column -->
        <div class="lg:pl-64 flex flex-col flex-1 h-full">
            <!-- Topbar -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button type="button"
                    class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
                    @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <!-- Menu Icon -->
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex">
                        <!-- Top Search -->
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Admin Profile Dropdown? -->
                        <span class="text-gray-700 text-sm font-medium">Administrator</span>
                    </div>
                </div>
            </div>

            <main class="flex-1 pb-8">
                <div class="mt-8 px-4 sm:px-6 md:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

</body>

</html>