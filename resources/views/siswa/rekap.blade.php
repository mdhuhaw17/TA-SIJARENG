@extends('layouts.siswa')

@section('title', 'Rekap Saya')
@section('header', 'Rekap Absensi Saya')

@section('content')

<div class="space-y-6">

    {{-- HEADER CARD --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl shadow-xl overflow-hidden">
        <div class="p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Rekap Absensi Saya</h1>
                <p class="text-blue-100 mt-2 text-base">
                    Laporan kehadiran personal – {{ Auth::user()->name }}
                </p>
                <div class="mt-4 flex items-center gap-3 flex-wrap">
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm text-white">
                        Kelas {{ Auth::user()->kelas }}
                    </span>
                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm text-white font-semibold">
                        Kehadiran {{ $persenTotal }}% ({{ $tahun }})
                    </span>
                </div>
            </div>
            <div class="shrink-0">
                @if(Auth::user()->foto)
                    <img src="{{ asset('storage/' . Auth::user()->foto) }}"
                        class="w-28 h-28 rounded-3xl object-cover border-4 border-white shadow-xl">
                @else
                    <div class="w-28 h-28 rounded-3xl bg-white flex items-center justify-center text-blue-600 text-5xl font-bold shadow-xl">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- FILTER & DOWNLOAD --}}
    <div class="bg-white rounded-2xl shadow p-5 flex flex-wrap items-end gap-4 justify-between">
        <form method="GET" action="{{ route('siswa.rekap') }}" class="flex gap-3 items-end flex-wrap">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tahun</label>
                <select name="tahun" onchange="this.form.submit()"
                    class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 bg-slate-50">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <a href="{{ route('siswa.rekap.pdf', ['tahun' => $tahun]) }}"
           target="_blank"
           style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#ef4444,#dc2626); color:white; border-radius:12px; padding:10px 20px; font-size:14px; font-weight:700; text-decoration:none; box-shadow:0 3px 10px rgba(239,68,68,.3); transition:.2s;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="12" y1="18" x2="12" y2="12"/>
                <line x1="9" y1="15" x2="15" y2="15"/>
            </svg>
            Download PDF
        </a>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow border border-slate-100">
            <p class="text-slate-500 text-sm">Total Hari</p>
            <h2 class="text-4xl font-bold text-slate-800 mt-1">{{ $totalAll }}</h2>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow border border-slate-100">
            <p class="text-slate-500 text-sm">Total Hadir</p>
            <h2 class="text-4xl font-bold text-slate-800 mt-1">{{ $totalHadir }}</h2>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow border border-slate-100">
            <p class="text-slate-500 text-sm">Total Izin</p>
            <h2 class="text-4xl font-bold text-slate-800 mt-1">{{ $totalIzin }}</h2>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow border border-slate-100">
            <p class="text-slate-500 text-sm">Total Alfa</p>
            <h2 class="text-4xl font-bold text-slate-800 mt-1">{{ $totalAlfa }}</h2>
        </div>
    </div>



    {{-- TABLE PER BULAN --}}
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="font-bold text-lg text-slate-800">Rekap Per Bulan – {{ $tahun }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">#</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Bulan</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Hadir</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Izin</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Alfa</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Total</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wide">Catatan Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapBulanan as $i => $row)
                    <tr class="border-t border-slate-100 hover:bg-blue-50/40 transition">
                        <td class="px-5 py-3 text-sm text-slate-500">{{ $i + 1 }}</td>
                        <td class="px-5 py-3 text-sm font-semibold text-slate-800">{{ $row['bulan'] }}</td>
                        <td class="px-5 py-3">
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">{{ $row['hadir'] }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">{{ $row['izin'] }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full">{{ $row['alfa'] }}</span>
                        </td>
                        <td class="px-5 py-3 text-sm text-slate-600">{{ $row['total'] }}</td>
                        <td class="px-5 py-3">
                            @php $nomorBulan = $i + 1; $catList = $catatanPerBulan[$nomorBulan] ?? []; @endphp
                            @if(count($catList) > 0)
                                <div style="display:flex; flex-direction:column; gap:4px;">
                                    @foreach($catList as $cat)
                                        <div style="display:flex; align-items:flex-start; gap:6px; background:#fefce8; border:1px solid #fde047; border-radius:8px; padding:6px 10px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="#ca8a04" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                            <span style="font-size:12px; color:#713f12; line-height:1.4;">{{ $cat->catatan }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span style="font-size:12px; color:#94a3b8;">—</span>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
                {{-- TOTAL ROW --}}
                <tfoot class="bg-blue-600 text-white">
                    <tr>
                        <td class="px-5 py-3 font-bold text-sm" colspan="2">TOTAL</td>
                        <td class="px-5 py-3 font-bold text-sm">{{ $totalHadir }}</td>
                        <td class="px-5 py-3 font-bold text-sm">{{ $totalIzin }}</td>
                        <td class="px-5 py-3 font-bold text-sm">{{ $totalAlfa }}</td>
                        <td class="px-5 py-3 font-bold text-sm">{{ $totalAll }}</td>
                        <td class="px-5 py-3 font-bold text-sm">{{ $persenTotal }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- CATATAN TAHUNAN --}}
    @if(isset($catatanTahunan) && $catatanTahunan)
    <div style="background: linear-gradient(135deg, #fefce8, #fef9c3); border: 1.5px solid #fde047; border-radius: 16px; padding: 18px 22px; display: flex; gap: 14px; align-items: flex-start;">
        <div style="width:40px; height:40px; border-radius:10px; background:#fef08a; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#ca8a04" stroke-width="2" viewBox="0 0 24 24">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
        </div>
        <div>
            <p style="font-size:12px; font-weight:700; color:#92400e; margin:0 0 4px; text-transform:uppercase; letter-spacing:.5px;">Catatan Guru</p>
            <p style="font-size:14px; color:#713f12; margin:0; line-height:1.6;">{{ $catatanTahunan->catatan }}</p>
        </div>
    </div>
    @endif

</div>

@endsection
