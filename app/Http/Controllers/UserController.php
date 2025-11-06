<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //  Daftar semua user (pakai VIEW)
    public function index()
    {
        $rows = DB::select("SELECT * FROM user_vu ORDER BY iduser DESC");
        $roles = DB::select("SELECT idrole, nama_role FROM role ORDER BY nama_role");
        return view('user.index', compact('rows', 'roles'));
    }

    //  Tambah user baru
    public function store(Request $r)
    {
        $r->validate([
            'username' => 'required|string|max:45',
            'email'    => 'nullable|email|max:100',
            'password' => 'required|string|min:4|max:100',
            'idrole'   => 'required|integer',
            'status'   => 'required|in:0,1'
        ]);

        $exists = DB::selectOne("SELECT iduser FROM user WHERE username = ? LIMIT 1", [$r->username]);
        if ($exists) {
            return back()->withErrors(['username' => 'Username sudah dipakai.'])->withInput();
        }

        $hash = Hash::make($r->password);
        DB::insert("
            INSERT INTO user (username, email, password, idrole, status)
            VALUES (?, ?, ?, ?, ?)
        ", [$r->username, $r->email, $hash, $r->idrole, $r->status]);

        return redirect('/users')->with('ok', '✅ User baru berhasil ditambahkan.');
    }

    //  Update user
    public function update($id, Request $r)
    {
        $r->validate([
            'username' => 'required|string|max:45',
            'email'    => 'nullable|email|max:100',
            'idrole'   => 'required|integer',
            'status'   => 'required|in:0,1',
            'password' => 'nullable|string|min:4|max:100'
        ]);

        $dup = DB::selectOne("SELECT iduser FROM user WHERE username = ? AND iduser <> ? LIMIT 1", [$r->username, $id]);
        if ($dup) {
            return back()->withErrors(['username' => 'Username sudah digunakan oleh user lain.'])->withInput();
        }

        if ($r->filled('password')) {
            $hash = Hash::make($r->password);
            DB::update("
                UPDATE user
                SET username=?, email=?, password=?, idrole=?, status=?
                WHERE iduser=?
            ", [$r->username, $r->email, $hash, $r->idrole, $r->status, $id]);
        } else {
            DB::update("
                UPDATE user
                SET username=?, email=?, idrole=?, status=?
                WHERE iduser=?
            ", [$r->username, $r->email, $r->idrole, $r->status, $id]);
        }

        return redirect('/users')->with('ok', '✏️ User berhasil diperbarui.');
    }

    // Hapus user
    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM user WHERE iduser=?", [$id]);
            return redirect('/users')->with('ok', '🗑️ User berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect('/users')->withErrors(['msg' => '⚠️ User tidak dapat dihapus karena masih digunakan di transaksi.']);
        }
    }
}
