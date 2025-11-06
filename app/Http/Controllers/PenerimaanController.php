<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    //  Menampilkan daftar penerimaan
    public function index()
    {
        // Ambil semua data dari view penerimaan_vu
        $rows = DB::select("SELECT * FROM penerimaan_vu ORDER BY idpenerimaan DESC");

        return view('penerimaan.index', compact('rows'));
    }

    // Form tambah penerimaan baru
    public function create()
    {
        // Ambil daftar pengadaan & user aktif
        $pengadaan = DB::select("SELECT idpengadaan FROM pengadaan ORDER BY idpengadaan DESC");
        $users = DB::select("SELECT iduser, username FROM user WHERE status = 1 ORDER BY username");

        return view('penerimaan.create', compact('pengadaan', 'users'));
    }

    //  Simpan penerimaan baru
    public function store(Request $r)
    {
        $r->validate([
            'idpengadaan' => 'required|integer',
            'iduser'      => 'required|integer',
            'status'      => 'required|in:0,1',
        ]);

        DB::insert("
            INSERT INTO penerimaan (created_at, status, idpengadaan, iduser)
            VALUES (NOW(), ?, ?, ?)
        ", [$r->status, $r->idpengadaan, $r->iduser]);

        $idpenerimaan = DB::getPdo()->lastInsertId();

        return redirect("/penerimaan/$idpenerimaan/items")
               ->with('ok', 'Data penerimaan berhasil dibuat.');
    }

    //  Tambah detail penerimaan barang
    public function addItem($id, Request $r)
    {
        $r->validate([
            'idbarang'           => 'required|integer',
            'jumlah_terima'      => 'required|integer|min:1',
            'harga_satuan_terima'=> 'required|numeric|min:0',
        ]);

        $subTotal = $r->jumlah_terima * $r->harga_satuan_terima;

        DB::insert("
            INSERT INTO detail_penerimaan (idpenerimaan, idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
            VALUES (?, ?, ?, ?, ?)
        ", [$id, $r->idbarang, $r->jumlah_terima, $r->harga_satuan_terima, $subTotal]);

        // Tidak perlu update stok manual di controller.

        return redirect("/penerimaan/$id/items")
               ->with('ok', 'Barang penerimaan berhasil ditambahkan.');
    }

    //  Detail barang per penerimaan
    public function items($id)
    {
        $penerimaan = DB::selectOne("
            SELECT * FROM penerimaan_vu WHERE idpenerimaan=?
        ", [$id]);

        $details = DB::select("
            SELECT dpn.iddetail_penerimaan, b.nama AS nama_barang, s.nama_satuan, dpn.jumlah_terima,
                   dpn.harga_satuan_terima, dpn.sub_total_terima
            FROM detail_penerimaan dpn
            JOIN barang b ON b.idbarang = dpn.idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
            WHERE dpn.idpenerimaan = ?
            ORDER BY dpn.iddetail_penerimaan DESC
        ", [$id]);

        $barangList = DB::select("SELECT idbarang, nama FROM barang ORDER BY nama");

        return view('penerimaan.items', compact('penerimaan', 'details', 'barangList'));
    }
}
