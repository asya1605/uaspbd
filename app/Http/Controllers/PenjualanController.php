<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $rows = DB::select("
            SELECT p.idpenjualan, p.created_at, p.subtotal_nilai, p.ppn, p.total_nilai, u.username
            FROM penjualan p
            JOIN user u ON u.iduser = p.iduser
            ORDER BY p.idpenjualan DESC
        ");

        return view('penjualan.index', compact('rows'));
    }

    public function create()
    {
        $margin = DB::selectOne("
            SELECT COALESCE((
                SELECT persen FROM margin_penjualan 
                WHERE status = 1 
                ORDER BY created_at DESC 
                LIMIT 1
            ), 0.15) AS persen
        ");

        $users = DB::select("
            SELECT iduser, username 
            FROM user 
            ORDER BY username
        ");

        return view('penjualan.create', compact('margin', 'users'));
    }

    public function store(Request $r)
    {
        $margin = DB::selectOne("
            SELECT idmargin_penjualan, persen
            FROM margin_penjualan
            WHERE status = 1
            ORDER BY created_at DESC
            LIMIT 1
        ");

        if (!$margin) {
            DB::insert("
                INSERT INTO margin_penjualan (persen, status, iduser)
                VALUES (0.15, 1, ?)
            ", [$r->iduser]);

            $idmargin = DB::getPdo()->lastInsertId();
        } else {
            $idmargin = $margin->idmargin_penjualan;
        }

        DB::insert("
            INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser, idmargin_penjualan)
            VALUES (NOW(), 0, 0, 0, ?, ?)
        ", [$r->iduser, $idmargin]);

        $id = DB::getPdo()->lastInsertId();

        $barang = DB::select("
            SELECT idbarang, nama, harga
            FROM barang
            WHERE status = 1
            ORDER BY nama
        ");

        return view('penjualan.items', [
            'idpenjualan' => $id,
            'barang'      => $barang
        ]);
    }

    public function addItem($id, Request $r)
    {
        $row = DB::selectOne("
            SELECT harga AS harga_jual FROM barang WHERE idbarang = ?
        ", [$r->idbarang]);

        if (!$row) {
            return back()->withErrors(['barang' => 'Barang tidak ditemukan']);
        }

        $hargaJual = (float) $row->harga_jual;
        $qty       = max(1, (int) $r->jumlah);
        $subtotal  = $hargaJual * $qty;

        DB::insert("
            INSERT INTO detail_penjualan (idpenjualan, idbarang, harga_satuan, jumlah, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ", [$id, $r->idbarang, $hargaJual, $qty, $subtotal]);

        $barang  = DB::select("SELECT idbarang, nama, harga FROM barang WHERE status = 1 ORDER BY nama");
        $header  = DB::selectOne("SELECT * FROM penjualan WHERE idpenjualan = ?", [$id]);
        $details = DB::select("
            SELECT d.*, b.nama 
            FROM detail_penjualan d
            JOIN barang b ON b.idbarang = d.idbarang
            WHERE d.idpenjualan = ?
            ORDER BY d.iddetail_penjualan DESC
        ", [$id]);

        return view('penjualan.items', [
            'idpenjualan' => $id,
            'barang'      => $barang,
            'header'      => $header,
            'details'     => $details
        ])->with('ok', 'Item berhasil ditambahkan');
    }
}
