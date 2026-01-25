@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-black uppercase font-display mb-12 text-center">Frequently Asked Questions</h1>

        <div x-data="{ active: null }" class="space-y-4">

            <!-- QA 1 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 1 ? null : 1)"
                    class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>Is BlockBookster free to use?</span>
                    <span x-text="active === 1 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 1" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    Yes! BlockBookster is completely free for all users. You can track your reading, create lists, and
                    review books without paying a dime. We may introduce premium features in the future, but the core
                    experience will always be free.
                </div>
            </div>

            <!-- QA 2 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 2 ? null : 2)"
                    class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>How do I add a book that's missing?</span>
                    <span x-text="active === 2 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 2" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    Currently, only administrators can add new books to the database to ensure data quality. You can request
                    a book addition through our Contact page, and our team will add it within 24-48 hours.
                </div>
            </div>

            <!-- QA 3 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 3 ? null : 3)"
                    class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>Can I export my data?</span>
                    <span x-text="active === 3 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 3" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    We are working on an export feature! Soon you will be able to download a CSV file containing your
                    library, reviews, and reading history from your Account Settings page.
                </div>
            </div>

            <!-- QA 4 -->
            <div class="border-2 border-black bg-white shadow-[4px_4px_0px_#000]">
                <button @click="active = (active === 4 ? null : 4)"
                    class="w-full text-left p-6 font-black uppercase flex justify-between items-center hover:bg-gray-50 transition-colors">
                    <span>How is the 'Popularity' score calculated?</span>
                    <span x-text="active === 4 ? '-' : '+'" class="text-xl"></span>
                </button>
                <div x-show="active === 4" class="p-6 pt-0 text-sm leading-relaxed border-t-2 border-black/10">
                    Popularity is based on a combination of factors including the number of users currently reading the
                    book, recent reviews, and list additions over the last 30 days.
                </div>
            </div>

        </div>
    </div>
@endsection