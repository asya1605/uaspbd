<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * 📋 Tampilkan daftar barang dengan filter aktif / semua
     */
    public function index(Request $r)
    {
        $filter = $r->query('filter', 'aktif');

        if ($filter === 'aktif') {
            $rows = DB::select("
                SELECT b.*, s.nama_satuan
                FROM barang b
                JOIN satuan s ON s.idsatuan = b.idsatuan
                WHERE b.status = 1
                ORDER BY b.idbarang DESC
            ");
        } else {
            $rows = DB::select("
                SELECT b.*, s.nama_satuan
                FROM barang b
                JOIN satuan s ON s.idsatuan = b.idsatuan
                ORDER BY b.idbarang DESC
            ");
        }

        $satuan = DB::select("SELECT * FROM satuan ORDER BY nama_satuan ASC");
        return view('barang.index', compact('rows', 'satuan', 'filter'));
    }

    /**
     * ➕ Tambah barang baru
     */
    public function store(Request $r)
    {
        $r->validate([
            'jenis'    => 'required|string|max:50',
            'nama'     => 'required|string|max:255',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|numeric|min:0',
        ]);

        DB::insert("
            INSERT INTO barang (jenis, nama, idsatuan, harga, status)
            VALUES (?, ?, ?, ?, 1)
        ", [
            $r->jenis,
            $r->nama,
            $r->idsatuan,
            $r->harga,
        ]);

        return redirect()->route('barang.index')->with('ok', '✅ Barang baru berhasil ditambahkan!');
    }

    /**
     * ✏️ Update data barang (termasuk ubah status aktif/nonaktif)
     */
    public function update(Request $r, $id)
    {
        $r->validate([
            'jenis'    => 'required|string|max:50',
            'nama'     => 'required|string|max:255',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|numeric|min:0',
            'status'   => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE barang
            SET jenis = ?, nama = ?, idsatuan = ?, harga = ?, status = ?
            WHERE idbarang = ?
        ", [
            $r->jenis,
            $r->nama,
            $r->idsatuan,
            $r->harga,
            $r->status,
            $id
        ]);

        return redirect()->route('barang.index')->with('ok', '✏️ Barang berhasil diperbarui!');
    }

    /**
     * 🗑️ Hapus barang
     */
    public function delete($id)
    {
        DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);
        return redirect()->route('barang.index')->with('ok', '🗑️ Barang berhasil dihapus!');
    }
}
