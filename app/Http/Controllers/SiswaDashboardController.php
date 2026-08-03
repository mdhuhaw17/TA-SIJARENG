<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\CatatanSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // -------------------------------------------------------
    // REKAP PER USER (SISWA)
    // -------------------------------------------------------
    public function rekap(Request $request)
    {
        $user  = Auth::user();
        $tahun = $request->get('tahun', Carbon::now()->year);

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 4);

        // Rekap per bulan dalam tahun yang dipilih
        $rekapBulanan = [];
        for ($b = 1; $b <= 12; $b++) {
            $absensi = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $b)
                ->whereYear('tanggal', $tahun)
                ->get();

            $hadir = $absensi->where('status', 'hadir')->count();
            $izin  = $absensi->where('status', 'izin')->count();
            $alfa  = $absensi->where('status', 'alfa')->count();
            $total = $absensi->count();

            $rekapBulanan[] = [
                'bulan' => Carbon::create($tahun, $b, 1)->translatedFormat('F'),
                'hadir' => $hadir,
                'izin'  => $izin,
                'alfa'  => $alfa,
                'total' => $total,
                'persen'=> $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        }

        // Total keseluruhan tahun
        $totalHadir = array_sum(array_column($rekapBulanan, 'hadir'));
        $totalIzin  = array_sum(array_column($rekapBulanan, 'izin'));
        $totalAlfa  = array_sum(array_column($rekapBulanan, 'alfa'));
        $totalAll   = array_sum(array_column($rekapBulanan, 'total'));
        $persenTotal= $totalAll > 0 ? round(($totalHadir / $totalAll) * 100) : 0;

        // Ambil SEMUA catatan dari admin untuk tahun ini (mingguan, bulanan, tahunan)
        $semuaCatatan = CatatanSiswa::where('user_id', $user->id)
            ->where('tahun', $tahun)
            ->orderBy('periode')
            ->orderBy('minggu')
            ->get();

        // Kelompokkan per bulan (array: key = nomor bulan 1-12, value = array catatan)
        $catatanPerBulan = [];
        $catatanTahunan  = null;

        foreach ($semuaCatatan as $cat) {
            if ($cat->periode === 'tahunan') {
                $catatanTahunan = $cat;
            } elseif ($cat->bulan) {
                $catatanPerBulan[(int) $cat->bulan][] = $cat;
            }
        }

        return view('siswa.rekap', compact(
            'rekapBulanan', 'tahun', 'tahunList',
            'totalHadir', 'totalIzin', 'totalAlfa', 'totalAll', 'persenTotal',
            'catatanPerBulan', 'catatanTahunan'
        ));

    }

    public function downloadRekapPdf(Request $request)
    {
        $user  = Auth::user();

        $tahun = $request->get('tahun', Carbon::now()->year);

        $rekapBulanan = [];
        for ($b = 1; $b <= 12; $b++) {
            $absensi = Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', $b)
                ->whereYear('tanggal', $tahun)
                ->get();

            $hadir = $absensi->where('status', 'hadir')->count();
            $izin  = $absensi->where('status', 'izin')->count();
            $alfa  = $absensi->where('status', 'alfa')->count();
            $total = $absensi->count();

            $rekapBulanan[] = [
                'bulan' => Carbon::create($tahun, $b, 1)->translatedFormat('F'),
                'hadir' => $hadir,
                'izin'  => $izin,
                'alfa'  => $alfa,
                'total' => $total,
                'persen'=> $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        }

        $totalHadir = array_sum(array_column($rekapBulanan, 'hadir'));
        $totalIzin  = array_sum(array_column($rekapBulanan, 'izin'));
        $totalAlfa  = array_sum(array_column($rekapBulanan, 'alfa'));
        $totalAll   = array_sum(array_column($rekapBulanan, 'total'));
        $persenTotal= $totalAll > 0 ? round(($totalHadir / $totalAll) * 100) : 0;

        // Ambil SEMUA catatan dari admin untuk PDF
        $semuaCatatan = CatatanSiswa::where('user_id', $user->id)
            ->where('tahun', $tahun)
            ->orderBy('periode')
            ->orderBy('minggu')
            ->get();

        $catatanPerBulan = [];
        $catatanTahunan  = null;
        foreach ($semuaCatatan as $cat) {
            if ($cat->periode === 'tahunan') {
                $catatanTahunan = $cat;
            } elseif ($cat->bulan) {
                $catatanPerBulan[(int) $cat->bulan][] = $cat;
            }
        }

        $pdf = Pdf::loadView('siswa.rekap-pdf', compact(
            'user', 'rekapBulanan', 'tahun',
            'totalHadir', 'totalIzin', 'totalAlfa', 'totalAll', 'persenTotal',
            'catatanPerBulan', 'catatanTahunan'
        ))->setPaper('a4', 'landscape');

        $filename = 'rekap-absensi-' . $user->name . '-' . $tahun . '.pdf';

        return $pdf->download($filename);
        
    }
}