<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Rekap Absensi – {{ $user->name }} – {{ $tahun }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            padding: 24px 28px;
        }

        /* HEADER */
        .pdf-header {
            background: #1565c0;
            color: white;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 18px;
        }
        .pdf-header h1 { font-size: 17px; font-weight: 700; margin-bottom: 4px; }
        .pdf-header p  { font-size: 10px; opacity: .85; }
        .pdf-header .hdr-table { width: 100%; border-collapse: collapse; }
        .pdf-header .hdr-left  { text-align: left; vertical-align: top; }
        .pdf-header .hdr-right { text-align: right; font-size: 10px; opacity: .9; vertical-align: top; }

        /* SUMMARY INFO – table layout */
        .summary-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 14px;
        }
        .summary-info table { width: 100%; border-collapse: collapse; margin: 0; }
        .summary-info td { font-size: 10px; color: #64748b; padding: 0 20px 0 0; vertical-align: top; }
        .summary-info td strong { display: block; font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 1px; }

        /* PROGRESS */
        .prog-section {
            margin-bottom: 14px;
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px 16px;
        }
        .prog-section .lbl-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .prog-section .lbl-left  { font-size: 10px; font-weight: 600; text-align: left; }
        .prog-section .lbl-right { font-size: 10px; font-weight: 600; text-align: right; }
        .prog-wrap { background: #e2e8f0; border-radius: 99px; height: 8px; }
        .prog-bar  { height: 8px; border-radius: 99px; background: #2563eb; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1e40af; color: white; }
        thead th {
            padding: 8px 12px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .3px;
        }
        tbody tr:nth-child(even) { background: #f8faff; }
        tbody tr:nth-child(odd)  { background: #ffffff; }
        tbody td { padding: 8px 12px; font-size: 10px; border-bottom: 1px solid #e2e8f0; }
        tfoot tr { background: #1e40af; color: white; }
        tfoot td { padding: 8px 12px; font-weight: 700; font-size: 10px; }

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

        .prog-sm { background: #e2e8f0; border-radius: 99px; height: 5px; width: 50px; display: inline-block; vertical-align: middle; }
        .prog-sm-bar { height: 5px; border-radius: 99px; background: #2563eb; }

        /* CATATAN */
        .catatan-box {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 5px;
            padding: 4px 7px;
            font-size: 9px;
            color: #713f12;
            margin-bottom: 3px;
            line-height: 1.4;
        }
        .catatan-tahunan {
            background: #fefce8;
            border: 1.5px solid #fde047;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 14px;
        }
        .catatan-tahunan .cat-title {
            font-size: 9px;
            font-weight: 700;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 4px;
        }
        .catatan-tahunan .cat-text {
            font-size: 10px;
            color: #713f12;
            line-height: 1.5;
        }

        /* FOOTER */
        .pdf-footer { margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 9px; color: #64748b; }
        .pdf-footer table { width: 100%; border-collapse: collapse; margin: 0; }
        .pdf-footer .fl { text-align: left; }
        .pdf-footer .fr { text-align: right; }
    </style>
</head>
<body>

<!-- BIG TITLE -->
<div style="text-align:center; margin-bottom:14px; padding-bottom:12px; border-bottom:2px solid #e2e8f0;">
    <div style="font-size:22px; font-weight:900; color:#1e293b; letter-spacing:1px; text-transform:uppercase;">REKAP ABSENSI SISWA</div>
    <div style="font-size:11px; color:#64748b; margin-top:4px;">Sistem Absensi Digital Les JARENG</div>
</div>



<!-- INFO SISWA DI HEADER (sudah ada) -->

<!-- SUMMARY INFO -->
<div class="summary-info">
    <table>
        <tr>
            <td><strong>{{ $totalAll }}</strong>Total Hari</td>
            <td><strong>{{ $totalHadir }}</strong>Total Hadir</td>
            <td><strong>{{ $totalAlfa }}</strong>Total Alfa</td>
            <td><strong>{{ $totalIzin }}</strong>Total Izin</td>
        </tr>
    </table>
</div>


<!-- TABLE -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Bulan</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Alfa</th>
            <th>Total Hari</th>
            <th>Catatan Guru</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapBulanan as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $row['bulan'] }}</strong></td>
            <td><span class="badge bg-green">{{ $row['hadir'] }}</span></td>
            <td><span class="badge bg-yellow">{{ $row['izin'] }}</span></td>
            <td><span class="badge bg-red">{{ $row['alfa'] }}</span></td>
            <td>{{ $row['total'] }}</td>
            <td>
                @php $nomorBulan = $i + 1; $catList = $catatanPerBulan[$nomorBulan] ?? []; @endphp
                @if(count($catList) > 0)
                    @foreach($catList as $cat)
                        <div class="catatan-box">{{ $cat->catatan }}</div>
                    @endforeach
                @else
                    <span style="color:#94a3b8;">—</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">TOTAL</td>
            <td>{{ $totalHadir }}</td>
            <td>{{ $totalIzin }}</td>
            <td>{{ $totalAlfa }}</td>
            <td>{{ $totalAll }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

{{-- CATATAN TAHUNAN --}}
@if(isset($catatanTahunan) && $catatanTahunan)
<div class="catatan-tahunan">
    <div class="cat-title">Catatan Guru</div>
    <div class="cat-text">{{ $catatanTahunan->catatan }}</div>
</div>
@endif

<!-- FOOTER -->
<div class="pdf-footer">
    <table>
        <tr>
            <td class="fl">Absensi Les JARENG – Dokumen Resmi</td>
            <td class="fr">Rekap atas nama: {{ $user->name }}</td>
        </tr>
    </table>
</div>

</body>
</html>
