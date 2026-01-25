@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-black uppercase font-display mb-8 text-center text-brand-blue">Privacy Policy</h1>

        <x-card class="prose max-w-none">
            <p class="font-bold">Last updated: January 25, 2026</p>

            <h3>1. Introduction</h3>
            <p>Welcome to BlockBookster. We value your privacy and are committed to protecting your personal data. This
                privacy policy will inform you as to how we look after your personal data when you visit our website and
                tell you about your privacy rights.</p>

            <h3>2. Data We Collect</h3>
            <p>We may collect, use, store and transfer different kinds of personal data about you which we have grouped
                together follows:</p>
            <ul>
                <li><strong>Identity Data</strong> includes first name, last name, username or similar identifier.</li>
                <li><strong>Contact Data</strong> includes email address.</li>
                <li><strong>Technical Data</strong> includes internet protocol (IP) address, your login data, browser type
                    and version.</li>
                <li><strong>Profile Data</strong> includes your username and password, books read, lists created, and
                    reviews made.</li>
            </ul>

            <h3>3. How We Use Your Data</h3>
            <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data
                in the following circumstances:</p>
            <ul>
                <li>Where we need to perform the contract we are about to enter into or have entered into with you.</li>
                <li>Where it is necessary for our legitimate interests (or those of a third party) and your interests and
                    fundamental rights do not override those interests.</li>
            </ul>

            <h3>4. Data Security</h3>
            <p>We have put in place appropriate security measures to prevent your personal data from being accidentally
                lost, used or accessed in an unauthorized way, altered or disclosed.</p>

            <h3>5. Contact Us</h3>
            <p>If you have any questions about this privacy policy or our privacy practices, please contact us at
                privacy@blockbookster.com.</p>
        </x-card>
    </div>
@endsection