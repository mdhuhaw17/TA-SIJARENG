<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>{{ $judulPeriode }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            padding: 20px 25px;
        }
        /* HEADER */
        .pdf-header {
            background: #1565c0;
            color: white;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 16px;
        }
        .pdf-header h1 { font-size: 16px; font-weight: 700; }
        .pdf-header p  { font-size: 10px; opacity: .85; margin-top: 4px; }
        .pdf-header .hdr-table { width: 100%; border-collapse: collapse; }
        .pdf-header .hdr-left  { text-align: left; }
        .pdf-header .hdr-right { text-align: right; font-size: 10px; opacity: .9; vertical-align: top; }

        /* SUMMARY INFO – table layout for dompdf */
        .summary-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 14px;
        }
        .summary-info table { width: 100%; border-collapse: collapse; margin: 0; }
        .summary-info td { font-size: 10px; color: #64748b; padding: 0 20px 0 0; vertical-align: top; }
        .summary-info td strong { display: block; font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 1px; }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        thead tr { background: #1e40af; color: white; }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .3px;
        }
        tbody tr:nth-child(even) { background: #f8faff; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody td { padding: 7px 10px; font-size: 10px; border-bottom: 1px solid #e2e8f0; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 9px;
            font-weight: 700;
        }
        .bg-green  { background: #d1fae5; color: #059669; }
        .bg-red    { background: #fee2e2; color: #dc2626; }
        .bg-yellow { background: #fef3c7; color: #b45309; }
        .bg-blue   { background: #dbeafe; color: #1d4ed8; }

        /* PROG */
        .prog-wrap { background: #e2e8f0; border-radius: 99px; height: 6px; width: 60px; display: inline-block; vertical-align: middle; }
        .prog-bar  { height: 6px; border-radius: 99px; background: #2563eb; }

        /* FOOTER */
        .pdf-footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 9px; color: #64748b; }
        .pdf-footer table { width: 100%; border-collapse: collapse; margin: 0; }
        .pdf-footer .fl { text-align: left; }
        .pdf-footer .fr { text-align: right; }
        .ket-cukup  { background: #d1fae5; color: #059669; }
        .ket-kurang { background: #fee2e2; color: #dc2626; }

        /* NOTA mingguan */
        .nota {
            background: #eff6ff;
            border-left: 3px solid #2563eb;
            border-radius: 0 8px 8px 0;
            padding: 8px 14px;
            margin-bottom: 12px;
            font-size: 10px;
            color: #1e40af;
        }
    </style>
</head>
<body>

<!-- BIG TITLE -->
<div style="text-align:center; margin-bottom:14px; padding-bottom:12px; border-bottom:2px solid #e2e8f0;">
    <div style="font-size:22px; font-weight:900; color:#1e293b; letter-spacing:1px; text-transform:uppercase;">REKAP ABSENSI SISWA</div>
    <div style="font-size:11px; color:#64748b; margin-top:4px;">Sistem Absensi Digital Les JARENG</div>
</div>



@php
    $totalHadir  = collect($rekap)->sum('hadir');
    $totalIzin   = collect($rekap)->sum('izin');
    $totalAlfa   = collect($rekap)->sum('alfa');
    $totalSiswa  = count($rekap);
    $cukupCount  = collect($rekap)->where('keterangan', 'Cukup')->count();
@endphp

<!-- SUMMARY INFO -->
<div class="summary-info">
    <table>
        <tr>
            <td><strong>{{ $totalSiswa }}</strong>Total Siswa</td>
            <td><strong>{{ $totalHadir }}</strong>Total Hadir</td>
            <td><strong>{{ $totalAlfa }}</strong>Total Alfa</td>
            <td><strong>{{ $totalIzin }}</strong>Total Izin</td>
            @if($periode === 'mingguan')
            <td><strong>{{ $cukupCount }} / {{ $totalSiswa }}</strong>Hadir &ge; 3x</td>
            @endif
        </tr>
    </table>
</div>

@if($periode === 'mingguan')
<div class="nota">
    ✅ <strong>{{ $cukupCount }}</strong> dari <strong>{{ $totalSiswa }}</strong> siswa memenuhi kehadiran minimal 3x dalam periode ini.
    &nbsp;|&nbsp; ⚠️ <strong>{{ $totalSiswa - $cukupCount }}</strong> siswa di bawah batas minimal.
</div>
@endif

<!-- TABLE -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Alfa</th>
            <th>Total Hari</th>
            @if($periode === 'mingguan')<th>Keterangan</th>@endif
        </tr>
    </thead>
    <tbody>
        @foreach($rekap as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $row['nama'] }}</strong></td>
            <td><span class="badge bg-blue">{{ $row['kelas'] }}</span></td>
            <td><span class="badge bg-green">{{ $row['hadir'] }}</span></td>
            <td><span class="badge bg-yellow">{{ $row['izin'] }}</span></td>
            <td><span class="badge bg-red">{{ $row['alfa'] }}</span></td>
            <td>{{ $row['total'] }}</td>
            @if($periode === 'mingguan')
            <td><span class="badge {{ $row['keterangan'] === 'Cukup' ? 'ket-cukup' : 'ket-kurang' }}">{{ $row['keterangan'] }}</span></td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

<!-- FOOTER -->
<div class="pdf-footer">
    <table>
        <tr>
            <td class="fl">Absensi Les JARENG – Dokumen Resmi</td>
            <td class="fr">Dicetak otomatis oleh sistem</td>
        </tr>
    </table>
</div>

</body>
</html>
