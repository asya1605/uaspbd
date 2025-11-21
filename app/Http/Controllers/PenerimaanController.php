<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenerimaanController extends Controller
{
    /** 📋 List batch yang sudah diterima */
    public function index()
    {
        $rows = DB::select("
            SELECT pr.*, p.idpengadaan, v.nama_vendor
            FROM penerimaan pr
            JOIN pengadaan p ON p.idpengadaan = pr.idpengadaan
            JOIN vendor v ON v.idvendor = p.vendor_idvendor
            WHERE pr.status = 'diterima'
            ORDER BY pr.idpenerimaan DESC
        ");

        // Cek apakah masih ada pengadaan yang punya sisa barang
        $cek = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM pengadaan p
            JOIN detail_pengadaan dp ON dp.idpengadaan = p.idpengadaan
            LEFT JOIN (
                SELECT dpn.idbarang, pn.idpengadaan, SUM(dpn.jumlah_terima) AS total_diterima
                FROM detail_penerimaan dpn
                JOIN penerimaan pn ON pn.idpenerimaan = dpn.idpenerimaan
                GROUP BY dpn.idbarang, pn.idpengadaan
            ) dr ON dr.idbarang = dp.idbarang AND dr.idpengadaan = dp.idpengadaan
            WHERE p.status != 'selesai' 
              AND dp.jumlah > IFNULL(dr.total_diterima, 0)
        ");

        $bolehTambah = ($cek->total > 0);

        return view('penerimaan.index', compact('rows', 'bolehTambah'));
    }

    /** ➕ Form create batch penerimaan */
    public function create()
    {
        $pengadaan = DB::select("
            SELECT DISTINCT p.idpengadaan, v.nama_vendor
            FROM pengadaan p
            JOIN vendor v ON v.idvendor = p.vendor_idvendor
            JOIN detail_pengadaan dp ON dp.idpengadaan = p.idpengadaan
            WHERE p.status != 'selesai'
              AND EXISTS (
                  SELECT 1
                  FROM detail_pengadaan dpx
                  WHERE dpx.idpengadaan = p.idpengadaan
                    AND dpx.jumlah > (
                        SELECT IFNULL(SUM(dpn2.jumlah_terima), 0)
                        FROM detail_penerimaan dpn2
                        JOIN penerimaan pn2 ON pn2.idpenerimaan = dpn2.idpenerimaan
                        WHERE pn2.idpengadaan = p.idpengadaan
                          AND dpn2.idbarang = dpx.idbarang
                    )
              )
            ORDER BY p.idpengadaan DESC
        ");

        return view('penerimaan.create', compact('pengadaan'));
    }

    /** 🔁 Load barang yang masih punya sisa */
    public function loadBarang($idpengadaan)
    {
        $barang = DB::select("
            SELECT 
                b.idbarang,
                b.nama AS nama_barang,
                s.nama_satuan,
                dp.jumlah AS jumlah_pesan,
                dp.harga_satuan,
                (dp.jumlah - IFNULL(SUM(dpn.jumlah_terima), 0)) AS sisa
            FROM detail_pengadaan dp
            JOIN barang b ON b.idbarang = dp.idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
            LEFT JOIN detail_penerimaan dpn
                ON dpn.idbarang = dp.idbarang
                AND dpn.idpenerimaan IN (
                    SELECT idpenerimaan
                    FROM penerimaan
                    WHERE idpengadaan = dp.idpengadaan
                )
            WHERE dp.idpengadaan = ?
            GROUP BY b.idbarang, b.nama, s.nama_satuan, dp.jumlah, dp.harga_satuan
            HAVING sisa > 0
        ", [$idpengadaan]);

        return response()->json($barang);
    }

    /** 💾 Simpan batch penerimaan */
    public function store(Request $r)
    {
        $r->validate([
            'idpengadaan' => 'required|integer',
            'jumlah_terima' => 'required|array',
        ]);

        $idpengadaan = $r->idpengadaan;
        $iduser = session('user')['iduser'] ?? 1;
        $username = session('user')['username'] ?? 'superadmin';

        // Insert batch baru
        DB::insert("
            INSERT INTO penerimaan (idpengadaan, iduser, username, status, created_at)
            VALUES (?, ?, ?, 'pending', NOW())
        ", [$idpengadaan, $iduser, $username]);

        $idpenerimaan = DB::getPdo()->lastInsertId();

        // Loop simpan detail + update stok via SP
        foreach ($r->jumlah_terima as $idbarang => $jumlah) {
            $harga = $r->harga_terima[$idbarang] ?? 0;

            DB::statement("CALL proc_tambah_detail_penerimaan(?, ?, ?, ?, ?)", [
                $idpenerimaan,
                $idbarang,
                $jumlah,
                $harga,
                $iduser
            ]);
        }

        return redirect()->route('penerimaan.index')
            ->with('ok', '✅ Batch penerimaan berhasil ditambahkan.');
    }

    /** 📦 Detail batch penerimaan */
    public function items($id)
    {
        $penerimaan = DB::selectOne("
            SELECT pr.*, v.nama_vendor, p.status AS status_pengadaan
            FROM penerimaan pr
            JOIN pengadaan p ON p.idpengadaan = pr.idpengadaan
            JOIN vendor v ON v.idvendor = p.vendor_idvendor
            WHERE pr.idpenerimaan = ?
        ", [$id]);

        if (!$penerimaan) {
            return redirect()->route('penerimaan.index')
                ->withErrors(['msg' => '❌ Data tidak ditemukan.']);
        }

        $details = DB::select("
            SELECT dpn.*, b.nama AS nama_barang, s.nama_satuan
            FROM detail_penerimaan dpn
            JOIN barang b ON b.idbarang = dpn.idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
            WHERE dpn.idpenerimaan = ?
            ORDER BY dpn.iddetail_penerimaan
        ", [$id]);

        return view('penerimaan.items', compact('penerimaan', 'details'));
    }
}

