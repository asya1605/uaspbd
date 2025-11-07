<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // 🧭 Menampilkan semua role
    public function index()
    {
        $rows = DB::select("SELECT * FROM role ORDER BY idrole ASC");
        return view('role.index', compact('rows'));
    }

    // ➕ Menambah role baru
    public function store(Request $r)
    {
        $r->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        DB::insert("INSERT INTO role (nama_role) VALUES (?)", [$r->nama_role]);

        return redirect('/role')->with('ok', '✅ Role berhasil ditambahkan.');
    }

    // ✏️ Mengubah role
    public function update($id, Request $r)
    {
        $r->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        DB::update("UPDATE role SET nama_role=? WHERE idrole=?", [$r->nama_role, $id]);

        return redirect('/role')->with('ok', '✏️ Role berhasil diperbarui.');
    }

    // 🗑️ Menghapus role
    public function delete($id)
    {
        DB::delete("DELETE FROM role WHERE idrole=?", [$id]);

        return redirect('/role')->with('ok', '🗑️ Role berhasil dihapus.');
    }
}
