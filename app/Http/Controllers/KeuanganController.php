<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\Pengajuan;
use App\Models\Termin;
use App\Models\User;
use App\Models\Dokumen;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    public function dashboard()
    {
        $totalKasMasuk = Termin::whereIn('status_termin', ['Lunas', 'Selesai'])->sum('nominal');
        $vaAktif = Termin::where('status_termin', 'Menunggu Pembayaran')->count();
        $poLunas = Pengajuan::whereIn('status_pengajuan', ['Lunas / Aktif', 'Selesai'])->count();
        $piutang = Termin::whereNotIn('status_termin', ['Lunas', 'Selesai', 'Batal', 'Menunggu Pembayaran'])->sum('nominal');
        $vaKedaluwarsa = Termin::where('status_termin', 'Batal')->count();
        
        $transaksiTerbaru = Termin::with(['pengajuan.user'])
            ->orderBy('updated_at', 'desc')
            ->take(8)
            ->get();

        $events = [];
        $terminAktif = Termin::with(['pengajuan.user'])
            ->whereNotIn('status_termin', ['Lunas', 'Selesai', 'Batal'])
            ->get();
        
        foreach($terminAktif as $t) {
            if($t->tanggal_jatuh_tempo) {
                $events[] = [
                    'date' => Carbon::parse($t->tanggal_jatuh_tempo)->format('Y-m-d'),
                    'title' => ($t->pengajuan->user->nama_perusahaan ?? $t->pengajuan->user->name ?? 'Klien') . ' - Termin ' . $t->termin_ke,
                    'description' => 'Tagihan Pokok: Rp ' . number_format($t->nominal, 0, ',', '.')
                ];
            }
        }

        return view('keuangan.dashboard', compact(
            'totalKasMasuk', 'vaAktif', 'poLunas', 'piutang', 'vaKedaluwarsa', 'transaksiTerbaru', 'events'
        ));
    }

    public function validasiIndex()
    {
        $settlements = Termin::with(['pengajuan.user'])
            ->where('status_termin', 'Lunas')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $expired_vas = Termin::with(['pengajuan.user'])
            ->where('status_termin', 'Batal')
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $overdue_termins = Termin::with(['pengajuan.user'])
            ->whereNotIn('status_termin', ['Lunas', 'Selesai', 'Batal'])
            ->whereDate('tanggal_jatuh_tempo', '<', Carbon::now())
            ->get();

        return view('keuangan.validasi', compact('settlements', 'expired_vas', 'overdue_termins'));
    }

    public function tandaiMutasi($id)
    {
        $termin = Termin::findOrFail($id);
        $pengajuan = Pengajuan::with('termins', 'details')->find($termin->pengajuan_id);
        
        $termin->update(['status_termin' => 'Selesai']);

        // LOGIKA TERMIN 1 
        if ($termin->termin_ke == 1) {
            foreach ($pengajuan->details as $detail) {
                $detail->update(['status_titik' => 'Disewa']); 
            }
            
            $pengajuan->update(['status_pengajuan' => 'Aktif / Produksi']);

            // Kirim Email Langsung Panggil File di Resource (HTML & Text)
            try {
                $dataEmail = ['pengajuan' => $pengajuan];
                
                Mail::send(['emails.pesanan_baru_aset', 'text' => 'emails.pesanan_baru_aset_text'], $dataEmail, function($message) use ($pengajuan) {
                    $nomor = $pengajuan->nomor_pengajuan ?? 'BOMA-XXX';
                    $message->to('aset@boma.com')
                            ->subject('🚨 BOMA SYS: Pesanan Baru Siap Dieksekusi! (' . $nomor . ')');
                });
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email ke Aset: ' . $e->getMessage());
            }
        }

        $semuaLunas = true;
        foreach ($pengajuan->termins as $t) {
            if (!in_array($t->status_termin, ['Lunas', 'Selesai'])) {
                $semuaLunas = false;
                break;
            }
        }

        if ($semuaLunas && $pengajuan->status_pengajuan !== 'Lunas / Aktif') {
            $pengajuan->update(['status_pengajuan' => 'Lunas / Aktif']);
        }

        return back()->with('success', 'Berhasil! Dana mutasi untuk pesanan ' . $pengajuan->nomor_pengajuan . ' telah diverifikasi dan disahkan.');
    }

    public function rilisDenda(Request $request, $id)
    {
        $termin = Termin::findOrFail($id);
        
        $hariTelat = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($termin->tanggal_jatuh_tempo)->startOfDay());
        $denda = $termin->nominal * 0.05 * $hariTelat;
        
        $termin->update([
            'nominal' => $termin->nominal + $denda,
            'keterangan' => 'DENDA KETERLAMBATAN: ' . $hariTelat . ' hari (Total Denda: Rp ' . number_format($denda, 0, ',', '.') . '). Segera lakukan pelunasan.',
        ]);

        return back()->with('success', 'Tagihan denda keterlambatan berhasil ditambahkan ke tagihan Klien!');
    }
    
    public function klien(Request $request)
    {
        $filter = $request->input('filter', 'semua');
        $search = $request->input('search');

        $query = User::where('role', 'klien')->with(['pengajuan.termins']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%");
            });
        }

        $klien_list = $query->get();

        return view('keuangan.klien', compact('klien_list', 'filter', 'search'));
    }

    public function buatInvoice(Request $request)
    {
        // Memastikan semua format status (huruf biasa/kapital) dan pesanan baru tertangkap
        $listPesanan = Pengajuan::with(['user', 'termins', 'details.billboard'])
            ->whereIn('status_pengajuan', [
                'Menunggu Pembayaran', 
                'Menunggu Termin', 
                'Aktif / Produksi', 
                'AKTIF / PRODUKSI', 
                'Lunas / Aktif', 
                'Selesai'
            ])
            ->orderBy('id', 'desc') // Diubah ke ID agar pesanan yang baru diinput pasti muncul di paling atas
            ->get();

        $pengajuan = null;
        if ($request->has('pesanan_id') && $request->pesanan_id != '') {
            $pengajuan = $listPesanan->firstWhere('id', $request->pesanan_id);
        }

        $bulanTahun = Carbon::now()->format('Y/m');
        $countBulanIni = Dokumen::where('jenis_dokumen', 'Invoice')
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();
                            
        $urutan = str_pad($countBulanIni + 1, 3, '0', STR_PAD_LEFT);
        $nomorInvoice = "INV/BOMA/{$bulanTahun}/{$urutan}";

        return view('keuangan.invoice_buat', compact('listPesanan', 'pengajuan', 'nomorInvoice'));
    }

    public function storeInvoice(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuans,id',
            'no_invoice' => 'required|string',
            'html_content' => 'required|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);
        $namaFile = 'dokumen_klien/INVOICE_' . str_replace('/', '_', $request->no_invoice) . '_' . time() . '.html';
        
        Storage::disk('public')->put($namaFile, $request->html_content);

        // PERBAIKAN: Menambahkan 'pengajuan_id' agar file otomatis masuk ke folder pesanan klien
        Dokumen::create([
            'user_id' => $pengajuan->user_id,
            'pengajuan_id' => $pengajuan->id, // <-- DITAMBAHKAN DI SINI
            'jenis_dokumen' => 'Invoice',
            'file_path' => $namaFile,
            'status' => 'Menunggu Validasi',
        ]);
        
        if ($request->has('termin_id') && $request->termin_id != '') {
            $termin = Termin::find($request->termin_id);
            if ($termin) {
                $termin->update(['keterangan' => 'INVOICE:' . $namaFile]);
            }
        }

        return redirect()->route('keuangan.invoice.buat')->with('success', 'Hebat! Dokumen Invoice Resmi berhasil diterbitkan dan masuk ke arsip Klien.');
    }

    public function klienIndex(Request $request)
    {
        $search = $request->input('search');
        $query = \App\Models\User::where('role', 'klien')
            ->with(['pengajuan.termins', 'dokumens']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%");
            });
        }
        
        $klien_list = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.klien', compact('klien_list', 'search'));
    }

    public function klienDetail($id)
    {
        $klienDetail = \App\Models\User::with([
            'dokumens', 
            'pengajuan.termins', 
            'pengajuan.details.billboard'
        ])->findOrFail($id);
        
        return view('admin.klien', compact('klienDetail'));
    }
}