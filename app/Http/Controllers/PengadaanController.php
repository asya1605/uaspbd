<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    /** 📋 List Pengadaan */
    public function index(Request $r)
    {
        $filter = $r->get('filter', 'all');

        // Filter dinamis menggunakan real-time status
        $statusFilter = "";
        if ($filter == "proses") {
            $statusFilter = "HAVING real_status = 'proses'";
        } elseif ($filter == "selesai") {
            $statusFilter = "HAVING real_status = 'selesai'";
        }

        // Query utama
        $rows = DB::select("
            SELECT 
                p.*,
                v.nama_vendor,
                u.username,

                -- Total pesan
                (SELECT SUM(jumlah) 
                 FROM detail_pengadaan dp 
                 WHERE dp.idpengadaan = p.idpengadaan) AS total_pesan,

                -- Total diterima
                (SELECT IFNULL(SUM(dpn.jumlah_terima),0)
                 FROM detail_penerimaan dpn
                 JOIN penerimaan pn ON pn.idpenerimaan = dpn.idpenerimaan
                 WHERE pn.idpengadaan = p.idpengadaan
                 AND pn.status = 'diterima') AS total_terima,

                -- Status realtime
                CASE 
                    WHEN (
                        (SELECT SUM(jumlah) 
                         FROM detail_pengadaan dp 
                         WHERE dp.idpengadaan = p.idpengadaan)
                        <=
                        (SELECT IFNULL(SUM(dpn.jumlah_terima),0)
                         FROM detail_penerimaan dpn
                         JOIN penerimaan pn ON pn.idpenerimaan = dpn.idpenerimaan
                         WHERE pn.idpengadaan = p.idpengadaan
                         AND pn.status='diterima')
                    ) THEN 'selesai'
                    ELSE 'proses'
                END AS real_status

            FROM pengadaan p
            JOIN vendor v ON v.idvendor = p.vendor_idvendor
            JOIN user u ON u.iduser = p.user_iduser

            $statusFilter
            ORDER BY p.idpengadaan DESC
        ");

        return view('pengadaan.index', compact('rows', 'filter'));
    }

    /** ➕ Form Create Pengadaan */
    public function create()
    {
        // Vendor aktif
        $vendors = DB::select("
            SELECT idvendor, nama_vendor 
            FROM vendor
            WHERE status = 1
            ORDER BY nama_vendor
        ");

        // Barang aktif saja
        $barangs = DB::select("
            SELECT b.idbarang, b.nama, b.harga, s.nama_satuan
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
            WHERE b.status = 1
            ORDER BY b.nama
        ");

        return view('pengadaan.create', compact('vendors', 'barangs'));
    }

    /** 💾 Simpan Pengadaan */
    public function store(Request $r)
    {
        $r->validate([
            'vendor_idvendor' => 'required|integer',
            'subtotal_nilai' => 'required|numeric|min:0',
            'list_json' => 'required|string',
        ]);

        $user = session('user');
        if (!$user) {
            return redirect()->route('login.form')
                ->withErrors(['error' => 'Silakan login terlebih dahulu.']);
        }

        $iduser = $user['iduser'];

        // Validasi vendor aktif
        $vendor = DB::selectOne("SELECT status FROM vendor WHERE idvendor = ?", [$r->vendor_idvendor]);
        if (!$vendor || $vendor->status != 1) {
            return back()->withErrors(['msg' => '❌ Vendor ini nonaktif.']);
        }

        // Insert pengadaan
        DB::statement("
            INSERT INTO pengadaan (
                user_iduser, vendor_idvendor, status,
                subtotal_nilai, ppn, total_nilai, total_barang, timestamp
            )
            VALUES (?, ?, 'proses', ?, hitung_ppn(?), (? + hitung_ppn(?)), 0, NOW())
        ", [
            $iduser,
            $r->vendor_idvendor,
            $r->subtotal_nilai,
            $r->subtotal_nilai,
            $r->subtotal_nilai,
            $r->subtotal_nilai
        ]);

        $idpengadaan = DB::getPdo()->lastInsertId();

        // Simpan detail melalui SP
        $list = json_decode($r->list_json, true);
        if ($list) {
            foreach ($list as $i) {
                DB::statement("CALL tambah_detail_pengadaan(?, ?, ?, ?)", [
                    $idpengadaan,
                    $i['idbarang'],
                    $i['harga'],
                    $i['jumlah']
                ]);
            }
        }

        return redirect()->route('pengadaan.index')
            ->with('ok', '✅ Pengadaan baru berhasil ditambahkan.');
    }

    /** 🔍 Detail Pengadaan */
public function items($id)
{
    // Ambil data pengadaan
    $pengadaan = DB::selectOne("
        SELECT 
            p.*,
            v.nama_vendor,
            u.username,

            -- Hitung total pesan
            (SELECT SUM(jumlah) 
             FROM detail_pengadaan dp 
             WHERE dp.idpengadaan = p.idpengadaan) AS total_pesan,

            -- Hitung total diterima (yang sudah diterima)
            (SELECT SUM(dpn.jumlah_terima)
             FROM detail_penerimaan dpn
             JOIN penerimaan pn ON pn.idpenerimaan = dpn.idpenerimaan
             WHERE pn.idpengadaan = p.idpengadaan
               AND pn.status = 'diterima'
            ) AS total_terima
        FROM pengadaan p
        JOIN vendor v ON v.idvendor = p.vendor_idvendor
        JOIN user u ON u.iduser = p.user_iduser
        WHERE p.idpengadaan = ?
        LIMIT 1
    ", [$id]);

    if (!$pengadaan) {
        return redirect()->route('pengadaan.index')
            ->withErrors(['msg' => 'Data pengadaan tidak ditemukan']);
    }

    // Hitung status real-time
    $totalPesan = intval($pengadaan->total_pesan ?? 0);
    $totalTerima = intval($pengadaan->total_terima ?? 0);

    $pengadaan->status = ($totalTerima >= $totalPesan && $totalPesan > 0)
        ? 'selesai'
        : 'proses';

    // Ambil item pengadaan
    $items = DB::select("
        SELECT dp.*, b.nama AS nama_barang
        FROM detail_pengadaan dp
        JOIN barang b ON b.idbarang = dp.idbarang
        WHERE dp.idpengadaan = ?
    ", [$id]);

    return view('pengadaan.items', compact('pengadaan', 'items'));
}

    /** ❌ Delete */
    public function delete($id)
    {
        DB::statement("CALL sp_hapus_pengadaan(?)", [$id]);
        return back()->with('ok', '🗑️ Pengadaan berhasil dihapus.');
    }
}
