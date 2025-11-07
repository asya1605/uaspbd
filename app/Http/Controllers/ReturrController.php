<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReturrController extends Controller
{
    public function index()
    {
        $rows = DB::select("SELECT * FROM retur_vu ORDER BY idretur DESC");
        return view('retur.index', compact('rows'));
    }

    public function create()
    {
        $penerimaan = DB::select("SELECT idpenerimaan FROM penerimaan ORDER BY idpenerimaan DESC");
        $users = DB::select("SELECT iduser, username FROM user_vu WHERE status = 1 ORDER BY username");
        return view('retur.create', compact('penerimaan', 'users'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'idpenerimaan' => 'required|integer',
            'iduser' => 'required|integer'
        ]);

        DB::statement("CALL sp_tambah_retur(?, ?)", [$r->idpenerimaan, $r->iduser]);
        return redirect('/retur')->with('ok', '✅ Retur berhasil dibuat.');
    }

    public function addItem($id, Request $r)
    {
        $r->validate([
            'iddetail_penerimaan' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string|max:200'
        ]);

        DB::statement("CALL sp_tambah_detail_retur(?, ?, ?, ?)", [
            $id,
            $r->iddetail_penerimaan,
            $r->jumlah,
            $r->alasan
        ]);

        return back()->with('ok', '🔁 Barang berhasil diretur.');
    }

    public function delete($id)
    {
        DB::statement("CALL sp_hapus_retur(?)", [$id]);
        return back()->with('ok', '🗑️ Retur berhasil dihapus.');
    }
}
