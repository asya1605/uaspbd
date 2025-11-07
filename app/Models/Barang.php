<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Barang
{
    // 🔍 Ambil semua barang dari view
    public static function all()
    {
        return DB::select("SELECT * FROM barang_vu ORDER BY idbarang DESC");
    }

    // ➕ Tambah barang baru via SP
    public static function create($jenis, $nama, $idsatuan, $harga, $status)
    {
        DB::statement("CALL sp_insert_barang(?, ?, ?, ?, ?)", [
            $jenis, $nama, $idsatuan, $harga, $status
        ]);
    }

    // ✏️ Update barang via SP
    public static function updateData($idbarang, $nama, $harga, $status)
    {
        DB::statement("CALL sp_update_barang(?, ?, ?, ?)", [
            $idbarang, $nama, $harga, $status
        ]);
    }

    // ❌ Hapus barang
    public static function delete($idbarang)
    {
        DB::delete("DELETE FROM barang WHERE idbarang = ?", [$idbarang]);
    }

    // 📊 Ambil satuan aktif untuk dropdown
    public static function satuanAktif()
    {
        return DB::select("SELECT idsatuan, nama_satuan FROM satuan_vu WHERE status_text='Aktif'");
    }
}
