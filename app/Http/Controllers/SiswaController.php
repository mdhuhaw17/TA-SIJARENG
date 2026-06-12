<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;

class SiswaController extends Controller
{
    public function profil()
    {
        return view('siswa.profil');
    }

    public function qrSaya()
    {
        return view('siswa.qrsaya');
    }

    public function riwayat()
    {
        $riwayat = Absensi::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('siswa.riwayat', compact('riwayat'));
    }
}