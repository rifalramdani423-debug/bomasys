<x-boma-layout>
    <!-- Tambahkan library Kalender -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-white">Lalu Lintas Tugas Lapangan</h1>
            <p class="text-sm text-zinc-400 mt-1">Sistem kalender otomatis untuk H-3, Pemasangan, dan Maintenance.</p>
        </div>
    </div>
    
    <div class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800">
        <!-- Render Area untuk Kalender -->
        <div id="calendar" class="text-sm"></div>
    </div>

    <style>
        /* Penyesuaian tema Kalender agar gelap (Dark Mode) */
        .fc-theme-standard td, .fc-theme-standard th { border-color: #27272a; }
        .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 900; color: white; }
        .fc .fc-button-primary { background-color: #18181b; border-color: #3f3f46; color: white; }
        .fc .fc-button-primary:hover { background-color: #27272a; border-color: #dc2626; }
        .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #dc2626; border-color: #dc2626; }
        .fc .fc-daygrid-day-number { color: #a1a1aa; }
        .fc .fc-day-today { background-color: rgba(220, 38, 38, 0.1) !important; }
        .fc-event { cursor: pointer; transition: transform 0.2s; }
        .fc-event:hover { transform: scale(1.02); }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            
            // Konversi PHP Array $tasks menjadi Format Event Kalender
            var rawTasks = @json($tasks);
            var eventsData = rawTasks.map(function(task) {
                // Menentukan warna berdasarkan jenis tugas
                let color = '#3b82f6'; // Biru (Maintenance)
                if(task.kode_tugas === 'PSB') color = '#dc2626'; // Merah (Pemasangan)
                if(task.kode_tugas === 'CHK') color = '#f59e0b'; // Kuning (H-3)

                return {
                    title: task.titik + ' - ' + task.jenis,
                    start: task.tanggal,
                    backgroundColor: color,
                    borderColor: color,
                    extendedProps: {
                        klien: task.klien,
                        pesan: task.pesan,
                        titik: task.titik,
                        kode_tugas: task.kode_tugas
                    }
                };
            });

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: eventsData,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                // Aksi ketika event di klik (Arahkan ke form pembuat IM)
                eventClick: function(info) {
                    if (confirm(`Aksi untuk ${info.event.title}\nKlien: ${info.event.extendedProps.klien}\n\nApakah Anda ingin menerbitkan Internal Memo (IM) sekarang?`)) {
                        let url = `{{ route('aset.im.buat') }}?titik=${info.event.extendedProps.titik}&jenis=${info.event.extendedProps.kode_tugas}`;
                        window.location.href = url;
                    }
                }
            });
            calendar.render();
        });
    </script>
</x-boma-layout>