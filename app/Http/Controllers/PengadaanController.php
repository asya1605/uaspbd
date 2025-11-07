<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    // 📋 Daftar pengadaan (gunakan view atau tabel)
    public function index()
    {
        $rows = DB::select("SELECT * FROM pengadaan_vu ORDER BY idpengadaan DESC");
        return view('pengadaan.index', compact('rows'));
    }

    // ➕ Form Tambah Pengadaan
    public function create()
    {
        $users = DB::select("SELECT iduser, username FROM user_vu WHERE status = 1 ORDER BY username");
        $vendors = DB::select("SELECT idvendor, nama_vendor FROM vendor_vu ORDER BY nama_vendor");
        return view('pengadaan.create', compact('users', 'vendors'));
    }

    // 💾 Simpan Pengadaan Baru (panggil SP)
    public function store(Request $r)
    {
        $r->validate([
            'user_iduser' => 'required|integer',
            'vendor_idvendor' => 'required|integer',
            'status' => 'required|in:0,1',
            'subtotal_nilai' => 'required|numeric|min:0',
            'ppn' => 'required|numeric|min:0',
        ]);

        DB::statement("CALL sp_tambah_pengadaan(?, ?, ?, ?, ?)", [
            $r->user_iduser,
            $r->vendor_idvendor,
            $r->status,
            $r->subtotal_nilai,
            $r->ppn
        ]);

        return redirect('/pengadaan')->with('ok', '✅ Pengadaan baru berhasil ditambahkan.');
    }

    // 🧾 Tambah Barang ke Detail Pengadaan
    public function addItem($id, Request $r)
    {
        $r->validate([
            'idbarang' => 'required|integer',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1'
        ]);

        DB::statement("CALL sp_tambah_detail_pengadaan(?, ?, ?, ?)", [
            $id,
            $r->idbarang,
            $r->harga_satuan,
            $r->jumlah
        ]);

        return back()->with('ok', '🧾 Barang berhasil ditambahkan ke pengadaan.');
    }

    // ❌ Hapus Pengadaan
    public function delete($id)
    {
        DB::statement("CALL sp_hapus_pengadaan(?)", [$id]);
        return back()->with('ok', '🗑️ Pengadaan berhasil dihapus.');
    }
}
