@php
    $userRole     = strtolower(Auth::user()->role ?? '');
    $isSuperAdmin = in_array($userRole, ['superadmin', 'super admin', 'super_admin']);
    $isAdmin      = in_array($userRole, ['admin']) || $isSuperAdmin;
    $isKeuangan   = in_array($userRole, ['keuangan', 'finance', 'akunting']);
    $isAset       = in_array($userRole, ['aset', 'asset', 'infrastruktur']);
    $isProduksi   = in_array($userRole, ['produksi', 'lapangan', 'teknisi', 'tim produksi']);
    $isGm         = in_array($userRole, ['gm', 'general manager', 'direktur', 'manager']);
    $isKlien      = $userRole === 'klien';

    $navActive = match (true) {
        $isKeuangan => 'bg-emerald-600/10 text-emerald-500 border-emerald-500/20',
        $isAset     => 'bg-blue-600/10 text-blue-500 border-blue-500/20',
        $isGm       => 'bg-purple-600/10 text-purple-500 border-purple-500/20',
        $isProduksi => 'bg-orange-600/10 text-orange-500 border-orange-500/20',
        default     => 'bg-red-600/10 text-red-500 border-red-500/20',
    };
    $navIdle = 'border-transparent text-zinc-400';

    $ico = [
        'grid'      => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z',
        'inbox'     => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
        'coin'      => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'users'     => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        'doc'       => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'userPlus'  => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'trash'     => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
        'trend'     => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'check'     => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'invoice'   => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
        'cube'      => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
        'db'        => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
        'calendar'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'pencil'    => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        'archive'   => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
        'shield'    => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        'chart'     => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'clock'     => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'home'      => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        'card'      => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'po'        => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z',
        'bell'      => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        'user'      => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        'logout'    => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
        'refresh'   => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
    ];

    $badgePesanan  = $isAdmin ? \App\Models\Pengajuan::where('status_pengajuan', 'Menunggu ACC')->count() : 0;
    $badgeValidasi = $isKeuangan ? \App\Models\Termin::whereNotNull('bukti_pembayaran')
        ->where(function ($q) { $q->whereNotIn('status_termin', ['Lunas', 'Menunggu Revisi'])->orWhereNull('status_termin'); })
        ->count() : 0;

    if ($isAdmin) {
        $menuSections = [
            ['label' => 'Menu Utama', 'items' => [
                ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Dashboard Eksekutif', 'icon' => $ico['grid']],
                ['route' => 'admin.pesanan',   'match' => 'admin.pesanan',   'label' => 'Pesanan Masuk',       'icon' => $ico['inbox'], 'badge' => $badgePesanan, 'live' => 'admin_antrean'],
                ['route' => 'admin.penawaran', 'match' => 'admin.penawaran', 'label' => 'Penawaran & Harga',   'icon' => $ico['coin']],
                ['route' => 'admin.klien',     'match' => 'admin.klien*',    'label' => 'Arsip Data Klien',    'icon' => $ico['users']],
                ['route' => 'admin.bast',      'match' => 'admin.bast',      'label' => 'Penerbitan BAST',     'icon' => $ico['doc']],
            ]],
        ];
        if ($isSuperAdmin) {
            $menuSections[] = ['label' => 'Area Super Admin', 'items' => [
                ['route' => 'superadmin.karyawan', 'match' => 'superadmin.karyawan*', 'label' => 'Manajemen Karyawan', 'icon' => $ico['userPlus']],
                ['route' => 'superadmin.sampah',   'match' => 'superadmin.sampah*',   'label' => 'Sampah Akun',        'icon' => $ico['trash']],
            ]];
        }
    } elseif ($isKeuangan) {
        $menuSections = [['label' => 'Menu Utama', 'items' => [
            ['route' => 'keuangan.dashboard',    'match' => 'keuangan.dashboard',    'label' => 'Dashboard Keuangan', 'icon' => $ico['trend']],
            ['route' => 'keuangan.klien',        'match' => 'keuangan.klien*',       'label' => 'Data Klien',         'icon' => $ico['users']],
            ['route' => 'keuangan.validasi',     'match' => 'keuangan.validasi',     'label' => 'Validasi Pembayaran','icon' => $ico['check'], 'badge' => $badgeValidasi, 'live' => 'keuangan_validasi'],
            ['route' => 'keuangan.invoice.buat', 'match' => 'keuangan.invoice.buat', 'label' => 'Pembuatan Invoice',  'icon' => $ico['invoice']],
        ]]];
    } elseif ($isAset) {
        $menuSections = [['label' => 'Menu Utama', 'items' => [
            ['route' => 'aset.index',       'match' => 'aset.index',       'label' => 'Dashboard Pemantauan', 'icon' => $ico['cube']],
            ['route' => 'aset.master_data', 'match' => 'aset.master_data', 'label' => 'Data Reklame Fisik',   'icon' => $ico['db']],
            ['route' => 'aset.penjadwalan', 'match' => 'aset.penjadwalan', 'label' => 'Jadwal Lapangan',      'icon' => $ico['calendar']],
            ['route' => 'aset.im.buat',     'match' => 'aset.im.buat',     'label' => 'Buat & Cetak IM',      'icon' => $ico['pencil']],
            ['route' => 'aset.im',          'match' => 'aset.im',          'label' => 'Brankas Arsip IM',     'icon' => $ico['archive'], 'except' => 'aset.im.buat'],
            ['route' => 'aset.validasi',    'match' => 'aset.validasi',    'label' => 'Validasi QC',          'icon' => $ico['shield']],
        ]]];
    } elseif ($isGm) {
        $menuSections = [['label' => 'Menu Utama', 'items' => [
            ['route' => 'gm.index', 'match' => 'gm.index', 'label' => 'Executive Summary',  'icon' => $ico['chart']],
            ['route' => 'gm.audit', 'match' => 'gm.audit', 'label' => 'Sistem Jejak Audit', 'icon' => $ico['clock']],
        ]]];
    } elseif ($isProduksi) {
        $menuSections = [['label' => 'Menu Utama', 'items' => [
            ['route' => 'produksi.index', 'match' => 'produksi.index', 'label' => 'Beranda Lapangan', 'icon' => $ico['home']],
            ['route' => 'produksi.tugas', 'match' => 'produksi.tugas', 'label' => 'Form Laporan QC',  'icon' => $ico['clipboard']],
        ]]];
    } else {
        $menuSections = [['label' => 'Menu Utama', 'items' => [
            ['route' => 'klien.dashboard', 'match' => 'klien.dashboard', 'label' => 'Dashboard Klien',   'icon' => $ico['trend']],
            ['route' => 'klien.index',     'match' => 'klien.index',     'label' => 'Pengajuan Sewa',    'icon' => $ico['home']],
            ['route' => 'klien.dokumen',   'match' => 'klien.dokumen',   'label' => 'Arsip Data Klien',  'icon' => $ico['doc']],
            ['route' => 'klien.keuangan',  'match' => 'klien.keuangan',  'label' => 'Keuangan & Termin', 'icon' => $ico['card']],
            ['route' => 'klien.po',        'match' => 'klien.po',        'label' => 'Pembuatan PO',      'icon' => $ico['po']],
        ]]];
    }

    $statusAwal = app(\App\Http\Controllers\StatusController::class)->terkini(request())->getData(true);

    // Halaman yang TIDAK boleh menyegarkan diri sendiri, karena isinya bisa hilang:
    // pilihan titik di peta, isian form panjang, keranjang, dan halaman profil.
    // Di halaman ini sistem tetap memberi tahu, tapi lewat banner "Muat Ulang".
    $autoMuatUlang = ! request()->routeIs(
        'klien.index',
        'klien.po',
        'klien.termin.buat',
        'keuangan.invoice.buat',
        'aset.im.buat',
        'aset.master_data',
        'profile.edit',
    );

    $breadcrumbRoot = match (true) {
        $isAdmin    => 'Admin Utama',
        $isKeuangan => 'Divisi Keuangan',
        $isAset     => 'Divisi Aset',
        $isProduksi => 'Tim Lapangan',
        $isGm       => 'Direksi',
        default     => 'Sewa',
    };

    $breadcrumbPage = 'Dashboard';
    foreach ($menuSections as $sec) {
        foreach ($sec['items'] as $it) {
            if (request()->routeIs($it['match']) && (!isset($it['except']) || !request()->routeIs($it['except']))) {
                $breadcrumbPage = $it['label'];
            }
        }
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BOMA Advertising') }} - Dashboard</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{--
        Struktur shell ditulis sebagai CSS asli, bukan utility Tailwind, supaya layout
        tetap benar walau build CSS di server tertinggal satu versi.
    --}}
    <style>
        [x-cloak] { display: none !important; }

        :root {
            --boma-sidebar-w: 17rem;
            --boma-rail-w: 5rem;
            --boma-header-h: 4rem;
        }
        @media (min-width: 768px) { :root { --boma-header-h: 5rem; } }

        html { -webkit-text-size-adjust: 100%; }
        body { margin: 0; overflow-x: hidden; }
        body.is-open { overflow: hidden; }
        @media (min-width: 1024px) { body.is-open { overflow: visible; } }

        /* ---------- SIDEBAR ---------- */
        .boma-sidebar {
            position: fixed; top: 0; bottom: 0; left: 0;
            width: var(--boma-sidebar-w); max-width: 85vw;
            display: flex; flex-direction: column;
            z-index: 60;
            transform: translateX(-100%);
            transition: transform .3s ease, width .3s ease;
        }
        body.is-open .boma-sidebar { transform: translateX(0); }

        .boma-overlay { position: fixed; inset: 0; z-index: 55; background: rgba(0,0,0,.7); }

        .boma-sidebar-head {
            display: flex; align-items: center; gap: .5rem;
            height: var(--boma-header-h); flex-shrink: 0;
            padding: 0 1rem;
        }
        .boma-brand { flex: 1 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .boma-nav {
            flex: 1 1 auto; min-height: 0;
            overflow-y: auto; overflow-x: hidden;
            padding: 1.25rem .75rem calc(1.25rem + env(safe-area-inset-bottom, 0px));
        }
        .boma-section {
            margin: 1rem 0 .5rem; padding: 0 .75rem;
            font-size: 10px; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: #71717a;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .boma-nav > .boma-section:first-child { margin-top: 0; }
        .boma-divider { display: none; margin: 1rem .5rem .5rem; border-top: 1px solid #27272a; }

        .boma-nav-link {
            position: relative;
            display: flex; align-items: center; gap: .75rem;
            padding: .625rem .75rem; margin-bottom: .25rem;
            border-radius: .75rem; border-width: 1px; border-style: solid;
            font-size: .875rem; font-weight: 500;
            transition: background-color .15s ease, color .15s ease;
        }
        .boma-nav-link:hover { background-color: rgba(39,39,42,.7); color: #fff; }
        .boma-nav-link > svg { width: 1.25rem; height: 1.25rem; flex-shrink: 0; }
        .boma-label { flex: 1 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .boma-badge { flex-shrink: 0; }
        .boma-dot { display: none; position: absolute; top: .5rem; right: .5rem; width: .5rem; height: .5rem; }

        /* ---------- KONTEN ---------- */
        .boma-content { display: flex; flex-direction: column; min-height: 100vh; min-width: 0; transition: padding-left .3s ease; }

        .boma-header {
            position: sticky; top: 0; z-index: 40;
            display: flex; align-items: center; gap: .5rem;
            height: var(--boma-header-h); flex-shrink: 0;
            padding: 0 .75rem;
        }
        @media (min-width: 768px) { .boma-header { gap: 1rem; padding: 0 1.5rem; } }
        @media (min-width: 1024px) { .boma-header { padding: 0 2rem; } }

        /* z-index:0 mengurung konten halaman di bawah header, supaya elemen ber-z-index
           tinggi di dalam halaman tidak menimpa dropdown notifikasi. */
        .boma-main { position: relative; z-index: 0; flex: 1 1 auto; min-width: 0; padding: .75rem; }
        @media (min-width: 640px) { .boma-main { padding: 1rem; } }
        @media (min-width: 768px) { .boma-main { padding: 1.5rem; } }
        @media (min-width: 1024px) { .boma-main { padding: 2rem; } }

        .boma-icon-btn {
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0; padding: .5rem; border-radius: .5rem;
            color: #a1a1aa; background: transparent; border: 0; cursor: pointer;
            transition: background-color .15s ease, color .15s ease;
        }
        .boma-icon-btn:hover { background-color: #27272a; color: #fff; }
        .boma-icon-btn > svg { width: 1.5rem; height: 1.5rem; }

        .boma-only-desktop { display: none !important; }

        @media (min-width: 1024px) {
            .boma-sidebar { transform: none !important; max-width: none; }
            .boma-overlay { display: none !important; }
            .boma-content { padding-left: var(--boma-sidebar-w); }

            .boma-only-mobile { display: none !important; }
            .boma-only-desktop { display: inline-flex !important; }

            body.is-collapsed .boma-sidebar { width: var(--boma-rail-w); }
            body.is-collapsed .boma-content { padding-left: var(--boma-rail-w); }
            body.is-collapsed .boma-brand,
            body.is-collapsed .boma-section,
            body.is-collapsed .boma-label,
            body.is-collapsed .boma-badge { display: none !important; }
            body.is-collapsed .boma-sidebar-head { justify-content: center; padding: 0 .5rem; }
            body.is-collapsed .boma-nav { padding-left: .5rem; padding-right: .5rem; }
            body.is-collapsed .boma-nav-link { justify-content: center; padding-left: 0; padding-right: 0; }
            body.is-collapsed .boma-divider { display: block; }
            body.is-collapsed .boma-dot { display: flex; }
        }

        /* ---------- NOTIFIKASI ---------- */
        .boma-notif { position: relative; }
        .boma-notif-panel {
            position: fixed; left: .75rem; right: .75rem;
            top: calc(var(--boma-header-h) + .5rem);
            z-index: 70;
            display: flex; flex-direction: column;
            max-height: 70vh; overflow: hidden;
        }
        .boma-notif-head { flex-shrink: 0; }
        .boma-notif-list { flex: 1 1 auto; min-height: 0; overflow-y: auto; overscroll-behavior: contain; }
        @media (min-width: 640px) {
            .boma-notif-panel {
                position: absolute; left: auto; right: 0; top: 100%;
                margin-top: .5rem; width: 22rem;
                max-width: calc(100vw - 2rem); max-height: 26rem;
            }
        }

        .boma-menu { position: absolute; right: 0; top: 100%; margin-top: .5rem; width: 13rem; z-index: 70; }

        .boma-toast {
            position: fixed; left: .75rem; right: .75rem; bottom: 1rem;
            z-index: 9999; display: flex; align-items: flex-start; gap: .75rem;
        }
        @media (min-width: 640px) {
            .boma-toast { left: auto; right: 1.5rem; bottom: auto; top: calc(var(--boma-header-h) + 1rem); width: 22rem; }
        }

        /* ---------- BANNER MUAT ULANG ---------- */
        .boma-banner {
            position: fixed; left: .75rem; right: .75rem; bottom: 1rem;
            z-index: 9998; display: flex; align-items: center; gap: .75rem;
        }
        @media (min-width: 640px) {
            .boma-banner { left: 50%; right: auto; transform: translateX(-50%); width: auto; max-width: 32rem; }
        }
        .has-bottomnav .boma-banner { bottom: calc(4.75rem + env(safe-area-inset-bottom, 0px)); }
        @media (min-width: 768px) { .has-bottomnav .boma-banner { bottom: 1rem; } }

        /* Kedipan halus saat sebuah angka berubah tanpa refresh */
        @keyframes boma-kedip {
            0%   { background-color: rgba(220,38,38,.35); }
            100% { background-color: transparent; }
        }
        .boma-kedip { animation: boma-kedip 1.2s ease-out; border-radius: .375rem; }

        /* ---------- BOTTOM NAV (PRODUKSI) ---------- */
        .boma-bottomnav {
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 45;
            display: flex; align-items: center; justify-content: space-around;
            height: calc(4rem + env(safe-area-inset-bottom, 0px));
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        @media (min-width: 768px) { .boma-bottomnav { display: none; } }
        .has-bottomnav .boma-main { padding-bottom: 6rem; }
        @media (min-width: 768px) { .has-bottomnav .boma-main { padding-bottom: 2rem; } }

        /* ---------- UTIL ---------- */
        .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #3f3f46 transparent; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #3f3f46; border-radius: 9999px; }
        .leaflet-container { background: #09090b; }

        @media (prefers-reduced-motion: reduce) {
            * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        }
    </style>

    {{-- Alpine sudah di-bundle lewat resources/js/app.js, jadi TIDAK ada tag CDN di sini.
         Memuat keduanya membuat setiap x-data ter-inisialisasi dua kali dan tombol jadi mati. --}}
    <script>
        document.addEventListener('alpine:init', function () {

            /*
                Satu sumber kebenaran untuk seluruh data yang menyegar sendiri.
                Angka diganti di tempat; isi daftar/tabel hanya memunculkan banner
                supaya tombol dan token CSRF yang sudah ter-render tidak tersentuh.
            */
            Alpine.store('live', {
                notifItems: @json($statusAwal['notif']['items'] ?? []),
                notifTotal: {{ $statusAwal['notif']['total'] ?? 0 }},
                daftarSig: @json($statusAwal['daftar_sig'] ?? null),

                toast: { tampil: false, title: '', desc: '' },
                perluMuatUlang: false,

                jeda: 10000,
                tickSepi: 0,
                gagalBerturut: 0,

                autoMuatUlang: {{ $autoMuatUlang ? 'true' : 'false' }},
                formKotor: false,

                mulai() {
                    this.pulihkanScroll();
                    this.jadwalkan();

                    document.addEventListener('visibilitychange', () => {
                        if (!document.hidden) this.ambil();
                    });

                    // Begitu user menyentuh isian apa pun, halaman berhenti menyegarkan
                    // diri sendiri supaya ketikannya tidak hilang. Banner tetap muncul.
                    ['input', 'change'].forEach((ev) => {
                        document.addEventListener(ev, (e) => {
                            const t = e.target;
                            if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.tagName === 'SELECT' || t.isContentEditable)) {
                                this.formKotor = true;
                            }
                        }, true);
                    });
                },

                /*
                    Halaman hanya boleh menyegarkan diri kalau user benar-benar
                    sedang diam. Kalau salah satu syarat gagal, jatuh ke banner.
                */
                amanUntukMuatUlang() {
                    if (!this.autoMuatUlang) return false;
                    if (this.formKotor) return false;
                    if (document.body.classList.contains('is-open')) return false;
                    if (document.querySelector('.swal2-container')) return false;
                    if (document.querySelector('[data-tanpa-auto-muat-ulang]')) return false;
                    if (window.getSelection && String(window.getSelection()).length > 0) return false;

                    const aktif = document.activeElement;
                    if (aktif && (aktif.tagName === 'INPUT' || aktif.tagName === 'TEXTAREA' || aktif.tagName === 'SELECT' || aktif.isContentEditable)) {
                        return false;
                    }

                    return true;
                },

                /*
                    Pengaman anti-loop. Kalau karena suatu hal sidik jari server tidak
                    pernah cocok dengan halaman yang baru ter-render, tanpa ini halaman
                    akan reload tanpa henti. Lebih dari 3 kali dalam semenit: menyerah,
                    matikan auto, pakai banner saja.
                */
                bolehReloadLagi() {
                    try {
                        const sekarang = Date.now();
                        let log = JSON.parse(sessionStorage.getItem('boma_reload_log') || '[]');
                        log = log.filter((t) => sekarang - t < 60000);

                        if (log.length >= 3) {
                            this.autoMuatUlang = false;
                            return false;
                        }

                        log.push(sekarang);
                        sessionStorage.setItem('boma_reload_log', JSON.stringify(log));
                        return true;
                    } catch (e) {
                        return true;
                    }
                },

                muatUlangSekarang() {
                    try {
                        sessionStorage.setItem('boma_auto_scroll', JSON.stringify({
                            path: window.location.pathname,
                            y: window.scrollY,
                        }));
                    } catch (e) {}

                    window.location.reload();
                },

                pulihkanScroll() {
                    window.addEventListener('load', () => {
                        try {
                            const mentah = sessionStorage.getItem('boma_auto_scroll');
                            if (!mentah) return;

                            sessionStorage.removeItem('boma_auto_scroll');
                            const data = JSON.parse(mentah);

                            if (data.path === window.location.pathname) {
                                window.scrollTo(0, data.y);
                            }
                        } catch (e) {}
                    });
                },

                jadwalkan() {
                    setTimeout(() => {
                        if (!document.hidden) this.ambil();
                        this.jadwalkan();
                    }, this.jeda);
                },

                async ambil() {
                    try {
                        const res = await fetch('{{ route('status.terkini') }}', {
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin',
                        });

                        // Sesi habis: berhenti membanjiri server, cukup cek tiap 2 menit.
                        if (res.status === 401 || res.status === 419) {
                            this.jeda = 120000;
                            return;
                        }
                        if (!res.ok) throw new Error('HTTP ' + res.status);

                        const data = await res.json();
                        this.gagalBerturut = 0;

                        let adaPerubahan = this.terapkanAngka(data.angka || {});

                        if (data.notif && data.notif.total > this.notifTotal) {
                            const baru = data.notif.items[0];
                            if (baru) {
                                this.toast = { tampil: true, title: baru.title, desc: baru.desc };
                                setTimeout(() => { this.toast.tampil = false; }, 7000);
                            }
                            adaPerubahan = true;
                        }

                        if (data.notif) {
                            this.notifItems = data.notif.items;
                            this.notifTotal = data.notif.total;
                        }

                        if (data.daftar_sig && this.daftarSig && data.daftar_sig !== this.daftarSig) {
                            if (this.amanUntukMuatUlang() && this.bolehReloadLagi()) {
                                this.muatUlangSekarang();
                                return;
                            }

                            this.perluMuatUlang = true;
                            adaPerubahan = true;
                        }
                        this.daftarSig = data.daftar_sig;

                        // Melambat saat sepi, langsung gesit lagi begitu ada perubahan.
                        if (adaPerubahan) {
                            this.tickSepi = 0;
                            this.jeda = 10000;
                        } else if (++this.tickSepi >= 30) {
                            this.jeda = 30000;
                        }
                    } catch (e) {
                        if (++this.gagalBerturut >= 3) this.jeda = 60000;
                    }
                },

                terapkanAngka(angka) {
                    let berubah = false;

                    Object.keys(angka).forEach((kunci) => {
                        const nilai = angka[kunci];

                        document.querySelectorAll('[data-live="' + kunci + '"]').forEach((el) => {
                            if (el.textContent.trim() === String(nilai)) return;

                            el.textContent = nilai;
                            berubah = true;

                            el.classList.remove('boma-kedip');
                            void el.offsetWidth;
                            el.classList.add('boma-kedip');

                            if (el.hasAttribute('data-live-sembunyi-nol')) {
                                el.style.display = Number(nilai) > 0 ? '' : 'none';
                            }
                        });

                        document.querySelectorAll('[data-live-lebar="' + kunci + '"]').forEach((el) => {
                            el.style.width = Number(nilai) + '%';
                        });
                    });

                    return berubah;
                },
            });

            Alpine.data('bomaShell', function () {
                return {
                    sidebarOpen: false,
                    sidebarCollapsed: false,

                    init() {
                        try {
                            this.sidebarCollapsed = localStorage.getItem('boma_sidebar_collapsed') === '1';
                        } catch (e) {}

                        this.$watch('sidebarCollapsed', function (val) {
                            try { localStorage.setItem('boma_sidebar_collapsed', val ? '1' : '0'); } catch (e) {}
                            setTimeout(function () { window.dispatchEvent(new Event('resize')); }, 320);
                        });

                        var self = this;
                        window.addEventListener('resize', function () {
                            if (window.innerWidth >= 1024 && self.sidebarOpen) self.sidebarOpen = false;
                        });
                        window.addEventListener('keydown', function (e) {
                            if (e.key === 'Escape') self.sidebarOpen = false;
                        });

                        Alpine.store('live').mulai();
                    },
                };
            });
        });
    </script>
</head>

<body class="bg-zinc-950 font-sans text-zinc-300 antialiased selection:bg-red-600 selection:text-white {{ $isProduksi ? 'has-bottomnav' : '' }}"
      x-data="bomaShell()"
      :class="{ 'is-open': sidebarOpen, 'is-collapsed': sidebarCollapsed }">

<div x-cloak
     x-show="sidebarOpen"
     x-transition.opacity.duration.200ms
     @click="sidebarOpen = false"
     class="boma-overlay"
     aria-hidden="true"></div>

<aside class="boma-sidebar border-r border-zinc-800 bg-zinc-900 shadow-2xl">

    <div class="boma-sidebar-head border-b border-zinc-800">
        <a href="#" class="boma-brand text-xl font-black tracking-widest text-white">
            BOMA <span class="text-red-600">SYS</span>
        </a>

        <button type="button"
                @click="sidebarCollapsed = !sidebarCollapsed"
                class="boma-icon-btn boma-only-desktop"
                :title="sidebarCollapsed ? 'Perbesar Menu' : 'Perkecil Menu'"
                :aria-label="sidebarCollapsed ? 'Perbesar Menu' : 'Perkecil Menu'">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <button type="button"
                @click="sidebarOpen = false"
                class="boma-icon-btn boma-only-mobile"
                aria-label="Tutup menu">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="boma-nav custom-scrollbar">
        @foreach($menuSections as $section)
            <p class="boma-section">{{ $section['label'] }}</p>
            <div class="boma-divider"></div>

            @foreach($section['items'] as $item)
                @php
                    $isActive = request()->routeIs($item['match']) && (!isset($item['except']) || !request()->routeIs($item['except']));
                    $badge    = $item['badge'] ?? 0;
                    $liveKey  = $item['live'] ?? null;
                @endphp
                <a href="{{ route($item['route']) }}"
                   @click="if (window.innerWidth < 1024) sidebarOpen = false"
                   class="boma-nav-link {{ $isActive ? $navActive : $navIdle }}"
                   :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''">

                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>

                    <span class="boma-label">{{ $item['label'] }}</span>

                    @if($liveKey)
                        <span class="boma-badge rounded-full bg-red-600 px-2 py-0.5 text-xs font-bold text-white"
                              data-live="{{ $liveKey }}"
                              data-live-sembunyi-nol
                              @if($badge < 1) style="display:none" @endif>{{ $badge }}</span>

                        <span class="boma-dot">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                        </span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>
</aside>

<div class="boma-content">

    <header class="boma-header border-b border-zinc-800 bg-zinc-950/85 backdrop-blur-md">

        <button type="button"
                @click="sidebarOpen = true"
                class="boma-icon-btn boma-only-mobile"
                aria-label="Buka menu">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <p class="min-w-0 flex-1 truncate text-xs font-medium text-zinc-500">
            <span class="hidden sm:inline">{{ $breadcrumbRoot }}</span>
            <span class="mx-1 hidden text-zinc-700 sm:inline">/</span>
            <span class="font-semibold text-zinc-200">{{ $breadcrumbPage }}</span>
        </p>

        <div class="flex shrink-0 items-center">

            <div x-data="{ openNotif: false }" class="boma-notif">

                <button type="button"
                        @click="openNotif = !openNotif"
                        class="boma-icon-btn"
                        style="position: relative;"
                        aria-label="Notifikasi">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['bell'] }}"/></svg>
                    <span x-cloak x-show="$store.live.notifTotal > 0" style="position:absolute;top:.375rem;right:.375rem;display:flex;width:.5rem;height:.5rem;">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                    </span>
                </button>

                <div x-cloak
                     x-show="openNotif"
                     @click.outside="openNotif = false"
                     @keydown.escape.window="openNotif = false"
                     x-transition.opacity.duration.150ms
                     class="boma-notif-panel rounded-2xl border border-zinc-800 bg-zinc-900 shadow-2xl">

                    <div class="boma-notif-head flex items-center justify-between border-b border-zinc-800 bg-zinc-950/60 px-4 py-3">
                        <h3 class="text-sm font-bold text-white">Laporan Aktivitas</h3>
                        <span x-cloak x-show="$store.live.notifTotal > 0"
                              class="shrink-0 rounded-full bg-red-600 px-2 py-0.5 text-xs font-bold text-white"
                              x-text="$store.live.notifTotal + ' Notifikasi'"></span>
                    </div>

                    <div class="boma-notif-list custom-scrollbar">
                        <template x-for="(notif, index) in $store.live.notifItems" :key="index">
                            <a :href="notif.link" class="flex items-start border-b border-zinc-800 px-4 py-3 transition hover:bg-zinc-800">
                                <span class="mr-3 mt-0.5 shrink-0 rounded-full bg-red-500/20 p-2">
                                    <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['clock'] }}"/></svg>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="flex items-start justify-between">
                                        <span class="truncate pr-2 text-sm font-bold text-white" x-text="notif.title"></span>
                                        <span class="shrink-0 whitespace-nowrap font-mono text-xs text-zinc-500" x-text="notif.waktu"></span>
                                    </span>
                                    <span class="mt-1 block text-xs leading-relaxed text-zinc-400" x-text="notif.desc"></span>
                                </span>
                            </a>
                        </template>

                        <template x-if="$store.live.notifTotal === 0">
                            <div class="px-4 py-8 text-center text-sm text-zinc-500">Belum ada aktivitas baru saat ini.</div>
                        </template>
                    </div>
                </div>
            </div>

            <div x-data="{ openProfile: false }" style="position: relative;">
                <button type="button"
                        @click="openProfile = !openProfile"
                        class="flex items-center rounded-xl p-1 transition hover:bg-zinc-800">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/'.Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=dc2626&color=ffffff' }}"
                         alt="Foto profil"
                         class="h-8 w-8 shrink-0 rounded-full border border-zinc-700 object-cover md:h-10 md:w-10">
                    <span class="ml-3 hidden min-w-0 text-right md:block">
                        <span class="block max-w-40 truncate text-sm font-bold uppercase text-white">{{ Auth::user()->name }}</span>
                        <span class="block text-xs font-semibold uppercase tracking-widest text-red-500">{{ Auth::user()->role }}</span>
                    </span>
                    <svg class="ml-2 hidden h-4 w-4 shrink-0 text-zinc-500 md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-cloak
                     x-show="openProfile"
                     @click.outside="openProfile = false"
                     @keydown.escape.window="openProfile = false"
                     x-transition.opacity.duration.150ms
                     class="boma-menu overflow-hidden rounded-xl border border-zinc-800 bg-zinc-900 shadow-2xl">

                    <div class="border-b border-zinc-800 px-4 py-3 md:hidden">
                        <p class="truncate text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs uppercase tracking-widest text-red-500">{{ Auth::user()->role }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-zinc-300 transition hover:bg-zinc-800 hover:text-white">
                        <svg class="mr-2 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['user'] }}"/></svg>
                        Profil Saya
                    </a>

                    <div class="border-t border-zinc-800"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center px-4 py-3 text-sm text-red-500 transition hover:bg-red-500/10">
                            <svg class="mr-2 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['logout'] }}"/></svg>
                            Keluar Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="boma-main">
        {{ $slot }}
    </main>
</div>

{{-- Banner: muncul saat isi daftar/tabel di server sudah berbeda dengan yang ter-render --}}
<div x-cloak
     x-show="$store.live.perluMuatUlang"
     x-transition.opacity.duration.200ms
     class="boma-banner rounded-xl border border-red-500/50 bg-zinc-900 px-4 py-3 shadow-2xl">

    <span class="shrink-0 rounded-full bg-red-500/20 p-2">
        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['refresh'] }}"/></svg>
    </span>

    <p class="min-w-0 flex-1 text-xs text-zinc-300 sm:text-sm">
        Ada data baru di halaman ini.
    </p>

    <button type="button"
            @click="window.location.reload()"
            class="shrink-0 rounded-lg bg-red-600 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-red-700">
        Muat Ulang
    </button>

    <button type="button"
            @click="$store.live.perluMuatUlang = false"
            class="boma-icon-btn"
            aria-label="Tutup">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

{{-- Toast notifikasi baru --}}
<div x-cloak
     x-show="$store.live.toast.tampil"
     x-transition.opacity.duration.300ms
     class="boma-toast rounded-xl border border-red-500 bg-zinc-900 px-4 py-3 shadow-2xl">

    <span class="shrink-0 animate-pulse rounded-full bg-red-500/20 p-2">
        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['bell'] }}"/></svg>
    </span>

    <div class="min-w-0 flex-1">
        <h4 class="truncate text-sm font-black text-red-400" x-text="$store.live.toast.title"></h4>
        <p class="mt-1 text-xs text-zinc-300" x-text="$store.live.toast.desc"></p>
    </div>

    <button type="button" @click="$store.live.toast.tampil = false" class="boma-icon-btn" aria-label="Tutup">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

@if($isProduksi)
    <nav class="boma-bottomnav border-t border-zinc-800 bg-zinc-900">
        <a href="{{ route('produksi.index') }}" class="flex h-full w-full flex-col items-center justify-center {{ request()->routeIs('produksi.index') ? 'text-orange-500' : 'text-zinc-500' }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['home'] }}"/></svg>
            <span class="mt-1 text-xs font-bold">Beranda</span>
        </a>
        <a href="{{ route('produksi.tugas') }}" class="flex h-full w-full flex-col items-center justify-center {{ request()->routeIs('produksi.tugas') ? 'text-orange-500' : 'text-zinc-500' }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ico['clipboard'] }}"/></svg>
            <span class="mt-1 text-xs font-bold">Tugas &amp; IM</span>
        </a>
    </nav>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.form-hapus').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                var pesanPeringatan = this.getAttribute('data-pesan') || 'Data yang dihapus tidak dapat dikembalikan!';

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: pesanPeringatan,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#3f3f46',
                    confirmButtonText: 'Ya, Eksekusi!',
                    cancelButtonText: 'Batal',
                    background: '#18181b',
                    color: '#f4f4f5',
                    customClass: { popup: 'border border-zinc-800 rounded-2xl shadow-2xl' },
                }).then(function (result) {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
</body>
</html>