<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 🧭 Tampilkan semua user
    public function index()
    {
        $rows = DB::select("
            SELECT u.iduser, u.username, u.email, u.password, u.idrole, u.status, r.nama_role
            FROM user u
            JOIN role r ON r.idrole = u.idrole
            ORDER BY u.iduser DESC
        ");

        $roles = DB::select("SELECT idrole, nama_role FROM role ORDER BY nama_role ASC");

        return view('user.index', compact('rows', 'roles'));
    }

    // ➕ Tambah user baru
    public function store(Request $r)
    {
        $r->validate([
            'username' => 'required|string|max:45',
            'email' => 'nullable|email|max:100',
            'password' => 'required|string|min:4|max:100',
            'idrole' => 'required|integer',
            'status' => 'required|in:0,1'
        ]);

        DB::insert("
            INSERT INTO user (username, email, password, idrole, status)
            VALUES (?, ?, ?, ?, ?)
        ", [
            $r->username,
            $r->email,
            $r->password,
            $r->idrole,
            $r->status
        ]);

        return redirect()->route('users.index')->with('ok', '✅ User berhasil ditambahkan.');
    }

    // ✏️ Update user
    public function update(Request $r, $id)
    {
        $r->validate([
            'username' => 'required|string|max:45',
            'email' => 'nullable|email|max:100',
            'idrole' => 'required|integer',
            'status' => 'required|in:0,1',
            'password' => 'nullable|string|min:4|max:100'
        ]);

        if ($r->filled('password')) {
            DB::update("
                UPDATE user 
                SET username=?, email=?, password=?, idrole=?, status=? 
                WHERE iduser=?
            ", [
                $r->username,
                $r->email,
                $r->password,
                $r->idrole,
                $r->status,
                $id
            ]);
        } else {
            DB::update("
                UPDATE user 
                SET username=?, email=?, idrole=?, status=? 
                WHERE iduser=?
            ", [
                $r->username,
                $r->email,
                $r->idrole,
                $r->status,
                $id
            ]);
        }

        return redirect()->route('users.index')->with('ok', '✏️ User berhasil diperbarui.');
    }

    // 🗑️ Hapus user
    public function delete($id)
    {
        DB::delete("DELETE FROM user WHERE iduser=?", [$id]);
        return redirect()->route('users.index')->with('ok', '🗑️ User berhasil dihapus.');
    }

    // 🔍 (opsional) check username/email duplicate
    public function check(Request $r)
    {
        if (!$r->filled('username') && !$r->filled('email')) {
            return response()->json(['found' => false]);
        }

        $query = DB::table('user');
        if ($r->filled('username')) {
            $query->where('username', $r->username);
        }
        if ($r->filled('email')) {
            $query->orWhere('email', $r->email);
        }

        $exists = $query->first();
        return response()->json(['found' => (bool) $exists]);
    }
}
