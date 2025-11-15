<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    /**
     * 📋 Tampilkan semua data satuan (aktif / semua)
     */
    public function index(Request $r)
    {
        $filter = $r->query('filter', 'aktif');

        if ($filter === 'aktif') {
            $rows = DB::select("
                SELECT idsatuan, nama_satuan, status
                FROM satuan
                WHERE status = 1
                ORDER BY idsatuan DESC
            ");
        } else {
            $rows = DB::select("
                SELECT idsatuan, nama_satuan, status
                FROM satuan
                ORDER BY idsatuan DESC
            ");
        }

        return view('satuan.index', compact('rows', 'filter'));
    }

    /**
     * ➕ Tambah satuan baru
     */
    public function store(Request $r)
    {
        $r->validate([
            'nama_satuan' => 'required|string|max:45',
        ]);

        DB::insert("
            INSERT INTO satuan (nama_satuan, status)
            VALUES (?, 1)
        ", [$r->nama_satuan]);

        return redirect()->route('satuan.index')->with('ok', '✅ Satuan baru berhasil ditambahkan.');
    }

    /**
     * ✏️ Update satuan
     */
    public function update(Request $r, $id)
    {
        $r->validate([
            'nama_satuan' => 'required|string|max:45',
            'status' => 'required|in:0,1',
        ]);

        DB::update("
            UPDATE satuan
            SET nama_satuan = ?, status = ?
            WHERE idsatuan = ?
        ", [$r->nama_satuan, $r->status, $id]);

        return redirect()->route('satuan.index')->with('ok', '✏️ Data satuan berhasil diperbarui.');
    }

    /**
     * 🗑️ Hapus satuan
     */
    public function delete($id)
    {
        DB::delete("DELETE FROM satuan WHERE idsatuan = ?", [$id]);
        return redirect()->route('satuan.index')->with('ok', '🗑️ Satuan berhasil dihapus.');
    }
}
