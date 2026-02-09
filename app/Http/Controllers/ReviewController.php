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

        $review->update($request->validated());

        return redirect()->back()->with('success', 'Review updated successfully!');
    }
}
