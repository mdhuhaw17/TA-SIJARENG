<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;


class AbsensiController extends Controller
{
    // MANUAL
    public function simpanAbsensi(Request $request)
    {
        foreach ($request->status as $userId => $status) {

            Absensi::updateOrCreate(

                [
                    'user_id' => $userId,
                    'tanggal' => Carbon::today()
                ],

                [
                    'status' => $status
                ]
            );
        }

        return back()->with(
            'success',
            'Absensi berhasil disimpan'
        );
    }

    // SCAN QR
    public function scanQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required'
        ]);

        // FORMAT QR
        // contoh: 1-Nama User

        $explode = explode('-', $request->qr_code);

        $userId = $explode[0] ?? null;

        $user = User::find($userId);

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // CHECK ABSEN HARI INI
        $cek = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($cek) {

            return response()->json([
                'success' => false,
                'message' => $user->name . ' sudah absen hari ini'
            ]);
        }

        // SIMPAN ABSENSI
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => Carbon::today(),
            'status' => 'hadir'
        ]);

        return response()->json([
            'success' => true,
            'message' => $user->name . ' berhasil absen',
            'user' => [
                'name' => $user->name,
                'kelas' => $user->kelas,
                'foto' => $user->foto
                    ? asset('storage/' . $user->foto)
                    : null
            ]
        ]);
    }

}