<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    // 📋 Daftar Penjualan
    public function index()
    {
        $rows = DB::select("SELECT * FROM penjualan_vu ORDER BY idpenjualan DESC");
        return view('penjualan.index', compact('rows'));
    }

    // ➕ Tambah Penjualan Baru (via SP)
    public function create()
    {
        $users = DB::select("SELECT iduser, username FROM user_vu WHERE status = 1 ORDER BY username");
        return view('penjualan.create', compact('users'));
    }

    public function store(Request $r)
    {
        $r->validate(['iduser' => 'required|integer']);
        DB::statement("CALL sp_tambah_penjualan(?)", [$r->iduser]);
        return redirect('/penjualan')->with('ok', '✅ Penjualan berhasil dibuat.');
    }

    // 🧾 Tambah Barang ke Penjualan (via SP)
    public function addItem($id, Request $r)
    {
        $r->validate([
            'idbarang' => 'required|integer',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1'
        ]);

        DB::statement("CALL sp_tambah_detail_penjualan(?, ?, ?, ?)", [
            $id,
            $r->idbarang,
            $r->harga,
            $r->jumlah
        ]);

        return back()->with('ok', '🛍️ Barang berhasil ditambahkan ke penjualan.');
    }

    public function delete($id)
    {
        DB::statement("CALL sp_hapus_penjualan(?)", [$id]);
        return back()->with('ok', '🗑️ Penjualan berhasil dihapus.');
    }
}
