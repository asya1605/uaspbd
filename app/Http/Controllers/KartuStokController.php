<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KartuStokController extends Controller
{
    public function index()
    {
        $rows = DB::select("SELECT * FROM kartu_stok_vu ORDER BY idkartu DESC");
        return view('stok.index', compact('rows'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'jenis_transaksi' => 'required|in:M,K,R',
            'masuk' => 'nullable|integer|min:0',
            'keluar' => 'nullable|integer|min:0',
            'idbarang' => 'required|integer',
            'idtransaksi' => 'nullable|integer'
        ]);

        DB::statement("CALL sp_tambah_kartu_stok(?, ?, ?, ?, ?)", [
            $r->jenis_transaksi,
            $r->masuk ?? 0,
            $r->keluar ?? 0,
            $r->idbarang,
            $r->idtransaksi
        ]);

        return back()->with('ok', '📦 Kartu stok berhasil ditambahkan.');
    }

    public function delete($id)
    {
        DB::delete("DELETE FROM kartu_stok WHERE idkartu=?", [$id]);
        return back()->with('ok', '🗑️ Data kartu stok berhasil dihapus.');
    }
}
