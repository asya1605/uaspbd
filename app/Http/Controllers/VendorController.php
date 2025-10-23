<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index()
    {
        $rows = DB::select("
            SELECT idvendor, nama_vendor, badan_hukum, status
            FROM vendor
            ORDER BY idvendor DESC
        ");
        return view('vendor.index', compact('rows'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'nama_vendor'  => 'required|string|max:100',
            'badan_hukum'  => 'required|in:Y,N',
            'status'       => 'required|in:0,1'
        ]);

        DB::insert("
            INSERT INTO vendor (nama_vendor, badan_hukum, status)
            VALUES (?, ?, ?)
        ", [$r->nama_vendor, $r->badan_hukum, $r->status]);

        return redirect('/vendor')->with('ok','Vendor ditambahkan.');
    }

    public function update($id, Request $r)
    {
        $r->validate([
            'nama_vendor'  => 'required|string|max:100',
            'badan_hukum'  => 'required|in:Y,N',
            'status'       => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE vendor
               SET nama_vendor=?, badan_hukum=?, status=?
             WHERE idvendor=?
        ", [$r->nama_vendor, $r->badan_hukum, $r->status, $id]);

        return redirect('/vendor')->with('ok','Vendor diperbarui.');
    }

    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM vendor WHERE idvendor=?", [$id]);
            return redirect('/vendor')->with('ok','Vendor dihapus.');
        } catch (\Throwable $e) {
            return redirect('/vendor')
                ->withErrors(['msg' => 'Vendor tidak bisa dihapus karena sudah dipakai di transaksi.']);
        }
    }
}
