<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function form()
    {
        // tampilan form login
        return view('auth.login');
    }

    public function login(Request $r)
    {
        // cari user berdasarkan username
        $user = DB::selectOne("
            SELECT u.iduser, u.username, u.password, u.idrole, r.nama_role
            FROM user u
            LEFT JOIN role r ON r.idrole = u.idrole
            WHERE u.username = ?
            LIMIT 1
        ", [$r->username]);

        // jika user tidak ditemukan
        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan.');
        }

        // (opsional) cek password
        if ($r->password !== $user->password) {
            return back()->with('error', 'Password salah.');
        }

        // simpan ke session
        $r->session()->put('user', [
            'iduser'   => $user->iduser,
            'username' => $user->username,
            'idrole'   => $user->idrole,
            'role'     => strtolower($user->nama_role), // contoh: 'super_admin' / 'admin'
        ]);

        return redirect('/dashboard');
    }

    public function dashboard()
    {
        // ambil jumlah data master
        $role   = (int) (DB::selectOne("SELECT COUNT(*) c FROM role")->c ?? 0);
        $user   = (int) (DB::selectOne("SELECT COUNT(*) c FROM user")->c ?? 0);
        $vendor = (int) (DB::selectOne("SELECT COUNT(*) c FROM vendor")->c ?? 0);
        $satuan = (int) (DB::selectOne("SELECT COUNT(*) c FROM satuan")->c ?? 0);
        $barang = (int) (DB::selectOne("SELECT COUNT(*) c FROM barang")->c ?? 0);

        $counts = compact('role','user','vendor','satuan','barang');

        $me = session('user');
        $username = $me['username'] ?? 'User';

        return view('dashboard', compact('counts','username'));
    }

    public function logout(Request $r)
    {
        $r->session()->forget('user');
        return redirect('/login');
    }
}
