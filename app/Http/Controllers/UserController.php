<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $rows = DB::select("
            SELECT u.iduser, u.username, u.email, u.idrole, r.nama_role
            FROM user u
            LEFT JOIN role r ON r.idrole = u.idrole
            ORDER BY u.iduser DESC
        ");

        $roles = DB::select("SELECT idrole, nama_role FROM role ORDER BY nama_role");

        return view('user.index', compact('rows','roles'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'username' => 'required|string|max:45',
            'email'    => 'nullable|email|max:100',
            'password' => 'required|string|min:4|max:100',
            'idrole'   => 'required|integer'
        ]);

        $exists = DB::selectOne("SELECT iduser FROM user WHERE username = ? LIMIT 1", [$r->username]);
        if ($exists) {
            return back()->withErrors(['username' => 'Username sudah dipakai'])->withInput();
        }

        $hash = Hash::make($r->password);

        DB::insert("
            INSERT INTO user (username, email, password, idrole)
            VALUES (?,?,?,?)
        ", [$r->username, $r->email, $hash, $r->idrole]);

        return redirect('/user')->with('ok','User ditambahkan.');
    }

    public function update($id, Request $r)
    {
        $r->validate([
            'username' => 'required|string|max:45',
            'email'    => 'nullable|email|max:100',
            'idrole'   => 'required|integer',
            'password' => 'nullable|string|min:4|max:100'
        ]);

        $dup = DB::selectOne("SELECT iduser FROM user WHERE username=? AND iduser<>? LIMIT 1", [$r->username, $id]);
        if ($dup) {
            return back()->withErrors(['username'=>'Username sudah dipakai user lain'])->withInput();
        }

        if ($r->filled('password')) {
            $hash = Hash::make($r->password);
            DB::update("
                UPDATE user SET username=?, email=?, password=?, idrole=?
                WHERE iduser=?
            ", [$r->username, $r->email, $hash, $r->idrole, $id]);
        } else {
            DB::update("
                UPDATE user SET username=?, email=?, idrole=?
                WHERE iduser=?
            ", [$r->username, $r->email, $r->idrole, $id]);
        }

        return redirect('/user')->with('ok','User diperbarui.');
    }

    public function delete($id)
    {
        DB::delete("DELETE FROM user WHERE iduser=?", [$id]);
        return redirect('/user')->with('ok','User dihapus.');
    }
}
