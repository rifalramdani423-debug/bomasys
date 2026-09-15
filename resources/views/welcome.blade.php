<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BOMA Advertising - Solusi Media Outdoor Terpercaya</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-white font-sans antialiased selection:bg-red-600 selection:text-white">

    <nav class="fixed top-0 left-0 right-0 z-50 bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0 flex items-center space-x-2">
                    <span class="text-2xl font-black tracking-wider text-white">
                        BOMA <span class="text-red-600">Advertising</span>
                    </span>
                </div>

                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-zinc-300">
                    <a href="#home" class="hover:text-red-500 transition">Home</a>
                    <a href="#katalog" class="hover:text-red-500 transition">Lokasi Billboard</a>
                    <a href="#tentang" class="hover:text-red-500 transition">Tentang Kami</a>
                    <a href="#kontak" class="hover:text-red-500 transition">Kontak</a>
                </div>

                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition duration-200 shadow-lg shadow-red-600/30">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-zinc-300 hover:text-white px-3 py-2 transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition duration-200 shadow-lg shadow-red-600/30">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-red-600/15 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-red-950/80 text-red-400 border border-red-800/50 mb-6">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse mr-2"></span>
                Mitra Media Luar Ruang Terpercaya di Indonesia
            </span>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight max-w-5xl mx-auto">
                Solusi Media Outdoor <br class="hidden md:inline"/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-700">Terpercaya</span> untuk Brand Anda
            </h1>

            <p class="mt-6 text-base md:text-lg text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                Promosikan brand Anda di lokasi billboard strategis dengan jangkauan maksimal, transparansi penyewaan, dan notifikasi status pemesanan secara langsung.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-red-600 text-white font-bold text-sm hover:bg-red-700 transition duration-200 shadow-xl shadow-red-600/25">
                    Sewa Billboard Sekarang
                </a>
                <a href="#katalog" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-zinc-900 text-zinc-300 font-semibold text-sm border border-zinc-800 hover:bg-zinc-800 hover:text-white transition duration-200">
                    Cek Lokasi Tersedia
                </a>
            </div>

            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-6 p-6 rounded-2xl bg-zinc-900/60 border border-zinc-800/80 backdrop-blur-sm max-w-4xl mx-auto">
                <div>
                    <h3 class="text-3xl font-black text-white">90+</h3>
                    <p class="text-xs text-zinc-400 mt-1">Titik Lokasi</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-white">35+</h3>
                    <p class="text-xs text-zinc-400 mt-1">Brand Partner</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-white">17</h3>
                    <p class="text-xs text-zinc-400 mt-1">Kota Jangkauan</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-white">20+</h3>
                    <p class="text-xs text-zinc-400 mt-1">Tahun Pengalaman</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-zinc-800 bg-zinc-950 py-8 text-center text-xs text-zinc-500">
        <p>&copy; {{ date('Y') }} BOMA Advertising. All rights reserved.</p>
    </footer>

</body>
</html>