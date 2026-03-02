@foreach($reviews as $review)
    <x-review-card :review="$review" :showBook="$showBook ?? true" />
@endforeach