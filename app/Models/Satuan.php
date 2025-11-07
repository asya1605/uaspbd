<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Satuan
{
    public static function all()
    {
        return DB::select("SELECT * FROM satuan_vu ORDER BY idsatuan DESC");
    }

    public static function create($nama_satuan, $status)
    {
        DB::insert("INSERT INTO satuan (nama_satuan, status) VALUES (?, ?)", [
            $nama_satuan, $status
        ]);
    }

    public static function updateData($id, $nama_satuan, $status)
    {
        DB::update("UPDATE satuan SET nama_satuan=?, status=? WHERE idsatuan=?", [
            $nama_satuan, $status, $id
        ]);
    }

    public static function delete($id)
    {
        DB::delete("DELETE FROM satuan WHERE idsatuan=?", [$id]);
    }
}
