<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MarginPenjualanController extends Controller
{
    public function index()
    {
        $rows = DB::select("SELECT * FROM margin_penjualan_vu ORDER BY idmargin DESC");
        return view('margin.index', compact('rows'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'persen' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1',
            'iduser' => 'required|integer'
        ]);

        DB::insert("
            INSERT INTO margin_penjualan (persen, status, iduser)
            VALUES (?, ?, ?)
        ", [$r->persen, $r->status, $r->iduser]);

        return redirect('/margin')->with('ok', '✅ Margin berhasil ditambahkan.');
    }

    public function update($id, Request $r)
    {
        $r->validate([
            'persen' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1'
        ]);

        DB::update("
            UPDATE margin_penjualan
            SET persen=?, status=?
            WHERE idmargin=?
        ", [$r->persen, $r->status, $id]);

        return redirect('/margin')->with('ok', '✏️ Margin berhasil diperbarui.');
    }

    public function delete($id)
    {
        DB::delete("DELETE FROM margin_penjualan WHERE idmargin=?", [$id]);
        return redirect('/margin')->with('ok', '🗑️ Margin berhasil dihapus.');
    }
}
