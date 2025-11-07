<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    // 🧭 Menampilkan semua satuan
    public function index()
    {
        $rows = DB::select("SELECT * FROM satuan ORDER BY idsatuan DESC");
        return view('satuan.index', compact('rows'));
    }

    // ➕ Tambah satuan baru
    public function store(Request $r)
    {
        $r->validate([
            'nama_satuan' => 'required|string|max:45',
            'status' => 'required|in:0,1'
        ]);

        DB::insert("
            INSERT INTO satuan (nama_satuan, status)
            VALUES (?, ?)
        ", [
            $r->nama_satuan,
            $r->status
        ]);

        return redirect('/satuan')->with('ok', '✅ Satuan berhasil ditambahkan.');
    }

    // ✏️ Update satuan
    public function update($id, Request $r)
    {
        $r->validate([
            'nama_satuan' => 'required|string|max:45',
            'status' => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE satuan
            SET nama_satuan=?, status=?
            WHERE idsatuan=?
        ", [
            $r->nama_satuan,
            $r->status,
            $id
        ]);

        return redirect('/satuan')->with('ok', '✏️ Satuan berhasil diperbarui.');
    }

    // 🗑️ Hapus satuan
    public function delete($id)
    {
        DB::delete("DELETE FROM satuan WHERE idsatuan=?", [$id]);
        return redirect('/satuan')->with('ok', '🗑️ Satuan berhasil dihapus.');
    }
}
