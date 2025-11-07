<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Role
{
    public static function all()
    {
        return DB::select("SELECT * FROM role_vu ORDER BY idrole ASC");
    }

    public static function create($nama_role)
    {
        DB::insert("INSERT INTO role (nama_role) VALUES (?)", [$nama_role]);
    }

    public static function updateData($id, $nama_role)
    {
        DB::update("UPDATE role SET nama_role=? WHERE idrole=?", [$nama_role, $id]);
    }

    public static function delete($id)
    {
        DB::delete("DELETE FROM role WHERE idrole=?", [$id]);
    }
}
