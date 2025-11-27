<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /** 📋 Tampilkan daftar penjualan */
    public function index()
    {
        $rows = DB::select("
            SELECT 
                p.idpenjualan, p.created_at, u.username, 
                p.subtotal_nilai, p.ppn, p.total_nilai,
                p.idmargin_penjualan, m.persen AS margin_persen
            FROM penjualan p
            JOIN user u ON u.iduser = p.iduser
            LEFT JOIN margin_penjualan m ON m.idmargin_penjualan = p.idmargin_penjualan
            ORDER BY p.idpenjualan DESC
        ");
        return view('penjualan.index', compact('rows'));
    }

    /** ➕ Form tambah penjualan baru */
public function create()
{
    $barang = DB::select("
        SELECT 
            b.idbarang, 
            b.nama AS nama_barang, 
            b.harga, 
            s.nama_satuan,
            -- ambil stok terakhir dari kartu_stok
            (SELECT stock 
             FROM kartu_stok ks 
             WHERE ks.idbarang = b.idbarang 
             ORDER BY ks.idkartu_stok DESC 
             LIMIT 1) AS stok_terakhir
        FROM barang b
        JOIN satuan s ON s.idsatuan = b.idsatuan
        WHERE b.status = 1
        ORDER BY b.nama ASC
    ");

    $margin = DB::selectOne("
        SELECT persen FROM margin_penjualan 
        WHERE status = 1 
        ORDER BY idmargin_penjualan DESC 
        LIMIT 1
    ");
    $margin_persen = $margin->persen ?? 0;

    return view('penjualan.create', compact('barang', 'margin_persen'));
}
    /** 💾 Simpan penjualan baru */
public function store(Request $r)
{
    $r->validate([
        'items' => 'required|json'
    ]);

    // 🔥 Ambil user dari session LOGIN MANUAL kamu
    $iduser = session('user')['iduser'] ?? 1;

    try {
        // Jalankan prosedur
        DB::statement("CALL sp_tambah_penjualan_otomatis(?, ?)", [
            $iduser,
            $r->items
        ]);

        // ✅ Jika sukses
        return redirect()->route('penjualan.index')
            ->with('ok', '✅ Penjualan berhasil disimpan.');

    } catch (\Illuminate\Database\QueryException $e) {
        // Tangkap pesan error dari MySQL SIGNAL
        $errorMessage = $e->getMessage();

        // Cek kalau pesan error berasal dari stok tidak cukup
        if (str_contains($errorMessage, 'Stok tidak mencukupi')) {
            return back()->withErrors(['msg' => $errorMessage]);
        }

        // Fallback umum
        return back()->withErrors(['msg' => 'Terjadi kesalahan saat menyimpan penjualan.']);
    }
}
    /** 👀 Detail Penjualan */
public function items($id)
{
    $penjualan = DB::selectOne("
        SELECT p.*, u.username, m.persen AS margin_persen
        FROM penjualan p
        JOIN user u ON u.iduser = p.iduser
        LEFT JOIN margin_penjualan m ON m.idmargin_penjualan = p.idmargin_penjualan
        WHERE p.idpenjualan = ?
    ", [$id]);

    $details = DB::select("
        SELECT d.*, b.nama AS nama_barang
        FROM detail_penjualan d
        JOIN barang b ON b.idbarang = d.idbarang
        WHERE d.idpenjualan = ?
    ", [$id]);

    return view('penjualan.items', compact('penjualan', 'details'));
}

}
