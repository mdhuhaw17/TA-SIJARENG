<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use App\Models\CatatanSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'mingguan');
        $bulan   = $request->get('bulan', Carbon::now()->month);
        $tahun   = $request->get('tahun', Carbon::now()->year);
        $minggu  = $request->get('minggu', 1); // minggu ke-1..5

        $siswaList = User::where('role', 'siswa')->orderBy('kelas')->orderBy('name')->get();

        $rekap = $this->buildRekap($siswaList, $periode, $bulan, $tahun, $minggu);

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 4);

        return view('admin.laporan', compact(
            'rekap', 'periode', 'bulan', 'tahun', 'minggu', 'tahunList'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $periode = $request->get('periode', 'mingguan');
        $bulan   = $request->get('bulan', Carbon::now()->month);
        $tahun   = $request->get('tahun', Carbon::now()->year);
        $minggu  = $request->get('minggu', 1);

        $siswaList = User::where('role', 'siswa')->orderBy('kelas')->orderBy('name')->get();

        $rekap = $this->buildRekap($siswaList, $periode, $bulan, $tahun, $minggu);

        $judulPeriode = $this->judulPeriode($periode, $bulan, $tahun, $minggu);

        $pdf = Pdf::loadView('admin.laporan-pdf', compact('rekap', 'judulPeriode', 'periode'))
                  ->setPaper('a4', 'landscape');

        $filename = 'rekap-absensi-' . $periode . '-' . $tahun . '.pdf';

        return $pdf->download($filename);
    }

    // -------------------------------------------------------
    private function buildRekap($siswaList, $periode, $bulan, $tahun, $minggu)
    {
        $rekap = [];

        // Ambil semua catatan untuk periode ini sekaligus
        $catatanQuery = CatatanSiswa::where('periode', $periode)
            ->where('tahun', $tahun)
            ->whereIn('user_id', $siswaList->pluck('id'));

        if ($periode === 'mingguan') {
            $catatanQuery->where('bulan', $bulan)->where('minggu', $minggu);
        } elseif ($periode === 'bulanan') {
            $catatanQuery->where('bulan', $bulan)->whereNull('minggu');
        } else {
            $catatanQuery->whereNull('bulan')->whereNull('minggu');
        }

        $catatanMap = $catatanQuery->get()->keyBy('user_id');

        foreach ($siswaList as $siswa) {
            $query = Absensi::where('user_id', $siswa->id);

            if ($periode === 'mingguan') {
                // Hitung minggu ke-N dalam bulan
                [$start, $end] = $this->mingguRange($minggu, $bulan, $tahun);
                $query->whereBetween('tanggal', [$start, $end]);
            } elseif ($periode === 'bulanan') {
                $query->whereMonth('tanggal', $bulan)
                      ->whereYear('tanggal', $tahun);
            } elseif ($periode === 'tahunan') {
                $query->whereYear('tanggal', $tahun);
            }

            $absensi = $query->get();

            $hadir = $absensi->where('status', 'hadir')->count();
            $izin  = $absensi->where('status', 'izin')->count();
            $alfa  = $absensi->where('status', 'alfa')->count();
            $total = $absensi->count();

            // Untuk mingguan: minimal 3 hadir dianggap cukup
            $keterangan = '';
            if ($periode === 'mingguan') {
                $keterangan = $hadir >= 3 ? 'Cukup' : 'Kurang';
            }

            $catatan = $catatanMap->get($siswa->id);

            $rekap[] = [
                'user_id'    => $siswa->id,
                'nama'       => $siswa->name,
                'kelas'      => $siswa->kelas,
                'hadir'      => $hadir,
                'izin'       => $izin,
                'alfa'       => $alfa,
                'total'      => $total,
                'keterangan' => $keterangan,
                'persen'     => $total > 0 ? round(($hadir / $total) * 100) : 0,
                'catatan'    => $catatan ? $catatan->catatan : '',
            ];
        }

        return $rekap;
    }

    private function mingguRange($minggu, $bulan, $tahun)
    {
        // Minggu ke-N dalam bulan: setiap 7 hari
        $firstDay = Carbon::create($tahun, $bulan, 1);
        $start    = $firstDay->copy()->addDays(($minggu - 1) * 7);
        $end      = $start->copy()->addDays(6)->endOfDay();

        // Pastikan tidak melewati akhir bulan
        $lastDay = $firstDay->copy()->endOfMonth();
        if ($end->gt($lastDay)) {
            $end = $lastDay;
        }

        return [$start->toDateString(), $end->toDateString()];
    }

    private function judulPeriode($periode, $bulan, $tahun, $minggu)
    {
        $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y');
        if ($periode === 'mingguan') {
            return "Rekap Mingguan – Minggu ke-{$minggu} {$namaBulan}";
        } elseif ($periode === 'bulanan') {
            return "Rekap Bulanan – {$namaBulan}";
        }
        return "Rekap Tahunan – {$tahun}";
    }
}
