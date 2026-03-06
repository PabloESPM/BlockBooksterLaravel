<div class="neo-card p-6 mb-12 bg-gray-100">
    <h2 class="text-xl font-black uppercase mb-4 flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Búsqueda Avanzada
    </h2>
    <form action="{{ route('books.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="title" value="{{ request('title') }}" placeholder="Título" class="neo-input bg-white">
        <input type="text" name="author" value="{{ request('author') }}" placeholder="Autor"
               class="neo-input bg-white">
        <input type="text" name="isbn" value="{{ request('isbn') }}" placeholder="ISBN" class="neo-input bg-white">
        <button type="submit" class="neo-btn-primary md:col-span-1">
            Buscar
        </button>
    </form>
</div>
