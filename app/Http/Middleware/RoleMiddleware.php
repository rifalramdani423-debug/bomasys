<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Trik Pamungkas: Jika Laravel membaca koma sebagai 1 kalimat utuh, kita pecah paksa!
        if (count($roles) === 1 && strpos($roles[0], ',') !== false) {
            $roles = explode(',', $roles[0]);
        }

        // Bersihkan spasi dan jadikan huruf kecil semua agar cocok 100%
        $userRole = strtolower(trim(Auth::user()->role));
        $allowedRoles = array_map(function($r) { 
            return strtolower(trim($r)); 
        }, $roles);

        if (!in_array($userRole, $allowedRoles)) {
            // Pesan detektif: Akan memunculkan data aslinya jika masih diblokir
            abort(403, 'AKSES DITOLAK. Role Akun Anda: [' . $userRole . '] | Role yg Diizinkan: [' . implode(', ', $allowedRoles) . ']');
        }

        return $next($request);
    }
}