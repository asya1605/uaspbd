<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Pengadaan
{
    // Ambil semua pengadaan dari VIEW
    public static function all()
    {
        return DB::select("SELECT * FROM pengadaan_vu ORDER BY idpengadaan DESC");
    }

    // Simpan pengadaan baru lewat Stored Procedure
    public static function create($user_id, $vendor_id, $status, $subtotal, $ppn)
    {
        DB::statement("CALL tambah_pengadaan(?, ?, ?, ?, ?)", [
            $user_id, $vendor_id, $status, $subtotal, $ppn
        ]);
    }

    // Tambah detail pengadaan (pakai SP)
    public static function addItem($idpengadaan, $idbarang, $harga, $jumlah)
    {
        DB::statement("CALL tambah_detail_pengadaan(?, ?, ?, ?)", [
            $idpengadaan, $idbarang, $harga, $jumlah
        ]);
    }

    // Hapus pengadaan (hapus juga detailnya)
    public static function delete($id)
    {
        DB::delete("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$id]);
        DB::delete("DELETE FROM pengadaan WHERE idpengadaan = ?", [$id]);
    }
}
