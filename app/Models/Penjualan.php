<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Penjualan
{
    // Ambil semua penjualan
    public static function all()
    {
        return DB::select("SELECT * FROM penjualan_vu ORDER BY idpenjualan DESC");
    }

    // Tambah penjualan baru via Stored Procedure
    public static function create($iduser)
    {
        $margin_aktif = DB::selectOne("SELECT get_margin_aktif() AS margin")->margin ?? 0;
        DB::statement("CALL tambah_penjualan(?, ?, 0, 0)", [$iduser, $margin_aktif]);
    }

    // Tambah detail penjualan via SP
    public static function addItem($idpenjualan, $idbarang, $harga, $jumlah)
    {
        DB::statement("CALL tambah_detail_penjualan(?, ?, ?, ?)", [
            $idpenjualan, $idbarang, $harga, $jumlah
        ]);

        DB::statement("CALL update_total_penjualan(?)", [$idpenjualan]);
    }

    // Hapus penjualan
    public static function delete($id)
    {
        DB::delete("DELETE FROM detail_penjualan WHERE idpenjualan = ?", [$id]);
        DB::delete("DELETE FROM penjualan WHERE idpenjualan = ?", [$id]);
    }
}
