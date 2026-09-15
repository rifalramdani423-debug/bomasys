<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PesananDisetujuiMail;
use App\Models\User;
use App\Models\Dokumen;
use App\Models\Pengajuan;
use App\Mail\PesananDitolakMail;
use App\Mail\PesananRevisiMail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ==========================================
    //            ---- DASHBOARD ----
    // ==========================================
    public function index()
{
    $penawaran_list = Penawaran::with(['user', 'details'])
                        ->latest() 
                        ->get();

    return view('admin.penawaran.index', compact('penawaran_list'));
}
    public function dashboard()
    {
        $jumlahAntrean = Pengajuan::where('status_pengajuan', 'Menunggu ACC')->count();
        $jumlahKlien = User::where('role', 'klien')->count();
        $proyeksiPendapatan = Pengajuan::whereNotIn('status_pengajuan', ['Ditolak', 'Batal'])->sum('estimasi_harga');
        
        $antreanPengajuan = Pengajuan::with(['user', 'details'])
            ->where('status_pengajuan', 'Menunggu ACC')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $titikTerisi = \App\Models\PengajuanDetail::count(); 
        $totalTitik = DB::table('billboards')->count();
        $persentaseOkupansi = $totalTitik > 0 ? round(($titikTerisi / $totalTitik) * 100) : 0;

        return view('admin.dashboard', compact(
            'jumlahAntrean', 
            'jumlahKlien', 
            'proyeksiPendapatan', 
            'antreanPengajuan',
            'titikTerisi',
            'totalTitik',
            'persentaseOkupansi'
        ));
    }

    // ==========================================
    //          ---- PESANAN MASUK ----
    // ==========================================
    public function pesanan(Request $request) 
    {
        $query = Pengajuan::with(['user', 'details.billboard']);
        $filter = $request->filter ?? 'menunggu'; 

        if ($filter == 'menunggu') {
            $query->where('status_pengajuan', 'Menunggu ACC');
        } elseif ($filter == 'aktif') {
            $query->where('status_pengajuan', 'Lunas / Aktif');
        } elseif ($filter == 'riwayat') {
            $query->where(function ($q) {
                $q->where(function ($qq) {
                    $qq->where('status_pengajuan', 'Lunas / Aktif')
                       ->where('selesai_sewa', '<', now());
                })->orWhere('status_pengajuan', 'Ditolak');
            });
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('details', function($detailQuery) use ($search) {
                    $detailQuery->where('kode_titik', 'like', '%' . $search . '%');
                });
            });
        }

        $pesanan_masuk = $query->orderBy('created_at', 'desc')->get();
        $jumlah_menunggu = Pengajuan::where('status_pengajuan', 'Menunggu ACC')->count();
        $userIds = $pesanan_masuk->pluck('user_id')->unique();
        $dokumenByUser = Dokumen::whereIn('user_id', $userIds)->get()->groupBy('user_id');

        return view('admin.pesanan', compact('pesanan_masuk', 'jumlah_menunggu', 'dokumenByUser'));
    }

    public function prosesPesanan(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:acc,revisi,tolak',
            'catatan_admin' => 'required_if:keputusan,revisi,tolak|string|max:1000|nullable'
        ]);

        $pengajuan = Pengajuan::with(['user', 'details.billboard'])->findOrFail($id);

        if ($request->keputusan === 'acc') {
            $pengajuan->update(['status_pengajuan' => 'Disetujui']);
            try {
                Mail::to($pengajuan->user->email)->send(new PesananDisetujuiMail($pengajuan));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email persetujuan pesanan: ' . $e->getMessage());
            }
            return redirect()->route('admin.penawaran')->with('success', 'Pesanan disetujui! Silakan input Harga Final.');
        } elseif ($request->keputusan === 'revisi') {
            $pengajuan->update([
                'status_pengajuan' => 'Menunggu Revisi',
                'catatan_admin' => $request->catatan_admin
            ]);
            try {
                Mail::to($pengajuan->user->email)->send(new PesananRevisiMail($pengajuan));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email revisi pesanan: ' . $e->getMessage());
            }
            return back()->with('success', 'Status diubah menjadi Menunggu Revisi. Email notifikasi telah dikirim ke klien.');
        } elseif ($request->keputusan === 'tolak') {
            $titiks = $pengajuan->details->pluck('kode_titik');
            if ($titiks->isNotEmpty()) {
                DB::table('billboards')->whereIn('kode_titik', $titiks)->update(['status' => 'Tersedia']);
            }
            $pengajuan->update([
                'status_pengajuan' => 'Ditolak',
                'catatan_admin' => $request->catatan_admin
            ]);
            try {
                Mail::to($pengajuan->user->email)->send(new PesananDitolakMail($pengajuan));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email penolakan pesanan: ' . $e->getMessage());
            }
            return back()->with('success', 'Pesanan ditolak permanen. Titik reklame telah dibebaskan kembali.');
        }
    }

    // ==========================================
    //         ---- PENAWARAN & HARGA ----
    // ==========================================
    public function penawaran()
    {
        $penawaran_list = Pengajuan::with(['user', 'details'])
                            ->where('status_pengajuan', 'Disetujui')
                            ->orderBy('created_at', 'desc')
                            ->get();

        foreach ($penawaran_list as $p) {
            $mulai = Carbon::parse($p->mulai_sewa);
            $selesai = Carbon::parse($p->selesai_sewa);
            
            $durasiBulan = $mulai->diffInMonths($selesai);
            if ($selesai->copy()->subMonths($durasiBulan)->diffInDays($mulai) > 2) {
                $durasiBulan++;
            }
            if ($durasiBulan <= 0) $durasiBulan = 1;
            
            $p->durasi_bulan = $durasiBulan;
            $rincianTitik = [];
            $subtotalTitik = 0;

            foreach ($p->details as $d) {
                $bb = DB::table('billboards')->where('kode_titik', $d->kode_titik)->first();
                if ($bb) {
                    $hargaBerdasarkanDurasi = $bb->harga_per_bulan * $durasiBulan;
                    $rincianTitik[] = [
                        'kode' => $d->kode_titik,
                        'ukuran' => $bb->ukuran,
                        'lokasi' => $bb->lokasi ?? '-',
                        'kab_kota' => $bb->kab_kota ?? '-',
                        'jenis_ooh' => $bb->jenis_ooh ?? '-',
                        'harga_asli' => $hargaBerdasarkanDurasi
                    ];
                    $subtotalTitik += $hargaBerdasarkanDurasi;
                }
            }

            $pajak11Persen = $subtotalTitik * 0.11;

            $p->rincian_lengkap = [
                'titik' => $rincianTitik,
                'subtotal' => $subtotalTitik,
                'pajak' => $pajak11Persen,
                'total_estimasi' => $subtotalTitik + $pajak11Persen, 
                'butuh_jasa' => $p->jasa_desain 
            ];
        }

        return view('admin.penawaran', compact('penawaran_list'));
    }

public function simpanHargaFinal(Request $request, $id)
    {
        $request->validate([
            'rincian_nama'    => 'required|array|min:1',
            'rincian_nama.*'  => 'required|string|max:255',
            'rincian_harga'   => 'required|array|min:1',
            'rincian_harga.*' => 'required|numeric',
        ], [
            'rincian_nama.*.required'  => 'Nama komponen biaya tidak boleh kosong.',
            'rincian_harga.*.required' => 'Nilai komponen biaya tidak boleh kosong.',
            'rincian_harga.*.numeric'  => 'Nilai komponen biaya harus berupa angka.',
        ]);

        $dataRincian = [];
        $totalFinal  = 0;

        foreach ($request->rincian_nama as $index => $namaItem) {
            $namaItem = trim($namaItem);
            if ($namaItem === '' || !isset($request->rincian_harga[$index])) {
                continue;
            }
            $hargaItem = (int) $request->rincian_harga[$index];
            $dataRincian[] = [
                'nama_item'  => $namaItem,
                'harga_item' => $hargaItem,
            ];
            $totalFinal += $hargaItem;
        }

        if (count($dataRincian) === 0 || $totalFinal < 1) {
            return back()->withInput()->with('error', 'Total harga harus lebih besar dari nol. Periksa kembali rincian komponen.');
        }

        $pengajuan = Pengajuan::findOrFail($id);
        
        // 1. Simpan ke Database
        $pengajuan->update([
            'harga_final'      => $totalFinal,
            'catatan_harga'    => json_encode($dataRincian),
            'status_pengajuan' => 'Menunggu Termin',
        ]);

        // 2. Kirim Email Notifikasi ke Klien
        try {
            \Illuminate\Support\Facades\Mail::send('emails.harga_final', ['pengajuan' => $pengajuan], function($message) use ($pengajuan) {
                $message->to($pengajuan->user->email)
                        ->subject('Kesepakatan Harga Final - BOMA Advertising');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email Harga Final: ' . $e->getMessage());
        }

        return back()->with('success', 'Harga final berhasil diterbitkan! Email instruksi pembuatan termin telah dikirim ke Klien.');
    }

    // ==========================================
    //           ---- DATA KLIEN ----
    // ==========================================
    public function klien(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        // Memulai query dasar
        $query = \App\Models\User::where('role', 'klien')
            ->with(['pengajuan.termins', 'dokumens']);

        // 1. Filter Pencarian Teks
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status Dropdown (Aktif / Proses / Inaktif)
        if ($status) {
            if ($status == 'aktif') {
                // Kategori 1: Punya minimal 1 pesanan yang sedang "Lunas / Aktif" (Tayang)
                $query->whereHas('pengajuan', function($q) {
                    $q->where('status_pengajuan', 'Lunas / Aktif');
                });
            } elseif ($status == 'proses') {
                // Kategori 2: Punya pesanan yang sedang diproses, TAPI belum ada yang tayang
                $query->whereHas('pengajuan', function($q) {
                    $q->whereNotIn('status_pengajuan', ['Lunas / Aktif', 'Selesai', 'Ditolak', 'Batal']);
                })->whereDoesntHave('pengajuan', function($q) {
                    $q->where('status_pengajuan', 'Lunas / Aktif');
                });
            } elseif ($status == 'inaktif') {
                // Kategori 3: KOSONG TOTAL (Tidak punya pesanan aktif dan tidak ada pesanan diproses)
                // Artinya klien ini hanya punya pesanan Selesai/Ditolak/Batal, atau belum pesan sama sekali
                $query->whereDoesntHave('pengajuan', function($q) {
                    $q->whereNotIn('status_pengajuan', ['Selesai', 'Ditolak', 'Batal']); 
                });
            }
        }
        
        // Urutkan dari klien yang paling baru mendaftar
        $klien_list = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.klien', compact('klien_list', 'search', 'status'));
    }

    public function klienDetail($id)
    {
        // Mengambil data klien super lengkap beserta seluruh relasi pesanannya
        $klienDetail = \App\Models\User::with([
            'dokumens', 
            'pengajuan.termins', 
            'pengajuan.details.billboard'
        ])->findOrFail($id);
        
        return view('admin.klien', compact('klienDetail'));
    }

    public function unduhArsipKlien($id)
    {
        $klien = \App\Models\User::with(['dokumens', 'pengajuan.termins'])->findOrFail($id);
        
        $zip = new \ZipArchive();
        $namaKlienBersih = preg_replace('/[^A-Za-z0-9\-]/', '_', $klien->nama_perusahaan ?? $klien->name);
        $fileName = 'Arsip_BOMA_' . $namaKlienBersih . '_' . time() . '.zip';
        
        // Pastikan folder penyimpanan sementara ada
        if (!\Illuminate\Support\Facades\File::exists(public_path('storage/temp_zip'))) {
            \Illuminate\Support\Facades\File::makeDirectory(public_path('storage/temp_zip'), 0777, true);
        }
        
        $zipPath = public_path('storage/temp_zip/' . $fileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            
            // 1. Masukkan Dokumen Utama (KTP, NPWP) ke folder Administrasi_Utama
            foreach ($klien->dokumens as $doc) {
                $path = storage_path('app/public/' . $doc->file_path);
                if (file_exists($path) && !is_dir($path)) {
                    $zip->addFile($path, 'Administrasi_Utama/' . $doc->jenis_dokumen . '_' . basename($path));
                }
            }

            // 2. Kelompokkan dokumen berdasarkan Pesanan/Pengajuan
            foreach ($klien->pengajuan as $pesanan) {
                $folderPesanan = 'Pesanan_' . $pesanan->nomor_pengajuan . '/';
                
                if ($pesanan->file_preview) {
                    $pathPreview = storage_path('app/public/' . $pesanan->file_preview);
                    if (file_exists($pathPreview)) {
                        $zip->addFile($pathPreview, $folderPesanan . 'Materi_Visual/' . basename($pathPreview));
                    }
                }

                foreach ($pesanan->termins as $termin) {
                    if ($termin->bukti_pembayaran) {
                        $pathBukti = storage_path('app/public/' . $termin->bukti_pembayaran);
                        if (file_exists($pathBukti)) {
                            $zip->addFile($pathBukti, $folderPesanan . 'Keuangan/Bukti_Transfer_Termin_' . $termin->termin_ke . '_' . basename($pathBukti));
                        }
                    }
                }
            }
            
            $zip->close();
            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Gagal membungkus file ZIP. Pastikan file fisik tidak korup.');
    }

    public function storeKlien(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'no_wa'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'no_wa'    => $request->no_wa,
            'role'     => 'klien', 
        ]);

        return back()->with('success', 'Akun Klien baru berhasil ditambahkan!');
    }

    public function updateKlien(Request $request, $id)
    {
        $klien = User::where('role', 'klien')->findOrFail($id);
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'no_wa'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6', 
        ]);

        $dataUpdate = [
            'name'  => $request->name,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $klien->update($dataUpdate);
        return back()->with('success', 'Data Klien berhasil diperbarui!');
    }

    public function destroyKlien($id)
    {
        $klien = User::where('role', 'klien')->findOrFail($id);
        $pengajuans = Pengajuan::where('user_id', $klien->id)->get();
        
        foreach ($pengajuans as $pengajuan) {
            $titiks = \App\Models\PengajuanDetail::where('pengajuan_id', $pengajuan->id)->pluck('kode_titik');
            if ($titiks->isNotEmpty()) {
                DB::table('billboards')->whereIn('kode_titik', $titiks)->update(['status' => 'Tersedia']);
            }
            \App\Models\PengajuanDetail::where('pengajuan_id', $pengajuan->id)->delete();
            \App\Models\Termin::where('pengajuan_id', $pengajuan->id)->delete();
            $pengajuan->delete();
        }

        $dokumens = Dokumen::where('user_id', $klien->id)->get();
        foreach ($dokumens as $dok) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($dok->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($dok->file_path);
            }
            $dok->delete();
        }
        
        $klien->delete(); 
        return back()->with('success', 'Sapu Bersih Selesai! Akun Klien beserta seluruh data pesanan, tagihan, dan dokumennya berhasil dihapus. Titik reklame kembali Tersedia.');
    }

    // ==========================================
    //           ---- BAST ----
    // ==========================================
    public function bast()
    {
        $pengajuans = Pengajuan::with('user')->latest()->get();
        return view('admin.bast', compact('pengajuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required',
            'no_surat' => 'required',
            'html_content' => 'required',
        ]);

        $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);
        $safeNoSurat = str_replace('/', '_', $request->no_surat);
        $fileName = 'bast_' . $safeNoSurat . '_' . time() . '.html';
        $path = 'dokumen_klien/' . $fileName;

        $htmlWrapper = '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>BAST - ' . $request->no_surat . '</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body onload="window.print()" class="bg-zinc-800 flex justify-center py-10">
            <div style="width: 210mm; min-height: 297mm; padding: 20mm;" class="bg-white shadow-2xl font-serif text-black leading-relaxed">
                ' . $request->html_content . '
            </div>
        </body>
        </html>';

        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $htmlWrapper);

        Dokumen::create([
            'user_id' => $pengajuan->user_id,
            'pengajuan_id' => $pengajuan->id,
            'jenis_dokumen' => 'BAST (' . $request->no_surat . ')',
            'file_path' => $path
        ]);

        return redirect()->back()->with('success', 'Hebat! Dokumen BAST berhasil diterbitkan dan masuk ke arsip Klien.');
    }
}