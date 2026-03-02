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
                $q->with(['book', 'likes'])->withCount('likes')->latest()->take(6);
            },
            'followedAuthors',
        ]);

        $reviews = $user->reviews()->with(['book', 'likes'])->withCount('likes')->latest()->paginate(3);
        $userLists = $user->lists();

        // Use the same visibility logic as load() but for standalone collection if needed 
        // Or just use the loaded property after pagination logic.
        // Actually, we need pagination for the "Load More" to work correctly.

        $listsPaginated = $user->lists()
            ->when($viewer && $viewer->id === $user->id, function ($q) {}, function ($q) use ($viewer, $user) {
                if ($viewer && $viewer->isFriend($user)) {
                    $q->whereIn('visibility', ['public', 'followers', 'friends']);
                } elseif ($viewer && $viewer->isFollowing($user)) {
                    $q->whereIn('visibility', ['public', 'followers']);
                } else {
                    $q->where('visibility', 'public');
                }
            })->paginate(3);

        /*
        |--------------------------------------------------------------------------
        | Books by status
        |--------------------------------------------------------------------------
        */
        $readBooks = $user->books()
            ->where('status', 'read')
            ->with('book.authors')
            ->paginate(6);

        $readingBooks = $user->books()
            ->where('status', 'reading')
            ->with('book.authors')
            ->paginate(6);

        $pendingBooks = $user->books()
            ->where('status', 'pending')
            ->with('book.authors')
            ->paginate(6);

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
            'reviews',
            'listsPaginated',
            'followersCount',
            'followingCount'
        ));
    }

    /**
     * AJAX: Load more reviews.
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
     * AJAX: Load more books by status.
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
     * AJAX: Load more lists.
     */
    public function loadLists(User $user)
    {
        $viewer = auth()->user();
        $lists = $user->lists()
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
     * AJAX: Load followers list.
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
     * AJAX: Load following list.
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

