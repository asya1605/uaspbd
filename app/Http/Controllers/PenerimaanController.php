<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    // 📋 Daftar penerimaan
    public function index()
    {
        $rows = DB::select("SELECT * FROM penerimaan_vu ORDER BY idpenerimaan DESC");
        return view('penerimaan.index', compact('rows'));
    }

    // 🧾 Form Tambah Penerimaan (status awal pending)
    public function create()
    {
        $pengadaan = DB::select("SELECT idpengadaan FROM pengadaan_vu ORDER BY idpengadaan DESC");
        $users = DB::select("SELECT iduser, username FROM user_vu WHERE status = 1 ORDER BY username");
        return view('penerimaan.create', compact('pengadaan', 'users'));
    }

    // 💾 Simpan penerimaan baru
    public function store(Request $r)
    {
        $r->validate([
            'idpengadaan' => 'required|integer',
            'iduser'      => 'required|integer',
        ]);

        DB::insert("
            INSERT INTO penerimaan (created_at, status, idpengadaan, iduser)
            VALUES (NOW(), 'pending', ?, ?)
        ", [$r->idpengadaan, $r->iduser]);

        $idpenerimaan = DB::getPdo()->lastInsertId();

        return redirect("/penerimaan/$idpenerimaan/items")
            ->with('ok', '📦 Penerimaan baru dibuat dan siap diverifikasi.');
    }

    // ➕ Tambah barang diterima
    public function addItem($id, Request $r)
    {
        $r->validate([
            'idbarang'      => 'required|integer',
            'jumlah_terima' => 'required|integer|min:1',
        ]);

        // Ambil harga dari barang
        $harga = DB::selectOne("SELECT harga FROM barang WHERE idbarang = ?", [$r->idbarang]);
        if (!$harga) return back()->withErrors(['msg' => 'Barang tidak ditemukan.']);

        $harga_satuan = $harga->harga;
        $subtotal = $r->jumlah_terima * $harga_satuan;

        DB::insert("
            INSERT INTO detail_penerimaan (idpenerimaan, idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
            VALUES (?, ?, ?, ?, ?)
        ", [$id, $r->idbarang, $r->jumlah_terima, $harga_satuan, $subtotal]);

        return back()->with('ok', '✅ Barang berhasil diterima sebagian.');
    }

    // 📋 Detail penerimaan
    public function items($id)
    {
        $penerimaan = DB::selectOne("SELECT * FROM penerimaan_vu WHERE idpenerimaan=?", [$id]);

        // Ringkasan dari pengadaan
        $ringkasan = DB::select("
            SELECT b.idbarang, b.nama AS nama_barang, s.nama_satuan, dp.jumlah AS total_dipesan,
            IFNULL(SUM(dp2.jumlah_terima), 0) AS total_diterima,
            dp.jumlah - IFNULL(SUM(dp2.jumlah_terima), 0) AS sisa
            FROM detail_pengadaan dp
            JOIN barang b ON b.idbarang = dp.idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
            LEFT JOIN detail_penerimaan dp2 ON dp2.idbarang = dp.idbarang AND dp2.idpenerimaan = ?
            WHERE dp.idpengadaan = ?
            GROUP BY b.idbarang, b.nama, s.nama_satuan, dp.jumlah
        ", [$id, $penerimaan->idpengadaan]);

        $details = DB::select("
            SELECT dpn.iddetail_penerimaan, b.nama AS nama_barang, s.nama_satuan,
                   dpn.jumlah_terima, dpn.harga_satuan_terima, dpn.sub_total_terima
            FROM detail_penerimaan dpn
            JOIN barang b ON b.idbarang = dpn.idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
            WHERE dpn.idpenerimaan = ?
            ORDER BY dpn.iddetail_penerimaan DESC
        ", [$id]);

        return view('penerimaan.items', compact('penerimaan', 'ringkasan', 'details'));
    }

    // ✅ Konfirmasi penerimaan selesai
    public function confirm($id)
    {
        DB::update("UPDATE penerimaan SET status='diterima', verified_at=NOW() WHERE idpenerimaan=?", [$id]);
        return back()->with('ok', '✅ Semua barang sudah diterima dan dikonfirmasi.');
    }
}
