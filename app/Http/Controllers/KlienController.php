<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PesananBaruMail;
use App\Models\Dokumen;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\CoreApi;

class KlienController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $pengajuans = \App\Models\Pengajuan::with(['termins', 'details.billboard'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPesanan = $pengajuans->count();
        $pesananAktif = $pengajuans->where('status_pengajuan', 'Lunas / Aktif')->count();

        $totalTagihan = \App\Models\Termin::whereHas('pengajuan', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where(function($q) {
                $q->where('status_termin', '!=', 'Lunas')
                  ->orWhereNull('status_termin');
            })->sum('nominal');

        return view('klien.dashboard', compact('user', 'pengajuans', 'totalPesanan', 'pesananAktif', 'totalTagihan'));
    }

    public function index()
    {
        $billboards = DB::table('billboards')->get();
        
        $pengajuanAktif = DB::table('pengajuan_details')
            ->join('pengajuans', 'pengajuan_details.pengajuan_id', '=', 'pengajuans.id')
            ->whereIn('pengajuans.status_pengajuan', ['Lunas / Aktif', 'Menunggu Pembayaran', 'Menunggu Verifikasi']) 
            ->select('pengajuan_details.kode_titik', 'pengajuans.selesai_sewa')
            ->orderBy('pengajuans.selesai_sewa', 'desc')
            ->get()
            ->keyBy('kode_titik');

        foreach ($billboards as $billboard) {
            if ($billboard->status === 'Tersewa' || $billboard->status === 'Disewa') { 
                if ($pengajuanAktif->has($billboard->kode_titik)) {
                    $billboard->selesai_sewa = $pengajuanAktif[$billboard->kode_titik]->selesai_sewa;
                } else {
                    $billboard->selesai_sewa = null;
                }
            } else {
                $billboard->selesai_sewa = null;
            }
        }

        $billboards = $billboards->sortBy(function ($item) {
            if (strtolower($item->status) === 'tersedia') {
                return 1;
            } elseif (strtolower($item->status) === 'booking') {
                return 2;
            } else {
                return 3;
            }
        })->values();

        return view('klien.index', compact('billboards'));
    }

   public function po(Request $request)
    {
        $user = auth()->user();
        
        $listPesanan = \App\Models\Pengajuan::with(['details', 'termins'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedId = $request->input('pesanan_id');

        if ($selectedId) {
            $pengajuan = $listPesanan->where('id', $selectedId)->first() ?? $listPesanan->first();
        } else {
            $pengajuan = $listPesanan->first();
        }

        $nomorPO = '';
        if ($pengajuan) {
            $bulanTahun = \Carbon\Carbon::now()->format('Y/m');
            $countBulanIni = \App\Models\Dokumen::where('jenis_dokumen', 'Purchase Order')
                                ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                ->count();
            
            $urutan = str_pad($countBulanIni + 1, 3, '0', STR_PAD_LEFT);
            $nomorPO = "PO/BOMA/{$bulanTahun}/{$urutan}";
        }

        return view('klien.po', compact('pengajuan', 'user', 'listPesanan', 'nomorPO'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mulai_sewa' => 'required|string',
            'selesai_sewa' => 'required|string',
            'titik_reklame' => 'required|array',
        ]);

        try {
            \App\Models\Billboard::whereIn('kode_titik', $request->titik_reklame)
                ->update(['status' => 'Booking']);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil diajukan'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storePengajuan(Request $request)
    {
        // 1. Validasi Data dari Front-End (Termasuk Materi Visual & Dokumen)
        $request->validate([
            'mulai_sewa' => 'required|string',
            'selesai_sewa' => 'required|string',
            'titik_reklame' => 'required|array',
            'estimasi_harga' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'nama_perusahaan' => 'nullable|string|max:255',
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_npwp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'link_desain' => 'nullable|string',
            'file_preview' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();

            // 2. Perbarui Profil Klien
            $user->name = $request->nama;
            $user->no_wa = $request->no_wa;
            if ($request->filled('nama_perusahaan')) {
                $user->nama_perusahaan = $request->nama_perusahaan;
            }
            $user->save();

            // 3. Simpan Dokumen KTP & Ekstraksi OCR Otomatis
            if ($request->hasFile('file_ktp')) {
                $pathKtp = $request->file('file_ktp')->store('dokumen_klien', 'public');
                
                $teksKtp = $this->ekstrakTeksOcr($pathKtp);
                $teksKtpBersih = preg_replace('/[^0-9]/', '', $teksKtp);
                preg_match('/\d{16}/', $teksKtpBersih, $matchesNik);
                $nikTerdeteksi = $matchesNik[0] ?? null;

                // TAMBAHAN: Cek Duplikasi NIK KTP
                if ($nikTerdeteksi) {
                    $nikAda = \App\Models\User::where('nik', $nikTerdeteksi)->where('id', '!=', $user->id)->exists();
                    if ($nikAda) {
                        DB::rollBack();
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($pathKtp); // Hapus KTP baru yang terlanjur terupload
                        return response()->json([
                            'success' => false,
                            'message' => 'Sistem mendeteksi NIK KTP ('.$nikTerdeteksi.') sudah pernah didaftarkan oleh instansi/akun lain. Silakan gunakan KTP yang berbeda.'
                        ], 400); // 400 Bad Request
                    }
                }

                \App\Models\Dokumen::create([
                    'user_id' => $user->id,
                    'jenis_dokumen' => 'KTP',
                    'file_path' => $pathKtp,
                    'status' => 'Menunggu Validasi',
                ]);

                $user->update(['nik' => $nikTerdeteksi ?? 'Telah Diunggah (File)']);
            }

            // 4. Simpan Dokumen NPWP & Ekstraksi OCR Otomatis
            if ($request->hasFile('file_npwp')) {
                $pathNpwp = $request->file('file_npwp')->store('dokumen_klien', 'public');
                
                $teksNpwp = $this->ekstrakTeksOcr($pathNpwp);
                $teksNpwpBersih = preg_replace('/[^0-9]/', '', $teksNpwp);
                preg_match('/\d{15,16}/', $teksNpwpBersih, $matchesNpwp);
                
                $npwpTerdeteksi = null;
                if (!empty($matchesNpwp[0])) {
                    $n = $matchesNpwp[0];
                    if (strlen($n) == 15) {
                        $npwpTerdeteksi = substr($n,0,2).'.'.substr($n,2,3).'.'.substr($n,5,3).'.'.substr($n,8,1).'-'.substr($n,9,3).'.'.substr($n,12,3);
                    } else {
                        $npwpTerdeteksi = $n;
                    }
                }

                // TAMBAHAN: Cek Duplikasi NPWP
                if ($npwpTerdeteksi) {
                    $npwpAda = \App\Models\User::where('npwp', $npwpTerdeteksi)->where('id', '!=', $user->id)->exists();
                    if ($npwpAda) {
                        DB::rollBack();
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($pathNpwp); // Hapus NPWP baru
                        return response()->json([
                            'success' => false,
                            'message' => 'Sistem mendeteksi NPWP ('.$npwpTerdeteksi.') sudah pernah didaftarkan oleh instansi/akun lain. Silakan periksa kembali.'
                        ], 400); // 400 Bad Request
                    }
                }

                \App\Models\Dokumen::create([
                    'user_id' => $user->id,
                    'jenis_dokumen' => 'NPWP',
                    'file_path' => $pathNpwp,
                    'status' => 'Menunggu Validasi',
                ]);
                
                $user->update(['npwp' => $npwpTerdeteksi ?? 'Telah Diunggah (File)']); 
            }

            // 5. Format Tanggal
            $mulai = Carbon::createFromFormat('d/m/Y', $request->mulai_sewa)->format('Y-m-d');
            $selesai = Carbon::createFromFormat('d/m/Y', $request->selesai_sewa)->format('Y-m-d');

            // 6. LOGIKA MATERI VISUAL
            // Cek apakah klien butuh jasa desain (checkbox dicentang)
            $jasaDesain = in_array($request->jasa_desain, ['1', 'on', true], true) ? true : false;
            $pathPreview = null;

            // Jika tidak butuh jasa desain, cek apakah klien mengunggah foto preview
            if (!$jasaDesain && $request->hasFile('file_preview')) {
                $pathPreview = $request->file('file_preview')->store('materi_visual', 'public');
            }

// 7. Logika Penomoran Pesanan (PSN-YYYYMM001)
            $tahunBulan = Carbon::now()->format('Ym'); // Menghasilkan misal: 202608
            $countBulanIni = \App\Models\Pengajuan::whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->count();
            
            $urutan = str_pad($countBulanIni + 1, 3, '0', STR_PAD_LEFT); 
            $nomorPengajuanBaru = "PSN-{$tahunBulan}{$urutan}"; // Hasil akhir: PSN-202608001

            // 7. Simpan Header Pengajuan
            $pengajuan = Pengajuan::create([
                'user_id' => $user->id,
                'nomor_pengajuan' => $nomorPengajuanBaru, 
                'mulai_sewa' => $mulai,
                'selesai_sewa' => $selesai,
                'status_pengajuan' => 'Menunggu ACC',
                'estimasi_harga' => $request->estimasi_harga,
                'jasa_desain' => $jasaDesain,
                'link_desain' => $jasaDesain ? null : $request->link_desain,
                'file_preview' => $pathPreview,
            ]);

            // 8. Simpan Detail & Kunci Titik Reklame
            foreach ($request->titik_reklame as $kode) {
                PengajuanDetail::create([
                    'pengajuan_id' => $pengajuan->id,
                    'kode_titik' => $kode,
                ]);

                DB::table('billboards')->where('kode_titik', $kode)->update(['status' => 'Booking']);
            }

            // 9. Kirim Email Notifikasi (Gunakan Try Catch agar proses tidak berhenti jika email gagal)
            try {
                $adminEmails = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->pluck('email');
                if ($adminEmails->isNotEmpty()) {
                    Mail::to($adminEmails)->send(new PesananBaruMail($pengajuan));
                }
            } catch (\Exception $e) {
                 \Log::error('Gagal mengirim email: ' . $e->getMessage());
                 // Proses tetap dilanjutkan meskipun email gagal
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil diproses'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    private function ekstrakTeksOcr($filePath)
    {
        $fullPath = storage_path('app/public/' . $filePath);
        $apiKey = env('OCR_SPACE_API_KEY', 'helloworld');

        $mimeType = mime_content_type($fullPath);
        $base64Data = base64_encode(file_get_contents($fullPath));
        $base64Image = 'data:' . $mimeType . ';base64,' . $base64Data;

        // connect_timeout & timeout WAJIB diset — tanpa ini, kalau API OCR lambat/tidak
        // merespons, PHP akan menunggu sampai batas waktu eksekusi server habis dan
        // seluruh proses pengajuan klien ikut gagal, padahal OCR hanya fitur bantu.
        $client = new \GuzzleHttp\Client(['verify' => false, 'connect_timeout' => 5, 'timeout' => 15]);

        try {
            $response = $client->post('https://api.ocr.space/parse/image', [
                'form_params' => [
                    'apikey'      => $apiKey,
                    'base64Image' => $base64Image,
                    'language'    => 'eng',
                    'OCREngine'   => '2',
                    'scale'       => 'true',
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            \Log::info('HASIL OCR BASE64:', ['hasil' => $result]);

            if (isset($result['ParsedResults'][0]['ParsedText'])) {
                return $result['ParsedResults'][0]['ParsedText'];
            }
        } catch (\Exception $e) {
            \Log::error('OCR Error Base64: ' . $e->getMessage());
        }

        return '';
    }

    public function dokumen()
    {
        $user = Auth::user();

        $arsip_dokumen = Dokumen::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        // --- TAMBAHAN BARU: Tarik data pengajuan untuk Dropdown & Folder ---
        $list_pengajuan = \App\Models\Pengajuan::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $namaKlien = $user->nama_perusahaan ?: $user->name;

        $laporanTayang = \Illuminate\Support\Facades\DB::table('laporan_qcs')
            ->join('internal_memos', 'laporan_qcs.no_im', '=', 'internal_memos.no_im')
            ->select('laporan_qcs.*', 'internal_memos.klien_visual', 'internal_memos.perihal')
            ->where('laporan_qcs.dibagikan_ke_klien', 1)
            ->where('internal_memos.klien_visual', 'LIKE', '%' . $namaKlien . '%')
            ->orderBy('laporan_qcs.created_at', 'desc')
            ->get();

        $materiVisualList = \App\Models\Pengajuan::with('details.billboard')
            ->where('user_id', $user->id)
            ->where(function($q) {
                $q->whereNotNull('link_desain')
                  ->orWhereNotNull('file_preview');
            })
            ->orderBy('created_at', 'desc')
            ->get();
                                
        // Masukkan $list_pengajuan ke dalam compact
        return view('klien.dokumen', compact('arsip_dokumen', 'laporanTayang', 'materiVisualList', 'list_pengajuan'));
    }

    public function storeDokumen(Request $request)
    {
        // Sesuaikan validasinya agar menerima pengajuan_id
        $request->validate([
            'jenis_dokumen' => 'required|string',
            'file_dokumen'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'pengajuan_id'  => 'nullable|exists:pengajuans,id'
        ]);

        $path = $request->file('file_dokumen')->store('dokumen_klien', 'public');
        $user = Auth::user();

        // --- Logika OCR KTP & NPWP TETAP SAMA SEPERTI KODINGANMU SEBELUMNYA ---
        if ($request->jenis_dokumen == 'KTP') {
            $teksKtp = $this->ekstrakTeksOcr($path);
            $teksKtpBersih = preg_replace('/[^0-9]/', '', $teksKtp);
            preg_match('/\d{16}/', $teksKtpBersih, $matchesNik);
            
            $nikTerdeteksi = $matchesNik[0] ?? null;
            $user->update(['nik' => $nikTerdeteksi ?? 'Telah Diunggah (File)']);
        } 
        elseif ($request->jenis_dokumen == 'NPWP') {
            $teksNpwp = $this->ekstrakTeksOcr($path);
            $teksNpwpBersih = preg_replace('/[^0-9]/', '', $teksNpwp);
            preg_match('/\d{15,16}/', $teksNpwpBersih, $matchesNpwp);
            
            $npwpTerdeteksi = null;
            if (!empty($matchesNpwp[0])) {
                $n = $matchesNpwp[0];
                if (strlen($n) == 15) {
                    $npwpTerdeteksi = substr($n,0,2).'.'.substr($n,2,3).'.'.substr($n,5,3).'.'.substr($n,8,1).'-'.substr($n,9,3).'.'.substr($n,12,3);
                } else {
                    $npwpTerdeteksi = $n;
                }
            }
            $user->update(['npwp' => $npwpTerdeteksi ?? 'Telah Diunggah (File)']);
        }
        // ----------------------------------------------------------------------

        // Simpan ke database beserta pengajuan_id-nya
        Dokumen::create([
            'user_id'       => $user->id,
            'pengajuan_id'  => $request->pengajuan_id ?? null, // Masuk ke pesanan spesifik jika ada
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path'     => $path,
            'status'        => 'Menunggu Validasi',
        ]);

        return back()->with('success', 'Dokumen ' . $request->jenis_dokumen . ' berhasil diunggah dan diproses oleh sistem!');
    }

    public function destroyDokumen($id)
    {
        $dokumen = \App\Models\Dokumen::where('user_id', Auth::id())->findOrFail($id);
        
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($dokumen->file_path);
        }
        
        $dokumen->delete();
        
        return back()->with('success', 'Dokumen berhasil dihapus. Silakan unggah dokumen baru jika diperlukan.');
    }

    public function keuanganIndex()
    {
        // 1. Tarik data pesanan yang aktif
        $raw_tagihan = \App\Models\Pengajuan::with(['details', 'termins'])
                            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                            ->whereIn('status_pengajuan', ['Menunggu Termin', 'Menunggu Pembayaran', 'Aktif / Produksi']) // STATUS BARU DITAMBAHKAN DI SINI
                            ->get();

        // 2. Urutkan berdasarkan tanggal jatuh tempo termin terdekat
        $tagihan_list = $raw_tagihan->sortBy(function($pengajuan) {
            // Jika klien belum mengatur termin sama sekali, taruh paling atas (Prioritas 0)
            if ($pengajuan->termins->isEmpty()) {
                return 0;
            }
            
            // Cari tanggal jatuh tempo terdekat dari termin yang BELUM lunas
            $terminAktif = $pengajuan->termins->where('status_termin', '!=', 'Lunas')->min('tanggal_jatuh_tempo');
            
            // Konversi ke timestamp untuk diurutkan (jika sudah lunas semua, taruh paling bawah)
            return $terminAktif ? \Carbon\Carbon::parse($terminAktif)->timestamp : 9999999999;
        })->values();

        return view('klien.keuangan', compact('tagihan_list'));
    }

    public function simpanTermin(Request $request, $id)
    {
        $pengajuan = \App\Models\Pengajuan::with('details')->where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'termin' => 'required|array|min:1',
            'termin.*.persentase' => 'required|numeric|min:0.01|max:100',
            'termin.*.tanggal' => 'required|date',
            'termin.*.keterangan' => 'nullable|string|max:255',
            'termin.*.bukti_pembayaran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $totalPersen = collect($request->termin)->sum('persentase');
        if (round($totalPersen, 2) != 100.00) {
            return back()->withErrors(['error' => 'Total keseluruhan persentase termin harus tepat 100% (Saat ini: ' . $totalPersen . '%)'])->withInput();
        }

        $batasMaksimal = \Carbon\Carbon::parse($pengajuan->selesai_sewa)->addDays(14);
        foreach ($request->termin as $item) {
            if (\Carbon\Carbon::parse($item['tanggal'])->greaterThan($batasMaksimal)) {
                return back()->withErrors(['error' => 'Tanggal jatuh tempo tidak boleh melebihi 14 hari setelah masa sewa berakhir (Maksimal: ' . $batasMaksimal->format('d/m/Y') . ')'])->withInput();
            }
        }

        DB::beginTransaction();

        try {
            \App\Models\Termin::where('pengajuan_id', $pengajuan->id)->delete();

            $folderPath = 'bukti_pembayaran/pengajuan_' . $pengajuan->id;

            foreach ($request->termin as $index => $item) {
                $nominalHitung = ($item['persentase'] / 100) * $pengajuan->harga_final;
                $pathFile = null;

                if (isset($item['bukti_pembayaran']) && $item['bukti_pembayaran']->isValid()) {
                    $pathFile = $item['bukti_pembayaran']->store($folderPath, 'public');

                    Dokumen::create([
                        'user_id'       => Auth::id(),
                        'jenis_dokumen' => 'Bukti Pembayaran',
                        'file_path'     => $pathFile,
                        'status'        => 'Menunggu Validasi',
                    ]);
                }
                
                \App\Models\Termin::create([
                    'pengajuan_id'        => $pengajuan->id,
                    'termin_ke'           => $index + 1,
                    'persentase'          => $item['persentase'],
                    'nominal'             => round($nominalHitung),
                    'tanggal_jatuh_tempo' => $item['tanggal'],
                    'keterangan'          => $item['keterangan'] ?? null,
                    'bukti_pembayaran'    => $pathFile,
                ]);
            }

            $pengajuan->update(['status_pengajuan' => 'Menunggu Pembayaran']);

            foreach ($pengajuan->details as $detail) {
                DB::table('billboards')
                    ->where('kode_titik', $detail->kode_titik)
                    ->update(['status' => 'Tersewa']);
            }

            try {
                $keuanganEmails = \App\Models\User::whereIn('role', ['keuangan', 'finance', 'akunting'])->pluck('email');
                if ($keuanganEmails->isNotEmpty()) {
                    Mail::to($keuanganEmails)->send(new PesananBaruMail($pengajuan));
                }
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email notifikasi termin: ' . $e->getMessage());
                // Proses tetap dilanjutkan meskipun email gagal
            }

            DB::commit();

            return redirect()->route('klien.keuangan')->with('success', 'Skema termin dan bukti pembayaran berhasil disimpan, status billboard diperbarui menjadi Tersewa!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan skema: ' . $e->getMessage()])->withInput();
        }
    }

    public function uploadBuktiTermin(Request $request, $terminId)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $termin = \App\Models\Termin::with('pengajuan')->findOrFail($terminId);

        if ($termin->pengajuan->user_id !== Auth::id()) {
            abort(403);
        }

        $folderPath = 'bukti_pembayaran/pengajuan_' . $termin->pengajuan_id;
        $path = $request->file('bukti_pembayaran')->store($folderPath, 'public');

        $termin->update([
            'bukti_pembayaran' => $path,
            'status_termin' => 'Menunggu Verifikasi Admin'
        ]);

        Dokumen::create([
            'user_id'       => Auth::id(),
            'jenis_dokumen' => 'Bukti Pembayaran',
            'file_path'     => $path,
            'status'        => 'Menunggu Validasi',
        ]);

        return back()->with('success', 'Bukti pembayaran Termin #' . $termin->termin_ke . ' berhasil diunggah!');
    }

    public function cekKetersediaan(Request $request)
    {
        $request->validate([
            'mulai' => 'required|string',
            'selesai' => 'required|string',
        ]);

        $mulai = Carbon::createFromFormat('d/m/Y', $request->mulai)->format('Y-m-d');
        $selesai = Carbon::createFromFormat('d/m/Y', $request->selesai)->format('Y-m-d');

        $billboards = DB::table('billboards')->get();
        
        $jadwalAktif = DB::table('pengajuan_details')
            ->join('pengajuans', 'pengajuan_details.pengajuan_id', '=', 'pengajuans.id')
            ->whereIn('pengajuans.status_pengajuan', ['Menunggu ACC', 'Menunggu Termin', 'Menunggu Pembayaran', 'Menunggu Verifikasi', 'Lunas / Aktif'])
            ->select('pengajuan_details.kode_titik', 'pengajuans.mulai_sewa', 'pengajuans.selesai_sewa', 'pengajuans.status_pengajuan')
            ->get();

        $hasil = [];

        foreach ($billboards as $bb) {
            $statusAkhir = 'Tersedia'; 
            $infoTambahan = '';

            $jadwalTitikIni = $jadwalAktif->where('kode_titik', $bb->kode_titik);
            $adaJadwalMasaDepan = false;
            $listJadwalLain = [];
            $jadwalBentrok = []; // Array baru untuk menampung list tanggal bentrok

            foreach ($jadwalTitikIni as $jadwal) {
                // Format tanggal agar mudah dan enak dibaca oleh Klien
                $tglM = Carbon::parse($jadwal->mulai_sewa)->format('d M Y');
                $tglS = Carbon::parse($jadwal->selesai_sewa)->format('d M Y');
                $teksJadwal = "• $tglM s/d $tglS";

                if ($mulai <= $jadwal->selesai_sewa && $selesai >= $jadwal->mulai_sewa) {
                    // Kondisi jika terjadi bentrok jadwal
                    if (in_array($jadwal->status_pengajuan, ['Menunggu ACC', 'Menunggu Termin'])) {
                        $statusAkhir = 'Booking';
                    } else {
                        $statusAkhir = 'Tersewa';
                    }
                    $jadwalBentrok[] = $teksJadwal;
                } else {
                    // Tidak bentrok, tapi titik ini punya jadwal di masa depan/lalu
                    $adaJadwalMasaDepan = true;
                    $listJadwalLain[] = $teksJadwal;
                }
            }

            // Susun kalimat informasi ke dalam pop-up
            if ($statusAkhir === 'Booking' || $statusAkhir === 'Tersewa') {
                $infoTambahan = "Titik ini tidak tersedia karena telah dibooking/disewa pada rentang:<br><strong class='text-red-700'>" . implode("<br>", $jadwalBentrok) . "</strong><br>Silakan antre atau pilih tanggal di luar jadwal tersebut.";
            } elseif ($statusAkhir === 'Tersedia' && $adaJadwalMasaDepan) {
                $statusAkhir = 'Tersedia Bertanda';
                $infoTambahan = "Aman untuk dipesan. Namun titik ini telah dijadwalkan oleh instansi lain pada:<br><strong class='text-zinc-900'>" . implode("<br>", $listJadwalLain) . "</strong>";
            }

            $hasil[] = [
                'kode_titik' => $bb->kode_titik,
                'status' => $statusAkhir,
                'info' => $infoTambahan,
                'harga' => $bb->harga_per_bulan,
                'ukuran' => $bb->ukuran,
                'lokasi' => $bb->lokasi,
                'latitude' => $bb->latitude,
                'longitude' => $bb->longitude
            ];
        }

        return response()->json($hasil);
    }

    public function storePo(Request $request)
    {
        // Validasi input form
        $request->validate([
            'pesanan_id' => 'required|exists:pengajuans,id',
            'nomor_po' => 'required|string|max:255',
            'tanggal_po' => 'required|date',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();

            // 1. Trik Cerdas: Buat dokumen fisik berupa file HTML siap cetak
            $kontenHTML = "
            <!DOCTYPE html>
            <html>
            <head><title>Purchase Order - {$request->nomor_po}</title></head>
            <body style='font-family: Arial, sans-serif; padding: 40px; color: #333;'>
                <div style='max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 40px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);'>
                    <h1 style='color: #dc2626; margin-bottom: 5px;'>PURCHASE ORDER (PO)</h1>
                    <p style='color: #666; margin-top: 0; font-size: 14px;'>Nomor: {$request->nomor_po}</p>
                    <hr style='border: 0; border-top: 2px solid #eee; margin: 20px 0;'>
                    
                    <table style='width: 100%; text-align: left; margin-bottom: 20px; font-size: 15px;'>
                        <tr><th style='width: 150px; padding-bottom: 10px;'>Tanggal PO</th><td style='padding-bottom: 10px;'>: {$request->tanggal_po}</td></tr>
                        <tr><th style='padding-bottom: 10px;'>Instansi/Klien</th><td style='padding-bottom: 10px;'>: {$request->nama_perusahaan}</td></tr>
                        <tr><th style='padding-bottom: 10px;'>Alamat</th><td style='padding-bottom: 10px;'>: {$request->alamat_perusahaan}</td></tr>
                        <tr><th style='padding-bottom: 10px;'>Terkait Pesanan</th><td style='padding-bottom: 10px;'>: #{$request->pesanan_id}</td></tr>
                    </table>
                    
                    <div style='background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px;'>
                        <h4 style='margin-top:0; margin-bottom: 10px;'>Catatan Instruksi:</h4>
                        <p style='margin: 0; font-size: 14px; line-height: 1.6;'>" . nl2br(htmlspecialchars($request->catatan ?? '-')) . "</p>
                    </div>
                    
                    <p style='margin-top: 50px; font-size: 11px; color: #999; text-align: center;'>Dokumen elektronik ini diterbitkan secara otomatis melalui sistem BOMA SYS.</p>
                </div>
                
                <!-- Script agar otomatis muncul jendela Print/Save as PDF saat dibuka -->
                <script>
                    window.onload = function() { window.print(); }
                </script>
            </body>
            </html>
            ";

            // 2. Simpan file HTML tersebut ke folder storage fisik
            $namaFile = 'dokumen_klien/PO_Pesanan_' . $request->pesanan_id . '_' . time() . '.html';
            \Illuminate\Support\Facades\Storage::disk('public')->put($namaFile, $kontenHTML);

            // 3. Simpan ke database dengan path file asli berekstensi .html
            \App\Models\Dokumen::create([
                'user_id' => $user->id,
                'jenis_dokumen' => 'Purchase Order',
                'file_path' => $namaFile,
                'status' => 'Menunggu Validasi',
            ]);

            // 4. Redirect ke halaman Arsip Klien beserta NOTIFIKASI SUCCESS
            return redirect()->route('klien.dokumen')->with('success', 'Hebat! Dokumen Purchase Order (PO) berhasil diterbitkan dan masuk ke arsip Anda.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat dokumen PO: ' . $e->getMessage())->withInput();
        }
    }

    public function createTermin($pesanan_id)
    {
        $pesanan = \App\Models\Pengajuan::findOrFail($pesanan_id);
        return view('klien.termin_create', compact('pesanan'));
    }

    public function storeTermin(Request $request, $pesanan_id)
    {
        $pengajuan = \App\Models\Pengajuan::with(['details', 'user', 'termins'])->where('user_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($pesanan_id);

        $request->validate([
            'termin' => 'required|array|min:1',
            'termin.*.persentase' => 'required|numeric|min:1|max:100',
            'termin.*.tanggal' => 'required|date',
            'termin.*.keterangan' => 'nullable|string|max:255',
        ]);

        $totalPersen = collect($request->termin)->sum('persentase');
        if (round($totalPersen, 2) != 100.00) {
            return back()->withErrors(['error' => 'Total keseluruhan persentase termin harus tepat 100%'])->withInput();
        }

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            \App\Models\Termin::where('pengajuan_id', $pengajuan->id)->delete();

            foreach ($request->termin as $index => $item) {
                $hargaAcuan = $pengajuan->harga_final ?? $pengajuan->estimasi_harga;
                $nominalHitung = ($item['persentase'] / 100) * $hargaAcuan;
                
                \App\Models\Termin::create([
                    'pengajuan_id'        => $pengajuan->id,
                    'termin_ke'           => $index + 1,
                    'persentase'          => $item['persentase'],
                    'nominal'             => round($nominalHitung),
                    'tanggal_jatuh_tempo' => $item['tanggal'],
                    'keterangan'          => $item['keterangan'] ?? null,
                ]);
            }

            $pengajuan->update(['status_pengajuan' => 'Menunggu Pembayaran']);

            $terminPertama = \App\Models\Termin::where('pengajuan_id', $pengajuan->id)->where('termin_ke', 1)->first();

            if ($terminPertama) {
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    "payment_type" => "bank_transfer",
                    "bank_transfer" => [
                        "bank" => "bca"
                    ],
                    "transaction_details" => [
                        "order_id" => "BOMA-TRM-" . $terminPertama->id . "-" . time(),
                        "gross_amount" => (int) $terminPertama->nominal,
                    ],
                    "customer_details" => [
                        "first_name" => $pengajuan->user->name ?? 'Klien BOMA',
                        "email" => $pengajuan->user->email ?? 'klien@boma.com',
                    ]
                ];

                try {
                    $response = CoreApi::charge($params);
                    
                    if (isset($response->va_numbers[0]->va_number)) {
                        $terminPertama->update([
                            'nomor_va' => $response->va_numbers[0]->va_number,
                            'status_termin' => 'Menunggu Pembayaran'
                        ]);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Gagal generate VA BCA: " . $e->getMessage());
                }
            }

            $pengajuan->load('termins');

            if ($pengajuan->user && $pengajuan->user->email) {
                try {
                    Mail::send('emails.termin_dibuat', ['pesanan' => $pengajuan], function($message) use ($pengajuan) {
                        $message->to($pengajuan->user->email)
                                ->subject('Skema Termin & Virtual Account Berhasil Dibuat ✅');
                    });
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gagal mengirim email Termin Dibuat: ' . $e->getMessage());
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('klien.keuangan')->with('success', 'Skema termin berhasil disimpan! Rincian tagihan dan Virtual Account telah dikirimkan ke email Anda.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan skema: ' . $e->getMessage()]);
        }
    }

    public function kirimUlangPesanan($id)
    {
        $pengajuan = \App\Models\Pengajuan::where('user_id', \Illuminate\Support\Facades\Auth::id())->findOrFail($id);
        
        $pengajuan->update([
            'status_pengajuan' => 'Menunggu ACC',
            'catatan_admin' => null
        ]);

        return back()->with('success', 'Hebat! Pesanan Anda telah diajukan ulang dan masuk kembali ke antrean pengecekan Admin.');
    }

    public function storeDokumenAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_dokumen' => 'required|string',
            'file_dokumen'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'pengajuan_id'  => 'nullable|exists:pengajuans,id'
        ]);

        // Simpan file ke folder storage
        $path = $request->file('file_dokumen')->store('dokumen_klien', 'public');
        
        // Logika cerdas jika admin memilih "Lainnya (Ketik Manual)"
        $jenis = $request->jenis_dokumen;
        if ($jenis === 'Lainnya' && $request->filled('jenis_dokumen_custom')) {
            $jenis = $request->jenis_dokumen_custom;
        }

        // Simpan ke database atas nama Klien (user_id klien)
        \App\Models\Dokumen::create([
            'user_id'       => $request->user_id, 
            'pengajuan_id'  => $request->pengajuan_id ?? null,
            'jenis_dokumen' => $jenis,
            'file_path'     => $path,
            'status'        => 'Valid', // Langsung berstatus 'Valid' karena yang unggah adalah Tim Internal BOMA
        ]);

        return back()->with('success', 'Hebat! Dokumen ' . $jenis . ' berhasil diunggah dan langsung masuk ke folder arsip Klien.');
    }

    public function updateDokumen(Request $request, $id)
    {
        $request->validate([
            'file_dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $dokumen = \App\Models\Dokumen::findOrFail($id);
        $role = auth()->user()->role;

        // Validasi Hak Akses (Gembok Keamanan)
        $canEdit = false;
        if (in_array($role, ['superadmin', 'admin'])) {
            $canEdit = true;
        } elseif (in_array($role, ['keuangan', 'finance']) && in_array($dokumen->jenis_dokumen, ['Invoice', 'Kwitansi'])) {
            $canEdit = true;
        } elseif ($role === 'klien' && $dokumen->user_id == auth()->id()) {
            $canEdit = true;
        }

        if (!$canEdit) {
            return back()->with('error', 'Akses ditolak! Anda tidak memiliki izin untuk mengubah dokumen divisi lain.');
        }

        // Hapus file fisik lama di storage jika ada
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($dokumen->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($dokumen->file_path);
        }

        // Simpan file baru
        $path = $request->file('file_dokumen')->store('dokumen_klien', 'public');

        // Update database
        $dokumen->update([
            'file_path' => $path,
            // Jika Klien yang ubah, status kembali Pending. Jika Admin/Keuangan otomatis Valid.
            'status' => ($role === 'klien') ? 'Menunggu Validasi' : 'Valid',
        ]);

        return back()->with('success', 'Mantap! Dokumen ' . $dokumen->jenis_dokumen . ' berhasil diperbarui.');
    }
}