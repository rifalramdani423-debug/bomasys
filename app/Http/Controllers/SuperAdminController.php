<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SuperAdminController extends Controller
{
    public function indexKaryawan(Request $request)
    {
        $query = User::where('role', '!=', 'klien');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        $karyawan_list = $query->orderBy('role', 'asc')->latest()->get();
        
        $divisi_list = User::where('role', '!=', 'klien')->whereNotNull('divisi')->distinct()->pluck('divisi');

        return view('admin.super.karyawan', compact('karyawan_list', 'divisi_list'));
    }

    /**
     * Menghapus (Soft Delete) akun karyawan.
     */
    public function destroyKaryawan($id)
    {
        $karyawan = User::where('role', '!=', 'klien')->findOrFail($id);
        
        // PROTEKSI KEAMANAN: Super Admin tidak bisa menghapus akunnya sendiri
        if (auth()->id() == $id) {
            return back()->with('error', 'Akses Ditolak: Anda tidak dapat menghapus akun Anda sendiri!');
        }
        
        // Hapus menggunakan fitur SoftDeletes bawaan model User
        $karyawan->delete(); 

        return back()->with('success', 'Akun Karyawan berhasil dinonaktifkan (dipindahkan ke Berkas Sampah).');
    }

    public function storeKaryawan(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'no_wa'    => 'nullable|string|max:20',
            'divisi'   => 'required|string',
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // Mapping otomatis Divisi ke Role & Kode Singkat NIK
        $divisiInput = $request->divisi;
        $role = 'admin';
        $kodeDivisi = 'ADM';

        if (str_contains(strtolower($divisiInput), 'aset')) {
            $role = 'aset';
            $kodeDivisi = 'AST';
        } elseif (str_contains(strtolower($divisiInput), 'keuangan') || str_contains(strtolower($divisiInput), 'finance')) {
            $role = 'keuangan';
            $kodeDivisi = 'FIN';
        } elseif (str_contains(strtolower($divisiInput), 'produksi') || str_contains(strtolower($divisiInput), 'lapangan')) {
            $role = 'produksi';
            $kodeDivisi = 'PRO';
        } elseif (str_contains(strtolower($divisiInput), 'direktur') || str_contains(strtolower($divisiInput), 'gm')) {
            $role = 'gm';
            $kodeDivisi = 'DIR';
        } elseif (str_contains(strtolower($divisiInput), 'super')) {
            $role = 'superadmin';
            $kodeDivisi = 'SPR';
        }

        // Generate Nik Otomatis: BOMA-[DIVISI]-[TAHUN]-[URUT]
        $tahun = date('Y');
        $jumlahUrut = \App\Models\User::where('divisi', $divisiInput)->count() + 1;
        $nomorUrut = str_pad($jumlahUrut, 3, '0', STR_PAD_LEFT);
        $generatedNik = "BOMA-{$kodeDivisi}-{$tahun}-{$nomorUrut}";

        User::create([
            'nik'      => $generatedNik,
            'name'     => $request->name,
            'email'    => $request->email,
            'no_wa'    => $request->no_wa,
            'divisi'   => $request->divisi,
            'role'     => $role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun Karyawan berhasil ditambahkan dengan NIK: ' . $generatedNik);
    }

    public function updateKaryawan(Request $request, $id)
    {
        $karyawan = User::where('role', '!=', 'klien')->findOrFail($id);

        $request->validate([
            'nik'    => 'required|string|max:50|unique:users,nik,' . $id,
            'name'   => 'required|string|max:255',
            'email'  => 'required|string|email|max:255|unique:users,email,' . $id,
            'no_wa'  => 'nullable|string|max:20',
            'divisi' => 'required|string|max:100',
            'role'   => 'required|in:admin,superadmin,keuangan,aset,produksi,gm',
        ]);

        $dataUpdate = [
            'nik'    => $request->nik,
            'name'   => $request->name,
            'email'  => $request->email,
            'no_wa'  => $request->no_wa,
            'divisi' => $request->divisi,
            'role'   => $request->role,
        ];

        if ($request->filled('password')) {
            $dataUpdate['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $karyawan->update($dataUpdate);

        return back()->with('success', 'Data Karyawan berhasil diperbarui!');
    }

    // ==========================================
    // FITUR TONG SAMPAH (SOFT DELETES)
    // ==========================================

    public function sampahIndex()
    {
        // Menarik semua data akun (Karyawan & Klien) yang sudah dihapus sementara (Soft Delete)
        $sampah_list = \App\Models\User::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        // Mengarahkan sistem ke file resources/views/admin/super/sampah-karyawan.blade.php
        return view('admin.super.sampah-karyawan', compact('sampah_list'));
    }

    public function restoreAkun($id)
    {
        // Cari user yang ada di tong sampah berdasarkan ID
        $user = \App\Models\User::withTrashed()->findOrFail($id);
        
        // Kembalikan akun ke sistem
        $user->restore();

        return redirect()->route('superadmin.sampah')->with('success', 'Akun ' . $user->name . ' berhasil dipulihkan dan bisa digunakan kembali!');
    }

    public function forceDeleteAkun($id)
    {
        // Cari user yang ada di tong sampah berdasarkan ID
        $user = \App\Models\User::withTrashed()->findOrFail($id);
        
        $namaAkun = $user->name;
        
        // Hapus secara permanen dari database
        $user->forceDelete();

        return redirect()->route('superadmin.sampah')->with('success', 'Akun ' . $namaAkun . ' telah dihancurkan secara permanen dari database!');
    }
}