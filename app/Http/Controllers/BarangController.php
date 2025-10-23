<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index()
    {
        $rows = DB::select("
            SELECT b.idbarang, b.jenis, b.nama, b.idsatuan, b.harga, b.status, s.nama_satuan
            FROM barang b
            LEFT JOIN satuan s ON s.idsatuan = b.idsatuan
            ORDER BY b.idbarang DESC
        ");

        $satuan = DB::select("
            SELECT idsatuan, nama_satuan
            FROM satuan
            WHERE status IN (1,'1','A','Y')
            ORDER BY nama_satuan
        ");

        return view('barang.index', compact('rows','satuan'));
    }

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

        return redirect('/barang')->with('ok','Barang ditambahkan.');
    }

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

        return redirect('/barang')->with('ok','Barang diperbarui.');
    }

    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM barang WHERE idbarang=?", [$id]);
            return redirect('/barang')->with('ok','Barang dihapus.');
        } catch (\Throwable $e) {
            return redirect('/barang')
              ->withErrors(['msg' => 'Barang tidak bisa dihapus karena sudah dipakai di transaksi.']);
        }
    }
}
