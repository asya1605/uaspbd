<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    //  Tampilkan semua satuan
    public function index()
    {
        // Ambil data dari VIEW satuan_vu (sudah ada kolom status_text)
        $rows = DB::select("SELECT * FROM satuan_vu ORDER BY idsatuan DESC");
        return view('satuan.index', compact('rows'));
    }

    //  Tambah data baru
    public function store(Request $r)
    {
        $r->validate([
            'nama_satuan' => 'required|string|max:100',
            'status'      => 'required|in:0,1'
        ]);

        DB::insert("
            INSERT INTO satuan (nama_satuan, status)
            VALUES (?, ?)
        ", [$r->nama_satuan, $r->status]);

        return redirect('/satuan')->with('ok', '✅ Satuan baru berhasil ditambahkan.');
    }

    // Update data satuan
    public function update($id, Request $r)
    {
        $r->validate([
            'nama_satuan' => 'required|string|max:100',
            'status'      => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE satuan
            SET nama_satuan = ?, status = ?
            WHERE idsatuan = ?
        ", [$r->nama_satuan, $r->status, $id]);

        return redirect('/satuan')->with('ok', '✏️ Data satuan berhasil diperbarui.');
    }

    // Hapus data satuan
    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM satuan WHERE idsatuan = ?", [$id]);
            return redirect('/satuan')->with('ok', '🗑️ Satuan berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect('/satuan')
                ->withErrors(['msg' => '⚠️ Data satuan tidak dapat dihapus karena digunakan di tabel lain.']);
        }
    }
}
