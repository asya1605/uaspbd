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

    // ➕ Tambah role baru
    public function store(Request $r)
    {
        $r->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        // Cek duplikat role
        $exists = DB::selectOne("SELECT * FROM role WHERE LOWER(nama_role) = LOWER(?)", [$r->nama_role]);
        if ($exists) {
            return back()->withErrors(['error' => '⚠️ Role sudah terdaftar!']);
        }

        DB::insert("INSERT INTO role (nama_role) VALUES (?)", [$r->nama_role]);

        return redirect()->route('role.index')->with('ok', '✅ Role berhasil ditambahkan.');
    }

    // ✏️ Update role
    public function update(Request $r, $id)
    {
        $r->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        // Cek duplikat nama role lain
        $exists = DB::selectOne("
            SELECT * FROM role WHERE LOWER(nama_role) = LOWER(?) AND idrole != ?
        ", [$r->nama_role, $id]);

        if ($exists) {
            return back()->withErrors(['error' => '⚠️ Nama role sudah digunakan!']);
        }

        DB::update("UPDATE role SET nama_role=? WHERE idrole=?", [$r->nama_role, $id]);

        return redirect()->route('role.index')->with('ok', '✏️ Role berhasil diperbarui.');
    }

    // 🗑️ Hapus role
    public function delete($id)
    {
        DB::delete("DELETE FROM role WHERE idrole=?", [$id]);

        return redirect()->route('role.index')->with('ok', '🗑️ Role berhasil dihapus.');
    }

    // 🔍 AJAX cek duplikat role (opsional)
    public function check(Request $r)
    {
        if (!$r->filled('nama_role')) {
            return response()->json(['found' => false]);
        }

        $exists = DB::table('role')
            ->whereRaw('LOWER(nama_role) = ?', [strtolower($r->nama_role)])
            ->exists();

        return response()->json(['found' => $exists]);
    }
}
