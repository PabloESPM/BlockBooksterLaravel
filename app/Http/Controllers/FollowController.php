<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowRequest;
use App\Http\Requests\UpdateFollowRequest;
use App\Models\Author;
use App\Models\FavList;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\JsonResponse;

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
        $auth = auth()->user();

        if ($auth->id === $user->id) {
            return response()->json(['error' => 'No puedes seguirte a ti mismo.'], 403);
        }

        if ($auth->isFollowing($user)) {
            $auth->unfollow($user);
            $following = false;
        } else {
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
        $auth = auth()->user();

        if ($auth->isFollowingAuthor($author)) {
            $auth->unfollowAuthor($author);
            $following = false;
        } else {
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
        // TODO: implementar cuando se añada la relación de seguimiento a Lista
        return response()->json(['following' => true]);
    }
}

