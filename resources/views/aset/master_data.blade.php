<x-boma-layout>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="flex justify-between items-center mb-6" x-data="{ openModal: false }">
        <h1 class="text-2xl font-black text-white">Inventaris Reklame (Master Data)</h1>
        
        <!-- Tombol Tambah Titik -->
        <button @click="openModal = true" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center shadow-lg shadow-red-600/20 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Titik Baru
        </button>

        <!-- Modal Form (Alpine.js) -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm transition-opacity" style="display: none;">
            <div @click.away="openModal = false" class="bg-zinc-900 border border-zinc-700 p-6 rounded-2xl w-full max-w-lg shadow-2xl transform scale-100">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center border-b border-zinc-800 pb-3">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    Input Titik Billboard Baru
                </h2>
                <form action="{{ route('aset.master_data.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-zinc-400">Kode Titik (Harus Unik)</label>
                            <input type="text" name="kode_titik" required placeholder="Contoh: BM 99" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white mt-1 focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-zinc-400">Alamat / Lokasi Lengkap</label>
                            <input type="text" name="lokasi" required placeholder="Jl. Sudirman..." class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white mt-1 focus:border-red-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-zinc-400">Koordinat Latitude</label>
                                <input type="text" name="latitude" placeholder="-6.914744" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white mt-1 focus:border-red-500 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-zinc-400">Koordinat Longitude</label>
                                <input type="text" name="longitude" placeholder="107.609810" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white mt-1 focus:border-red-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-zinc-400">Ukuran</label>
                                <input type="text" name="ukuran" placeholder="4m x 8m (V)" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white mt-1 focus:border-red-500 outline-none">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-zinc-400">Harga per Bulan</label>
                                <input type="number" name="harga_per_bulan" required placeholder="35000000" class="w-full bg-zinc-950 border border-zinc-800 rounded-lg px-3 py-2 text-white mt-1 focus:border-red-500 outline-none">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-zinc-800">
                        <button type="button" @click="openModal = false" class="px-5 py-2.5 text-sm font-bold text-zinc-400 hover:text-white transition">Batal</button>
                        <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2.5 rounded-lg font-bold text-sm transition shadow-lg">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PETA PERSEBARAN ASET (VIEW ONLY) -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 shadow-xl flex flex-col mb-6">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-md font-bold text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                Peta Pemantauan Titik Reklame
            </h2>
            <div class="flex space-x-3 text-[10px] font-bold bg-zinc-950 p-2 rounded-lg border border-zinc-800">
                <span class="flex items-center text-zinc-400"><div class="w-2.5 h-2.5 bg-green-500 rounded-full mr-1.5 shadow-[0_0_5px_#22c55e]"></div> Tersedia</span>
                <span class="flex items-center text-zinc-400"><div class="w-2.5 h-2.5 bg-red-500 rounded-full mr-1.5 shadow-[0_0_5px_#ef4444]"></div> Tersewa / Booking</span>
            </div>
        </div>
        <div class="w-full h-[400px] bg-zinc-950 rounded-xl border border-zinc-800 relative z-0 overflow-hidden">
            <div id="map" class="absolute inset-0 z-0"></div>
        </div>
    </div>

    <!-- TABEL INVENTARIS -->
    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-zinc-800 bg-zinc-950/50">
            <h3 class="text-sm font-bold text-zinc-300">Daftar Inventaris Lengkap</h3>
        </div>
        <table class="w-full text-sm text-left text-zinc-300">
            <thead class="text-[10px] text-zinc-500 uppercase bg-zinc-950 tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-bold">Kode Titik</th>
                    <th class="px-6 py-4 font-bold">Lokasi & Ukuran</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @forelse($billboards as $bb)
                <tr class="hover:bg-zinc-800/50 transition">
                    <td class="px-6 py-4 font-black text-white text-base">{{ $bb->kode_titik }}</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-zinc-200">{{ $bb->lokasi }}</span> <br>
                        <span class="text-[11px] text-zinc-500 font-mono mt-1 inline-block bg-zinc-950 px-2 py-0.5 rounded border border-zinc-800">{{ $bb->ukuran }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm {{ $bb->status == 'Tersedia' ? 'bg-green-500/10 text-green-500 border border-green-500/20' : 'bg-red-500/10 text-red-500 border border-red-500/20' }}">
                            {{ $bb->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($bb->status == 'Tersedia')
                            <a href="{{ route('aset.im.buat', ['titik' => $bb->kode_titik, 'jenis' => 'INT']) }}" class="text-[10px] font-bold bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition shadow-lg shadow-blue-600/20 inline-flex items-center">
                                Pasang Iklan BOMA &rarr;
                            </a>
                        @else
                            <span class="text-[10px] text-zinc-600 italic">Sedang Digunakan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-zinc-500">Belum ada data titik reklame yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Leaflet JS & Map Script -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Koordinat pusat default (Bandung)
            var map = L.map('map').setView([-6.914744, 107.609810], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

            var billboards = @json($billboards);

            billboards.forEach(function(bb) {
                // Pastikan titik memiliki koordinat (latitude & longitude)
                if (bb.latitude && bb.longitude) {
                    let warnaFill = bb.status === 'Tersedia' ? '#22c55e' : '#ef4444'; // Hijau atau Merah
                    
                    var marker = L.circleMarker([parseFloat(bb.latitude), parseFloat(bb.longitude)], {
                        radius: 8,
                        fillColor: warnaFill,
                        color: '#ffffff',
                        weight: 2,
                        fillOpacity: 1
                    }).addTo(map);

                    // Konten Popup
                    marker.bindPopup(`
                        <div class="min-w-[150px] font-sans">
                            <h4 class="font-black text-red-600 text-sm mb-1">${bb.kode_titik}</h4>
                            <p class="text-[10px] text-zinc-600 leading-tight mb-2 border-b border-zinc-200 pb-2">${bb.lokasi}</p>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-[10px] text-zinc-500 font-bold">Status:</span>
                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded ${bb.status === 'Tersedia' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">${bb.status}</span>
                            </div>
                        </div>
                    `);
                }
            });
        });
    </script>
</x-boma-layout>