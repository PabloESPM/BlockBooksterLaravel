<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display the user's reviews in the dashboard.
     */
    public function dashboardIndex()
    {
        $reviews = auth()->user()->reviews()->with('book.authors')->latest()->get();
        return view('pages.dashboard.reviews', compact('reviews'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        // Ensure user owns the review
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        $review->update([
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
        ]);

        auth()->user()->books()->updateOrCreate(
            ['book_isbn' => $review->book_isbn],
            ['rating' => $validated['rating']]
        );

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->reviews()->updateOrCreate(
            ['book_isbn' => $validated['book_isbn']],
            [
                'title' => $validated['title'] ?? null,
                'body' => $validated['body']
            ]
        );

        auth()->user()->books()->updateOrCreate(
            ['book_isbn' => $validated['book_isbn']],
            ['rating' => $validated['rating']]
        );

        return redirect()->back()->with('success', 'Review published successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // Ensure user owns the review
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Toggle the like status for a review.
     */
    public function toggleLike(\Illuminate\Http\Request $request, Review $review)
    {
        $user = auth()->user();
        $like = \App\Models\ReviewLike::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            \App\Models\ReviewLike::create([
                'user_id' => $user->id,
                'review_id' => $review->id
            ]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $review->likes()->count()
        ]);
    }
}
