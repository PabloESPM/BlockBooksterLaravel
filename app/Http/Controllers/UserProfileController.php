<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * Muestra el perfil del usuario especificado.
     * El encabezado siempre es visible; las secciones de contenido dependen de la visibilidad del perfil.
     */
    public function show(User $user)
    {
        $viewer = auth()->user();
        $isOwner = $viewer && $viewer->id === $user->id;

        // Determinamos si el visitante puede ver el contenido completo del perfil
        $canViewContent = match ($user->profile_visibility) {
            // Público: cualquiera puede ver
            'public' => true,
            // Solo seguidores: el dueño o alguien que le sigue
            'followers' => $isOwner || ($viewer && $viewer->isFollowing($user)),
            // Solo amigos (seguimiento mutuo): el dueño o un amigo
            'friends' => $isOwner || ($viewer && $viewer->isFriend($user)),
            // Privado: solo el dueño del perfil
            'private' => $isOwner,
            // Por defecto permitimos el acceso
            default => true,
        };

        // Cargamos los datos relacionados solo si hay permiso para mostrarlos
        $user->load([
            'country',
            'lists' => function ($q) use ($viewer, $user, $isOwner) {

                // El dueño ve todas sus listas
                if ($isOwner) {
                    return;
                }

                // Amigos: public + followers + friends
                if ($viewer && $viewer->isFriend($user)) {
                    $q->whereIn('visibility', ['public', 'followers', 'friends']);
                    return;
                }

                // Seguidores: public + followers
                if ($viewer && $viewer->isFollowing($user)) {
                    $q->whereIn('visibility', ['public', 'followers']);
                    return;
                }

                // Público general: solo public
                $q->where('visibility', 'public');
            },
            'reviews' => function ($q) {
                $q->with(['book', 'likes'])->withCount('likes')->latest()->take(6);
            },
            'followedAuthors',
        ]);

        $reviews = $user->reviews()->with(['book', 'likes'])->withCount('likes')->latest()->paginate(3);

        // Listas paginadas con la misma lógica de visibilidad
        $listsPaginated = $user->lists()
            ->with(['likes', 'user'])
            ->withCount(['books', 'likes'])
            ->when($isOwner, function ($q) {}, function ($q) use ($viewer, $user) {
                if ($viewer && $viewer->isFriend($user)) {
                    $q->whereIn('visibility', ['public', 'followers', 'friends']);
                } elseif ($viewer && $viewer->isFollowing($user)) {
                    $q->whereIn('visibility', ['public', 'followers']);
                } else {
                    $q->where('visibility', 'public');
                }
            })->paginate(3);

        // Libros por estado
        $readBooks = $user->books()->where('status', 'read')->with('book.authors')->paginate(6);
        $readingBooks = $user->books()->where('status', 'reading')->with('book.authors')->paginate(6);
        $pendingBooks = $user->books()->where('status', 'pending')->with('book.authors')->paginate(6);

        // Estadísticas sociales
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('pages.users.show', compact(
            'user',
            'canViewContent',
            'readBooks',
            'readingBooks',
            'pendingBooks',
            'reviews',
            'listsPaginated',
            'followersCount',
            'followingCount'
        ));
    }

    /**
     * AJAX: Cargar más reseñas.
     */
    public function loadReviews(User $user)
    {
        $reviews = $user->reviews()->with(['book', 'likes'])->withCount('likes')->latest()->paginate(3);

        $html = view('components.review-card-list', ['reviews' => $reviews, 'showBook' => true])->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $reviews->hasMorePages(),
            'nextPage' => $reviews->currentPage() + 1,
        ]);
    }

    /**
     * AJAX: Cargar más libros por estado.
     */
    public function loadBooks(User $user, $status)
    {
        $books = $user->books()
            ->where('status', $status)
            ->with('book.authors')
            ->paginate(6);

        $html = view('components.book-card-user-list', ['books' => $books])->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $books->hasMorePages(),
            'nextPage' => $books->currentPage() + 1,
        ]);
    }

    /**
     * AJAX: Cargar más listas.
     */
    public function loadLists(User $user)
    {
        $viewer = auth()->user();

        $lists = $user->lists()
            ->with(['likes', 'user'])
            ->withCount(['books', 'likes'])
            ->when($viewer && $viewer->id === $user->id, function ($q) {}, function ($q) use ($viewer, $user) {
                if ($viewer && $viewer->isFriend($user)) {
                    $q->whereIn('visibility', ['public', 'followers', 'friends']);
                } elseif ($viewer && $viewer->isFollowing($user)) {
                    $q->whereIn('visibility', ['public', 'followers']);
                } else {
                    $q->where('visibility', 'public');
                }
            })->paginate(3);

        $html = view('components.list-card-list', ['lists' => $lists])->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $lists->hasMorePages(),
            'nextPage' => $lists->currentPage() + 1,
        ]);
    }

    /**
     * AJAX: Cargar lista de seguidores.
     */
    public function loadFollowers(User $user)
    {
        $followers = $user->followers()->paginate(10);
        $html = view('components.user-card-list', ['users' => $followers, 'type' => 'followers'])->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $followers->hasMorePages(),
            'nextPage' => $followers->currentPage() + 1,
        ]);
    }

    /**
     * AJAX: Cargar lista de seguidos.
     */
    public function loadFollowing(User $user)
    {
        $following = $user->following()->paginate(10);
        $html = view('components.user-card-list', ['users' => $following, 'type' => 'following'])->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $following->hasMorePages(),
            'nextPage' => $following->currentPage() + 1,
        ]);
    }
}


