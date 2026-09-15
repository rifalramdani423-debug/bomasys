<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil role user (di-lowercase agar aman)
        $role = strtolower($request->user()->role);

        // Arahkan admin dan superadmin ke dashboard admin
        if ($role === 'admin' || $role === 'superadmin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'keuangan') {
            return redirect()->route('keuangan.dashboard');
        } elseif ($role === 'aset') {
            return redirect()->route('aset.index');
        } elseif ($role === 'produksi') {
            return redirect()->route('produksi.index');
        } elseif ($role === 'gm') {
            return redirect()->route('gm.index');
        } 
        
        // Default (klien)
        return redirect()->route('klien.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}