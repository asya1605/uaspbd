<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //  Tampilkan form login
    public function form()
    {
        return view('auth.login');
    }

    //  Proses login (tanpa bcrypt)
    public function login(Request $r)
    {
        $r->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        //  Ambil data user beserta role-nya
        $user = DB::selectOne("
            SELECT u.iduser, u.username, u.password, u.idrole,
                   COALESCE(r.nama_role, 'Unknown') AS nama_role,
                   u.status
            FROM user u
            LEFT JOIN role r ON r.idrole = u.idrole
            WHERE u.username = ?
            LIMIT 1
        ", [$r->username]);

        //  Username tidak ditemukan
        if (!$user) {
            return back()->withErrors(['error' => 'Username tidak ditemukan.']);
        }

        // Password salah (tanpa hash)
        if ($r->password !== $user->password) {
            return back()->withErrors(['error' => 'Password salah.']);
        }

        //  Akun dinonaktifkan
        if (isset($user->status) && $user->status != 1) {
            return back()->withErrors(['error' => 'Akun dinonaktifkan.']);
        }

        //  Simpan data user ke session
        $r->session()->put('user', [
            'iduser'   => $user->iduser,
            'username' => $user->username,
            'idrole'   => $user->idrole ?? null,
            'role'     => strtolower($user->nama_role ?? 'unknown'),
        ]);

        //  Redirect ke dashboard
        return redirect()->route('dashboard');
    }

    // Dashboard ringkasan data
    public function dashboard()
    {
        $counts = [
            'role'   => DB::table('role')->count(),
            'user'   => DB::table('user')->count(),
            'vendor' => DB::table('vendor')->count(),
            'satuan' => DB::table('satuan')->count(),
            'barang' => DB::table('barang')->count(),
        ];

        $me = session('user');
        $username = $me['username'] ?? 'User';

        return view('dashboard.index', compact('counts', 'username'));
    }

    //  Logout
    public function logout(Request $r)
    {
        $r->session()->forget('user');
        return redirect()->route('login.form');
    }
}
