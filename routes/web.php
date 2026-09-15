<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KlienController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\GmController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

// ==========================================
//          ---- HALAMAN UTAMA ----
// ==========================================
Route::get('/', function () {
    return view('welcome');
});


// ==========================================
//            ---- BAGIAN KLIEN ----
// ==========================================
Route::middleware(['auth', 'verified', 'role:klien'])->prefix('klien')->group(function () {

    Route::get('/dashboard', [KlienController::class, 'dashboard'])->name('klien.dashboard');

    Route::get('/', [KlienController::class, 'index'])->name('klien.index');

    Route::get('/dokumen', [KlienController::class, 'dokumen'])->name('klien.dokumen');
    Route::post('/dokumen/store', [KlienController::class, 'storeDokumen'])->name('klien.dokumen.store');
    Route::delete('/dokumen/delete/{id}', [KlienController::class, 'destroyDokumen'])->name('klien.dokumen.delete');

    Route::get('/po', [KlienController::class, 'po'])->name('klien.po');
    Route::post('/po/simpan', [KlienController::class, 'storePo'])->name('klien.po.store');

    Route::post('/cek-ketersediaan', [KlienController::class, 'cekKetersediaan'])->name('klien.cek-ketersediaan');
    Route::post('/ajukan-sewa', [KlienController::class, 'storePengajuan'])->name('klien.pengajuan.store');

    Route::get('/keuangan', [KlienController::class, 'keuanganIndex'])->name('klien.keuangan');
    Route::post('/keuangan/{id}/simpan-termin', [KlienController::class, 'simpanTermin'])->name('keuangan.simpan');
    Route::post('/keuangan/{id}', [KlienController::class, 'simpanTermin'])->name('klien.keuangan');

    Route::get('/termin/buat/{pesanan_id}', [KlienController::class, 'createTermin'])->name('klien.termin.buat');
    Route::post('/termin/simpan/{pesanan_id}', [KlienController::class, 'storeTermin'])->name('klien.termin.store');
    Route::post('/termin/{id}/upload', [KlienController::class, 'uploadBuktiTermin'])->name('klien.termin.upload');

    Route::post('/pesanan/{id}/kirim-ulang', [KlienController::class, 'kirimUlangPesanan'])->name('klien.pesanan.kirim_ulang');
});


// ==========================================
//            ---- BAGIAN ADMIN ----
// ==========================================
Route::middleware(['auth', 'verified', 'role:admin,superadmin,keuangan'])->prefix('admin')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('admin.pesanan');
    Route::post('/pesanan/{id}/proses', [AdminController::class, 'prosesPesanan'])->name('admin.pesanan.proses');

    Route::get('/penawaran', [AdminController::class, 'penawaran'])->name('admin.penawaran');
    Route::post('/penawaran/{id}/deal', [AdminController::class, 'simpanHargaFinal'])->name('admin.penawaran.deal');
    Route::post('/dokumen/store', [App\Http\Controllers\KlienController::class, 'storeDokumenAdmin'])->name('admin.dokumen.store');

    Route::get('/klien', [App\Http\Controllers\AdminController::class, 'klien'])->name('admin.klien');
    Route::get('/klien/detail/{id}', [App\Http\Controllers\AdminController::class, 'klienDetail'])->name('admin.klien.detail');
    Route::get('/klien/unduh-zip/{id}', [App\Http\Controllers\AdminController::class, 'unduhArsipKlien'])->name('admin.klien.unduh-arsip');
    Route::post('/klien/store', [AdminController::class, 'storeKlien'])->name('admin.klien.store');
    Route::put('/klien/update/{id}', [AdminController::class, 'updateKlien'])->name('admin.klien.update');
    Route::delete('/klien/delete/{id}', [AdminController::class, 'destroyKlien'])->name('admin.klien.delete');

    Route::get('/bast', [AdminController::class, 'bast'])->name('admin.bast');
    Route::post('/bast/store', [AdminController::class, 'store'])->name('admin.bast.store');
});


// ==========================================
//        ---- BAGIAN SUPER ADMIN ----
// ==========================================
Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('super-admin')->group(function () {

    Route::get('/karyawan', [SuperAdminController::class, 'indexKaryawan'])->name('superadmin.karyawan');
    Route::post('/karyawan/store', [SuperAdminController::class, 'storeKaryawan'])->name('superadmin.karyawan.store');
    Route::put('/karyawan/update/{id}', [SuperAdminController::class, 'updateKaryawan'])->name('superadmin.karyawan.update');
    Route::delete('/karyawan/delete/{id}', [SuperAdminController::class, 'destroyKaryawan'])->name('superadmin.karyawan.delete');

    Route::get('/sampah', [SuperAdminController::class, 'sampahIndex'])->name('superadmin.sampah');
    Route::post('/sampah/restore/{id}', [SuperAdminController::class, 'restoreAkun'])->name('superadmin.sampah.restore');
    Route::delete('/sampah/force-delete/{id}', [SuperAdminController::class, 'forceDeleteAkun'])->name('superadmin.sampah.force-delete');
});


// ==========================================
//          ---- BAGIAN KEUANGAN ----
// ==========================================
Route::middleware(['auth', 'verified', 'role:keuangan'])->prefix('keuangan')->group(function () {

    Route::get('/dashboard', [KeuanganController::class, 'dashboard'])->name('keuangan.dashboard');
    Route::get('/klien', [KeuanganController::class, 'klienIndex'])->name('keuangan.klien');
    Route::get('/klien/detail/{id}', [App\Http\Controllers\KeuanganController::class, 'klienDetail'])->name('keuangan.klien.detail');

    Route::get('/validasi', [KeuanganController::class, 'validasiIndex'])->name('keuangan.validasi');
    Route::post('/termin/{id}/proses', [KeuanganController::class, 'prosesTermin'])->name('keuangan.termin.proses');

    Route::get('/invoice/buat', [KeuanganController::class, 'buatInvoice'])->name('keuangan.invoice.buat');
    Route::post('/invoice/simpan', [KeuanganController::class, 'storeInvoice'])->name('keuangan.invoice.store');

    Route::post('/rekonsiliasi/{id}/tandai', [App\Http\Controllers\KeuanganController::class, 'tandaiMutasi']);
    Route::post('/rekonsiliasi/{id}/rilis-denda', [App\Http\Controllers\KeuanganController::class, 'rilisDenda']);

    Route::post('/dokumen/store', [App\Http\Controllers\KlienController::class, 'storeDokumenAdmin'])->name('keuangan.dokumen.store');
});


// ==========================================
//            ---- BAGIAN ASET ----
// ==========================================
Route::middleware(['auth', 'verified', 'role:aset'])->prefix('aset')->name('aset.')->group(function () {

    Route::get('/dashboard', [AsetController::class, 'dashboard'])->name('index');

    Route::get('/master-data', [AsetController::class, 'masterData'])->name('master_data');
    Route::post('/master-data/simpan', [AsetController::class, 'storeBillboard'])->name('master_data.store');

    Route::get('/penjadwalan', [AsetController::class, 'penjadwalan'])->name('penjadwalan');

    // route('aset.im') = Brankas Arsip, route('aset.im.buat') = Form Buat IM
    Route::get('/im', [AsetController::class, 'indexIm'])->name('im');
    Route::get('/im/buat', [AsetController::class, 'buatIm'])->name('im.buat');
    Route::post('/im/simpan', [AsetController::class, 'storeIm'])->name('im.store');
    Route::delete('/im/{id}', [AsetController::class, 'destroyIm'])->name('im.destroy');

    Route::get('/validasi', [AsetController::class, 'validasi'])->name('validasi');
    Route::post('/validasi/{id}/proses', [AsetController::class, 'prosesValidasi'])->name('validasi.proses');
    Route::post('/validasi/foto-klien/{id}', [AsetController::class, 'updateFotoKlien'])->name('validasi.foto_klien');
});


// ==========================================
//          ---- BAGIAN PRODUKSI ----
// ==========================================
Route::middleware(['auth', 'verified', 'role:produksi'])->prefix('produksi')->name('produksi.')->group(function () {

    Route::get('/dashboard', [ProduksiController::class, 'dashboard'])->name('index');
    Route::get('/tugas', [ProduksiController::class, 'tugas'])->name('tugas');
    Route::post('/tugas/simpan-laporan', [ProduksiController::class, 'storeLaporan'])->name('laporan.store');
});


// ==========================================
//        ---- BAGIAN EKSEKUTIF (GM) ----
// ==========================================
Route::middleware(['auth', 'verified', 'role:gm'])->prefix('gm')->group(function () {

    Route::get('/dashboard', [GmController::class, 'dashboard'])->name('gm.index');
    Route::get('/audit', [GmController::class, 'audit'])->name('gm.audit');
});


// ==========================================
//           ---- BAGIAN PROFIL ----
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ==========================================
//    ---- PENGATUR LALU LINTAS DASHBOARD ----
// ==========================================
Route::get('/dashboard', function () {
    $role = strtolower(auth()->user()->role);

    return match ($role) {
        'admin', 'superadmin' => redirect()->route('admin.dashboard'),
        'klien'               => redirect()->route('klien.dashboard'),
        'keuangan'            => redirect()->route('keuangan.dashboard'),
        'aset'                => redirect()->route('aset.index'),
        'produksi'            => redirect()->route('produksi.index'),
        'gm'                  => redirect()->route('gm.index'),
        default               => redirect('/'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');


// ==========================================
//   ---- API STATUS TERKINI (POLLING) ----
// ==========================================
// Dipanggil berkala oleh layout untuk menyegarkan angka, badge, dan notifikasi
// tanpa perlu refresh halaman. Lihat StatusController.
Route::get('/status-terkini', [StatusController::class, 'terkini'])
    ->middleware('auth')
    ->name('status.terkini');


Route::post('/api/midtrans/webhook', [PaymentController::class, 'handleWebhook']);


// ==========================================
//          ---- UTILITAS INTERNAL ----
// ==========================================
// Dibatasi superadmin. Sebelumnya rute ini terbuka untuk publik sehingga siapa pun
// yang tahu URL-nya bisa membersihkan cache berulang kali dari luar.
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('route:clear'); // Ini yang paling penting sekarang!
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');

    return 'Semua Cache & Rute Berhasil Dihapus! Silakan cek web.';
})->middleware(['auth', 'role:superadmin']);

Route::put('/dokumen/update/{id}', [App\Http\Controllers\KlienController::class, 'updateDokumen'])->middleware('auth')->name('dokumen.update');

// Otentikasi Bawaan Breeze
require __DIR__.'/auth.php';