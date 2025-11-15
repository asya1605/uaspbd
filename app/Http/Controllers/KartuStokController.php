<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KartuStokController extends Controller
{
    // 📦 Menampilkan stok terakhir semua barang
    public function index()
    {
        $rows = DB::select("
            SELECT b.idbarang, b.nama, 
                   COALESCE((
                       SELECT stock FROM kartu_stok ks
                       WHERE ks.idbarang = b.idbarang
                       ORDER BY idkartu_stok DESC
                       LIMIT 1
                   ), 0) AS stok_terakhir
            FROM barang b
            ORDER BY b.nama ASC
        ");

        return view('kartu-stok.index', compact('rows'));
    }

    // 🕰️ Menampilkan history stok per barang
    public function history($idbarang)
    {
        $barang = DB::selectOne("SELECT nama FROM barang WHERE idbarang = ?", [$idbarang]);

        $histories = DB::select("
            SELECT jenis_transaksi, masuk, keluar, stock, create_at, idtransaksi
            FROM kartu_stok
            WHERE idbarang = ?
            ORDER BY create_at DESC
        ", [$idbarang]);

        return view('kartu-stok.history', compact('barang', 'histories', 'idbarang'));
    }
}
