<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    // Tampilkan daftar vendor (pakai VIEW)
    public function index()
    {
        $rows = DB::select("SELECT * FROM vendor_vu ORDER BY idvendor DESC");
        return view('vendor.index', compact('rows'));
    }

    // Tambah vendor baru
    public function store(Request $r)
    {
        $r->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:Y,N',
            'status'      => 'required|in:0,1'
        ]);

        DB::insert("
            INSERT INTO vendor (nama_vendor, badan_hukum, status)
            VALUES (?, ?, ?)
        ", [$r->nama_vendor, $r->badan_hukum, (string)$r->status]);

        return redirect('/vendor')->with('ok', '✅ Vendor baru berhasil ditambahkan.');
    }

    //  Update vendor
    public function update($id, Request $r)
    {
        $r->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|in:Y,N',
            'status'      => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE vendor
            SET nama_vendor = ?, badan_hukum = ?, status = ?
            WHERE idvendor = ?
        ", [$r->nama_vendor, $r->badan_hukum, (string)$r->status, $id]);

        return redirect('/vendor')->with('ok', '✏️ Data vendor berhasil diperbarui.');
    }

    //  Hapus vendor
    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM vendor WHERE idvendor = ?", [$id]);
            return redirect('/vendor')->with('ok', '🗑️ Vendor berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect('/vendor')->withErrors([
                'msg' => '⚠️ Vendor tidak dapat dihapus karena sudah digunakan dalam transaksi pengadaan.'
            ]);
        }
    }
}
