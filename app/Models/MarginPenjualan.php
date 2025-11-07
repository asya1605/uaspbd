<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class MarginPenjualan
{
    // Laporan margin dari view
    public static function all()
    {
        return DB::select("
            SELECT 
                idpenjualan, tanggal_penjualan, kasir,
                total_penjualan, total_modal, margin,
                ROUND((margin / NULLIF(total_penjualan, 0)) * 100, 2) AS persentase
            FROM margin_penjualan_vu
            ORDER BY tanggal_penjualan DESC
        ");
    }

    // Tambah margin baru
    public static function create($persen, $status, $iduser)
    {
        DB::insert("
            INSERT INTO margin_penjualan (created_at, persen, status, updated_at, iduser)
            VALUES (NOW(), ?, ?, NOW(), ?)
        ", [$persen, $status, $iduser]);
    }

    // Update margin
    public static function updateData($id, $persen, $status)
    {
        DB::update("
            UPDATE margin_penjualan SET persen=?, status=?, updated_at=NOW() WHERE idmargin_penjualan=?
        ", [$persen, $status, $id]);
    }

    public static function delete($id)
    {
        DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan=?", [$id]);
    }
}
