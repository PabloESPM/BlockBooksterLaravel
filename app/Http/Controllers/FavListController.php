<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavListRequest;
use App\Http\Requests\UpdateFavListRequest;
use App\Models\FavList;

class FavListController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index()
    {
        $lists = FavList::where('visibility', 'public')
            ->with([
                'user',
                'likes',
                'books' => function ($query) {
                    $query->take(4); // Vista previa de 4 libros para la tarjeta
                }
            ])
            ->withCount(['books', 'likes'])
            ->latest()
            ->paginate(12);

        return view('pages.lists.index', compact('lists'));
    }

    /**
     * Muestra las listas del usuario en el panel de control.
     */
    public function dashboardIndex()
    {
        $lists = auth()->user()->lists()->with(['likes'])->withCount('likes')->latest()->get();
        return view('pages.dashboard.lists', compact('lists'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        //
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     */
    public function store(StoreFavListRequest $request)
    {
        $request->user()->lists()->create($request->validated());

        return redirect()->route('dashboard.lists')->with('success', '¡Lista creada correctamente!');
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(FavList $list)
    {
        // Carga las relaciones: usuario, libros (con autores)
        $list->load(['user', 'books.authors']);
        return view('pages.lists.show', compact('list'));
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(FavList $favList)
    {
        //
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(UpdateFavListRequest $request, FavList $list)
    {
        // Asegura que el usuario sea el propietario de la lista
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $list->update($request->validated());

        return redirect()->back()->with('success', '¡Lista actualizada correctamente!');
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(FavList $list)
    {
        // Asegura que el usuario sea el propietario de la lista
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $list->delete();

        return redirect()->route('dashboard.lists')->with('success', '¡Lista eliminada correctamente!');
    }

    /**
     * Asocia un libro a la lista.
     */
    public function attachBook(\Illuminate\Http\Request $request, FavList $list)
    {
        // Asegura que el usuario sea el propietario de la lista
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'book_isbn' => 'required|exists:books,isbn',
        ]);

        $bookIsbn = $request->input('book_isbn');

        if (!$list->books()->where('book_isbn', $bookIsbn)->exists()) {
            $list->books()->attach($bookIsbn, ['added_at' => now()]);
            return back()->with('success', '¡Libro añadido a la lista correctamente!');
        }

        return back()->with('info', 'El libro ya está en esta lista.');
    }

    /**
     * Crea una nueva lista y asocia un libro inmediatamente.
     */
    public function storeAndAttach(StoreFavListRequest $request)
    {
        $request->validate([
            'book_isbn' => 'required|exists:books,isbn',
            // otros campos son validados por StoreFavListRequest
        ]);

        $list = $request->user()->lists()->create($request->validated());

        $list->books()->attach($request->input('book_isbn'), ['added_at' => now()]);

        return back()->with('success', '¡Lista creada y libro añadido correctamente!');
    }

    /**
     * Alterna el estado de "me gusta" para una lista.
     */
    public function toggleLike(\Illuminate\Http\Request $request, FavList $list)
    {
        $user = auth()->user();
        $like = \App\Models\ListLike::where('user_id', $user->id)
            ->where('list_id', $list->id)
            ->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            \App\Models\ListLike::create([
                'user_id' => $user->id,
                'list_id' => $list->id
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $list->likes()->count()
        ]);
    }
}
