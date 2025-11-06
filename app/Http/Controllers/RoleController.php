<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //  Tampilkan semua role
    public function index()
    {
        // Ambil data dari VIEW role_vu (ada kolom total_user)
        $rows = DB::select("SELECT * FROM role_vu ORDER BY idrole ASC");
        return view('role.index', compact('rows'));
    }

    //  Tambah role baru
    public function store(Request $r)
    {
        $r->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        DB::insert("INSERT INTO role (nama_role) VALUES (?)", [$r->nama_role]);

        return redirect('/role')->with('ok', '✅ Role baru berhasil ditambahkan.');
    }

    // Update data role
    public function update($id, Request $r)
    {
        $r->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        DB::update("
            UPDATE role 
            SET nama_role = ? 
            WHERE idrole = ?
        ", [$r->nama_role, $id]);

        return redirect('/role')->with('ok', '✏️ Role berhasil diperbarui.');
    }

    // Hapus data role
    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM role WHERE idrole = ?", [$id]);
            return redirect('/role')->with('ok', '🗑️ Role berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect('/role')->withErrors([
                'msg' => '⚠️ Role tidak dapat dihapus karena masih digunakan oleh user.'
            ]);
        }
    }
}
