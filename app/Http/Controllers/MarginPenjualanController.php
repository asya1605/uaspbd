<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MarginPenjualanController extends Controller
{
    /**
     * 🩷 Tampilkan semua data margin penjualan
     */
    public function index()
    {
        try {
            $rows = DB::select("
                SELECT 
                    idmargin_penjualan AS idmargin,
                    persen AS persentase,
                    status
                FROM margin_penjualan
                ORDER BY idmargin_penjualan DESC
            ");

            return view('margin-penjualan.index', compact('rows'));
        } catch (\Exception $e) {
            return back()->withErrors(['Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * 💾 Tambah data margin baru
     */
    public function store(Request $r)
    {
        $r->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1'
        ]);

        try {
            DB::insert("
                INSERT INTO margin_penjualan (persen, status, created_at, updated_at)
                VALUES (?, ?, NOW(), NOW())
            ", [$r->persentase, $r->status]);

            return redirect()->route('margin.index')->with('ok', '✅ Margin penjualan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * ✏️ Update data margin
     */
    public function update($id, Request $r)
    {
        $r->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:0,1'
        ]);

        try {
            DB::update("
                UPDATE margin_penjualan
                SET persen=?, status=?, updated_at=NOW()
                WHERE idmargin_penjualan=?
            ", [$r->persentase, $r->status, $id]);

            return redirect()->route('margin.index')->with('ok', '✏️ Margin penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * 🗑️ Hapus data margin
     */
    public function delete($id)
    {
        try {
            DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan=?", [$id]);
            return redirect()->route('margin.index')->with('ok', '🗑️ Margin penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['Database error: ' . $e->getMessage()]);
        }
    }
}
