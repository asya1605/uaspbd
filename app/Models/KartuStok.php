<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class KartuStok
{
    // Ambil semua data kartu stok dari VIEW
    public static function all()
    {
        return DB::select("SELECT * FROM kartu_stok_vu ORDER BY create_at DESC");
    }

    // Tambah manual kartu stok (opsional)
    public static function create($jenis, $masuk, $keluar, $idbarang, $idtransaksi = null)
    {
        $stok_akhir = DB::selectOne("SELECT stok_barang(?) AS s", [$idbarang])->s ?? 0;
        $stok_baru = $stok_akhir + ($masuk - $keluar);

        DB::insert("
            INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, create_at, idtransaksi, idbarang)
            VALUES (?, ?, ?, ?, NOW(), ?, ?)
        ", [$jenis, $masuk, $keluar, $stok_baru, $idtransaksi, $idbarang]);
    }

    // Hapus kartu stok manual (tidak disarankan)
    public static function delete($id)
    {
        DB::delete("DELETE FROM kartu_stok WHERE idkartu_stok = ?", [$id]);
    }
}
