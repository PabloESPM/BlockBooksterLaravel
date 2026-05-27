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
                            <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 border-l-4 border-black shadow-[-6px_0px_0px_0px_rgba(0,0,0,1)]">
                                <div class="px-4 sm:px-6 pb-4 border-b-2 border-black">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-2xl font-display font-black text-brand-blue tracking-tight uppercase" id="slide-over-title">Menú</h2>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" @click="open = false"
                                                class="border-2 border-black p-1 hover:bg-brand-yellow shadow-[2px_2px_0px_#000] active:shadow-none active:translate-x-[2px] active:translate-y-[2px] focus:outline-none transition-all">
                                                <span class="sr-only">Cerrar menú</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="square" stroke-linejoin="miter"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                    <!-- Mobile Nav Links -->
                                    <nav class="space-y-3">
                                        <a href="/books"
                                            class="block border-2 border-black p-3 font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[2px] active:translate-y-[2px] {{ request()->is('books*') ? 'bg-brand-yellow text-black' : 'bg-white text-black' }}">
                                            Libros
                                        </a>
                                        <a href="/authors"
                                            class="block border-2 border-black p-3 font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[2px] active:translate-y-[2px] {{ request()->is('authors*') ? 'bg-brand-yellow text-black' : 'bg-white text-black' }}">
                                            Autores
                                        </a>
                                        <a href="/lists"
                                            class="block border-2 border-black p-3 font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[2px] active:translate-y-[2px] {{ request()->is('lists*') ? 'bg-brand-yellow text-black' : 'bg-white text-black' }}">
                                            Listas
                                        </a>
                                        <a href="/community"
                                            class="block border-2 border-black p-3 font-bold uppercase hover:bg-brand-yellow hover:text-black transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-[2px] active:translate-y-[2px] {{ request()->is('community*') ? 'bg-brand-yellow text-black' : 'bg-white text-black' }}">
                                            Comunidad
                                        </a>
                                    </nav>

                                    <div class="mt-8 border-t-2 border-black py-6">
                                        @auth
                                             <div class="flex items-center px-4 pb-4 border-b-2 border-black">
                                                 <div class="flex-shrink-0">
                                                     <img class="h-10 w-10 object-cover border-2 border-black"
                                                         src="{{ auth()->user()->avatar_url }}"
                                                         alt="Avatar de {{ auth()->user()->name }}">
                                                 </div>
                                                 <div class="ml-3">
                                                     <div class="text-base font-bold text-black uppercase">{{ auth()->user()->name }}</div>
                                                     <div class="text-xs font-semibold text-gray-500">{{ auth()->user()->email }}</div>
                                                 </div>
                                             </div>
                                             <div class="mt-4 space-y-0 bg-white border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                                 @if(auth()->user()->type === 'admin' || auth()->user()->type === 'worker')
                                                     <a href="{{ route('admin.dashboard') }}"
                                                        class="block px-4 py-3 text-sm text-brand-blue font-black hover:bg-brand-yellow border-b-2 border-black transition-colors"
                                                        role="menuitem">PANEL ADMIN</a>
                                                 @endif
                                                 <a href="/dashboard"
                                                     class="block px-4 py-3 text-sm text-black font-bold hover:bg-brand-yellow border-b-2 border-black transition-colors"
                                                     role="menuitem">PERFIL</a>
                                                 <a href="{{ route('dashboard.social') }}"
                                                     class="block px-4 py-3 text-sm text-black font-bold hover:bg-brand-yellow border-b-2 border-black transition-colors"
                                                     role="menuitem">SOCIAL</a>
                                                 <a href="/dashboard/lists"
                                                     class="block px-4 py-3 text-sm text-black font-bold hover:bg-brand-yellow border-b-2 border-black transition-colors"
                                                     role="menuitem">MIS LISTAS</a>
                                                 <a href="/dashboard/reviews"
                                                     class="block px-4 py-3 text-sm text-black font-bold hover:bg-brand-yellow border-b-2 border-black transition-colors"
                                                     role="menuitem">MIS RESEÑAS</a>
                                                 <a href="/dashboard/settings"
                                                     class="block px-4 py-3 text-sm text-black font-bold hover:bg-brand-yellow border-b-2 border-black transition-colors"
                                                     role="menuitem">AJUSTES</a>
                                                 <form method="POST" action="{{ route('logout') }}">
                                                     @csrf
                                                     <button type="submit"
                                                         class="block w-full text-left px-4 py-3 text-sm text-black font-bold hover:bg-red-500 hover:text-white transition-colors"
                                                         role="menuitem">CERRAR SESIÓN</button>
                                                 </form>
                                             </div>
                                        @else
                                            <div class="grid grid-cols-2 gap-4 px-4">
                                                <x-neutral-button href="{{ route('login') }}" class="text-sm py-3 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] uppercase">INICIA SESIÓN</x-neutral-button>
                                                <x-primary-button href="/register" class="text-sm py-3 shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] uppercase">REGÍSTRATE</x-primary-button>
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