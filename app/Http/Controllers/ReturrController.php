<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReturrController extends Controller
{
    // 📋 Daftar Retur
    public function index()
    {
        $rows = DB::select("SELECT * FROM retur_vu ORDER BY idretur DESC");
        return view('retur.index', compact('rows'));
    }

    // 🧾 Buat Retur dari Penerimaan tertentu
    public function create($idpenerimaan = null)
    {
        // Ambil semua penerimaan (untuk dropdown jika tidak langsung dari detail)
        $penerimaanList = DB::select("SELECT idpenerimaan FROM penerimaan ORDER BY idpenerimaan DESC");

        // Kalau user datang dari tombol “Return” di detail penerimaan
        $selectedPenerimaan = null;
        $details = [];
        if ($idpenerimaan) {
            $selectedPenerimaan = DB::selectOne("SELECT * FROM penerimaan_vu WHERE idpenerimaan = ?", [$idpenerimaan]);
            $details = DB::select("
                SELECT dpn.iddetail_penerimaan, b.nama AS nama_barang, dpn.jumlah_terima, 
                       dpn.harga_satuan_terima, dpn.sub_total_terima
                FROM detail_penerimaan dpn
                JOIN barang b ON b.idbarang = dpn.idbarang
                WHERE dpn.idpenerimaan = ?
            ", [$idpenerimaan]);
        }

        $users = DB::select("SELECT iduser, username FROM user_vu WHERE status = 1 ORDER BY username");

        return view('retur.create', compact('penerimaanList', 'selectedPenerimaan', 'details', 'users'));
    }

    // 💾 Simpan retur baru (panggil SP retur)
    public function store(Request $r)
    {
        $r->validate([
            'idpenerimaan' => 'required|integer',
            'iduser' => 'required|integer',
        ]);

        DB::statement("CALL sp_tambah_retur(?, ?)", [$r->idpenerimaan, $r->iduser]);

        return redirect()->route('retur.index')->with('ok', '✅ Retur baru berhasil dibuat.');
    }

    // ➕ Tambah barang ke detail retur
    public function addItem($idretur, Request $r)
    {
        $r->validate([
            'iddetail_penerimaan' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
            'alasan' => 'required|string|max:200'
        ]);

        DB::statement("CALL sp_tambah_detail_retur(?, ?, ?, ?)", [
            $idretur,
            $r->iddetail_penerimaan,
            $r->jumlah,
            $r->alasan
        ]);

        return back()->with('ok', '🔁 Barang berhasil diretur.');
    }

    // 📄 Detail retur (lihat barang-barang yang diretur)
    public function items($idretur)
    {
        $retur = DB::selectOne("SELECT * FROM retur_vu WHERE idretur = ?", [$idretur]);

        $details = DB::select("
            SELECT dr.iddetail_retur, b.nama AS nama_barang, dr.jumlah, dr.alasan, dr.created_at
            FROM detail_retur dr
            JOIN detail_penerimaan dpn ON dpn.iddetail_penerimaan = dr.iddetail_penerimaan
            JOIN barang b ON b.idbarang = dpn.idbarang
            WHERE dr.idretur = ?
        ", [$idretur]);

        return view('retur.items', compact('retur', 'details'));
    }

    // ❌ Hapus retur
    public function delete($id)
    {
        DB::statement("CALL sp_hapus_retur(?)", [$id]);
        return back()->with('ok', '🗑️ Retur berhasil dihapus.');
    }
}
