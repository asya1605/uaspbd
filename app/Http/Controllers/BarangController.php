<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * 🌸 Tampilkan halaman utama Barang
     */
    public function index()
    {
        // Ambil semua data barang + satuan
        $rows = DB::select("
            SELECT b.*, s.nama_satuan
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
            ORDER BY b.idbarang DESC
        ");

        // Ambil semua data satuan untuk dropdown
        $satuan = DB::select("SELECT * FROM satuan ORDER BY nama_satuan ASC");

        return view('barang.index', compact('rows', 'satuan'));
    }

    /**
     * 🌸 Simpan barang baru
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
            VALUES (?, ?, ?, ?, ?)
        ", [
            $r->jenis,
            $r->nama,
            $r->idsatuan,
            $r->harga,
            $r->status ?? 1
        ]);

        return back()->with('ok', 'Barang berhasil ditambahkan!');
    }

    /**
     * 🌸 Update data barang
     */
    public function update(Request $r, $id)
    {
        $r->validate([
            'jenis'    => 'required|string|max:50',
            'nama'     => 'required|string|max:255',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|numeric|min:0',
            'status'   => 'nullable|integer'
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
            $r->status ?? 1,
            $id
        ]);

        return back()->with('ok', 'Barang berhasil diperbarui!');
    }

    /**
     * 🌸 Cek barang duplikat (dipanggil via AJAX)
     */
    public function check(Request $r)
    {
        // Hindari query kosong
        if (!$r->filled(['jenis', 'nama', 'harga'])) {
            return response()->json(['found' => false]);
        }

        $barang = DB::table('barang as b')
            ->join('satuan as s', 'b.idsatuan', '=', 's.idsatuan')
            ->select('b.*', 's.nama_satuan')
            ->where('b.jenis', $r->jenis)
            ->where('b.nama', $r->nama)
            ->where('b.harga', $r->harga)
            ->first();

        if ($barang) {
            return response()->json([
                'found' => true,
                'data'  => $barang
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * 🌸 Hapus barang
     */
    public function delete($id)
    {
        DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);
        return back()->with('ok', 'Barang berhasil dihapus!');
    }
}
