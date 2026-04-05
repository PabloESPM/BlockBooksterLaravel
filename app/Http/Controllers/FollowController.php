<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowRequest;
use App\Http\Requests\UpdateFollowRequest;
use App\Models\Author;
use App\Models\FavList;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index()
    {
    //
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
    public function store(StoreFollowRequest $request)
    {
    //
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(Follow $follow)
    {
    //
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(Follow $follow)
    {
    //
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(UpdateFollowRequest $request, Follow $follow)
    {
    //
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(Follow $follow)
    {
    //
    }

    /**
     * Alterna seguir/dejar de seguir a un Usuario (AJAX).
     */
    public function toggleUser(User $user): JsonResponse
    {
        $auth = Auth::user();

        if ($auth->id === $user->id) {
            return response()->json(['error' => 'No puedes seguirte a ti mismo.'], 403);
        }

        if ($auth->isFollowing($user)) {
            $auth->unfollow($user);
            $following = false;
        }
        else {
            $auth->follow($user);
            $following = true;
        }

        return response()->json(['following' => $following]);
    }

    /**
     * Alterna seguir/dejar de seguir a un Autor (AJAX).
     */
    public function toggleAuthor(Author $author): JsonResponse
    {
        $auth = Auth::user();

        if ($auth->isFollowingAuthor($author)) {
            $auth->unfollowAuthor($author);
            $following = false;
        }
        else {
            $auth->followAuthor($author);
            $following = true;
        }

        return response()->json(['following' => $following]);
    }

    /**
     * Alterna seguir/dejar de seguir una Lista (AJAX).
     */
    public function toggleList(FavList $list): JsonResponse
    {
        $auth = Auth::user();
        
        if ($auth->isFollowingList($list)) {
            $auth->unfollowList($list);
            $following = false;
        } else {
            $auth->followList($list);
            $following = true;
        }

        return response()->json([
            'following' => $following,
            'likes_count' => $list->likes()->count()
        ]);
    }
}