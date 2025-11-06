<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    //  Tampilkan semua barang (pakai view barang_vu)
    public function index()
    {
        $rows = DB::select("SELECT * FROM barang_vu ORDER BY idbarang DESC");

        // Ambil satuan aktif dari view satuan_vu
        $satuan = DB::select("
            SELECT idsatuan, nama_satuan 
            FROM satuan_vu
            WHERE status_text = 'Aktif'
            ORDER BY nama_satuan
        ");

        return view('barang.index', compact('rows', 'satuan'));
    }

    // Tambah barang baru
    public function store(Request $r)
    {
        $r->validate([
            'jenis'    => 'required|in:A,B,C',
            'nama'     => 'required|string|max:45',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|numeric|min:0',
            'status'   => 'required|in:0,1',
        ]);

        DB::insert("
            INSERT INTO barang (jenis, nama, idsatuan, harga, status)
            VALUES (?, ?, ?, ?, ?)
        ", [$r->jenis, $r->nama, $r->idsatuan, $r->harga, $r->status]);

        return redirect('/barang')->with('ok', 'Barang berhasil ditambahkan.');
    }

    // Update barang
    public function update($id, Request $r)
    {
        $r->validate([
            'jenis'    => 'required|in:A,B,C',
            'nama'     => 'required|string|max:45',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|numeric|min:0',
            'status'   => 'required|in:0,1',
        ]);

        DB::update("
            UPDATE barang
            SET jenis=?, nama=?, idsatuan=?, harga=?, status=?
            WHERE idbarang=?
        ", [$r->jenis, $r->nama, $r->idsatuan, $r->harga, $r->status, $id]);

        return redirect('/barang')->with('ok', 'Barang berhasil diperbarui.');
    }

    //  Hapus barang
    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM barang WHERE idbarang=?", [$id]);
            return redirect('/barang')->with('ok', 'Barang berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect('/barang')->withErrors([
                'msg' => 'Barang tidak bisa dihapus karena sudah digunakan dalam transaksi.'
            ]);
        }
    }
}
