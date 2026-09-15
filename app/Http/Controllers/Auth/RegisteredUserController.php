<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\KlienBaruMail;
use App\Mail\SelamatDatangKlienMail;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'no_wa' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nama_perusahaan' => $request->nama_perusahaan,
            'no_wa' => $request->no_wa,
            'role' => 'klien',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        try {
            // 1. Kirim Email Sapaan ke Klien (Otomatis ambil dari form yang diisi klien)
            Mail::to($user->email)->send(new SelamatDatangKlienMail($user));

            // 2. Tarik SEMUA email karyawan dengan role admin & superadmin dari database
            $adminEmails = User::whereIn('role', ['admin', 'superadmin'])->pluck('email');
            
            // 3. Kirim Notifikasi ke kumpulan email admin tersebut
            if ($adminEmails->isNotEmpty()) {
                Mail::to($adminEmails)->send(new KlienBaruMail($user));
            }
        } catch (\Exception $e) {
            Log::error('Mail Error: ' . $e->getMessage());
        }

        return redirect()->route('login')->with('status', 'Pendaftaran akun berhasil! Silakan masuk untuk memverifikasi dan melanjutkan.');
    }
}