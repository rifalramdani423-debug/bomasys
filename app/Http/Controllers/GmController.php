<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Termin;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GmController extends Controller
{
    // 1. EXECUTIVE COMMAND CENTER (DASHBOARD GM)
    public function dashboard()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $pendapatanBulanIni = Termin::where('status_termin', 'Lunas')
            ->whereMonth('updated_at', $bulanIni)
            ->whereYear('updated_at', $tahunIni)
            ->sum('nominal');

        $negosiasiAktif = Pengajuan::whereIn('status_pengajuan', ['Menunggu ACC', 'Disetujui', 'Menunggu Termin'])->count();
        $pendingApproval = Pengajuan::where('status_pengajuan', 'Menunggu ACC')->count();

        $totalTitik = DB::table('billboards')->count();
        $titikTersewa = DB::table('billboards')->where('status', 'Tersewa')->count();
        $kesehatanAset = $totalTitik > 0 ? round(($titikTersewa / $totalTitik) * 100) : 0;
        $perbaikanAset = DB::table('billboards')->where('status', 'Perbaikan')->count();

        $pipelineTerbaru = Pengajuan::with('user')->orderBy('updated_at', 'desc')->limit(5)->get();

        return view('gm.dashboard', compact(
            'pendapatanBulanIni', 'negosiasiAktif', 'pendingApproval', 
            'kesehatanAset', 'totalTitik', 'perbaikanAset', 'pipelineTerbaru'
        ));
    }

    // 2. SISTEM JEJAK AUDIT (AUDIT TRAIL LENGKAP & PENCARIAN)
    public function audit(Request $request)
    {
        $modul = $request->input('modul');
        $tanggal = $request->input('tanggal');
        $search = $request->input('search');

        // A. Tarik aktivitas Pesanan (Logika Aktor Admin vs Klien)
        $pengajuans = DB::table('pengajuans')
            ->join('users', 'pengajuans.user_id', '=', 'users.id')
            ->select(
                'pengajuans.updated_at as waktu',
                DB::raw("CASE 
                    WHEN pengajuans.status_pengajuan IN ('Menunggu ACC', 'Dibatalkan Klien') THEN users.name 
                    ELSE 'Divisi Admin BOMA' 
                END as aktor"),
                DB::raw("CASE 
                    WHEN pengajuans.status_pengajuan IN ('Menunggu ACC', 'Dibatalkan Klien') THEN 'KLIEN' 
                    ELSE 'ADMINISTRATOR' 
                END as peran"),
                DB::raw("CONCAT('Memperbarui status pesanan menjadi: ', pengajuans.status_pengajuan, ' (Klien: ', users.name, ')') as aktivitas"),
                DB::raw("'Modul Pesanan' as modul"),
                'pengajuans.id as ref_id'
            );

        // B. Tarik aktivitas Keuangan (Termin)
        $termins = DB::table('termins')
            ->join('pengajuans', 'termins.pengajuan_id', '=', 'pengajuans.id')
            ->join('users', 'pengajuans.user_id', '=', 'users.id')
            ->select(
                'termins.updated_at as waktu',
                DB::raw("'Divisi Keuangan BOMA' as aktor"),
                DB::raw("'KEUANGAN' as peran"),
                DB::raw("CONCAT('Pembaruan Termin Ke-', termins.termin_ke, ' (', IFNULL(termins.status_termin, 'Belum Bayar'), ') - Tagihan: ', users.name) as aktivitas"),
                DB::raw("'Modul Keuangan' as modul"),
                'termins.id as ref_id'
            )->whereNotNull('termins.status_termin');

        // C. Tarik aktivitas Aset & Produksi (Billboards)
        $billboards = DB::table('billboards')
            ->select(
                'updated_at as waktu',
                DB::raw("'Divisi Aset & Produksi' as aktor"),
                DB::raw("'ASET / LAPANGAN' as peran"),
                DB::raw("CONCAT('Memperbarui status operasional titik reklame (', kode_titik, ') menjadi: ', status) as aktivitas"),
                DB::raw("'Modul Aset' as modul"),
                'id as ref_id'
            )->whereNotNull('updated_at');

        // D. Tarik aktivitas Arsip Dokumen
        $dokumens = DB::table('dokumens')
            ->join('users', 'dokumens.user_id', '=', 'users.id')
            ->select(
                'dokumens.created_at as waktu',
                'users.name as aktor',
                'users.role as peran',
                DB::raw("CONCAT('Menambahkan arsip dokumen: ', dokumens.jenis_dokumen) as aktivitas"),
                DB::raw("'Modul Arsip' as modul"),
                'dokumens.id as ref_id'
            );

        // GABUNGKAN KE-4 TABEL MENJADI SATU LOG TERPUSAT
        $union = $pengajuans->union($termins)->union($billboards)->union($dokumens);

        $query = DB::table(DB::raw("({$union->toSql()}) as audit_logs"))
            ->mergeBindings($union)
            ->orderBy('waktu', 'desc');

        // Terapkan Filter & Pencarian
        if ($tanggal) {
            $query->whereDate('waktu', $tanggal);
        }
        if ($modul && $modul != 'Semua Modul') {
            $query->where('modul', $modul);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('aktivitas', 'like', "%{$search}%")
                  ->orWhere('aktor', 'like', "%{$search}%")
                  ->orWhere('peran', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->paginate(15);

        return view('gm.audit', compact('auditLogs', 'modul', 'tanggal', 'search'));
    }
}