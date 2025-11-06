<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    //  Daftar Penjualan
    public function index()
    {
        $rows = DB::select("SELECT * FROM penjualan_vu ORDER BY idpenjualan DESC");
        return view('penjualan.index', compact('rows'));
    }

    // Form Tambah Penjualan
    public function create()
    {
        $users = DB::select("SELECT iduser, username FROM user_vu WHERE status = 1 ORDER BY username");
        return view('penjualan.create', compact('users'));
    }

    // Simpan Penjualan Baru (SP)
    public function store(Request $r)
    {
        $r->validate([
            'iduser' => 'required|integer'
        ]);

        // Panggil Stored Procedure tambah_penjualan
        DB::statement("CALL tambah_penjualan(?, 1, 0, 0)", [$r->iduser]);
        $id = DB::getPdo()->lastInsertId();

        return redirect("/penjualan/$id/items")
            ->with('ok', '✅ Penjualan baru berhasil dibuat.');
    }

    //  Tambahkan Barang ke Detail Penjualan
    public function addItem($id, Request $r)
    {
        $r->validate([
            'idbarang' => 'required|integer',
            'jumlah'   => 'required|integer|min:1',
        ]);

        // Ambil harga & hitung subtotal pakai FUNCTION subtotal_barang()
        $harga = DB::selectOne("SELECT harga FROM barang WHERE idbarang=? AND status=1", [$r->idbarang]);
        if (!$harga) {
            return back()->withErrors(['msg' => 'Barang tidak ditemukan atau tidak aktif.']);
        }

        $subtotal = DB::selectOne("SELECT subtotal_barang(?,?) AS hasil", [$harga->harga, $r->jumlah])->hasil;

        // Tambahkan ke detail_penjualan via SP
        DB::statement("CALL tambah_detail_penjualan(?, ?, ?, ?)", [$id, $r->idbarang, $harga->harga, $r->jumlah]);

        // Update total via SP
        DB::statement("CALL update_total_penjualan(?)", [$id]);

        // Ambil ulang header & detail
        $header  = DB::selectOne("SELECT * FROM penjualan_vu WHERE idpenjualan=?", [$id]);
        $details = DB::select("SELECT * FROM detail_penjualan_vu WHERE idpenjualan=?", [$id]);
        $barang  = DB::select("SELECT idbarang, nama, harga FROM barang WHERE status=1 ORDER BY nama");

        return view('penjualan.items', compact('id', 'barang', 'header', 'details'))
            ->with('ok', "✅ Item '{$r->idbarang}' berhasil ditambahkan dengan subtotal Rp $subtotal");
    }
}
