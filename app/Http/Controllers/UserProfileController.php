<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * Display the specified user's profile.
     */
    public function show(User $user)
    {
        $viewer = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Profile privacy checks
        |--------------------------------------------------------------------------
        */
        if ($user->profile_visibility === 'private' && (!$viewer || $viewer->id !== $user->id)) {
            abort(403, 'This profile is private.');
        }

        if ($user->profile_visibility === 'followers' && (!$viewer || !$viewer->isFollowing($user))) {
            abort(403, 'This profile is visible to followers only.');
        }

        if ($user->profile_visibility === 'friends' && (!$viewer || !$viewer->isFriend($user))) {
            abort(403, 'This profile is visible to friends only.');
        }

        /*
        |--------------------------------------------------------------------------
        | Load related data
        |--------------------------------------------------------------------------
        */
        $user->load([
            'country',
            'lists' => function ($q) use ($viewer, $user) {

                // El dueño ve todas sus listas
                if ($viewer && $viewer->id === $user->id) {
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
                $q->with(['book', 'likes'])->withCount('likes')->latest();
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | Books by status
        |--------------------------------------------------------------------------
        */
        $readBooks = $user->books()
            ->where('status', 'read')
            ->with('book')
            ->get();

        $readingBooks = $user->books()
            ->where('status', 'reading')
            ->with('book')
            ->get();

        $pendingBooks = $user->books()
            ->where('status', 'pending')
            ->with('book')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Social stats
        |--------------------------------------------------------------------------
        */
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('pages.users.show', compact(
            'user',
            'readBooks',
            'readingBooks',
            'pendingBooks',
            'followersCount',
            'followingCount'
        ));
    }
}

