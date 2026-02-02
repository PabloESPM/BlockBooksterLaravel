<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display the specified user's public profile.
     */
    public function show(\App\Models\User $user)
    {
        $viewer = auth()->user();

        // Privacy check
        if ($user->profile_visibility === 'private' && (!$viewer || $viewer->id !== $user->id)) {
            abort(403, 'This profile is private.');
        }

        if ($user->profile_visibility === 'followers' && (!$viewer || !$viewer->isFollowing($user))) {
            abort(403, 'This profile is visible to followers only.');
        }

        if ($user->profile_visibility === 'friends' && (!$viewer || !$viewer->isFriend($user))) {
            abort(403, 'This profile is visible to friends only.');
        }

        // Fetch data
        $user->load([
            'country',
            'lists' => function ($q) {
                $q->where('public', true);
            },
            'reviews.book'
        ]);

        $readBooks = $user->books()->where('status', 'read')->with('book')->get();
        $readingBooks = $user->books()->where('status', 'reading')->with('book')->get();
        $pendingBooks = $user->books()->where('status', 'pending')->with('book')->get();

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
