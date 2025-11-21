<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenerimaanController extends Controller
{
    /** 📋 List batch penerimaan */
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

    // Cek apakah masih ada pengadaan yang punya sisa
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
        SELECT p.idpengadaan, v.nama_vendor
        FROM pengadaan p
        JOIN vendor v ON v.idvendor = p.vendor_idvendor
        WHERE (
            (SELECT SUM(jumlah) FROM detail_pengadaan dp 
             WHERE dp.idpengadaan = p.idpengadaan)
            >
            (SELECT IFNULL(SUM(dpn.jumlah_terima),0)
             FROM detail_penerimaan dpn
             JOIN penerimaan pn ON pn.idpenerimaan = dpn.idpenerimaan
             WHERE pn.idpengadaan = p.idpengadaan
             AND pn.status = 'diterima')
        )
        ORDER BY p.idpengadaan DESC
    ");

    return view('penerimaan.create', compact('pengadaan'));
}

    /** 🔁 Load barang berdasarkan id pengadaan */
    public function loadBarang($idpengadaan)
    {
        $barang = DB::select("
            SELECT b.idbarang, b.nama AS nama_barang, s.nama_satuan,
                dp.jumlah AS jumlah_pesan,
                dp.harga_satuan,
                (dp.jumlah - IFNULL((
                    SELECT SUM(dpn.jumlah_terima)
                    FROM detail_penerimaan dpn
                    JOIN penerimaan pn ON pn.idpenerimaan = dpn.idpenerimaan
                    WHERE pn.idpengadaan = dp.idpengadaan
                      AND dpn.idbarang = dp.idbarang
                ), 0)) AS sisa
            FROM detail_pengadaan dp
            JOIN barang b ON b.idbarang = dp.idbarang
            JOIN satuan s ON s.idsatuan = b.idsatuan
            WHERE dp.idpengadaan = ?
            HAVING sisa > 0
        ", [$idpengadaan]);

        return response()->json($barang);
    }

    /** 💾 Simpan penerimaan */
public function store(Request $r)
{
    $r->validate([
        'idpengadaan' => 'required|integer',
        'jumlah_terima' => 'required|array',
    ]);

    $idpengadaan = $r->idpengadaan;
    $iduser = session('user')['iduser'] ?? 1;
    $username = session('user')['username'] ?? 'superadmin';

    // STATUS WAJIB 'diterima'
    DB::insert("
        INSERT INTO penerimaan (idpengadaan, iduser, username, status, created_at)
        VALUES (?, ?, ?, 'diterima', NOW())
    ", [$idpengadaan, $iduser, $username]);

    $idpenerimaan = DB::getPdo()->lastInsertId();

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
        ->with('ok', 'Batch penerimaan berhasil ditambahkan.');
}
    /** 📦 Detail penerimaan */
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
                ->withErrors(['msg' => 'Data tidak ditemukan']);
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

    /** ✔ KONFIRMASI PENERIMAAN */
public function confirm($id)
{
    // 1. Update status penerimaan
    DB::update("
        UPDATE penerimaan
        SET status = 'diterima', verified_at = NOW()
        WHERE idpenerimaan = ? AND status = 'pending'
    ", [$id]);

    // 2. Ambil idpengadaan
    $idpengadaan = DB::selectOne("
        SELECT idpengadaan FROM penerimaan WHERE idpenerimaan = ?
    ", [$id])->idpengadaan;

    // 3. Panggil stored procedure update status pengadaan
    DB::statement("CALL sp_update_status_pengadaan(?)", [$idpengadaan]);

    return back()->with('ok', 'Penerimaan berhasil dikonfirmasi.');
}
}
