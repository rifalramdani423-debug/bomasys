<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Termin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    private const CACHE_TTL = 5;
    private const BATAS_NOTIF = 15;

    public function terkini(Request $request)
    {
        $user = $request->user();
        $role = strtolower($user->role ?? '');

        $data = match (true) {
            in_array($role, ['admin', 'superadmin', 'super admin', 'super_admin']) => $this->untukAdmin(),
            in_array($role, ['keuangan', 'finance', 'akunting'])                   => $this->untukKeuangan(),
            in_array($role, ['aset', 'asset', 'infrastruktur'])                    => $this->untukAset(),
            in_array($role, ['produksi', 'lapangan', 'teknisi', 'tim produksi'])   => $this->untukProduksi(),
            $role === 'klien'                                                      => $this->untukKlien($user),
            default                                                                => ['angka' => [], 'daftar_sig' => '-', 'notif' => []],
        };

        return response()
            ->json([
                'angka'      => $data['angka'],
                'daftar_sig' => $data['daftar_sig'],
                'notif'      => [
                    'total' => count($data['notif']),
                    'items' => $data['notif'],
                ],
            ])
            ->header('Cache-Control', 'no-store, private');
    }

    /**
     * Sidik jari isi daftar: gabungan jumlah baris dan waktu ubah terakhir.
     * Kalau salah satu berubah, artinya ada yang perlu dimuat ulang.
     */
    private function sidikJari(array $bagian): string
    {
        return substr(md5(implode('|', array_map(fn ($v) => (string) $v, $bagian))), 0, 12);
    }

    private function rupiah($angka): string
    {
        return number_format((float) $angka, 0, ',', '.');
    }

    private function untukAdmin(): array
    {
        return Cache::remember('boma_live_admin', self::CACHE_TTL, function () {

            $antrean = Pengajuan::where('status_pengajuan', 'Menunggu ACC');

            $jumlahAntrean = (clone $antrean)->count();
            $ubahTerakhir  = (clone $antrean)->max('updated_at');

            $totalTitik  = DB::table('billboards')->count();
            $titikTerisi = DB::table('billboards')->whereIn('status', ['Tersewa', 'Disewa'])->count();

            $proyeksi = Pengajuan::whereNotIn('status_pengajuan', ['Ditolak', 'Batal'])->sum('estimasi_harga');

            $notif = [];

            $pesananMenunggu = Pengajuan::with('user:id,name,nama_perusahaan')
                ->where('status_pengajuan', 'Menunggu ACC')
                ->orderByDesc('updated_at')
                ->limit(self::BATAS_NOTIF)
                ->get();

            foreach ($pesananMenunggu as $p) {
                $notif[] = [
                    'title' => 'Pesanan Menunggu ACC',
                    'desc'  => 'Pesanan ' . $p->nomor_pengajuan . ' dari ' . ($p->user->nama_perusahaan ?: $p->user->name) . ' butuh persetujuan.',
                    'link'  => route('admin.pesanan'),
                    'waktu' => Carbon::parse($p->updated_at)->format('d M, H:i'),
                ];
            }

            $klienBaru = User::where('role', 'klien')
                ->whereDate('created_at', Carbon::today())
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            foreach ($klienBaru as $k) {
                $notif[] = [
                    'title' => 'Klien Baru Terdaftar',
                    'desc'  => 'Instansi/Klien ' . $k->name . ' bergabung hari ini.',
                    'link'  => route('admin.klien'),
                    'waktu' => Carbon::parse($k->created_at)->format('d M, H:i'),
                ];
            }

            return [
                'angka' => [
                    'admin_antrean'      => $jumlahAntrean,
                    'admin_klien'        => User::where('role', 'klien')->count(),
                    'admin_titik_terisi' => $titikTerisi,
                    'admin_total_titik'  => $totalTitik,
                    'admin_okupansi'     => $totalTitik > 0 ? round(($titikTerisi / $totalTitik) * 100) : 0,
                    'admin_proyeksi'     => $this->rupiah($proyeksi),
                ],
                'daftar_sig' => $this->sidikJari([$jumlahAntrean, $ubahTerakhir]),
                'notif'      => $notif,
            ];
        });
    }

    private function untukKeuangan(): array
    {
        return Cache::remember('boma_live_keuangan', self::CACHE_TTL, function () {

            $belumTervalidasi = fn () => Termin::whereNotNull('bukti_pembayaran')
                ->where(function ($q) {
                    $q->whereNotIn('status_termin', ['Lunas', 'Menunggu Revisi'])->orWhereNull('status_termin');
                });

            $jumlah       = $belumTervalidasi()->count();
            $ubahTerakhir = $belumTervalidasi()->max('updated_at');

            $notif = [];

            $antrean = $belumTervalidasi()
                ->with('pengajuan.user:id,name,nama_perusahaan')
                ->orderByDesc('updated_at')
                ->limit(self::BATAS_NOTIF)
                ->get();

            foreach ($antrean as $t) {
                if (! $t->pengajuan || ! $t->pengajuan->user) {
                    continue;
                }

                $nama = $t->pengajuan->user->nama_perusahaan ?: $t->pengajuan->user->name;

                $notif[] = [
                    'title' => 'Validasi Transfer Masuk',
                    'desc'  => 'Termin ke-' . $t->termin_ke . ' (Rp ' . $this->rupiah($t->nominal) . ') Pesanan ' . $t->pengajuan->nomor_pengajuan . ' dari ' . $nama,
                    'link'  => route('keuangan.validasi'),
                    'waktu' => Carbon::parse($t->updated_at)->format('d M, H:i'),
                ];
            }

            return [
                'angka'      => ['keuangan_validasi' => $jumlah],
                'daftar_sig' => $this->sidikJari([$jumlah, $ubahTerakhir]),
                'notif'      => $notif,
            ];
        });
    }

    private function untukKlien(User $user): array
    {
        return Cache::remember('boma_live_klien_' . $user->id, self::CACHE_TTL, function () use ($user) {

            $pengajuans = Pengajuan::where('user_id', $user->id);

            $totalPesanan = (clone $pengajuans)->count();
            $pesananAktif = (clone $pengajuans)->where('status_pengajuan', 'Lunas / Aktif')->count();
            $ubahPengajuan = (clone $pengajuans)->max('updated_at');

            $terminMilikKlien = fn () => Termin::whereHas('pengajuan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            $totalTagihan = $terminMilikKlien()
                ->where(function ($q) {
                    $q->where('status_termin', '!=', 'Lunas')->orWhereNull('status_termin');
                })
                ->sum('nominal');

            $ubahTermin = $terminMilikKlien()->max('updated_at');

            // Status tiap pesanan ikut dimasukkan ke sidik jari, karena perubahan status
            // (Disetujui, Ditolak, Menunggu Revisi) tidak selalu mengubah jumlah baris.
            $daftarStatus = (clone $pengajuans)
                ->orderBy('id')
                ->pluck('status_pengajuan')
                ->implode(',');

            $notif = [];

            $butuhTindakLanjut = (clone $pengajuans)
                ->whereIn('status_pengajuan', ['Disetujui', 'Menunggu Termin', 'Menunggu Pembayaran'])
                ->orderByDesc('updated_at')
                ->limit(self::BATAS_NOTIF)
                ->get();

            foreach ($butuhTindakLanjut as $pes) {
                $notif[] = [
                    'title' => 'Tindak Lanjut Pembayaran',
                    'desc'  => 'Pesanan ' . $pes->nomor_pengajuan . ' berstatus: ' . $pes->status_pengajuan . '. Segera selesaikan tagihan.',
                    'link'  => route('klien.keuangan'),
                    'waktu' => Carbon::parse($pes->updated_at)->format('d M, H:i'),
                ];
            }

            $ditolak = (clone $pengajuans)
                ->where('status_pengajuan', 'Ditolak')
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();

            foreach ($ditolak as $tolak) {
                $notif[] = [
                    'title' => 'Pesanan Ditolak',
                    'desc'  => 'Pesanan ' . $tolak->nomor_pengajuan . ' ditolak oleh Admin. Silakan cek catatan.',
                    'link'  => route('klien.dashboard'),
                    'waktu' => Carbon::parse($tolak->updated_at)->format('d M, H:i'),
                ];
            }

            $revisi = (clone $pengajuans)
                ->where('status_pengajuan', 'Menunggu Revisi')
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();

            foreach ($revisi as $rev) {
                $notif[] = [
                    'title' => 'Pesanan Perlu Revisi',
                    'desc'  => 'Pesanan ' . $rev->nomor_pengajuan . ' diminta revisi oleh Admin. Cek catatannya.',
                    'link'  => route('klien.dashboard'),
                    'waktu' => Carbon::parse($rev->updated_at)->format('d M, H:i'),
                ];
            }

            $revisiTermin = $terminMilikKlien()
                ->with('pengajuan:id,nomor_pengajuan')
                ->where('status_termin', 'Menunggu Revisi')
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();

            foreach ($revisiTermin as $rt) {
                $notif[] = [
                    'title' => 'Revisi Bukti Transfer',
                    'desc'  => 'Termin ke-' . $rt->termin_ke . ' (Pesanan ' . ($rt->pengajuan->nomor_pengajuan ?? '-') . ') ditolak Keuangan. Cek tagihan Anda.',
                    'link'  => route('klien.keuangan'),
                    'waktu' => Carbon::parse($rt->updated_at)->format('d M, H:i'),
                ];
            }

            return [
                'angka' => [
                    'klien_total_pesanan' => $totalPesanan,
                    'klien_aktif'         => $pesananAktif,
                    'klien_tagihan'       => $this->rupiah($totalTagihan),
                ],
                'daftar_sig' => $this->sidikJari([$totalPesanan, $ubahPengajuan, $ubahTermin, $daftarStatus]),
                'notif'      => $notif,
            ];
        });
    }

    private function untukAset(): array
    {
        return Cache::remember('boma_live_aset', self::CACHE_TTL, function () {

            $booking = DB::table('billboards')->where('status', 'Booking');

            $jumlah       = (clone $booking)->count();
            $ubahTerakhir = (clone $booking)->max('updated_at');

            $notif = [];

            foreach ((clone $booking)->orderByDesc('updated_at')->limit(self::BATAS_NOTIF)->get() as $bok) {
                $notif[] = [
                    'title' => 'Status Titik Berubah',
                    'desc'  => 'Titik ' . $bok->kode_titik . ' saat ini sedang di-booking.',
                    'link'  => route('aset.index'),
                    'waktu' => Carbon::parse($bok->updated_at)->format('d M, H:i'),
                ];
            }

            return [
                'angka'      => ['aset_booking' => $jumlah],
                'daftar_sig' => $this->sidikJari([$jumlah, $ubahTerakhir]),
                'notif'      => $notif,
            ];
        });
    }

    private function untukProduksi(): array
    {
        return Cache::remember('boma_live_produksi', self::CACHE_TTL, function () {

            $tersewa = DB::table('billboards')->where('status', 'Tersewa');

            $jumlah       = (clone $tersewa)->count();
            $ubahTerakhir = (clone $tersewa)->max('updated_at');

            $notif = [];

            foreach ((clone $tersewa)->orderByDesc('updated_at')->limit(self::BATAS_NOTIF)->get() as $titik) {
                $notif[] = [
                    'title' => 'Tugas Pantauan Lapangan',
                    'desc'  => 'Titik ' . $titik->kode_titik . ' berstatus Tersewa. Mohon cek jadwal.',
                    'link'  => route('produksi.tugas'),
                    'waktu' => Carbon::parse($titik->updated_at)->format('d M, H:i'),
                ];
            }

            return [
                'angka'      => ['produksi_tugas' => $jumlah],
                'daftar_sig' => $this->sidikJari([$jumlah, $ubahTerakhir]),
                'notif'      => $notif,
            ];
        });
    }
}