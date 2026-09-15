<x-boma-layout>
    <div class="pb-10 flex flex-col space-y-6">
        
        <!-- HEADER DASHBOARD -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white mb-1.5 tracking-tight">Dashboard Keuangan 📊</h1>
                <p class="text-xs sm:text-sm text-zinc-400">Pemantauan arus kas otomatis, status Virtual Account, dan analitik piutang Klien.</p>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 px-5 py-3 rounded-2xl shadow-lg w-full md:w-auto text-left md:text-right">
                <p class="text-[10px] text-zinc-500 uppercase tracking-widest font-bold mb-1">Total Kas Terselesaikan (Settlement)</p>
                <h2 class="text-2xl font-black text-emerald-400">Rp {{ number_format($totalKasMasuk ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- METRIK UTAMA (4 KOLOM) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="bg-blue-500/10 p-2.5 rounded-xl border border-blue-500/20">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-white relative z-10">{{ $vaAktif ?? 0 }}</h3>
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mt-1 relative z-10">VA Aktif (Menunggu Bayar)</p>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="bg-emerald-500/10 p-2.5 rounded-xl border border-emerald-500/20">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-white relative z-10">{{ $poLunas ?? 0 }}</h3>
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mt-1 relative z-10">Pesanan Lunas</p>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="bg-amber-500/10 p-2.5 rounded-xl border border-amber-500/20">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-white relative z-10">Rp {{ number_format($piutang ?? 0, 0, ',', '.') }}</h3>
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mt-1 relative z-10">Total Piutang Berjalan</p>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 shadow-lg relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-500/10 rounded-full blur-2xl group-hover:bg-red-500/20 transition"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="bg-red-500/10 p-2.5 rounded-xl border border-red-500/20">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-white relative z-10">{{ $vaKedaluwarsa ?? 0 }}</h3>
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mt-1 relative z-10">VA Kedaluwarsa / Batal</p>
            </div>
        </div>

        <!-- MAIN LAYOUT: TABEL MONITORING & KALENDER -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <!-- KOLOM KIRI: MONITORING TRANSAKSI LIVE -->
            <div class="xl:col-span-2 bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl flex flex-col">
                <div class="flex justify-between items-center mb-6 border-b border-zinc-800 pb-4">
                    <h3 class="text-lg font-black text-white flex items-center">
                        <span class="relative flex h-3 w-3 mr-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        Live Monitoring Midtrans
                    </h3>
                    <a href="#" class="text-xs font-bold text-blue-400 hover:text-blue-300 transition">Sinkronisasi Data &rarr;</a>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] text-zinc-500 uppercase tracking-wider border-b border-zinc-800">
                                <th class="pb-3 font-bold px-2">Order ID</th>
                                <th class="pb-3 font-bold px-2">Klien / Instansi</th>
                                <th class="pb-3 font-bold px-2">Nominal</th>
                                <th class="pb-3 font-bold px-2">Status Gateway</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($transaksiTerbaru ?? [] as $trx)
                            <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/30 transition">
                                <td class="py-3 px-2">
                                    <p class="font-bold text-white">{{ $trx->order_id }}</p>
                                    <p class="text-[10px] text-zinc-500 font-mono">Tgl: {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</p>
                                </td>
                                <td class="py-3 px-2">
                                    <p class="font-bold text-white">{{ $trx->pengajuan->user->nama_perusahaan ?? 'Nama Klien' }}</p>
                                </td>
                                <td class="py-3 px-2">
                                    <p class="font-black text-emerald-400">Rp {{ number_format($trx->nominal, 0, ',', '.') }}</p>
                                </td>
                                <td class="py-3 px-2">
                                    @if($trx->status_termin == 'Lunas')
                                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">Settlement</span>
                                    @elseif($trx->status_termin == 'Menunggu Pembayaran')
                                        <span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">Pending VA</span>
                                    @else
                                        <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">Expired / Batal</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-zinc-500 text-xs">
                                    Belum ada transaksi Virtual Account terbaru saat ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- KOLOM KANAN: KALENDER JATUH TEMPO -->
            <div class="xl:col-span-1 bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-black text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Kalender Penagihan
                    </h3>
                </div>

                <!-- Navigasi Kalender -->
                <div class="flex justify-between items-center mb-4 bg-zinc-950 p-2 rounded-xl border border-zinc-800">
                    <button id="prevMonth" class="p-2 hover:bg-zinc-800 text-zinc-400 hover:text-white rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                    <h4 id="monthYear" class="text-xs font-black text-white uppercase tracking-widest">Bulan Tahun</h4>
                    <button id="nextMonth" class="p-2 hover:bg-zinc-800 text-zinc-400 hover:text-white rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    <div class="text-[10px] font-bold text-zinc-500">Min</div>
                    <div class="text-[10px] font-bold text-zinc-500">Sen</div>
                    <div class="text-[10px] font-bold text-zinc-500">Sel</div>
                    <div class="text-[10px] font-bold text-zinc-500">Rab</div>
                    <div class="text-[10px] font-bold text-zinc-500">Kam</div>
                    <div class="text-[10px] font-bold text-zinc-500">Jum</div>
                    <div class="text-[10px] font-bold text-zinc-500">Sab</div>
                </div>
                
                <div id="calendar-grid" class="grid grid-cols-7 gap-1 mb-4">
                    <!-- Javascript Data Injeksi Kalender -->
                </div>
                
                <!-- Detail Hari Kalender (Muncul saat tanggal diklik) -->
                <div id="event-details" class="hidden bg-zinc-950 p-4 rounded-xl border border-zinc-800">
                    <h4 id="event-date-title" class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-3 border-b border-zinc-800 pb-2">Detail Tanggal</h4>
                    <ul id="event-list" class="space-y-3"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT KALENDER & INTERAKSI -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const events = @json($events ?? []);
            let currentDate = new Date();
            
            function renderCalendar() {
                const monthYear = document.getElementById('monthYear');
                const grid = document.getElementById('calendar-grid');
                grid.innerHTML = '';
                
                const month = currentDate.getMonth();
                const year = currentDate.getFullYear();
                
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                monthYear.textContent = `${months[month]} ${year}`;
                
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                
                for (let i = 0; i < firstDay; i++) {
                    grid.innerHTML += `<div class="p-2 rounded-lg"></div>`;
                }
                
                for (let day = 1; day <= daysInMonth; day++) {
                    let dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    let dayEvents = events.filter(e => e.date === dateStr);
                    let hasEvent = dayEvents.length > 0;
                    
                    let dotIndicator = hasEvent ? `<div class="w-1.5 h-1.5 bg-amber-500 rounded-full mx-auto mt-0.5"></div>` : '';
                    let interactClass = hasEvent ? 'cursor-pointer hover:bg-zinc-700 border-zinc-700' : 'cursor-default border-transparent';
                    let baseClass = hasEvent ? 'bg-zinc-800' : 'bg-transparent text-zinc-400';
                    
                    let today = new Date();
                    if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        baseClass = 'bg-emerald-900/30 text-emerald-400 border border-emerald-500/50';
                    }

                    grid.innerHTML += `
                        <div onclick="showEventDetails('${dateStr}')" class="relative p-2 rounded-lg text-center text-xs font-bold transition border ${baseClass} ${interactClass}">
                            ${day}
                            ${dotIndicator}
                        </div>
                    `;
                }
            }

            window.showEventDetails = function(dateStr) {
                let dayEvents = events.filter(e => e.date === dateStr);
                const container = document.getElementById('event-details');
                const title = document.getElementById('event-date-title');
                const list = document.getElementById('event-list');
                
                if (dayEvents.length === 0) {
                    container.classList.add('hidden');
                    return;
                }

                title.textContent = `JATUH TEMPO: ${dateStr}`;
                list.innerHTML = '';
                dayEvents.forEach(ev => {
                    list.innerHTML += `
                        <li class="flex items-start text-xs bg-zinc-900 p-3 rounded-lg border border-zinc-800 border-l-2 border-l-amber-500">
                            <div>
                                <span class="font-black text-white block mb-0.5">${ev.title}</span>
                                <span class="text-[10px] text-zinc-400">${ev.description}</span>
                            </div>
                        </li>
                    `;
                });
                container.classList.remove('hidden');
            };

            document.getElementById('prevMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
                document.getElementById('event-details').classList.add('hidden');
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
                document.getElementById('event-details').classList.add('hidden');
            });

            renderCalendar();
        });
    </script>
</x-boma-layout>