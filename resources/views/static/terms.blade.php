@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-black uppercase font-display mb-8 text-center text-brand-yellow text-shadow-neo">Terms of
            Service</h1>

        <x-card class="prose max-w-none">
            <p class="font-bold">Last updated: January 25, 2026</p>

            <h3>1. Acceptance of Terms</h3>
            <p>By accessing and using BlockBookster, you accept and agree to be bound by the terms and provision of this
                agreement.</p>

            <h3>2. User Conduct</h3>
            <p>You agree to use the site only for lawful purposes. You are specifically prohibited from:</p>
            <ul>
                <li>Posting any content that is abusive, threatening, or obscene.</li>
                <li>Attempting to interfere with the proper working of the site.</li>
                <li>Creating multiple accounts for the purpose of manipulating ratings.</li>
            </ul>

            <h3>3. Intellectual Property</h3>
            <p>The content on BlockBookster, including but not limited to text, graphics, and logos, is the property of
                BlockBookster and is protected by copyright laws.</p>

            <h3>4. Limitation of Liability</h3>
            <p>In no event shall BlockBookster be liable for any damages (including, without limitation, damages for loss of
                data or profit, or due to business interruption) arising out of the use or inability to use the materials on
                BlockBookster's website.</p>

            <h3>5. Governing Law</h3>
            <p>These terms and conditions are governed by and construed in accordance with the laws of Spain and you
                irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>
        </x-card>
    </div>
@endsection