<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index() {
        $rows = DB::select('SELECT * FROM satuan ORDER BY idsatuan DESC');
        return view('satuan.index', ['rows' => $rows]);
    }

    public function store(Request $r) {
        DB::insert('INSERT INTO satuan (nama_satuan,status) VALUES (?,?)',
                   [$r->nama_satuan, $r->status ?? 1]);
        return redirect('/satuan')->with('ok','Satuan ditambahkan');
    }

    public function update($id, Request $r) {
        DB::update('UPDATE satuan SET nama_satuan=?, status=? WHERE idsatuan=?',
                   [$r->nama_satuan, $r->status ?? 1, $id]);
        return redirect('/satuan')->with('ok','Satuan diubah');
    }

    public function delete($id) {
        DB::delete('DELETE FROM satuan WHERE idsatuan=?', [$id]);
        return redirect('/satuan')->with('ok','Satuan dihapus');
    }
}
