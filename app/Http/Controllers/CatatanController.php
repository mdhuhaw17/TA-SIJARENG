<?php

namespace App\Http\Controllers;

use App\Models\CatatanSiswa;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    /**
     * Simpan atau update catatan admin untuk siswa tertentu.
     */
    public function simpan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periode' => 'required|in:mingguan,bulanan,tahunan',
            'tahun'   => 'required|integer',
            'bulan'   => 'nullable|integer|min:1|max:12',
            'minggu'  => 'nullable|integer|min:1|max:5',
            'catatan' => 'required|string|max:1000',
        ]);

        CatatanSiswa::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'periode' => $request->periode,
                'bulan'   => $request->bulan,
                'tahun'   => $request->tahun,
                'minggu'  => $request->minggu,
            ],
            [
                'catatan' => $request->catatan,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Catatan berhasil disimpan']);
    }

    /**
     * Hapus catatan admin untuk siswa tertentu.
     */
    public function hapus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periode' => 'required|in:mingguan,bulanan,tahunan',
            'tahun'   => 'required|integer',
            'bulan'   => 'nullable|integer',
            'minggu'  => 'nullable|integer',
        ]);

        CatatanSiswa::where([
            'user_id' => $request->user_id,
            'periode' => $request->periode,
            'bulan'   => $request->bulan,
            'tahun'   => $request->tahun,
            'minggu'  => $request->minggu,
        ])->delete();

        return response()->json(['success' => true, 'message' => 'Catatan berhasil dihapus']);
    }
}
