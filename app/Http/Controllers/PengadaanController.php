<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    // 📋 Daftar pengadaan
    public function index()
    {
        $rows = DB::select("SELECT * FROM pengadaan_vu ORDER BY idpengadaan DESC");
        return view('pengadaan.index', compact('rows'));
    }

    // ➕ Form Tambah Pengadaan
    public function create()
    {
        $vendors = DB::select("SELECT * FROM vendor ORDER BY nama_vendor");
        $barangs = DB::select("
            SELECT b.idbarang, b.nama, b.harga, s.nama_satuan
            FROM barang b
            JOIN satuan s ON s.idsatuan = b.idsatuan
            ORDER BY b.nama
        ");
        return view('pengadaan.create', compact('vendors', 'barangs'));
    }

    // 💾 Simpan Pengadaan Baru (langsung lengkap)
    public function store(Request $r)
    {
        $r->validate([
            'vendor_idvendor' => 'required|integer',
            'status' => 'required|in:0,1',
            'subtotal_nilai' => 'required|numeric|min:0',
            'list_json' => 'required|string'
        ]);

        // 🧍 Ambil iduser dari session login
        $user = session('user');
        if (!$user) {
            return redirect()->route('login.form')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
        }

        $iduser = $user['iduser'];

        // 🧾 Simpan data utama ke tabel pengadaan
        DB::statement("
            INSERT INTO pengadaan (
                user_iduser, vendor_idvendor, status,
                subtotal_nilai, ppn, total_nilai, total_barang, timestamp
            )
            VALUES (?, ?, ?, ?, hitung_ppn(?), (? + hitung_ppn(?)), 0, NOW())
        ", [
            $iduser,
            $r->vendor_idvendor,
            $r->status,
            $r->subtotal_nilai,
            $r->subtotal_nilai,
            $r->subtotal_nilai,
            $r->subtotal_nilai
        ]);

        // Ambil ID pengadaan terakhir
        $idpengadaan = DB::getPdo()->lastInsertId();

        // 🧩 Simpan detail barang dari list JSON (jika ada)
        $list = json_decode($r->list_json, true);
        if ($list && count($list) > 0) {
            foreach ($list as $item) {
                DB::statement("CALL tambah_detail_pengadaan(?, ?, ?, ?)", [
                    $idpengadaan,
                    $item['idbarang'],
                    $item['harga'],
                    $item['jumlah']
                ]);
            }
        }

        return redirect()->route('pengadaan.index')
            ->with('ok', '✅ Pengadaan dan detail barang berhasil ditambahkan.');
    }

    // 🧾 Tambah Barang dari halaman detail pengadaan
    public function addItem($id, Request $r)
    {
        $r->validate([
            'idbarang' => 'required|integer',
            'harga_satuan' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1'
        ]);

        DB::statement("CALL tambah_detail_pengadaan(?, ?, ?, ?)", [
            $id,
            $r->idbarang,
            $r->harga_satuan,
            $r->jumlah
        ]);

        return back()->with('ok', '🧾 Barang berhasil ditambahkan ke pengadaan.');
    }

    // 🔍 Detail Barang Pengadaan
    public function items($id)
    {
        // Ambil data pengadaan dari view
        $pengadaan = DB::selectOne("SELECT * FROM pengadaan_vu WHERE idpengadaan = ?", [$id]);

        // Ambil daftar detail barang
        $items = DB::select("
            SELECT dp.iddetail_pengadaan, dp.harga_satuan, dp.jumlah, dp.sub_total,
                   b.nama AS nama_barang
            FROM detail_pengadaan dp
            JOIN barang b ON b.idbarang = dp.idbarang
            WHERE dp.idpengadaan = ?
        ", [$id]);

        // 🔧 Kirim variabel yang sesuai ke Blade
        return view('pengadaan.items', [
            'pengadaan' => $pengadaan,
            'items' => $items,
            'idpengadaan' => $id // ✅ Fix error Undefined variable $idpengadaan
        ]);
    }

    // ❌ Hapus Pengadaan
    public function delete($id)
    {
        DB::statement("CALL sp_hapus_pengadaan(?)", [$id]);
        return back()->with('ok', '🗑️ Pengadaan berhasil dihapus.');
    }
}
