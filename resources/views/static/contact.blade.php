@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12">
        <div>
            <h1 class="text-5xl font-black uppercase font-display mb-6">Get in <span class="text-brand-blue">Touch</span>
            </h1>
            <p class="text-xl mb-8 font-bold text-gray-700">Have a question? Found a bug? Just want to say hi? We'd love to
                hear from you.</p>

            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-black text-white flex items-center justify-center font-black rounded-full text-xl shadow-[4px_4px_0px_#888]">
                        @</div>
                    <div>
                        <h3 class="font-black uppercase text-sm">Email Us</h3>
                        <p class="font-mono text-sm">hello@blockbookster.com</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-brand-yellow text-black flex items-center justify-center font-black rounded-full text-xl border-2 border-black shadow-[4px_4px_0px_#000]">
                        X</div>
                    <div>
                        <h3 class="font-black uppercase text-sm">Follow Us</h3>
                        <p class="font-mono text-sm">@BlockBookster</p>
                    </div>
                </div>
            </div>
        </div>

        <x-card class="bg-white">
            <h2 class="font-black text-2xl uppercase mb-6">Send a Message</h2>
            <form class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Name</label>
                    <input type="text" class="neo-input w-full" placeholder="Your Name">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Email</label>
                    <input type="email" class="neo-input w-full" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Subject</label>
                    <select class="neo-input w-full">
                        <option>General Inquiry</option>
                        <option>Bug Report</option>
                        <option>Feature Request</option>
                        <option>Book Addition</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase mb-2">Message</label>
                    <textarea rows="5" class="neo-input w-full" placeholder="How can we help?"></textarea>
                </div>
                <button type="submit" class="neo-btn-primary w-full py-3">Send Message</button>
            </form>
        </x-card>
    </div>
@endsection