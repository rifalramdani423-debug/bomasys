<x-boma-layout>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <h1 class="text-2xl font-black text-white mb-6">Dashboard Aset</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-xl">
            <h3 class="text-zinc-400 text-sm font-bold">Total Papan Reklame</h3>
            <p class="text-3xl font-black text-white mt-2">{{ $totalTitik }}</p>
        </div>
        <div class="bg-green-900/20 p-6 rounded-2xl border border-green-800/50 shadow-xl">
            <h3 class="text-green-400 text-sm font-bold">Titik Tersedia / Kosong</h3>
            <p class="text-3xl font-black text-green-500 mt-2">{{ $totalTersedia }}</p>
        </div>
        <div class="bg-red-900/20 p-6 rounded-2xl border border-red-800/50 shadow-xl">
            <h3 class="text-red-400 text-sm font-bold">Titik Disewa</h3>
            <p class="text-3xl font-black text-red-500 mt-2">{{ $totalDisewa }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-10">
        
        <div class="lg:col-span-7 space-y-6">
            
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-xl">
                <div class="bg-zinc-950/80 px-5 py-4 border-b border-zinc-800 flex items-center">
                    <span class="bg-blue-500/20 text-blue-500 p-1.5 rounded-lg mr-3 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </span>
                    <h2 class="text-md font-bold text-white">Notifikasi Titik Tersewa Baru</h2>
                </div>
                <div class="p-5 space-y-4">
                    @forelse($pemasanganMenunggu as $menunggu)
                        <div class="p-4 rounded-xl border border-blue-900/40 bg-blue-900/10 flex flex-col relative overflow-hidden group">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                            
                            <div class="pl-2">
                                <p class="text-sm text-blue-400 font-black mb-1">Pesanan {{ $menunggu->nomor_pengajuan }} ({{ $menunggu->nama_perusahaan ?? $menunggu->nama_klien }})</p>
                                
                                <div class="flex items-center text-[11px] text-zinc-400 mb-2 bg-zinc-950/50 inline-flex px-2 py-1 rounded border border-zinc-800/50">
                                    <svg class="w-3 h-3 mr-1.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Jadwal Pemasangan (Hari H): <span class="text-emerald-400 font-bold ml-1">{{ \Carbon\Carbon::parse($menunggu->mulai_sewa)->format('d M Y') }}</span>
                                </div>
                                
                                <p class="text-[10px] text-zinc-500">Validasi pembayaran awal sukses. Pemasangan otomatis masuk ke Agenda Kalender.</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 border border-dashed border-zinc-800 rounded-xl bg-zinc-900/50">
                            <p class="text-xs text-zinc-500 font-medium">Belum ada pesanan baru yang sudah sah dibayar.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden shadow-xl">
                <div class="bg-zinc-950/80 px-5 py-4 border-b border-zinc-800 flex items-center">
                    <span class="bg-orange-500/20 text-orange-500 p-1.5 rounded-lg mr-3 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    <h2 class="text-md font-bold text-white">Peringatan Eksekusi Hari Ini</h2>
                </div>
                <div class="p-5 space-y-3">
                    @forelse($alerts as $alert)
                        <div class="p-4 rounded-xl border border-orange-700/50 bg-orange-900/10 flex items-start relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500"></div>
                            <span class="text-orange-500 ml-2 mr-3 mt-0.5"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></span>
                            <p class="text-sm text-orange-300 font-bold">{{ $alert['teks'] }}</p>
                        </div>
                    @empty
                        <div class="text-center py-6 border border-dashed border-zinc-800 rounded-xl bg-zinc-900/50">
                            <p class="text-xs text-zinc-500 font-medium">Tidak ada jadwal eksekusi lapangan untuk hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl shadow-xl overflow-hidden sticky top-6">
                
                <div class="bg-zinc-950/80 px-5 py-4 border-b border-zinc-800">
                    <h2 class="text-md font-bold text-white">Kalender & Agenda Tugas</h2>
                    <p class="text-[10px] text-zinc-500 mt-1 flex items-center">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-1.5 shadow-[0_0_5px_#ef4444]"></span> Pemasangan &nbsp;&nbsp;&nbsp; 
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 mr-1.5 shadow-[0_0_5px_#eab308]"></span> Cek H-3
                    </p>
                </div>
                
                <div class="p-4 bg-zinc-900/50">
                    <div id="mini-calendar" class="text-xs font-sans"></div>
                </div>

                <div class="border-t border-zinc-800 px-5 py-4 bg-zinc-900">
                    <h3 class="text-xs font-black text-zinc-400 mb-4 uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Daftar Agenda Mendatang
                    </h3>
                    
                    <div class="space-y-3 max-h-[320px] overflow-y-auto custom-scrollbar pr-2 pb-2">
                        @forelse($tasksMendatang as $task)
                            @php
                                $isH3 = $task['kode_tugas'] == 'CHK';
                                $colorBg = $isH3 ? 'bg-yellow-500/10 border-yellow-500/30' : 'bg-red-500/10 border-red-500/30';
                                $colorText = $isH3 ? 'text-yellow-500' : 'text-red-500';
                                $title = $isH3 ? 'Persiapan Titik' : 'Pemasangan Visual';
                                
                                $bb = $billboards->firstWhere('kode_titik', $task['titik']);
                                $lokasi = $bb ? $bb->lokasi : '';
                            @endphp
                            
                            <div class="p-4 rounded-xl border {{ $colorBg }} flex flex-col relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 {{ $isH3 ? 'bg-yellow-500' : 'bg-red-500' }}"></div>
                                
                                <div class="pl-1 mb-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded bg-zinc-950 border border-zinc-800 {{ $colorText }} shadow-sm">
                                                {{ \Carbon\Carbon::parse($task['tanggal'])->format('d M Y') }}
                                            </span>
                                            <span class="text-[9px] font-bold text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded uppercase tracking-wider">{{ $task['klien'] }}</span>
                                        </div>
                                    </div>
                                    
                                    <h4 class="text-sm text-white font-black leading-tight">{{ $title }}</h4>
                                    <p class="text-[11px] text-zinc-300 font-medium mt-1">{{ $task['titik'] }} - {{ $lokasi }}</p>
                                </div>
                                
                                <a href="{{ route('aset.im.buat', ['titik' => $task['titik'], 'jenis' => $task['kode_tugas']]) }}" class="w-full bg-zinc-950 hover:bg-zinc-800 text-white text-[10px] uppercase tracking-wider font-bold px-4 py-2.5 rounded-lg border border-zinc-700 transition shadow-sm text-center flex items-center justify-center">
                                    Buat IM Eksekusi <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-6 bg-zinc-950/50 rounded-xl border border-zinc-800 border-dashed">
                                <p class="text-xs text-zinc-500 font-medium">Tidak ada agenda lapangan dalam waktu dekat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #52525b; }
        
        .fc-theme-standard td, .fc-theme-standard th { border-color: #27272a; }
        .fc .fc-toolbar-title { font-size: 1rem; font-weight: 900; color: white; }
        .fc .fc-button-primary { background-color: #18181b; border-color: #3f3f46; color: white; padding: 0.25rem 0.5rem; font-size: 0.75rem; text-transform: capitalize; font-weight: bold;}
        .fc .fc-button-primary:hover { background-color: #27272a; border-color: #ef4444; }
        .fc .fc-daygrid-day-number { color: #d4d4d8; text-decoration: none; padding: 4px; font-weight: 500;}
        .fc .fc-day-today { background-color: rgba(239, 68, 68, 0.1) !important; }
        
        .fc-event {
            cursor: pointer;
            border-radius: 4px !important;
            padding: 2px 4px !important;
            margin-bottom: 3px !important;
            font-size: 0.65rem !important; 
            font-weight: 800 !important;
            border: none !important;
            text-align: left;
            transition: transform 0.2s;
        }
        .fc-event:hover {
            transform: scale(1.05);
            z-index: 10;
        }
        .fc-event-title {
            color: white !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block !important;
        }
        .fc-event-time { display: none !important; } 
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('mini-calendar');
            
            var rawTasks = @json($allTasks ?? []);
            var eventsData = rawTasks.map(function(task) {
                let color = '#3b82f6'; 
                let titlePrefix = '';
                
                if(task.kode_tugas === 'PSB') {
                    color = '#ef4444'; 
                    titlePrefix = 'Pasang: ';
                } else if(task.kode_tugas === 'CHK') {
                    color = '#eab308'; 
                    titlePrefix = 'H-3: ';
                }

                return {
                    title: titlePrefix + task.titik, 
                    start: task.tanggal,
                    backgroundColor: color,
                    display: 'block', 
                    extendedProps: {
                        klien: task.klien,
                        jenis: task.jenis,
                        titik: task.titik
                    }
                };
            });

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: eventsData,
                contentHeight: 380, 
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                eventDidMount: function(info) {
                    let eventData = info.event.extendedProps;
                    let tooltipText = `${eventData.jenis}\nTitik: ${eventData.titik}\nKlien: ${eventData.klien}`;
                    info.el.setAttribute('title', tooltipText);
                }
            });
            calendar.render();
        });
    </script>
</x-boma-layout>