<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BOMA Advertising') }} - Solusi Media Outdoor</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-zinc-300 antialiased bg-zinc-950 selection:bg-red-600 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            
            <!-- Efek Cahaya Merah (Glow) di Background -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-red-600/10 blur-[150px] rounded-full pointer-events-none"></div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 bg-zinc-900/80 backdrop-blur-xl border border-zinc-800 shadow-2xl sm:rounded-3xl">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <a href="/" class="text-3xl font-black tracking-wider text-white hover:scale-105 transition-transform duration-300">
                        BOMA <span class="text-red-600">Advertising</span>
                    </a>
                </div>

                <!-- Area Konten Form -->
                {{ $slot }}
            </div>
        </div>
    </body>
</html>