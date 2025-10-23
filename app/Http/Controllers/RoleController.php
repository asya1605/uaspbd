<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $rows = DB::select("SELECT idrole, nama_role FROM role ORDER BY idrole");
        return view('role.index', compact('rows'));
    }

    public function store(Request $r)
    {
        $r->validate(['nama_role' => 'required|string|max:100']);
        DB::insert("INSERT INTO role(nama_role) VALUES (?)", [$r->nama_role]);
        return redirect('/role')->with('ok', 'Role ditambahkan.');
    }

    public function update($id, Request $r)
    {
        $r->validate(['nama_role' => 'required|string|max:100']);
        DB::update("UPDATE role SET nama_role=? WHERE idrole=?", [$r->nama_role, $id]);
        return redirect('/role')->with('ok', 'Role diperbarui.');
    }

    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM role WHERE idrole=?", [$id]);
            return redirect('/role')->with('ok', 'Role dihapus.');
        } catch (\Throwable $e) {
            return redirect('/role')
                ->withErrors(['msg' => 'Role tidak bisa dihapus (digunakan oleh user).']);
        }
    }
}
