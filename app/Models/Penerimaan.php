<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Penerimaan
{
    // Ambil semua data penerimaan dari VIEW
    public static function all()
    {
        return DB::select("SELECT * FROM penerimaan_vu ORDER BY idpenerimaan DESC");
    }

    // Simpan penerimaan baru (otomatis via trigger dari pengadaan)
    public static function create($idpengadaan, $iduser, $status)
    {
        DB::insert("
            INSERT INTO penerimaan (created_at, status, idpengadaan, iduser)
            VALUES (NOW(), ?, ?, ?)
        ", [$status, $idpengadaan, $iduser]);
    }

    // Tambahkan barang ke detail penerimaan
    public static function addItem($idpenerimaan, $idbarang, $jumlah, $harga)
    {
        $subtotal = $jumlah * $harga;
        DB::insert("
            INSERT INTO detail_penerimaan 
                (jumlah_terima, harga_satuan_terima, sub_total_terima, idpenerimaan, idbarang)
            VALUES (?, ?, ?, ?, ?)
        ", [$jumlah, $harga, $subtotal, $idpenerimaan, $idbarang]);
    }

    // Hapus penerimaan
    public static function delete($id)
    {
        DB::delete("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?", [$id]);
        DB::delete("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$id]);
    }
}
