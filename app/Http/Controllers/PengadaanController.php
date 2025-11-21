<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    /** 📋 List pengadaan */
    public function index(Request $r)
    {
        $filter = $r->get('filter', 'all');
        $where = "";

        if ($filter === "proses") {
            $where = "WHERE p.status = 'proses'";
        } elseif ($filter === "selesai") {
            $where = "WHERE p.status = 'selesai'";
        }

        $rows = DB::select("
            SELECT p.*, v.nama_vendor, u.username
            FROM pengadaan p
            JOIN vendor v ON v.idvendor = p.vendor_idvendor
            JOIN user u ON u.iduser = p.user_iduser
            $where
            ORDER BY p.idpengadaan DESC
        ");

        return view('pengadaan.index', compact('rows', 'filter'));
    }

    /** ➕ Form tambah pengadaan */
    public function create()
    {
        $vendors = DB::select("
            SELECT idvendor, nama_vendor 
            FROM vendor
            WHERE status = 1
            ORDER BY nama_vendor
        ");

        $barangs = DB::select("
            SELECT b.idbarang, b.nama, b.harga, s.nama_satuan
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
            ORDER BY b.nama
        ");

        return view('pengadaan.create', compact('vendors', 'barangs'));
    }

    /** 💾 Simpan pengadaan */
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

        // Cek vendor
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

        // Simpan detail barang melalui SP
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

    /** 🔍 Detail pengadaan */
    public function items($id)
    {
        $pengadaan = DB::selectOne("
            SELECT p.*, v.nama_vendor, u.username
            FROM pengadaan p
            JOIN vendor v ON v.idvendor = p.vendor_idvendor
            JOIN user u ON u.iduser = p.user_iduser
            WHERE p.idpengadaan = ?
        ", [$id]);

        $items = DB::select("
            SELECT dp.*, b.nama AS nama_barang
            FROM detail_pengadaan dp
            JOIN barang b ON b.idbarang = dp.idbarang
            WHERE dp.idpengadaan = ?
        ", [$id]);

        return view('pengadaan.items', compact('pengadaan', 'items'));
    }

    /** ❌ Hapus pengadaan */
    public function delete($id)
    {
        DB::statement("CALL sp_hapus_pengadaan(?)", [$id]);
        return back()->with('ok', '🗑️ Pengadaan berhasil dihapus.');
    }
}

