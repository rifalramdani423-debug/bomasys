<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ==========================================
// ROBOT BOMA SYS: EKSEKUSI JADWAL OTOMATIS
// ==========================================
Schedule::command('bomasys:jadwal-otomatis')->dailyAt('08:00');

// 2. Robot Pengingat Tagihan Termin Klien (Jam 07:00 Pagi)
Schedule::command('bomasys:pengingat-tagihan')->dailyAt('07:00');


//jika ingin menyalakan di sidang taru code " php artisan schedule:work " di terminal