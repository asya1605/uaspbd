<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User
{
    public static function all()
    {
        return DB::select("SELECT * FROM user_vu ORDER BY iduser DESC");
    }

    public static function create($username, $email, $password, $idrole, $status)
    {
        $hash = Hash::make($password);
        DB::insert("
            INSERT INTO user (username, email, password, idrole, status)
            VALUES (?, ?, ?, ?, ?)
        ", [$username, $email, $hash, $idrole, $status]);
    }

    public static function updateData($id, $username, $email, $password, $idrole, $status)
    {
        if ($password) {
            $hash = Hash::make($password);
            DB::update("
                UPDATE user SET username=?, email=?, password=?, idrole=?, status=? WHERE iduser=?
            ", [$username, $email, $hash, $idrole, $status, $id]);
        } else {
            DB::update("
                UPDATE user SET username=?, email=?, idrole=?, status=? WHERE iduser=?
            ", [$username, $email, $idrole, $status, $id]);
        }
    }

    public static function delete($id)
    {
        DB::delete("DELETE FROM user WHERE iduser=?", [$id]);
    }

    public static function getRoles()
    {
        return DB::select("SELECT idrole, nama_role FROM role ORDER BY nama_role");
    }
}
