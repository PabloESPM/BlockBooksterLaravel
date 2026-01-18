<div x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" style="display: none;">

    <template x-teleport="#mobile-menu-container">
        <div x-show="open" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <!-- Background backdrop, show/hide based on slide-over state. -->
            <div x-show="open" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="open"
                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                            class="pointer-events-auto w-screen max-w-md">
                            <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                                <div class="px-4 sm:px-6">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Menu</h2>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" @click="open = false"
                                                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:ring-offset-2">
                                                <span class="sr-only">Close panel</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                    <!-- Mobile Nav Links -->
                                    <nav class="space-y-1">
                                        <a href="/"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50">Home</a>
                                        <a href="/books"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50">Books</a>
                                        <a href="/lists"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50">Lists</a>
                                        <a href="/authors"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50">Authors</a>
                                        <a href="/users"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-50">Community</a>
                                    </nav>

                                    <div class="mt-8 border-t border-gray-200 py-6">
                                        @auth
                                            <div class="flex items-center px-4">
                                                <div class="flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full"
                                                        src="https://ui-avatars.com/api/?name=User+Name" alt="">
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-base font-medium text-gray-800">User Name</div>
                                                    <div class="text-sm font-medium text-gray-500">user@example.com</div>
                                                </div>
                                            </div>
                                            <div class="mt-3 space-y-1 px-2">
                                                <a href="/dashboard"
                                                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Your
                                                    Dashboard</a>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900">Sign
                                                        out</button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="grid grid-cols-2 gap-4 px-4">
                                                <a href="/login"
                                                    class="flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50">Sign
                                                    In</a>
                                                <a href="/register"
                                                    class="flex w-full items-center justify-center rounded-md border border-transparent bg-brand-blue px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700">Sign
                                                    Up</a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>