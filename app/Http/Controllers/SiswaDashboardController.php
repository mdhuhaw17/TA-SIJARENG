<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $hadir = Absensi::where('user_id', $user->id)
            ->where('status', 'hadir')
            ->count();

        $izin = Absensi::where('user_id', $user->id)
            ->where('status', 'izin')
            ->count();

        $alfa = Absensi::where('user_id', $user->id)
            ->where('status', 'alfa')
            ->count();

        $absensiTerakhir = Absensi::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('siswa.dashboard', compact(
            'hadir',
            'izin',
            'alfa',
            'absensiTerakhir'
        ));
    }
}