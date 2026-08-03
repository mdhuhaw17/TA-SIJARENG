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

        // QR code format: "id-nama", ambil bagian ID saja
        $qrRaw  = trim($request->qr_code);
        $userId = explode('-', $qrRaw)[0];

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau user tidak ditemukan'
            ]);
        }

        $cek = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => $user->name . ' sudah absen hari ini'
            ]);
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => Carbon::today(),
            'status'  => 'hadir'
        ]);

        return response()->json([
            'success' => true,
            'message' => $user->name . ' berhasil absen',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'kelas' => $user->kelas,
                'foto'  => $user->foto
                    ? asset('storage/' . $user->foto)
                    : null
            ]
        ]);
    }

    // SCAN WAJAH
    public function scanWajah(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ]);
        }

        $cek = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        if ($cek) {
            return response()->json([
                'success' => false,
                'message' => $user->name . ' sudah absen hari ini',
                'already_absen' => true,
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'kelas' => $user->kelas,
                    'foto'  => $user->foto
                        ? asset('storage/' . $user->foto)
                        : null
                ]
            ]);
        }

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => Carbon::today(),
            'status'  => 'hadir'
        ]);

        return response()->json([
            'success' => true,
            'message' => $user->name . ' berhasil absen',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'kelas' => $user->kelas,
                'foto'  => $user->foto
                    ? asset('storage/' . $user->foto)
                    : null
            ]
        ]);
    }

}