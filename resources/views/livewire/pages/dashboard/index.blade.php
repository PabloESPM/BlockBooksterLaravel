<?php
/**
 * Página Livewire SFC — Panel de Control (Vista General)
 *
 * Muestra las estadísticas del usuario (libros leídos, leyendo, listas, reseñas),
 * el libro que está leyendo actualmente y la actividad reciente.
 * Absorbe la lógica de DashboardController@index.
 */

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.app')] #[Title('Mi Panel')] class extends Component {

    /**
     * Redirigir a login si el usuario no está autenticado.
     */
    public function mount(): void
    {
        if (auth()->guest()) {
            $this->redirectRoute('login', navigate: true);
        }
    }

    /**
     * Número de libros marcados como «leído».
     */
    #[Computed]
    public function librosLeidos(): int
    {
        return auth()->user()->books()->where('status', 'read')->count();
    }

    /**
     * Número de libros marcados como «leyendo actualmente».
     */
    #[Computed]
    public function leyendoActualmente(): int
    {
        return auth()->user()->books()->where('status', 'reading')->count();
    }

    /**
     * Total de listas creadas por el usuario.
     */
    #[Computed]
    public function totalListas(): int
    {
        return auth()->user()->lists()->count();
    }

    /**
     * Total de reseñas escritas por el usuario.
     */
    #[Computed]
    public function totalResenias(): int
    {
        return auth()->user()->reviews()->count();
    }

    /**
     * Colección completa de libros con estado «leyendo» del usuario,
     * ordenados por fecha de inicio descendente.
     * Sustituye al antiguo libroEnLectura() que sólo devolvía el primero.
     */
    #[Computed]
    public function librosEnLectura()
    {
        return auth()->user()->books()
            ->with('book.authors')
            ->where('status', 'reading')
            ->orderByDesc('started_at')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Cambiar el estado de lectura de un libro directamente desde el panel.
     * Permite marcar como «leído» o «dejar de leer» (vuelve a pending)
     * sin abandonar el dashboard.
     *
     * @param string $isbn      ISBN del libro a actualizar
     * @param string $estado    'read' | 'pending'
     */
    public function cambiarEstadoDesdePanel(string $isbn, string $estado): void
    {
        // Validar que el estado sea uno de los valores permitidos
        if (!in_array($estado, ['read', 'pending'])) {
            return;
        }

        $pivote = \App\Models\BookUser::where('user_id', auth()->id())
            ->where('book_isbn', $isbn)
            ->first();

        if (!$pivote) {
            return;
        }

        $pivote->update([
            'status'      => $estado,
            'finished_at' => $estado === 'read' ? now() : null,
            'started_at'  => $estado === 'pending' ? null : $pivote->started_at,
        ]);

        // Invalidar propiedades computadas para forzar refresco reactivo
        unset($this->librosEnLectura, $this->librosLeidos, $this->leyendoActualmente,
              $this->librosLeidosColeccion, $this->actividadReciente);
    }

    /**
     * Actividad reciente: últimas 5 interacciones del usuario con libros.
     */
    #[Computed]
    public function actividadReciente()
    {
        // book_user no tiene timestamps (public $timestamps = false),
        // por lo que ordenamos por 'id' como proxy del orden de inserción.
        return auth()->user()->books()
            ->with('book')
            ->whereIn('status', ['read', 'reading'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();
    }

    /**
     * Número de libros marcados como «quiero leer» (pending).
     */
    #[Computed]
    public function librosParaLeer(): int
    {
        return auth()->user()->books()->where('status', 'pending')->count();
    }

    /**
     * Colección de libros con estado «quiero leer» (máx. 6),
     * ordenados por inserción descendente, con libro y autores.
     */
    #[Computed]
    public function librosParaLeerColeccion()
    {
        return auth()->user()->books()
            ->with('book.authors')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->limit(6)
            ->get();
    }

    /**
     * Colección de libros con estado «leído» (máx. 6),
     * ordenados por fecha de finalización descendente, con libro y autores.
     */
    #[Computed]
    public function librosLeidosColeccion()
    {
        return auth()->user()->books()
            ->with('book.authors')
            ->where('status', 'read')
            ->orderByDesc('finished_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get();
    }

    /**
     * Resolver la URL de la portada de un libro.
     */
    public function resolverPortada($book): string
    {
        return $book->cover_image ?? 'https://via.placeholder.com/300x450';
    }
}; ?>

<div>
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Barra lateral --}}
        @include('livewire.pages.dashboard.partials.sidebar')

        {{-- Contenido Principal --}}
        <div class="flex-1 space-y-8">
            {{-- Encabezado --}}
            <header class="flex flex-col md:flex-row justify-between items-start md:items-end border-b-4 border-black pb-6">
                <div>
                    <h1 class="text-4xl font-black uppercase font-display">Hola, <span
                            class="text-brand-blue">{{ auth()->user()->name ?? 'Lector' }}</span></h1>
                    <p class="text-gray-600 font-bold mt-2">Esto es lo que está pasando con tus libros.</p>
                </div>
                <a href="{{ route('books.index') }}" wire:navigate class="hidden md:inline-block neo-btn-primary text-sm">
                    + Registrar nuevo libro
                </a>
            </header>

            {{-- Cuadrícula de estadísticas (5 tarjetas: leídos, leyendo, para leer, listas, reseñas) --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                {{-- Libros leídos --}}
                <x-card class="text-center py-6 bg-brand-yellow/10">
                    <div class="text-4xl font-black">{{ $this->librosLeidos }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Leídos</div>
                </x-card>
                {{-- Leyendo actualmente --}}
                <x-card class="text-center py-6 bg-brand-blue/5">
                    <div class="text-4xl font-black">{{ $this->leyendoActualmente }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Leyendo</div>
                </x-card>
                {{-- Quiero leer (pending) --}}
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $this->librosParaLeer }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Para leer</div>
                </x-card>
                {{-- Listas creadas --}}
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $this->totalListas }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Listas creadas</div>
                </x-card>
                {{-- Reseñas escritas --}}
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $this->totalResenias }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Reseñas</div>
                </x-card>
            </div>

            {{-- Actividad reciente --}}
            <section>
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-black border border-black"></span>
                    Actividad reciente
                </h2>
                @forelse($this->actividadReciente as $item)
                    <div class="mb-3">
                        <x-card class="flex items-center gap-4 py-4">
                            <div class="w-10 h-10 {{ $item->status === 'read' ? 'bg-green-100' : 'bg-brand-yellow/20' }} rounded-full border-2 border-black flex items-center justify-center shrink-0">
                                <span class="text-xl">{{ $item->status === 'read' ? '📚' : '📖' }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold">
                                    {{ $item->status === 'read' ? 'Terminaste de leer' : 'Estás leyendo' }}
                                    <a href="{{ route('books.show', $item->book->isbn) }}" wire:navigate
                                       class="text-brand-blue hover:underline">{{ $item->book->title }}</a>
                                </p>
                                {{-- book_user no tiene updated_at; usamos finished_at o started_at según el estado --}}
                                <p class="text-xs text-gray-500 uppercase">
                                    @if($item->status === 'read' && $item->finished_at)
                                        Terminado el {{ \Carbon\Carbon::parse($item->finished_at)->translatedFormat('d M, Y') }}
                                    @elseif($item->started_at)
                                        Desde el {{ \Carbon\Carbon::parse($item->started_at)->translatedFormat('d M, Y') }}
                                    @else
                                        Recientemente
                                    @endif
                                </p>
                            </div>
                        </x-card>
                    </div>
                @empty
                    <x-card class="text-center py-8">
                        <p class="font-bold text-gray-400 uppercase">Sin actividad reciente.</p>
                    </x-card>
                @endforelse
            </section>

            {{-- Leyendo actualmente --}}
            <section>
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Leyendo actualmente
                </h2>

                @if($this->librosEnLectura->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($this->librosEnLectura as $entrada)
                            @php
                                $libro    = $entrada->book;
                                $portada  = $libro ? $this->resolverPortada($libro) : null;
                                $autorNombre = $libro && $libro->authors->first()
                                    ? trim($libro->authors->first()->name . ' ' . ($libro->authors->first()->surname ?? ''))
                                    : 'Autor desconocido';
                            @endphp
                            @if($libro)
                                <x-card class="flex flex-col sm:flex-row gap-5 items-start">
                                    {{-- Portada --}}
                                    <div class="w-20 flex-shrink-0 border-2 border-black shadow-[4px_4px_0px_#000] self-start">
                                        @if($portada && !str_contains($portada, 'placeholder'))
                                            <img src="{{ $portada }}"
                                                 alt="{{ $libro->title }}"
                                                 class="w-full h-auto object-cover">
                                        @else
                                            <div class="aspect-[2/3] bg-gray-200 flex items-center justify-center">
                                                <span class="text-xs font-black opacity-20 -rotate-45 uppercase">Sin portada</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info + acciones --}}
                                    <div class="flex-1 w-full min-w-0">
                                        {{-- Título y autor --}}
                                        <h3 class="font-bold text-lg uppercase leading-tight truncate">
                                            <a href="{{ route('books.show', $libro->isbn) }}"
                                               wire:navigate
                                               class="hover:text-brand-blue transition-colors">
                                                {{ $libro->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-3">por {{ $autorNombre }}</p>

                                        {{-- Fecha de inicio --}}
                                        <p class="text-xs font-bold text-gray-500 uppercase mb-4">
                                            Comenzado
                                            {{ $entrada->started_at
                                                ? \Carbon\Carbon::parse($entrada->started_at)->translatedFormat('d M, Y')
                                                : 'recientemente' }}
                                        </p>

                                        {{-- Acciones: Leído / Dejar de leer / Ver libro --}}
                                        <div class="flex flex-wrap items-center gap-2 border-t-2 border-black/10 pt-3">
                                            {{-- Botón: Marcar como leído --}}
                                            <button
                                                wire:click="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'read')"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-60 cursor-wait"
                                                wire:target="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'read')"
                                                class="neo-btn-primary py-1.5 px-4 text-xs flex items-center gap-1.5"
                                            >
                                                <span wire:loading.remove wire:target="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'read')">
                                                    📚 Leído
                                                </span>
                                                <span wire:loading wire:target="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'read')">
                                                    <span class="w-3 h-3 border-2 border-black border-t-transparent rounded-full animate-spin inline-block"></span>
                                                </span>
                                            </button>

                                            {{-- Botón: Dejar de leer (vuelve a «para leer») --}}
                                            <button
                                                wire:click="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'pending')"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-60 cursor-wait"
                                                wire:target="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'pending')"
                                                class="bg-brand-yellow text-black border-2 border-black font-bold uppercase tracking-wide py-1.5 px-4 text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-all flex items-center gap-1.5"
                                            >
                                                <span wire:loading.remove wire:target="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'pending')">
                                                    ✕ Dejar de leer
                                                </span>
                                                <span wire:loading wire:target="cambiarEstadoDesdePanel('{{ $libro->isbn }}', 'pending')">
                                                    <span class="w-3 h-3 border-2 border-black border-t-transparent rounded-full animate-spin inline-block"></span>
                                                </span>
                                            </button>

                                            {{-- Enlace: Ver página del libro --}}
                                            <a href="{{ route('books.show', $libro->isbn) }}"
                                               wire:navigate
                                               class="neo-btn-secondary py-1.5 px-4 text-xs ml-auto">
                                                Ver libro →
                                            </a>
                                        </div>
                                    </div>
                                </x-card>
                            @endif
                        @endforeach
                    </div>
                @else
                    {{-- Estado vacío --}}
                    <x-card class="text-center py-8">
                        <p class="font-bold text-gray-400 uppercase mb-2">No estás leyendo nada ahora mismo.</p>
                        <a href="{{ route('books.index') }}" wire:navigate
                           class="text-brand-blue underline font-bold text-sm">Explorar libros</a>
                    </x-card>
                @endif
            </section>

            {{-- ─────────────────────────────────────────────────────────── --}}
            {{-- Mi Biblioteca: Para leer + Leídos en cuadrícula compacta    --}}
            {{-- ─────────────────────────────────────────────────────────── --}}
            <section>
                <h2 class="text-xl font-black uppercase mb-6 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-blue border border-black"></span>
                    Mi Biblioteca
                </h2>

                {{-- Sub-sección: Para leer (pending) --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between border-b-2 border-black pb-1 mb-4">
                        <h3 class="text-sm font-black uppercase flex items-center gap-2">
                            📕 Para leer
                        </h3>
                        <span class="text-xs font-bold text-gray-500 uppercase">
                            {{ $this->librosParaLeer }} {{ $this->librosParaLeer === 1 ? 'libro' : 'libros' }}
                        </span>
                    </div>

                    @if($this->librosParaLeerColeccion->isNotEmpty())
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                            @foreach($this->librosParaLeerColeccion as $entrada)
                                @php
                                    $libroB = $entrada->book;
                                    $portadaB = $libroB ? $this->resolverPortada($libroB) : null;
                                @endphp
                                @if($libroB)
                                    <a href="{{ route('books.show', $libroB->isbn) }}"
                                       wire:navigate
                                       class="neo-card p-0 block overflow-hidden group hover:-translate-y-1 transition-all"
                                       title="{{ $libroB->title }}">
                                        {{-- Portada --}}
                                        <div class="aspect-[2/3] relative overflow-hidden bg-gray-200">
                                            @if($portadaB && !str_contains($portadaB, 'placeholder'))
                                                <img src="{{ $portadaB }}"
                                                     alt="{{ $libroB->title }}"
                                                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300"
                                                     loading="lazy">
                                            @else
                                                <div class="absolute inset-0 flex items-center justify-center bg-gray-200">
                                                    <span class="text-xs font-black uppercase opacity-20 -rotate-45">Sin portada</span>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Título --}}
                                        <div class="p-1.5 border-t-2 border-black">
                                            <p class="text-xs font-bold uppercase truncate leading-tight">{{ $libroB->title }}</p>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <x-card class="text-center py-6">
                            <p class="font-bold text-gray-400 uppercase text-sm mb-2">Sin libros pendientes.</p>
                            <a href="{{ route('books.index') }}" wire:navigate
                               class="text-brand-blue underline font-bold text-xs">Explorar libros →</a>
                        </x-card>
                    @endif
                </div>

                {{-- Sub-sección: Leídos (read) --}}
                <div>
                    <div class="flex items-center justify-between border-b-2 border-black pb-1 mb-4">
                        <h3 class="text-sm font-black uppercase flex items-center gap-2">
                            📚 Leídos
                        </h3>
                        <span class="text-xs font-bold text-gray-500 uppercase">
                            {{ $this->librosLeidos }} {{ $this->librosLeidos === 1 ? 'libro' : 'libros' }}
                        </span>
                    </div>

                    @if($this->librosLeidosColeccion->isNotEmpty())
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                            @foreach($this->librosLeidosColeccion as $entrada)
                                @php
                                    $libroC = $entrada->book;
                                    $portadaC = $libroC ? $this->resolverPortada($libroC) : null;
                                @endphp
                                @if($libroC)
                                    <a href="{{ route('books.show', $libroC->isbn) }}"
                                       wire:navigate
                                       class="neo-card p-0 block overflow-hidden group hover:-translate-y-1 transition-all"
                                       title="{{ $libroC->title }}">
                                        {{-- Portada --}}
                                        <div class="aspect-[2/3] relative overflow-hidden bg-gray-200">
                                            @if($portadaC && !str_contains($portadaC, 'placeholder'))
                                                <img src="{{ $portadaC }}"
                                                     alt="{{ $libroC->title }}"
                                                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300"
                                                     loading="lazy">
                                            @else
                                                <div class="absolute inset-0 flex items-center justify-center bg-gray-200">
                                                    <span class="text-xs font-black uppercase opacity-20 -rotate-45">Sin portada</span>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Título --}}
                                        <div class="p-1.5 border-t-2 border-black">
                                            <p class="text-xs font-bold uppercase truncate leading-tight">{{ $libroC->title }}</p>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <x-card class="text-center py-6">
                            <p class="font-bold text-gray-400 uppercase text-sm">Aún no has terminado ningún libro.</p>
                        </x-card>
                    @endif
                </div>
            </section>


        </div>
    </div>
</div>
