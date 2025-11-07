<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        // Ambil semua data barang
        $rows = DB::select("
            SELECT b.*, s.nama_satuan
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
            ORDER BY b.idbarang DESC
        ");

        // Ambil semua data satuan untuk dropdown
        $satuan = DB::select("SELECT * FROM satuan ORDER BY nama_satuan ASC");

        // Kirim ke view
        return view('barang.index', compact('rows', 'satuan'));
    }

    public function store(Request $r)
    {
        DB::insert("
            INSERT INTO barang (jenis, nama, idsatuan, harga, status)
            VALUES (?, ?, ?, ?, ?)
        ", [
            $r->jenis,
            $r->nama,
            $r->idsatuan,
            $r->harga,
            $r->status
        ]);

        return back()->with('ok', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $r, $id)
    {
        DB::update("
            UPDATE barang
            SET jenis=?, nama=?, idsatuan=?, harga=?, status=?
            WHERE idbarang=?
        ", [
            $r->jenis,
            $r->nama,
            $r->idsatuan,
            $r->harga,
            $r->status,
            $id
        ]);

        return back()->with('ok', 'Barang berhasil diperbarui!');
    }

    public function delete($id)
    {
        DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);
        return back()->with('ok', 'Barang berhasil dihapus!');
    }
}
