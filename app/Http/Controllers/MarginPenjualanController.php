<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MarginPenjualanController extends Controller
{
    /**
     * 📋 Tampilkan semua data margin dengan filter aktif / semua
     */
    public function index(Request $r)
    {
        $filter = $r->query('filter', 'aktif'); // default: aktif

        if ($filter === 'aktif') {
            $rows = DB::select("
                SELECT 
                    idmargin_penjualan AS idmargin,
                    persen AS persentase,
                    status,
                    created_at
                FROM margin_penjualan
                WHERE status = 1
                ORDER BY idmargin_penjualan DESC
            ");
        } else {
            $rows = DB::select("
                SELECT 
                    idmargin_penjualan AS idmargin,
                    persen AS persentase,
                    status,
                    created_at
                FROM margin_penjualan
                ORDER BY idmargin_penjualan DESC
            ");
        }

        return view('margin-penjualan.index', compact('rows', 'filter'));
    }

    /**
     * ➕ Tambah margin baru → otomatis aktif, margin lama nonaktif
     */
    public function store(Request $r)
    {
        $r->validate([
            'persentase' => 'required|numeric|min:0|max:100',
        ]);

        try {
            // 🔹 Jalankan stored procedure
            DB::statement("CALL sp_tambah_margin_penjualan(?, ?)", [
                $r->persentase,
                Auth::id() ?? 1, // fallback ke admin ID 1
            ]);

            return redirect()
                ->route('margin.index')
                ->with('ok', '✅ Margin baru berhasil ditambahkan dan otomatis aktif.');
        } catch (\Exception $e) {
            return back()->withErrors(['Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * ✏️ Update margin (ubah persentase + status aktif/nonaktif)
     */
    public function update($id, Request $r)
    {
        $r->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'status' => 'nullable|in:0,1',
        ]);

        try {
            // Jika status yang diubah menjadi aktif → margin lain otomatis nonaktif
            if ($r->status == 1) {
                DB::update("UPDATE margin_penjualan SET status = 0");
            }

            DB::update("
                UPDATE margin_penjualan
                SET persen = ?, status = ?, updated_at = NOW()
                WHERE idmargin_penjualan = ?
            ", [$r->persentase, $r->status ?? 0, $id]);

            return redirect()
                ->route('margin.index')
                ->with('ok', '✏️ Margin berhasil diperbarui.');
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
            // Cegah hapus jika hanya ada 1 margin aktif (optional safety)
            $cekAktif = DB::table('margin_penjualan')->where('status', 1)->count();
            $marginHapus = DB::table('margin_penjualan')->where('idmargin_penjualan', $id)->first();

            if ($cekAktif == 1 && $marginHapus && $marginHapus->status == 1) {
                return back()->withErrors(['Tidak bisa menghapus margin aktif terakhir.']);
            }

            DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
            return redirect()->route('margin.index')->with('ok', '🗑️ Margin berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['Database error: ' . $e->getMessage()]);
        }
    }
}
