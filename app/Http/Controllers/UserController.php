<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 🧭 Tampilkan semua user
    public function index()
    {
        // ambil semua user + nama role
        $rows = DB::select("
            SELECT u.*, r.nama_role 
            FROM user u 
            JOIN role r ON r.idrole = u.idrole 
            ORDER BY u.iduser DESC
        ");

        // ambil semua role untuk dropdown
        $roles = DB::select("SELECT * FROM role ORDER BY nama_role ASC");

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

        return redirect('/users')->with('ok', '✅ User berhasil ditambahkan.');
    }

    // ✏️ Update user
    public function update($id, Request $r)
    {
        $r->validate([
            'username' => 'required|string|max:45',
            'email' => 'nullable|email|max:100',
            'idrole' => 'required|integer',
            'status' => 'required|in:0,1',
            'password' => 'nullable|string|min:4|max:100'
        ]);

        // Jika password diisi, update semua kolom
        if ($r->password) {
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
        } 
        // Jika password kosong, jangan ubah password
        else {
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

        return redirect('/users')->with('ok', '✏️ User berhasil diperbarui.');
    }

    // 🗑️ Hapus user
    public function delete($id)
    {
        DB::delete("DELETE FROM user WHERE iduser=?", [$id]);
        return redirect('/users')->with('ok', '🗑️ User berhasil dihapus.');
    }
}
