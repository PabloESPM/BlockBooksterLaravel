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
     * Primer libro con estado «reading» del usuario (o null si no hay ninguno).
     */
    #[Computed]
    public function libroEnLectura()
    {
        return auth()->user()->books()
            ->with('book.authors')
            ->where('status', 'reading')
            ->first();
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
     * Resolver la URL de la portada de un libro.
     */
    public function resolverPortada($book): string
    {
        if ($book->cover_path) {
            return Storage::url($book->cover_path);
        }
        if (!empty($book->cover_image)) {
            return $book->cover_image;
        }
        if (!empty($book->cover) && str_starts_with($book->cover, 'http')) {
            return $book->cover;
        }
        return 'https://via.placeholder.com/300x450';
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

            {{-- Cuadrícula de estadísticas --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <x-card class="text-center py-6 bg-brand-yellow/10">
                    <div class="text-4xl font-black">{{ $this->librosLeidos }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Libros leídos</div>
                </x-card>
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $this->leyendoActualmente }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Leyendo actualmente</div>
                </x-card>
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $this->totalListas }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Listas creadas</div>
                </x-card>
                <x-card class="text-center py-6">
                    <div class="text-4xl font-black">{{ $this->totalResenias }}</div>
                    <div class="text-xs font-bold uppercase text-gray-600">Reseñas</div>
                </x-card>
            </div>

            {{-- Leyendo actualmente --}}
            <section>
                <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 bg-brand-yellow border border-black"></span>
                    Leyendo actualmente
                </h2>
                @if($this->libroEnLectura && $this->libroEnLectura->book)
                    @php
                        $libro = $this->libroEnLectura->book;
                        $portada = $this->resolverPortada($libro);
                        $autorNombre = $libro->authors->first()
                            ? trim($libro->authors->first()->name . ' ' . ($libro->authors->first()->surname ?? ''))
                            : 'Autor desconocido';
                    @endphp
                    <x-card class="flex flex-col md:flex-row gap-6 items-center md:items-start">
                        <div class="w-24 flex-shrink-0 border-2 border-black shadow-[4px_4px_0px_#000]">
                            <img src="{{ $portada }}" alt="{{ $libro->title }}" class="w-full h-auto">
                        </div>
                        <div class="flex-1 w-full">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-lg uppercase leading-tight">
                                        <a href="{{ route('books.show', $libro->isbn) }}" wire:navigate class="hover:text-brand-blue">
                                            {{ $libro->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600">por {{ $autorNombre }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-xs font-bold text-gray-500 uppercase">
                                    Comenzado {{ $this->libroEnLectura->started_at ? $this->libroEnLectura->started_at->translatedFormat('d M, Y') : 'recientemente' }}
                                </span>
                                <a href="{{ route('books.show', $libro->isbn) }}" wire:navigate
                                   class="neo-btn-secondary py-1 px-3 text-xs">Ver libro</a>
                            </div>
                        </div>
                    </x-card>
                @else
                    <x-card class="text-center py-8">
                        <p class="font-bold text-gray-400 uppercase mb-2">No estás leyendo nada ahora mismo.</p>
                        <a href="{{ route('books.index') }}" wire:navigate
                           class="text-brand-blue underline font-bold text-sm">Explorar libros</a>
                    </x-card>
                @endif
            </section>

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
        </div>
    </div>
</div>
