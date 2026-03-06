<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Muestra las reseñas del usuario en el panel de control.
     */
    public function dashboardIndex()
    {
        $reviews = auth()->user()->reviews()->with('book.authors')->latest()->get();
        return view('pages.dashboard.reviews', compact('reviews'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        // Asegura que el usuario sea el propietario de la reseña
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

        return redirect()->back()->with('success', '¡Reseña actualizada correctamente!');
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
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

        return redirect()->back()->with('success', '¡Reseña publicada correctamente!');
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(Review $review)
    {
        // Asegura que el usuario sea el propietario de la reseña
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', '¡Reseña eliminada correctamente!');
    }

    /**
     * Alterna el estado de "me gusta" para una reseña.
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

