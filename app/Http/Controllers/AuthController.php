<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // 🔐 Form login
    public function form()
    {
        return view('auth.login');
    }

    // 🔑 Proses login (tanpa bcrypt)
    public function login(Request $r)
    {
        $r->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 🧩 Ambil data user via raw SQL
        $user = DB::selectOne("
            SELECT u.iduser, u.username, u.password, u.idrole,
                   COALESCE(r.nama_role, 'Unknown') AS nama_role,
                   u.status
            FROM user u
            LEFT JOIN role r ON r.idrole = u.idrole
            WHERE u.username = ?
            LIMIT 1
        ", [$r->username]);

        if (!$user) {
            return back()->withErrors(['error' => 'Username tidak ditemukan.']);
        }

        if ($r->password !== $user->password) {
            return back()->withErrors(['error' => 'Password salah.']);
        }

        if (isset($user->status) && $user->status != 1) {
            return back()->withErrors(['error' => 'Akun dinonaktifkan.']);
        }

        // 🧠 Simpan user ke session manual
        $r->session()->put('user', [
            'iduser'   => $user->iduser,
            'username' => $user->username,
            'idrole'   => $user->idrole ?? null,
            'role'     => strtolower($user->nama_role ?? 'unknown'),
        ]);

        return redirect()->route('dashboard');
    }

    // 🩷 Dashboard
    public function dashboard()
    {
        $counts = [
            'role'   => DB::selectOne("SELECT COUNT(*) AS c FROM role")->c,
            'user'   => DB::selectOne("SELECT COUNT(*) AS c FROM user")->c,
            'vendor' => DB::selectOne("SELECT COUNT(*) AS c FROM vendor")->c,
            'satuan' => DB::selectOne("SELECT COUNT(*) AS c FROM satuan")->c,
            'barang' => DB::selectOne("SELECT COUNT(*) AS c FROM barang")->c,
        ];

        $me = session('user');
        $username = $me['username'] ?? 'User';

        return view('dashboard.index', compact('counts', 'username'));
    }

    // 🚪 Logout
    public function logout(Request $r)
    {
        $r->session()->forget('user');
        return redirect()->route('login.form');
    }
}
