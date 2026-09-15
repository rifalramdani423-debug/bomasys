<x-boma-layout>
    <div class="pb-10 space-y-6">
        
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-black text-white mb-1">Executive Command Center 👑</h1>
                <p class="text-sm text-zinc-400">Ringkasan performa Boma Advertising secara real-time dari seluruh divisi.</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-zinc-500 font-bold uppercase">Status Operasional</p>
                <p class="text-sm font-black text-emerald-500 flex items-center justify-end">
                    <span class="relative flex h-3 w-3 mr-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span></span>
                    SISTEM AMAN & TERPANTAU
                </p>
            </div>
        </div>

        <!-- METRIK 4 DIVISI (SUMMARY REAL DATA) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- Keuangan -->
            <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 border border-emerald-500/20 p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-emerald-500/10 group-hover:scale-110 transition duration-500"><svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/></svg></div>
                <p class="text-[10px] uppercase text-zinc-400 font-bold mb-1 relative z-10">Total Kas Masuk (Bulan Ini)</p>
                <h3 class="text-2xl font-black text-emerald-400 relative z-10">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-600 mt-2 font-medium relative z-10">Dari pelunasan termin klien</p>
            </div>
            
            <!-- Admin -->
            <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 border border-red-500/20 p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-red-500/10 group-hover:scale-110 transition duration-500"><svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
                <p class="text-[10px] uppercase text-zinc-400 font-bold mb-1 relative z-10">Negosiasi & Prospek (Admin)</p>
                <h3 class="text-2xl font-black text-white relative z-10">{{ $negosiasiAktif }} <span class="text-sm font-bold text-zinc-500">Proyek Aktif</span></h3>
                <p class="text-xs text-red-500 mt-2 font-medium relative z-10">{{ $pendingApproval }} Pengajuan menunggu respon</p>
            </div>

            <!-- Aset -->
            <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 border border-blue-500/20 p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-blue-500/10 group-hover:scale-110 transition duration-500"><svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/></svg></div>
                <p class="text-[10px] uppercase text-zinc-400 font-bold mb-1 relative z-10">Okupansi Titik (Aset)</p>
                <h3 class="text-2xl font-black text-blue-400 relative z-10">{{ $kesehatanAset }}<span class="text-sm font-bold text-zinc-500">% Tersewa</span></h3>
                <p class="text-xs text-blue-600 mt-2 font-medium relative z-10">{{ $totalTitik }} Titik Terdata / {{ $perbaikanAset }} Perbaikan</p>
            </div>

            <!-- Produksi -->
            <div class="bg-gradient-to-br from-zinc-900 to-zinc-950 border border-orange-500/20 p-5 rounded-2xl shadow-xl relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 text-orange-500/10 group-hover:scale-110 transition duration-500"><svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9h-2V7h-2v5H6v-2h2V7c0-1.1.9-2 2-2h2v2h2v5h-2v-2z"/></svg></div>
                <p class="text-[10px] uppercase text-zinc-400 font-bold mb-1 relative z-10">Kinerja Lapangan (Produksi)</p>
                <h3 class="text-2xl font-black text-orange-400 relative z-10">Optimal</h3>
                <p class="text-xs text-orange-600 mt-2 font-medium relative z-10">Log pemantauan berjalan normal</p>
            </div>
        </div>

        <!-- TAMPILAN GRAFIK & PIPELINE -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- AREA GRAFIK (MEMAKAI CHART.JS) -->
            <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white">Tren Okupansi & Interaksi Klien Tahunan</h3>
                    <p class="text-[10px] text-zinc-500 mt-0.5">Visualisasi rasio penyewaan titik reklame BOMA Sys</p>
                </div>
                <!-- Canvas untuk Chart.js -->
                <div class="w-full h-64 mt-4 relative">
                    <canvas id="gmChart"></canvas>
                </div>
            </div>

            <!-- Pipeline Log Cepat -->
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl overflow-hidden flex flex-col">
                <h3 class="text-sm font-bold text-white mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Pipeline Proyek Terbaru
                </h3>
                <div class="flex-1 space-y-4 overflow-y-auto custom-scrollbar pr-2">
                    @forelse($pipelineTerbaru as $pipe)
                        <div class="flex items-start p-3 bg-zinc-950/50 rounded-xl border border-zinc-800/50">
                            <div class="w-2 h-2 mt-1.5 rounded-full {{ $pipe->status_pengajuan == 'Lunas / Aktif' ? 'bg-emerald-500' : 'bg-red-500' }} mr-3 shrink-0"></div>
                            <div>
                                <p class="text-xs font-bold text-white">{{ $pipe->user?->nama_perusahaan ?: ($pipe->user?->name ?: 'Klien (Data Terhapus)') }}</p>
                                <p class="text-[10px] text-zinc-400 mt-0.5">Status: <span class="text-zinc-300 font-medium">{{ $pipe->status_pengajuan }}</span></p>
                                <p class="text-[10px] text-emerald-500 font-mono mt-1">Estimasi: Rp {{ number_format($pipe->harga_final ?? $pipe->estimasi_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-xs text-zinc-500">Belum ada proyek yang tercatat.</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('gm.audit') }}" class="mt-4 text-[11px] font-bold text-purple-400 hover:text-white text-center block w-full py-2.5 bg-purple-500/10 border border-purple-500/20 hover:bg-purple-500 hover:border-purple-500 transition rounded-xl">Analisis Jejak Audit Lengkap &rarr;</a>
            </div>
        </div>
    </div>

    <!-- SCRIPT UNTUK MERENDER GRAFIK CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('gmChart').getContext('2d');
            
            // Konfigurasi Gradient untuk Line Chart
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); // Emerald 500 transparency
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Proyeksi Titik Tersewa',
                        data: [12, 19, 15, 25, 22, 30, 28, {{ $kesehatanAset > 0 ? 35 : 20 }}, 40, 45, 50, 60],
                        borderColor: '#10b981', // Emerald 500
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4 // Membuat garis melengkung halus
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#18181b', // zinc-900
                            titleColor: '#fff',
                            bodyColor: '#a1a1aa', // zinc-400
                            borderColor: '#27272a', // zinc-800
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#27272a', borderDash: [5, 5] }, // zinc-800
                            ticks: { color: '#71717a', font: { size: 10 } } // zinc-500
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#71717a', font: { size: 10 } } // zinc-500
                        }
                    }
                }
            });
        });
    </script>
</x-boma-layout>