<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * 📋 Tampilkan daftar vendor (filter aktif / semua)
     */
    public function index(Request $r)
    {
        $filter = $r->query('filter', 'aktif');

        if ($filter === 'aktif') {
            $rows = DB::select("
                SELECT * FROM vendor
                WHERE status = 1
                ORDER BY idvendor DESC
            ");
        } else {
            $rows = DB::select("SELECT * FROM vendor ORDER BY idvendor DESC");
        }

        return view('vendor.index', compact('rows', 'filter'));
    }

    /**
     * ➕ Tambah vendor baru
     */
    public function store(Request $r)
    {
        $r->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:Y,N',
        ]);

        DB::insert("
            INSERT INTO vendor (nama_vendor, badan_hukum, status)
            VALUES (?, ?, 1)
        ", [$r->nama_vendor, $r->badan_hukum]);

        return redirect()->route('vendor.index')->with('ok', '✅ Vendor baru berhasil ditambahkan.');
    }

    /**
     * ✏️ Update vendor (termasuk ubah status)
     */
    public function update(Request $r, $id)
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
        ", [$r->nama_vendor, $r->badan_hukum, $r->status, $id]);

        return redirect()->route('vendor.index')->with('ok', '✏️ Data vendor berhasil diperbarui.');
    }

    /**
     * 🗑️ Hapus vendor
     */
    public function delete($id)
    {
        DB::delete("DELETE FROM vendor WHERE idvendor=?", [$id]);
        return redirect()->route('vendor.index')->with('ok', '🗑️ Vendor berhasil dihapus.');
    }
}
