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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Follow $follow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowRequest $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Follow $follow)
    {
        //
    }

    /**
     * Toggle follow/unfollow for a User (AJAX).
     */
    public function toggleUser(User $user): JsonResponse
    {
        $auth = auth()->user();

        if ($auth->id === $user->id) {
            return response()->json(['error' => 'Cannot follow yourself.'], 403);
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
     * Toggle follow/unfollow for an Author (AJAX).
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
     * Toggle follow/unfollow for a List (AJAX).
     * Stubbed until a List followers relationship is implemented.
     */
    public function toggleList(FavList $list): JsonResponse
    {
        // TODO: implement when List follow relationship is added
        return response()->json(['following' => true]);
    }
}
