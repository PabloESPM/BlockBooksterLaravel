<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Auth') - BlockBookster</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Space+Grotesk:wght@500;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-body bg-brand-blue flex items-center justify-center min-h-screen p-4"
    style="background-image: radial-gradient(#1e3a8a 1px, transparent 1px); background-size: 20px 20px;">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <a href="/" class="text-4xl font-display font-black text-white tracking-tighter uppercase"
                style="text-shadow: 4px 4px 0px #000;">
                Block<span class="text-brand-yellow text-shadow-black">Book</span>ster
            </a>
        </div>

        <!-- Auth Card -->
        <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8">
            @yield('content')

            @if(View::hasSection('footer-link'))
                <div class="mt-8 pt-4 border-t-2 border-black text-center text-sm font-bold">
                    @yield('footer-link')
                </div>
            @endif
        </div>

        <div class="mt-8 text-center text-xs font-bold text-white uppercase tracking-widest">
            Identity Verified
        </div>
    </div>

</body>

</html>