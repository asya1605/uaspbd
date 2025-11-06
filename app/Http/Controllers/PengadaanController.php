<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    //  Daftar Pengadaan (pakai VIEW)
    public function index()
    {
        $rows = DB::select("SELECT * FROM pengadaan_vu ORDER BY idpengadaan DESC");

        // Hitung total barang (pakai FUNCTION total_pengadaan)
        foreach ($rows as $r) {
            $total = DB::selectOne("SELECT total_pengadaan(?) AS total", [$r->idpengadaan]);
            $r->total_barang = $total ? $total->total : 0;
        }

        return view('pengadaan.index', compact('rows'));
    }

    // Form Tambah Pengadaan
    public function create()
    {
        $users   = DB::select("SELECT iduser, username FROM user_vu WHERE status = 1 ORDER BY username");
        $vendors = DB::select("SELECT idvendor, nama_vendor FROM vendor_vu ORDER BY nama_vendor");

        return view('pengadaan.create', compact('users', 'vendors'));
    }

    // Simpan Pengadaan Baru
    public function store(Request $r)
    {
        $r->validate([
            'user_iduser'     => 'required|integer',
            'vendor_idvendor' => 'required|integer',
            'status'          => 'required|in:0,1',
            'subtotal_nilai'  => 'required|numeric|min:0',
            'ppn'             => 'required|numeric|min:0',
        ]);

        $total = $r->subtotal_nilai + $r->ppn;

        // Simpan data pengadaan baru
        DB::insert("
            INSERT INTO pengadaan (timestamp, user_iduser, vendor_idvendor, status, subtotal_nilai, ppn, total_nilai)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?)
        ", [$r->user_iduser, $r->vendor_idvendor, $r->status, $r->subtotal_nilai, $r->ppn, $total]);

        $idpengadaan = DB::getPdo()->lastInsertId();

        return redirect("/pengadaan/$idpengadaan/items")
            ->with('ok', '✅ Pengadaan baru berhasil dibuat.');
    }

    // Tambah Barang ke Detail Pengadaan
    public function addItem($id, Request $r)
    {
        $r->validate([
            'idbarang'     => 'required|integer',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah'       => 'required|integer|min:1',
        ]);

        // Hitung subtotal per barang
        $subtotal = $r->harga_satuan * $r->jumlah;

        DB::insert("
            INSERT INTO detail_pengadaan (idpengadaan, idbarang, harga_satuan, jumlah, sub_total)
            VALUES (?, ?, ?, ?, ?)
        ", [$id, $r->idbarang, $r->harga_satuan, $r->jumlah, $subtotal]);

        // Update total nilai dengan FUNCTION total_pengadaan
        $total = DB::selectOne("SELECT total_pengadaan(?) AS total", [$id])->total;
        $ppn   = round($total * 0.11);
        $grand = $total + $ppn;

        DB::update("
            UPDATE pengadaan 
               SET subtotal_nilai=?, ppn=?, total_nilai=? 
             WHERE idpengadaan=?
        ", [$total, $ppn, $grand, $id]);

        return redirect("/pengadaan/$id/items")->with('ok', '🧾 Barang berhasil ditambahkan ke pengadaan.');
    }

    //  Detail Barang per Pengadaan
    public function items($id)
    {
        $pengadaan = DB::selectOne("SELECT * FROM pengadaan_vu WHERE idpengadaan=?", [$id]);

        $details = DB::select("
            SELECT dp.iddetail_pengadaan, b.nama AS nama_barang, dp.harga_satuan, dp.jumlah, dp.sub_total
            FROM detail_pengadaan dp
            JOIN barang b ON b.idbarang = dp.idbarang
            WHERE dp.idpengadaan = ?
            ORDER BY dp.iddetail_pengadaan DESC
        ", [$id]);

        $barangList = DB::select("SELECT idbarang, nama FROM barang ORDER BY nama");

        return view('pengadaan.items', compact('pengadaan', 'details', 'barangList'));
    }
}
