@foreach($books as $userBook)
    <x-book-card :title="$userBook->book->title" :author="$userBook->book->authors->pluck('name')->join(', ')"
        :cover="$userBook->book->cover_image" :id="$userBook->book->isbn" :rating="$userBook->rating ?? 0" />
@endforeach