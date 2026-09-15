<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanQc;
use App\Models\InternalMemo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $namaTim = $user->name;
        $idTim = $user->id;

        // 1. Ambil daftar 'no_im' yang SUDAH dilaporkan
        $imSelesai = LaporanQc::pluck('no_im')->toArray();

        // 2. Tarik IM berdasarkan ID Ketua Tim ATAU string nama tim (Fleksibel & Aman)
        $daftarTugas = InternalMemo::where(function($q) use ($idTim, $namaTim) {
                $q->where('ditugaskan_ke_id', $idTim)
                  ->orWhere('tujuan', 'LIKE', '%' . $namaTim . '%');
            })
            ->whereNotIn('no_im', $imSelesai)
            ->orderBy('tanggal_target', 'asc')
            ->get();

        $totalTugasHariIni = $daftarTugas->where('tanggal_target', Carbon::today()->format('Y-m-d'))->count();
        
        $selesaiBulanIni = LaporanQc::whereMonth('created_at', Carbon::now()->month)
            ->where('tim_pelaksana', $namaTim)
            ->where('status', 'Disetujui')
            ->count();

        return view('produksi.dashboard', compact('totalTugasHariIni', 'selesaiBulanIni', 'daftarTugas'));
    }

    public function tugas(Request $request)
    {
        $no_im = $request->query('no_im');
        $user = auth()->user();

        // Jika form QC dibuka langsung dari sidebar tanpa pilih tugas spesifik, arahkan ke tugas aktif pertama
        if (!$no_im) {
            $tugasPertama = InternalMemo::where(function($q) use ($user) {
                    $q->where('ditugaskan_ke_id', $user->id)
                      ->orWhere('tujuan', 'LIKE', '%' . $user->name . '%');
                })
                ->orderBy('created_at', 'desc')
                ->first();

            if ($tugasPertama) {
                return redirect()->route('produksi.tugas', ['no_im' => $tugasPertama->no_im]);
            }

            return redirect()->route('produksi.index')->with('error', 'Belum ada tugas atau IM aktif saat ini.');
        }

        $tugasAktif = InternalMemo::where('no_im', $no_im)->firstOrFail();

        return view('produksi.tugas', compact('tugasAktif'));
    }

    public function storeLaporan(Request $request)
    {
        $request->validate([
            'no_im' => 'required',
            'kode_titik' => 'required',
            'anggota_tim' => 'required|string|max:255',
            'foto_dinamis' => 'required|array',
            'foto_dinamis.*' => 'image|max:5120',
            'nama_foto_dinamis' => 'required|array'
        ]);

        $fotoHasil = [];

        if ($request->hasFile('foto_dinamis')) {
            foreach ($request->file('foto_dinamis') as $index => $file) {
                $namaKategori = $request->nama_foto_dinamis[$index];
                $path = $file->store('laporan_qc/dinamis', 'public');
                
                $fotoHasil[] = [
                    'kategori' => $namaKategori,
                    'path' => $path
                ];
            }
        }

        LaporanQc::create([
            'no_im' => $request->no_im,
            'kode_titik' => $request->kode_titik,
            'jenis_pekerjaan' => $request->jenis_pekerjaan,
            'tim_pelaksana' => auth()->user()->name,
            'anggota_tim' => $request->anggota_tim,
            'foto_hasil' => json_encode($fotoHasil),
            'catatan_lapangan' => $request->catatan_lapangan,
            'tanggal_dikerjakan' => Carbon::now(),
            'status' => 'Menunggu Validasi'
        ]);

        return redirect()->route('produksi.index')->with('success', 'Laporan QC berhasil dikirim ke Divisi Aset!');
    }
}