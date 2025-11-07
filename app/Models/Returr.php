<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Returr
{
    // Ambil semua retur
    public static function all()
    {
        return DB::select("
            SELECT r.idretur, r.created_at, u.username, r.idpenerimaan
            FROM returr r
            JOIN user u ON u.iduser = r.iduser
            ORDER BY r.idretur DESC
        ");
    }

    // Tambah retur baru
    public static function create($idpenerimaan, $iduser)
    {
        DB::insert("
            INSERT INTO returr (created_at, idpenerimaan, iduser)
            VALUES (NOW(), ?, ?)
        ", [$idpenerimaan, $iduser]);
    }

    // Tambahkan detail retur
    public static function addItem($idretur, $iddetail_penerimaan, $jumlah, $alasan)
    {
        DB::insert("
            INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan)
            VALUES (?, ?, ?, ?)
        ", [$jumlah, $alasan, $idretur, $iddetail_penerimaan]);
        // stok otomatis bertambah via trigger trg_update_stok_retur
    }

    // Hapus retur
    public static function delete($id)
    {
        DB::delete("DELETE FROM detail_retur WHERE idretur = ?", [$id]);
        DB::delete("DELETE FROM returr WHERE idretur = ?", [$id]);
    }
}
