<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // 🧭 Menampilkan semua vendor
    public function index()
    {
        $rows = DB::select("SELECT * FROM vendor ORDER BY idvendor DESC");
        return view('vendor.index', compact('rows'));
    }

    // ➕ Tambah vendor baru
    public function store(Request $r)
    {
        $r->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:Y,N',
            'status' => 'required|in:0,1'
        ]);

        DB::insert("
            INSERT INTO vendor (nama_vendor, badan_hukum, status)
            VALUES (?, ?, ?)
        ", [
            $r->nama_vendor,
            $r->badan_hukum,
            $r->status
        ]);

        return redirect('/vendor')->with('ok', '✅ Vendor berhasil ditambahkan.');
    }

    // ✏️ Update vendor
    public function update($id, Request $r)
    {
        $r->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:Y,N',
            'status' => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE vendor 
            SET nama_vendor=?, badan_hukum=?, status=? 
            WHERE idvendor=?
        ", [
            $r->nama_vendor,
            $r->badan_hukum,
            $r->status,
            $id
        ]);

        return redirect('/vendor')->with('ok', '✏️ Vendor berhasil diperbarui.');
    }

    // 🗑️ Hapus vendor
    public function delete($id)
    {
        DB::delete("DELETE FROM vendor WHERE idvendor=?", [$id]);
        return redirect('/vendor')->with('ok', '🗑️ Vendor berhasil dihapus.');
    }
}
