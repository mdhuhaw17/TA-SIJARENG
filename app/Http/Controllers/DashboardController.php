<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Absensi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // TOTAL SISWA
        $totalSiswa = User::where('role', 'siswa')->count();

        // TOTAL KELAS
        $totalKelas = Group::count();

        // HADIR
        $totalHadir = Absensi::whereDate(
            'tanggal',
            Carbon::today()
        )
        ->where('status', 'hadir')
        ->count();

        // IZIN
        $totalIzin = Absensi::whereDate(
            'tanggal',
            Carbon::today()
        )
        ->where('status', 'izin')
        ->count();

        // ALFA
        $totalAlfa = Absensi::whereDate(
            'tanggal',
            Carbon::today()
        )
        ->where('status', 'alfa')
        ->count();

        // SUDAH ABSEN
        $sudahAbsen = Absensi::whereDate(
            'tanggal',
            Carbon::today()
        )->count();

        // BELUM ABSEN
        $belumAbsen = $totalSiswa - $sudahAbsen;

        // PERSENTASE
        $persentase = 0;

        if ($totalSiswa > 0) {

            $persentase = round(
                ($totalHadir / $totalSiswa) * 100
            );
        }

        // DATA DETAIL SISWA
        $hadirUsers = Absensi::with('user')
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'hadir')
            ->get();

        $izinUsers = Absensi::with('user')
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'izin')
            ->get();

        $alfaUsers = Absensi::with('user')
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'alfa')
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalKelas',
            'totalHadir',
            'totalIzin',
            'totalAlfa',
            'belumAbsen',
            'persentase',
            'hadirUsers',
            'izinUsers',
            'alfaUsers'
        ));
    }
}