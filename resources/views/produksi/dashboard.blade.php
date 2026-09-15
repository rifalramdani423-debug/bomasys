<x-boma-layout>
    <div class="pb-24 pt-2 md:pt-0"> 
        
        <!-- HEADER MOBILE -->
        <div class="mb-6 flex justify-between items-center bg-orange-500/10 border border-orange-500/20 p-4 rounded-2xl">
            <div>
                <h1 class="text-xl font-black text-white">Halo, {{ auth()->user()->name }}! 👷‍♂️</h1>
                <p class="text-xs text-orange-400 font-medium">Tetap utamakan K3 & Keselamatan Kerja.</p>
            </div>
            <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-zinc-900 border border-zinc-800 p-4 rounded-2xl text-center">
                <h2 class="text-3xl font-black text-white mb-1">{{ $totalTugasHariIni }}</h2>
                <p class="text-[10px] uppercase tracking-wider text-zinc-500 font-bold">Tugas Hari Ini</p>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 p-4 rounded-2xl text-center">
                <h2 class="text-3xl font-black text-emerald-500 mb-1">{{ $selesaiBulanIni }}</h2>
                <p class="text-[10px] uppercase tracking-wider text-zinc-500 font-bold">Selesai Bulan Ini</p>
            </div>
        </div>

        <h3 class="text-sm font-bold text-zinc-400 mb-3 uppercase tracking-wider">Kotak Tugas (IM Masuk)</h3>
        
        <div class="space-y-4">
            @forelse($daftarTugas as $tugas)
                @php
                    // Menerjemahkan array JSON menjadi teks yang rapi
                    $titikArray = json_decode($tugas->kode_titik, true);
                    $titikRapi = is_array($titikArray) ? implode(' | ', $titikArray) : $tugas->kode_titik;
                @endphp
                
                <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 border {{ $tugas->tanggal_target == date('Y-m-d') ? 'border-red-500/50' : 'border-orange-500/30' }} rounded-2xl p-5 shadow-xl relative overflow-hidden">
                    
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="bg-zinc-800 text-zinc-300 text-[10px] font-bold px-2 py-1 rounded">{{ $tugas->no_im }}</span>
                        @if($tugas->tanggal_target == date('Y-m-d'))
                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded animate-pulse">HARI INI</span>
                        @else
                            <span class="text-xs text-zinc-500">Target: {{ \Carbon\Carbon::parse($tugas->tanggal_target)->format('d M Y') }}</span>
                        @endif
                    </div>
                    
                    <h4 class="text-lg font-black text-white mb-1">{{ $tugas->perihal }}</h4>
                    <p class="text-xs text-zinc-400 mb-2">Visual: <span class="text-zinc-200 font-bold">{{ $tugas->klien_visual }}</span></p>
                    
                    <!-- Ini bagian yang menampilkan teks rapinya -->
                    <p class="text-[11px] text-zinc-300 mb-4 flex items-start">
                        <svg class="w-4 h-4 mr-1.5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span class="leading-relaxed">{{ $titikRapi }}</span>
                    </p>
                    
                    <a href="{{ route('produksi.tugas', ['no_im' => $tugas->no_im]) }}" class="block w-full bg-orange-600 hover:bg-orange-500 active:bg-orange-700 text-white text-center text-sm font-bold py-3 rounded-xl transition shadow-lg shadow-orange-500/20">
                        Buka & Lapor Eksekusi
                    </a>
                </div>
            @empty
                <div class="bg-zinc-900 border border-zinc-800 border-dashed rounded-2xl p-8 text-center">
                    <p class="text-zinc-500 text-sm">Semua tugas telah diselesaikan! Tidak ada IM aktif saat ini.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-boma-layout>