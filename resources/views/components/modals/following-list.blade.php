@props(['users'])

@auth
    <div x-data="{ show: true }"
         x-show="show"
         @keydown.escape.window="show = false; setTimeout(() => $wire.closeFollowingModal(), 150)"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="show = false; setTimeout(() => $wire.closeFollowingModal(), 150)"
             class="bg-white border-2 border-black shadow-[8px_8px_0px_#000] w-full max-w-lg p-6 relative max-h-[80vh] flex flex-col"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95 opacity-0 translate-y-4"
             x-transition:enter-end="scale-100 opacity-100 translate-y-0">

            <button @click="show = false; setTimeout(() => $wire.closeFollowingModal(), 150)"
                    class="absolute top-4 right-4 text-2xl font-black hover:text-red-600 z-50">&times;</button>

            <h2 class="text-xl font-black uppercase mb-6 font-display border-b-2 border-black pb-2">Siguiendo</h2>

            <div class="overflow-y-auto pr-2 custom-scrollbar flex-grow space-y-4">
                @forelse($users as $u)
                    <div wire:key="modal-following-{{ $u->id }}">
                        <x-user-card
                            :user="$u"
                            statLabel="seguidores"
                            :statValue="$u->followers_count"
                        />
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 italic font-bold">
                        Aún no sigue a nadie.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endauth
