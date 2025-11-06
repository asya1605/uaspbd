<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    /**
     * 🧾 Menampilkan daftar barang aktif & form update stok
     */
    public function index()
    {
        // Ambil semua barang aktif beserta stok terakhir dari FUNCTION stok_barang()
        $barang = DB::select("
            SELECT 
                b.idbarang, 
                b.nama, 
                b.harga, 
                IFNULL(stok_barang(b.idbarang), 0) AS stok_terakhir
            FROM barang b
            WHERE b.status = 1
            ORDER BY b.nama
        ");

        return view('stok.update', compact('barang'));
    }

    /**
     * ⚙️ Proses Update Stok Barang
     * tipe = 'M' (Masuk) → Insert ke penerimaan
     * tipe = 'K' (Keluar) → Insert ke penjualan
     */
    public function update(Request $r)
    {
        // 🔍 Validasi input
        $r->validate([
            'idbarang' => 'required|integer',
            'jumlah'   => 'required|integer|min:1',
            'harga'    => 'required|numeric|min:0',
            'tipe'     => 'required|in:M,K'
        ]);

        // ✅ Hitung subtotal
        $subtotal = $r->jumlah * $r->harga;

        if ($r->tipe === 'M') {
            // ---------------------------------------------------------
            // 🟢 STOK MASUK (PENERIMAAN)
            // ---------------------------------------------------------
            // 1️⃣ Insert ke tabel penerimaan
            DB::insert("
                INSERT INTO penerimaan (created_at, status, idpengadaan, iduser)
                VALUES (NOW(), '1', NULL, 1)
            ");
            $idpenerimaan = DB::getPdo()->lastInsertId();

            // 2️⃣ Insert ke tabel detail_penerimaan
            DB::insert("
                INSERT INTO detail_penerimaan 
                    (jumlah_terima, harga_satuan_terima, sub_total_terima, idpenerimaan, idbarang)
                VALUES (?, ?, ?, ?, ?)
            ", [$r->jumlah, $r->harga, $subtotal, $idpenerimaan, $r->idbarang]);

        } else {
            // ---------------------------------------------------------
            // 🔴 STOK KELUAR (PENJUALAN)
            // ---------------------------------------------------------
            // 1️⃣ Insert ke tabel penjualan
            DB::insert("
                INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser)
                VALUES (NOW(), 0, 0, 0, 1)
            ");
            $idpenjualan = DB::getPdo()->lastInsertId();

            // 2️⃣ Insert ke tabel detail_penjualan
            DB::insert("
                INSERT INTO detail_penjualan 
                    (jumlah_jual, harga_satuan_jual, sub_total_jual, idpenjualan, idbarang)
                VALUES (?, ?, ?, ?, ?)
            ", [$r->jumlah, $r->harga, $subtotal, $idpenjualan, $r->idbarang]);
        }

        // ---------------------------------------------------------
        // 📦 Ambil stok akhir setelah perubahan
        // ---------------------------------------------------------
        $stokAkhir = DB::selectOne("SELECT stok_barang(?) AS stok_akhir", [$r->idbarang])->stok_akhir;
        $aksi = $r->tipe === 'M' ? 'ditambahkan' : 'dikurangi';

        return back()->with('ok', "✅ Stok barang berhasil $aksi. Sisa stok sekarang: $stokAkhir");
    }
}
