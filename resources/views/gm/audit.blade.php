<x-boma-layout>
    <div class="pb-10 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white mb-1">Sistem Jejak Audit (Audit Trail) 🛡️</h1>
                <p class="text-sm text-zinc-400">Log aktivitas real-time dari seluruh divisi (Admin, Keuangan, Aset, dan Klien).</p>
            </div>
            
            <!-- FORM PENCARIAN & FILTER DINAMIS -->
            <form method="GET" action="{{ route('gm.audit') }}" class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                
                <!-- Kolom Search Baru -->
                <div class="relative w-full sm:w-56">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas atau aktor..." class="w-full bg-zinc-900 border border-zinc-800 rounded-lg pl-9 pr-3 py-2 text-white text-xs focus:border-purple-500 outline-none">
                    <svg class="w-4 h-4 text-zinc-500 absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <select name="modul" onchange="this.form.submit()" class="w-full sm:w-auto bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-white text-xs focus:border-purple-500 outline-none cursor-pointer">
                    <option value="Semua Modul" {{ $modul == 'Semua Modul' ? 'selected' : '' }}>-- Semua Area --</option>
                    <option value="Modul Pesanan" {{ $modul == 'Modul Pesanan' ? 'selected' : '' }}>Modul Admin (Pesanan)</option>
                    <option value="Modul Keuangan" {{ $modul == 'Modul Keuangan' ? 'selected' : '' }}>Modul Keuangan</option>
                    <option value="Modul Aset" {{ $modul == 'Modul Aset' ? 'selected' : '' }}>Modul Aset & Produksi</option>
                    <option value="Modul Arsip" {{ $modul == 'Modul Arsip' ? 'selected' : '' }}>Modul Arsip</option>
                </select>
                
                <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()" class="w-full sm:w-auto bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-white text-xs focus:border-purple-500 [color-scheme:dark] outline-none cursor-pointer">
                
                @if(request('tanggal') || request('search') || ($modul && $modul != 'Semua Modul'))
                    <a href="{{ route('gm.audit') }}" class="px-3 py-2 bg-zinc-800 text-zinc-300 hover:text-white rounded-lg text-xs hover:bg-zinc-700 transition text-center sm:text-left flex items-center justify-center">Reset</a>
                @endif
                
                <button type="submit" class="hidden"></button>
            </form>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-zinc-950/50 border-b border-zinc-800 text-zinc-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-xs tracking-wider uppercase">Waktu (Timestamp)</th>
                            <th class="px-6 py-4 font-semibold text-xs tracking-wider uppercase">Pelaku / Aktor</th>
                            <th class="px-6 py-4 font-semibold text-xs tracking-wider uppercase">Aktivitas Sistem Terekam</th>
                            <th class="px-6 py-4 font-semibold text-xs tracking-wider uppercase text-center">Modul Area</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @forelse($auditLogs as $log)
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="px-6 py-4 text-xs font-mono text-zinc-400">
                                {{ \Carbon\Carbon::parse($log->waktu)->format('d M Y') }}<br>
                                <span class="text-white font-bold">{{ \Carbon\Carbon::parse($log->waktu)->format('H:i:s') }} WIB</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-white font-bold text-xs">{{ $log->aktor }}</span>
                                    <span class="text-[10px] text-purple-400 font-bold bg-purple-500/10 border border-purple-500/20 px-2 py-0.5 rounded w-max mt-1 uppercase">{{ $log->peran }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-zinc-300 font-medium">{{ $log->aktivitas }}</p>
                                <p class="text-[10px] text-zinc-500 font-mono mt-0.5">ID Referensi: #{{ $log->ref_id }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($log->modul == 'Modul Keuangan')
                                    <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold px-3 py-1 rounded-full uppercase">KEUANGAN</span>
                                @elseif($log->modul == 'Modul Pesanan')
                                    <span class="bg-red-500/10 text-red-400 border border-red-500/20 text-[10px] font-bold px-3 py-1 rounded-full uppercase">ADMIN</span>
                                @elseif($log->modul == 'Modul Aset')
                                    <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold px-3 py-1 rounded-full uppercase">ASET</span>
                                @else
                                    <span class="bg-zinc-800 text-zinc-400 border border-zinc-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">ARSIP</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <p class="text-zinc-400 text-sm font-medium">Data log tidak ditemukan dengan pencarian tersebut.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-950/30">
                {{ $auditLogs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-boma-layout>