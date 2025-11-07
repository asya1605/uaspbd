<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Vendor
{
    public static function all()
    {
        return DB::select("SELECT * FROM vendor_vu ORDER BY idvendor DESC");
    }

    public static function create($nama_vendor, $badan_hukum, $status)
    {
        DB::insert("INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES (?, ?, ?)", [
            $nama_vendor, $badan_hukum, $status
        ]);
    }

    public static function updateData($id, $nama_vendor, $badan_hukum, $status)
    {
        DB::update("UPDATE vendor SET nama_vendor=?, badan_hukum=?, status=? WHERE idvendor=?", [
            $nama_vendor, $badan_hukum, $status, $id
        ]);
    }

    public static function delete($id)
    {
        DB::delete("DELETE FROM vendor WHERE idvendor=?", [$id]);
    }
}
