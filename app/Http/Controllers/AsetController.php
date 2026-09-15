<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LaporanQc;
use App\Models\Billboard;
use App\Models\Pengajuan;
use App\Models\InternalMemo;
use App\Models\User; 
use Carbon\Carbon;

class AsetController extends Controller
{
    public function dashboard()
    {
        $totalTitik = DB::table('billboards')->count();
        
        $pengajuanAktif = DB::table('pengajuan_details')
            ->join('pengajuans', 'pengajuan_details.pengajuan_id', '=', 'pengajuans.id')
            ->whereIn('pengajuans.status_pengajuan', ['Lunas / Aktif', 'Aktif / Produksi', 'Menunggu Pembayaran', 'Menunggu Verifikasi'])
            ->select('pengajuan_details.kode_titik', 'pengajuans.selesai_sewa', 'pengajuans.id as pengajuan_id')
            ->get()
            ->keyBy('kode_titik');

        $billboards = DB::table('billboards')->get();
        foreach ($billboards as $billboard) {
            if ($pengajuanAktif->has($billboard->kode_titik)) {
                $billboard->selesai_sewa = $pengajuanAktif[$billboard->kode_titik]->selesai_sewa;
                $billboard->status_sewa = 'Disewa';
            } else {
                $billboard->selesai_sewa = null;
                $billboard->status_sewa = $billboard->status; 
            }
        }

        $totalDisewa = $billboards->where('status_sewa', 'Disewa')->count();
        $totalMaintenance = DB::table('billboards')->where('status', 'Maintenance')->count();
        $totalTersedia = $totalTitik - $totalDisewa - $totalMaintenance;

        $pemasanganMenunggu = DB::table('pengajuans')
            ->join('users', 'pengajuans.user_id', '=', 'users.id')
            ->whereIn('pengajuans.status_pengajuan', ['Lunas / Aktif', 'Aktif / Produksi'])
            ->select('pengajuans.*', 'users.name as nama_klien', 'users.nama_perusahaan')
            ->orderBy('pengajuans.mulai_sewa', 'asc')
            ->get();

        $allTasks = $this->getTugasHariIni();
        $hariIni = Carbon::today()->format('Y-m-d');
        
        $alerts = [];
        $tasksMendatang = []; 

        foreach ($allTasks as $t) {
            if ($t['tanggal'] == $hariIni) {
                $alerts[] = [
                    'tipe' => 'warning', 
                    'teks' => "{$t['jenis']} Hari Ini: Titik {$t['titik']} ({$t['klien']})"
                ];
            }
            if (strtotime($t['tanggal']) >= strtotime($hariIni)) {
                $tasksMendatang[] = $t;
            }
        }

        usort($tasksMendatang, function($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

       return view('aset.dashboard', compact('totalTitik', 'totalDisewa', 'totalMaintenance', 'totalTersedia', 'billboards', 'pemasanganMenunggu', 'alerts', 'tasksMendatang', 'allTasks'));
    }

    public function masterData()
    {
        $pengajuanAktif = DB::table('pengajuan_details')
            ->join('pengajuans', 'pengajuan_details.pengajuan_id', '=', 'pengajuans.id')
            ->whereIn('pengajuans.status_pengajuan', ['Lunas / Aktif', 'Aktif / Produksi'])
            ->pluck('pengajuans.selesai_sewa', 'pengajuan_details.kode_titik');

        $billboards = DB::table('billboards')->orderBy('kode_titik', 'asc')->get();
        
        foreach ($billboards as $bb) {
            $bb->status_real = $pengajuanAktif->has($bb->kode_titik) ? 'Disewa' : $bb->status;
        }

        return view('aset.master_data', compact('billboards'));
    }

    public function penjadwalan()
    {
        $tasks = $this->getTugasHariIni();
        usort($tasks, function($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        return view('aset.penjadwalan', compact('tasks'));
    }

    public function internalMemo()
    {
        return view('aset.im');
    }

    public function buatIm(Request $request)
    {
        $titikDariUrl = $request->get('titik'); 
        $jenis = $request->get('jenis'); 
        $pengajuan_id = $request->get('pengajuan_id'); 
        
        $klienVisualDefault = '';
        $titikList = [];
        $tglEksekusiDefault = date('Y-m-d');
        
        $semuaBillboard = \Illuminate\Support\Facades\DB::table('billboards')->get();

        if ($pengajuan_id) {
            $pengajuan = \App\Models\Pengajuan::with(['user', 'details'])->find($pengajuan_id);
            if ($pengajuan) {
                $namaKlien = $pengajuan->user?->nama_perusahaan ?: $pengajuan->user?->name ?: 'Klien (Telah Dihapus)';
                $klienVisualDefault = $namaKlien . ' / (Ketik Visual Disini)';
                $tglEksekusiDefault = \Carbon\Carbon::parse($pengajuan->mulai_sewa)->format('Y-m-d');
                
                foreach ($pengajuan->details as $d) {
                    $titikList[] = $d->kode_titik;
                }
                
                $semuaBillboard = \Illuminate\Support\Facades\DB::table('billboards')
                                    ->whereIn('kode_titik', $titikList)
                                    ->get();
            }
        } 
        elseif ($titikDariUrl) {
            $titikList[] = $titikDariUrl;
            
            $semuaBillboard = \Illuminate\Support\Facades\DB::table('billboards')
                                ->where('kode_titik', $titikDariUrl)
                                ->get();
                                
            $cekPesananAktif = \App\Models\Pengajuan::with('user')
                ->whereIn('status_pengajuan', ['Disetujui', 'Menunggu Termin', 'Menunggu Pembayaran', 'Aktif / Produksi', 'Lunas / Aktif', 'Sedang Sewa'])
                ->whereHas('details', function($q) use ($titikDariUrl) {
                    $q->where('kode_titik', $titikDariUrl);
                })->first();

            if ($cekPesananAktif && $cekPesananAktif->user) {
                $namaKlien = $cekPesananAktif->user->nama_perusahaan ?: $cekPesananAktif->user->name;
                $klienVisualDefault = $namaKlien . ' / (Ketik Visual Disini)';
            }
        }

        $ketuaTimList = \App\Models\User::where('role', 'produksi')
                            ->where('divisi', 'Ketua Tim Lapangan')
                            ->get();

        $anggotaTimList = \App\Models\User::where('role', 'produksi')
                            ->where('divisi', 'Anggota Lapangan')
                            ->get();

        $tahunIni = date('Y');
        $jumlahIm = \App\Models\InternalMemo::whereYear('created_at', $tahunIni)->count();
        $nomorUrut = str_pad($jumlahIm + 1, 3, '0', STR_PAD_LEFT); 
        
        $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][date('n') - 1];
        
        $kodeKegiatan = 'PSB';
        if ($jenis == 'CHK') $kodeKegiatan = 'CHK';
        if ($jenis == 'MNT') $kodeKegiatan = 'MNT';

        $noIm = "{$nomorUrut}/BCC-IM/{$kodeKegiatan}/{$bulanRomawi}/{$tahunIni}";

        $pekerjaSibuk = [];
        
        $imHariIni = \App\Models\InternalMemo::whereDate('tanggal_target', $tglEksekusiDefault)->get();
        
        foreach ($imHariIni as $im) {
            if ($im->catatan && str_contains($im->catatan, 'Daftar_Anggota:')) {
                $bagian = explode(' | ', $im->catatan);
                $daftarNama = str_replace('Daftar_Anggota: ', '', $bagian[0]);
                $arrayNama = array_map('trim', explode(',', $daftarNama));               
                $pekerjaSibuk = array_merge($pekerjaSibuk, $arrayNama);
            }
        }
        
        $pekerjaSibuk = array_unique($pekerjaSibuk);
        return view('aset.im', compact('semuaBillboard', 'titikList', 'jenis', 'noIm', 'ketuaTimList', 'anggotaTimList', 'klienVisualDefault', 'tglEksekusiDefault', 'pekerjaSibuk'));
    }

    public function storeIm(Request $request)
    {
        $request->validate([
            'kode_titik' => 'required|array|min:1',
            'perihal' => 'required',
            'tanggal_target' => 'required|date',
            'tujuan' => 'required', 
            'kebutuhan_foto' => 'required|array|min:1',
            'anggota_pilihan' => 'required|array|min:1' 
        ]);

        $ketuaTim = \App\Models\User::find($request->tujuan);
        $namaTim = $ketuaTim ? $ketuaTim->name : 'Tim Produksi'; 

        $noIm = $request->no_im;
        $cekDuplikat = \App\Models\InternalMemo::where('no_im', $noIm)->exists();
        if ($cekDuplikat) {
            $tahunIni = date('Y');
            $jumlahIm = \App\Models\InternalMemo::whereYear('created_at', $tahunIni)->count();
            $nomorUrut = str_pad($jumlahIm + 101, 3, '0', STR_PAD_LEFT); 
            $bulanRomawi = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][date('n') - 1];
            $noIm = "{$nomorUrut}/BCC-IM/CHK/{$bulanRomawi}/{$tahunIni}";
        }

        $daftarAnggotaString = implode(', ', $request->anggota_pilihan);
        $catatanFinal = "Daftar_Anggota: " . $daftarAnggotaString . " | " . ($request->catatan ?? '');

        \App\Models\InternalMemo::create([
            'no_im' => $noIm,
            'ditugaskan_ke_id' => $request->tujuan, 
            'tujuan' => $namaTim,                   
            'perihal' => $request->perihal,
            'klien_visual' => $request->klien_visual ?? 'Internal BOMA',
            'kode_titik' => json_encode($request->kode_titik), 
            'tanggal_target' => $request->tanggal_target,
            'catatan' => $catatanFinal, 
            'diterbitkan_oleh' => auth()->user()->name,
            'gambar_acuan' => null, 
            'kebutuhan_foto' => json_encode($request->kebutuhan_foto), 
        ]);

        return redirect()->route('aset.im')->with('success', 'Internal Memo berhasil dikirim ke ' . $namaTim . '!');
    }

    public function validasi()
    {
        $laporans = DB::table('laporan_qcs')
            ->leftJoin('internal_memos', 'laporan_qcs.no_im', '=', 'internal_memos.no_im')
            ->select('laporan_qcs.*', 'internal_memos.klien_visual')
            ->where('laporan_qcs.status', 'Menunggu Validasi')
            ->orderBy('laporan_qcs.created_at', 'desc')
            ->get();
        
        return view('aset.validasi', compact('laporans'));
    }

    public function prosesValidasi(Request $request, $id)
    {
        $laporan = \App\Models\LaporanQc::findOrFail($id);
        
        if ($request->aksi === 'revisi') {
            $request->validate([
                'catatan_revisi' => 'required'
            ], [
                'catatan_revisi.required' => 'Catatan revisi wajib diisi agar Tim Produksi tahu apa yang salah!'
            ]);

            $laporan->update([
                'status' => 'Revisi',
                'catatan_revisi' => $request->catatan_revisi
            ]);
            
            return redirect()->back()->with('error', 'Laporan dikembalikan ke Tim Produksi dengan catatan revisi.');
        }

        if ($request->aksi === 'terima') {
            $laporan->status = 'Disetujui';
            $laporan->catatan_revisi = null; 

            $titikArray = json_decode($laporan->kode_titik, true);
            if(is_array($titikArray)){
                foreach($titikArray as $titikLengkap){
                    $kode = explode(' - ', $titikLengkap)[0];
                    \App\Models\Billboard::where('kode_titik', trim($kode))->update(['status' => 'Tersewa']);
                }
            } else {
                \App\Models\Billboard::where('kode_titik', $laporan->kode_titik)->update(['status' => 'Tersewa']);
            }

            $fotoDipilih = $request->foto_klien ?? [];
            if (count($fotoDipilih) > 0) {
                $laporan->dibagikan_ke_klien = true;
                $laporan->foto_klien = json_encode($fotoDipilih);
                $pesan = 'Laporan Selesai! Foto yang dicentang otomatis diteruskan ke Arsip Klien.';
            } else {
                $laporan->dibagikan_ke_klien = false;
                $laporan->foto_klien = null;
                $pesan = 'Laporan Selesai dan hanya disimpan ke Arsip Internal BOMA.';
            }
            
            $laporan->save();

            return redirect()->back()->with('success', $pesan);
        }
    }

    private function getTugasHariIni()
    {
        $tasks = [];
        
        $pengajuans = \App\Models\Pengajuan::with(['details', 'user'])
            ->whereIn('status_pengajuan', ['Lunas / Aktif', 'Aktif / Produksi', 'Sedang Sewa'])
            ->get();

        foreach ($pengajuans as $pesanan) {
            $mulai = \Carbon\Carbon::parse($pesanan->mulai_sewa);
            $selesai = \Carbon\Carbon::parse($pesanan->selesai_sewa);

            foreach ($pesanan->details as $detail) {
                $hMin3 = $mulai->copy()->subDays(3);
                $tasks[] = [
                    'jenis' => 'Cek H-3',
                    'tanggal' => $hMin3->format('Y-m-d'), 
                    'titik' => $detail->kode_titik,
                    'klien' => $pesanan->user->nama_perusahaan ?? $pesanan->user->name ?? 'Klien',
                    'pesan' => 'Pengecekan kesiapan fisik papan.',
                    'kode_tugas' => 'CHK'
                ];

                $tasks[] = [
                    'jenis' => 'Pemasangan Baru',
                    'tanggal' => $mulai->format('Y-m-d'), 
                    'titik' => $detail->kode_titik,
                    'klien' => $pesanan->user->nama_perusahaan ?? $pesanan->user->name ?? 'Klien',
                    'pesan' => 'Jadwal hari pertama penayangan.',
                    'kode_tugas' => 'PSB'
                ];

                $cekBulan = $mulai->copy()->addMonth();
                while ($cekBulan->lt($selesai)) {
                    $tasks[] = [
                        'jenis' => 'Perawatan Bulanan',
                        'tanggal' => $cekBulan->format('Y-m-d'),
                        'titik' => $detail->kode_titik,
                        'klien' => $pesanan->user->nama_perusahaan ?? $pesanan->user->name ?? 'Klien',
                        'pesan' => 'Perawatan rutin bulanan.',
                        'kode_tugas' => 'MNT'
                    ];
                    $cekBulan->addMonth();
                }
            }
        }

        return $tasks;
    }

    public function storeBillboard(Request $request)
    {
        $request->validate([
            'kode_titik' => 'required|unique:billboards,kode_titik',
            'lokasi' => 'required',
            'harga_per_bulan' => 'required|numeric',
        ]);

        \App\Models\Billboard::create([
            'kode_titik' => $request->kode_titik,
            'lokasi' => $request->lokasi,
            'ukuran' => $request->ukuran ?? '-',
            'jenis_ooh' => $request->jenis_ooh ?? 'Frontlite',
            'kab_kota' => $request->kab_kota ?? 'Kota Bandung',
            'harga_per_bulan' => $request->harga_per_bulan,
            'status' => 'Tersedia',
            'latitude' => $request->latitude ?? 0,
            'longitude' => $request->longitude ?? 0,
        ]);

        return redirect()->back()->with('success', 'Titik Billboard Baru Berhasil Ditambahkan!');
    }

    public function indexIm(Request $request)
    {
        $query = DB::table('internal_memos')
            ->leftJoin('laporan_qcs', 'internal_memos.no_im', '=', 'laporan_qcs.no_im')
            ->select(
                'internal_memos.*', 
                'laporan_qcs.id as laporan_id',
                'laporan_qcs.status as status_laporan',
                'laporan_qcs.tanggal_dikerjakan',
                'laporan_qcs.foto_hasil', 
                'laporan_qcs.foto_klien',
                'laporan_qcs.dibagikan_ke_klien'
            )
            ->orderBy('internal_memos.created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('internal_memos.no_im', 'like', '%' . $search . '%')
                  ->orWhere('internal_memos.klien_visual', 'like', '%' . $search . '%')
                  ->orWhere('internal_memos.tujuan', 'like', '%' . $search . '%')
                  ->orWhere('internal_memos.perihal', 'like', '%' . $search . '%');
            });
        }

        $listIm = $query->get();
        return view('aset.im_index', compact('listIm')); 
    }

    public function destroyIm($id)
    {
        $im = InternalMemo::findOrFail($id);
        if ($im->gambar_acuan && \Illuminate\Support\Facades\Storage::disk('public')->exists($im->gambar_acuan)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($im->gambar_acuan);
        }
        
        $im->delete();

        return redirect()->back()->with('success', 'Arsip Internal Memo berhasil ditarik dan dihapus!');
    }

    public function updateFotoKlien(Request $request, $laporan_id)
    {
        $laporan = LaporanQc::findOrFail($laporan_id);
        $fotoDipilih = $request->foto_klien ?? [];
        
        if (count($fotoDipilih) > 0) {
            $laporan->update([
                'dibagikan_ke_klien' => true,
                'foto_klien' => json_encode($fotoDipilih)
            ]);
            $pesan = count($fotoDipilih) . ' Foto berhasil ditambahkan dan dibagikan ke Arsip Klien!';
        } else {
            $laporan->update([
                'dibagikan_ke_klien' => false,
                'foto_klien' => null
            ]);
            $pesan = 'Akses foto ke klien berhasil dicabut. Foto hanya tersimpan di internal.';
        }

        return redirect()->back()->with('success', $pesan);
    }
}