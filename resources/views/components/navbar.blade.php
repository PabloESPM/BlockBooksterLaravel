<nav x-data="{ searchOpen: false }" class="bg-white border-b-2 border-black relative z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> <!-- Increased height for chunky feel -->
            <!-- Left Side: Logo & Desktop Links -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center mr-8">
                    <a href="/" class="text-3xl font-display font-black text-brand-blue tracking-tighter uppercase"
                        style="text-shadow: 2px 2px 0px #000;">
                        Block<span class="text-brand-yellow text-shadow-black">Book</span>ster
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8 h-full items-center">
                    <a href="/books"
                        class="text-black font-bold uppercase tracking-wide border-b-4 border-transparent hover:border-brand-yellow px-1 py-2 text-sm transition-all h-full flex items-center">
                        Libros
                    </a>
                    <a href="/authors"
                       class="text-black font-bold uppercase tracking-wide border-b-4 border-transparent hover:border-brand-yellow px-1 py-2 text-sm transition-all h-full flex items-center">
                        Autores
                    </a>
                    <a href="/lists"
                        class="text-black font-bold uppercase tracking-wide border-b-4 border-transparent hover:border-brand-yellow px-1 py-2 text-sm transition-all h-full flex items-center">
                        Listas
                    </a>
                    <a href="/community"
                        class="text-black font-bold uppercase tracking-wide border-b-4 border-transparent hover:border-brand-yellow px-1 py-2 text-sm transition-all h-full flex items-center">
                        Comunidad
                    </a>
                </div>
            </div>

            <!-- Right Side: Search & User -->
            <div class="flex items-center space-x-4">
                <!-- Desktop Search
                <div class="hidden sm:block relative">
                    <input type="text" placeholder="SEARCH..."
                        class="w-64 bg-white border-2 border-black rounded-none py-2 px-4 text-sm font-bold placeholder-gray-500 focus:outline-none focus:bg-brand-yellow/10 focus:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all">
                </div>
                -->

                <!-- User Dropdown / Auth Buttons -->
                @auth
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button"
                                class="bg-white border-2 border-black p-1 flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[2px] active:translate-y-[2px]"
                                id="user-menu-button">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 object-cover border border-black"
                                    src="https://ui-avatars.com/api/?name=User+Name&background=0E3FA9&color=fff&rounded=false"
                                    alt="">
                            </button>
                        </div>

                        <div x-show="open" @click.away="open = false"
                            class="origin-top-right absolute right-0 mt-2 w-48 bg-white border-2 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] py-1 focus:outline-none z-50 transform transition-all"
                            role="menu">

                            @if(auth()->user()->type === 'admin' || auth()->user()->type === 'worker')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-brand-blue font-black hover:bg-brand-yellow border-b border-gray-100"
                                    role="menuitem">ADMIN DASHBOARD</a>
                            @endif
                            <a href="/dashboard"
                                class="block px-4 py-2 text-sm text-black font-bold hover:bg-brand-yellow border-b border-gray-100"
                                role="menuitem">PROFILE</a>
                            <a href="/dashboard/lists"
                                class="block px-4 py-2 text-sm text-black font-bold hover:bg-brand-yellow border-b border-gray-100"
                                role="menuitem">MY LISTS</a>
                            <a href="/dashboard/reviews"
                                class="block px-4 py-2 text-sm text-black font-bold hover:bg-brand-yellow border-b border-gray-100"
                                role="menuitem">MY REVIEWS</a>
                            <a href="/dashboard/settings"
                                class="block px-4 py-2 text-sm text-black font-bold hover:bg-brand-yellow"
                                role="menuitem">SETTINGS</a>

                            <div class="border-t-2 border-black my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-black font-bold hover:bg-red-500 hover:text-white"
                                    role="menuitem">SIGN OUT</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex space-x-3">
                        <x-neutral-button href="{{ route('login') }}" class="text-sm">INICIA SESIÓN</x-neutral-button>
                        <x-primary-button href="/register"
                            class="text-sm py-2 px-4 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">REGISTRATE</x-primary-button>
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" @click="$dispatch('toggle-mobile-menu')"
                        class="inline-flex items-center justify-center p-2 rounded-md text-black hover:bg-gray-100 focus:outline-none border-2 border-transparent hover:border-black">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="square" stroke-linejoin="miter" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<x-mobile-menu />
