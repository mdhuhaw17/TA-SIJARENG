@extends('layouts.dashboard')

@section('title', 'Laporan Absensi')
@section('header', 'Laporan Absensi')

@section('content')

<style>
/* =========================================
   LAPORAN PAGE
   ========================================= */
.lap-header {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border-radius: 16px;
    padding: 22px 28px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    box-shadow: 0 6px 24px rgba(37,99,235,0.25);
}
.lap-header h1 { font-size: 20px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 10px; }
.lap-header p  { font-size: 13px; opacity: .85; margin: 4px 0 0; }
.lap-header-right { display: flex; align-items: center; gap: 12px; }

/* BACK BUTTON */
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.18);
    color: white;
    border: 1.5px solid rgba(255,255,255,0.35);
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: .2s;
    backdrop-filter: blur(4px);
}
.btn-back:hover { background: rgba(255,255,255,0.28); transform: translateY(-1px); }

/* TABS */
.tab-row {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
    background: white;
    border-radius: 12px;
    padding: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07);
    width: fit-content;
}
.tab-btn {
    padding: 9px 22px;
    border-radius: 9px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    background: transparent;
    color: #64748b;
    transition: .2s;
    display: flex;
    align-items: center;
    gap: 7px;
}
.tab-btn.active {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    box-shadow: 0 3px 10px rgba(37,99,235,.3);
}
.tab-btn:hover:not(.active) { background: #f0f4ff; color: #2563eb; }

/* FILTER BAR */
.filter-bar {
    background: white;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07);
    display: flex;
    gap: 14px;
    align-items: flex-end;
    flex-wrap: wrap;
}
.filter-bar label { font-size: 12px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px; }
.filter-bar select {
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    padding: 8px 12px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    background: #f8fafc;
    cursor: pointer;
    transition: .15s;
}
.filter-bar select:focus { border-color: #3b82f6; background: white; }

/* STAT CARDS – plain white, no color */
.stat-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}
.stat-card {
    background: white;
    border-radius: 14px;
    padding: 18px 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    border: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 14px;
}
.stat-card .icon-wrap {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-card .val  { font-size: 26px; font-weight: 700; color: #1e293b; line-height: 1; }
.stat-card .lbl  { font-size: 12px; color: #64748b; margin-top: 3px; }

/* TABLE */
.table-box {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.07);
    overflow: hidden;
}
.table-toolbar {
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    gap: 10px;
    flex-wrap: wrap;
}
.table-toolbar h2 { font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px; }
.btn-pdf {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 9px 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: .2s;
    box-shadow: 0 3px 10px rgba(239,68,68,.25);
}
.btn-pdf:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(239,68,68,.35); }

.lap-table { width: 100%; border-collapse: collapse; }
.lap-table thead tr { background: #f8fafc; }
.lap-table thead th {
    padding: 12px 16px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.lap-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: .15s; }
.lap-table tbody tr:hover { background: #f8fbff; }
.lap-table tbody td { padding: 12px 16px; font-size: 14px; color: #334155; }
.badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 600;
}
.badge-green  { background: #d1fae5; color: #059669; }
.badge-red    { background: #fee2e2; color: #dc2626; }
.badge-yellow { background: #fef3c7; color: #d97706; }
.badge-blue   { background: #dbeafe; color: #2563eb; }
.ket-cukup  { background: #d1fae5; color: #059669; }
.ket-kurang { background: #fee2e2; color: #dc2626; }

/* PROGRESS BAR */
.prog-wrap { background: #e2e8f0; border-radius: 99px; height: 7px; min-width: 80px; }
.prog-bar  { height: 7px; border-radius: 99px; background: linear-gradient(90deg,#3b82f6,#10b981); }

/* PAGINATION */
.pagination-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 10px;
}
.pagination-info { font-size: 13px; color: #64748b; }
.pagination-btns { display: flex; gap: 6px; }
.pg-btn {
    width: 34px;
    height: 34px;
    border: 1.5px solid #e2e8f0;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    transition: .15s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.pg-btn:hover { border-color: #3b82f6; color: #2563eb; background: #eff6ff; }
.pg-btn.active { background: #2563eb; color: white; border-color: #2563eb; }
.pg-btn:disabled { opacity: .4; cursor: not-allowed; }

/* EMPTY */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

/* NOTA MINGGUAN */
.nota-minggu {
    background: #f0fdf4;
    border: 1px solid #86efac;
    border-radius: 12px;
    padding: 12px 18px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* CATATAN */
.btn-catatan {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: .15s;
}
.btn-catatan.ada {
    background: #dbeafe;
    color: #1d4ed8;
}
.btn-catatan.ada:hover { background: #bfdbfe; }
.btn-catatan.kosong {
    background: #f1f5f9;
    color: #64748b;
}
.btn-catatan.kosong:hover { background: #e2e8f0; color: #334155; }
.catatan-preview {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 12px;
    color: #475569;
    display: inline-block;
    vertical-align: middle;
}

@media (max-width: 900px) {
    .stat-row { grid-template-columns: repeat(2,1fr); }
    .lap-header { flex-direction: column; align-items: flex-start; gap: 10px; }
}
@media (max-width: 600px) {
    .tab-row { flex-wrap: wrap; }
    .filter-bar { flex-direction: column; align-items: stretch; }
}
</style>

<div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
    <a href="{{ route('admin.dashboard') }}"
       class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-2xl shadow"
       style="display:inline-flex; align-items:center; gap:6px; font-weight:600; font-size:14px; text-decoration:none; transition:.2s;">
        ← Kembali
    </a>
</div>

<!-- TAB PERIODA -->
<div class="tab-row">
    <button class="tab-btn {{ $periode === 'mingguan' ? 'active' : '' }}"
            onclick="setPeriode('mingguan')">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
        Mingguan
    </button>
    <button class="tab-btn {{ $periode === 'bulanan'  ? 'active' : '' }}"
            onclick="setPeriode('bulanan')">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>
        </svg>
        Bulanan
    </button>
    <button class="tab-btn {{ $periode === 'tahunan'  ? 'active' : '' }}"
            onclick="setPeriode('tahunan')">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M3 3v18h18"/>
            <path d="M7 14l4-4 4 4 4-8"/>
        </svg>
        Tahunan
    </button>
</div>

<!-- FILTER BAR -->
<form id="filterForm" method="GET" action="{{ route('laporan') }}">
    <input type="hidden" name="periode" id="inputPeriode" value="{{ $periode }}">

    <div class="filter-bar">

        {{-- MINGGUAN --}}
        <div id="filterMinggu" style="{{ $periode !== 'mingguan' ? 'display:none' : '' }}">
            <label>Minggu ke-</label>
            <select name="minggu" onchange="submitFilter()">
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ $minggu == $i ? 'selected' : '' }}>Minggu {{ $i }}</option>
                @endfor
            </select>
        </div>

        {{-- BULAN (mingguan + bulanan) --}}
        <div id="filterBulan" style="{{ $periode === 'tahunan' ? 'display:none' : '' }}">
            <label>Bulan</label>
            <select name="bulan" onchange="submitFilter()">
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $nb)
                    <option value="{{ $idx + 1 }}" {{ $bulan == $idx+1 ? 'selected' : '' }}>{{ $nb }}</option>
                @endforeach
            </select>
        </div>

        {{-- TAHUN --}}
        <div>
            <label>Tahun</label>
            <select name="tahun" onchange="submitFilter()">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>

    </div>
</form>

@php
    $totalHadir  = collect($rekap)->sum('hadir');
    $totalIzin   = collect($rekap)->sum('izin');
    $totalAlfa   = collect($rekap)->sum('alfa');
    $totalSiswa  = count($rekap);
    $cukupCount  = collect($rekap)->where('keterangan', 'Cukup')->count();
@endphp

<!-- STAT CARDS – plain white -->
<div class="stat-row">
    <div class="stat-card">
        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div>
            <div class="val">{{ $totalSiswa }}</div>
            <div class="lbl">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>
        <div>
            <div class="val">{{ $totalHadir }}</div>
            <div class="lbl">Total Hadir</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
        </div>
        <div>
            <div class="val">{{ $totalAlfa }}</div>
            <div class="lbl">Total Alfa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div>
            <div class="val">{{ $totalIzin }}</div>
            <div class="lbl">Total Izin</div>
        </div>
    </div>
</div>

@if($periode === 'mingguan')
<div class="nota-minggu">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <span style="color:#166534;font-size:14px;font-weight:600;">
        {{ $cukupCount }} dari {{ $totalSiswa }} siswa memenuhi kehadiran minimal 3x dalam minggu ini.
    </span>
</div>
@endif

<!-- TABLE BOX -->
<div class="table-box">
    <div class="table-toolbar">
        <h2>
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 17v-6m4 6V7m4 10v-3"/>
                <rect x="3" y="3" width="18" height="18" rx="2"/>
            </svg>
            Rekap Kehadiran Siswa
        </h2>
        <button onclick="openDownloadModal()" class="btn-pdf">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="12" y1="18" x2="12" y2="12"/>
                <line x1="9" y1="15" x2="15" y2="15"/>
            </svg>
            Download PDF
        </button>
    </div>

    @if(count($rekap) > 0)
    <div style="overflow-x:auto;">
        <table class="lap-table" id="mainTable">
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
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($rekap as $i => $row)
                <tr class="data-row" data-index="{{ $i }}">
                    <td class="row-num">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $row['nama'] }}</td>
                    <td><span class="badge badge-blue">Kelas {{ $row['kelas'] }}</span></td>
                    <td><span class="badge badge-green">{{ $row['hadir'] }}</span></td>
                    <td><span class="badge badge-yellow">{{ $row['izin'] }}</span></td>
                    <td><span class="badge badge-red">{{ $row['alfa'] }}</span></td>
                    <td>{{ $row['total'] }}</td>
                    @if($periode === 'mingguan')
                    <td>
                        <span class="badge {{ $row['keterangan'] === 'Cukup' ? 'ket-cukup' : 'ket-kurang' }}">
                            {{ $row['keterangan'] }}
                        </span>
                    </td>
                    @endif
                    <td>
                        <button type="button"
                            class="btn-catatan {{ $row['catatan'] ? 'ada' : 'kosong' }}"
                            onclick="openCatatanModal({{ $row['user_id'] }}, '{{ addslashes($row['nama']) }}', `{{ addslashes($row['catatan']) }}`)"
                            title="{{ $row['catatan'] ?: 'Belum ada catatan' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            {{ $row['catatan'] ? 'Edit Catatan' : 'Beri Catatan' }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="pagination-bar">
        <div class="pagination-info" id="paginationInfo"></div>
        <div class="pagination-btns" id="paginationBtns"></div>
    </div>

    @else
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 14px;display:block;">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
            <line x1="9" y1="12" x2="15" y2="12"/>
            <line x1="9" y1="16" x2="12" y2="16"/>
        </svg>
        <p style="font-size:15px;font-weight:600;color:#94a3b8;">Belum ada data absensi untuk periode ini.</p>
    </div>
    @endif
</div>

<script>
// TAB SWITCH
function setPeriode(p) {
    document.getElementById('inputPeriode').value = p;
    document.getElementById('filterMinggu').style.display = (p === 'mingguan') ? '' : 'none';
    document.getElementById('filterBulan').style.display  = (p === 'tahunan')  ? 'none' : '';
    submitFilter();
}
function submitFilter() {
    document.getElementById('filterForm').submit();
}

// PAGINATION
const PER_PAGE = 10;
let currentPage = 1;

const allRows = document.querySelectorAll('.data-row');
const total   = allRows.length;

function totalPages() {
    return Math.ceil(total / PER_PAGE);
}

function showPage(page) {
    currentPage = page;
    const start = (page - 1) * PER_PAGE;
    const end   = start + PER_PAGE;
    let visibleNum = start;
    allRows.forEach((row, i) => {
        if (i >= start && i < end) {
            row.style.display = '';
            // update nomor urut sesuai page
            const numCell = row.querySelector('.row-num');
            if (numCell) numCell.textContent = ++visibleNum;
        } else {
            row.style.display = 'none';
        }
    });
    renderPagination();
}

function renderPagination() {
    const tp = totalPages();
    if (tp <= 1) return;

    const info = document.getElementById('paginationInfo');
    const btns = document.getElementById('paginationBtns');
    if (!info || !btns) return;

    const start = (currentPage - 1) * PER_PAGE + 1;
    const end   = Math.min(currentPage * PER_PAGE, total);
    info.textContent = `Menampilkan ${start}–${end} dari ${total} siswa`;

    let html = '';

    // PREV
    html += `<button class="pg-btn" onclick="showPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M15 18l-6-6 6-6"/>
        </svg>
    </button>`;

    // PAGE NUMBERS
    for (let p = 1; p <= tp; p++) {
        if (tp > 7 && p > 2 && p < tp - 1 && Math.abs(p - currentPage) > 1) {
            if (p === 3 || p === tp - 2) html += `<button class="pg-btn" disabled>…</button>`;
            continue;
        }
        html += `<button class="pg-btn ${p === currentPage ? 'active' : ''}" onclick="showPage(${p})">${p}</button>`;
    }

    // NEXT
    html += `<button class="pg-btn" onclick="showPage(${currentPage + 1})" ${currentPage === tp ? 'disabled' : ''}>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M9 18l6-6-6-6"/>
        </svg>
    </button>`;

    btns.innerHTML = html;
}

// INIT
if (total > 0) {
    showPage(1);
}

// DOWNLOAD MODAL
function openDownloadModal() {
    const modal = document.getElementById('modalDownloadPdf');
    const card  = document.getElementById('modalDownloadCard');
    modal.style.display = 'flex';
    setTimeout(() => { card.style.transform = 'scale(1)'; card.style.opacity = '1'; }, 10);
}
function closeDownloadModal() {
    const modal = document.getElementById('modalDownloadPdf');
    const card  = document.getElementById('modalDownloadCard');
    card.style.transform = 'scale(0.95)';
    card.style.opacity   = '0';
    setTimeout(() => { modal.style.display = 'none'; }, 250);
}
function updateDownloadFilter() {
    const p = document.getElementById('dlPeriode').value;
    document.getElementById('dlWrapMinggu').style.display = (p === 'mingguan') ? '' : 'none';
    document.getElementById('dlWrapBulan').style.display  = (p === 'tahunan')  ? 'none' : '';
}
// Close modal on backdrop click
document.getElementById('modalDownloadPdf').addEventListener('click', function(e) {
    if (e.target === this) closeDownloadModal();
});
</script>

<!-- MODAL DOWNLOAD PDF -->
<div id="modalDownloadPdf"
     style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div id="modalDownloadCard"
         style="background:white; border-radius:20px; width:420px; max-width:100%; padding:28px; box-shadow:0 20px 40px rgba(0,0,0,0.15); transform:scale(0.95); opacity:0; transition:.25s; box-sizing:border-box;">

        <!-- ICON -->
        <div style="display:flex; justify-content:center; margin-bottom:18px;">
            <div style="width:56px; height:56px; border-radius:50%; background:#fee2e2; display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/>
                    <line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
            </div>
        </div>

        <h3 style="font-size:17px; font-weight:700; color:#1e293b; text-align:center; margin:0 0 4px;">Download Laporan PDF</h3>
        <p style="font-size:13px; color:#64748b; text-align:center; margin:0 0 22px;">Pilih periode laporan yang ingin diunduh</p>

        <!-- FORM -->
        <form id="formDownloadPdf" method="GET" action="{{ route('laporan.pdf') }}" target="_blank">

            <!-- PERIODE -->
            <div style="margin-bottom:14px;">
                <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:5px;">Periode</label>
                <select name="periode" id="dlPeriode" onchange="updateDownloadFilter()"
                    style="width:100%; border:1.5px solid #e2e8f0; border-radius:10px; padding:9px 12px; font-size:14px; outline:none; background:#f8fafc; cursor:pointer;">
                    <option value="mingguan" {{ $periode === 'mingguan' ? 'selected' : '' }}> Mingguan</option>
                    <option value="bulanan"  {{ $periode === 'bulanan'  ? 'selected' : '' }}> Bulanan</option>
                    <option value="tahunan"  {{ $periode === 'tahunan'  ? 'selected' : '' }}> Tahunan</option>
                </select>
            </div>

            <!-- MINGGU (tampil jika mingguan) -->
            <div id="dlWrapMinggu" style="margin-bottom:14px; {{ $periode !== 'mingguan' ? 'display:none;' : '' }}">
                <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:5px;">Minggu ke-</label>
                <select name="minggu"
                    style="width:100%; border:1.5px solid #e2e8f0; border-radius:10px; padding:9px 12px; font-size:14px; outline:none; background:#f8fafc; cursor:pointer;">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $minggu == $i ? 'selected' : '' }}>Minggu {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- BULAN (tampil jika mingguan / bulanan) -->
            <div id="dlWrapBulan" style="margin-bottom:14px; {{ $periode === 'tahunan' ? 'display:none;' : '' }}">
                <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:5px;">Bulan</label>
                <select name="bulan"
                    style="width:100%; border:1.5px solid #e2e8f0; border-radius:10px; padding:9px 12px; font-size:14px; outline:none; background:#f8fafc; cursor:pointer;">
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $nb)
                        <option value="{{ $idx + 1 }}" {{ $bulan == $idx+1 ? 'selected' : '' }}>{{ $nb }}</option>
                    @endforeach
                </select>
            </div>

            <!-- TAHUN -->
            <div style="margin-bottom:22px;">
                <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:5px;">Tahun</label>
                <select name="tahun"
                    style="width:100%; border:1.5px solid #e2e8f0; border-radius:10px; padding:9px 12px; font-size:14px; outline:none; background:#f8fafc; cursor:pointer;">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <!-- BUTTONS -->
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="closeDownloadModal()"
                    style="flex:1; padding:11px; background:#f1f5f9; border:1.5px solid #e2e8f0; color:#475569; border-radius:12px; font-weight:600; font-size:14px; cursor:pointer; transition:.15s;">
                    Batal
                </button>
                <button type="submit"
                    style="flex:1; padding:11px; background:linear-gradient(135deg,#ef4444,#dc2626); border:none; color:white; border-radius:12px; font-weight:700; font-size:14px; cursor:pointer; box-shadow:0 3px 10px rgba(239,68,68,.3); transition:.15s; display:flex; align-items:center; justify-content:center; gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>
                    </svg>
                    Download
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL CATATAN ===== --}}
<div id="modalCatatan"
     style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
    <div id="modalCatatanCard"
         style="background:white; border-radius:20px; width:480px; max-width:100%; padding:28px; box-shadow:0 20px 40px rgba(0,0,0,0.15); transform:scale(0.95); opacity:0; transition:.25s; box-sizing:border-box;">

        {{-- ICON --}}
        <div style="display:flex; justify-content:center; margin-bottom:18px;">
            <div style="width:56px; height:56px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </div>
        </div>

        <h3 id="modalCatatanTitle" style="font-size:17px; font-weight:700; color:#1e293b; text-align:center; margin:0 0 4px;">Catatan untuk Siswa</h3>
        <p style="font-size:13px; color:#64748b; text-align:center; margin:0 0 20px;">Catatan ini akan tampil di rekap siswa.</p>

        {{-- TEXTAREA --}}
        <div style="margin-bottom:18px;">
            <label style="font-size:12px; font-weight:600; color:#475569; display:block; margin-bottom:6px;">Catatan</label>
            <textarea id="inputCatatan" rows="4" maxlength="1000" placeholder="Tulis catatan untuk siswa ini..."
                style="width:100%; border:1.5px solid #e2e8f0; border-radius:12px; padding:10px 14px; font-size:14px; outline:none; resize:vertical; font-family:inherit; transition:.15s; box-sizing:border-box;"
                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
            <div style="text-align:right; font-size:11px; color:#94a3b8; margin-top:4px;"><span id="charCount">0</span>/1000</div>
        </div>

        {{-- BUTTONS --}}
        <div style="display:flex; gap:10px;">
            <button type="button" onclick="closeCatatanModal()"
                style="flex:1; padding:11px; background:#f1f5f9; border:1.5px solid #e2e8f0; color:#475569; border-radius:12px; font-weight:600; font-size:14px; cursor:pointer; transition:.15s;">
                Batal
            </button>
            <button type="button" id="btnHapusCatatan" onclick="hapusCatatan()"
                style="padding:11px 16px; background:#fee2e2; border:none; color:#dc2626; border-radius:12px; font-weight:600; font-size:14px; cursor:pointer; transition:.15s; display:none;">
                Hapus
            </button>
            <button type="button" onclick="simpanCatatan()"
                style="flex:1; padding:11px; background:linear-gradient(135deg,#2563eb,#3b82f6); border:none; color:white; border-radius:12px; font-weight:700; font-size:14px; cursor:pointer; box-shadow:0 3px 10px rgba(37,99,235,.3); transition:.15s; display:flex; align-items:center; justify-content:center; gap:6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                </svg>
                Simpan Catatan
            </button>
        </div>
    </div>
</div>

{{-- TOAST NOTIFIKASI --}}
<div id="toastCatatan"
     style="display:none; position:fixed; bottom:24px; right:24px; background:#1e293b; color:white; padding:12px 20px; border-radius:12px; font-size:14px; font-weight:600; z-index:99999; box-shadow:0 8px 24px rgba(0,0,0,.2); transition:.3s; align-items:center; gap:10px;">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="#10b981" stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <span id="toastMsg">Catatan berhasil disimpan</span>
</div>

<script>
// ===== CATATAN MODAL =====
let _catatanUserId  = null;
let _catatanPeriode = '{{ $periode }}';
let _catatanBulan   = {{ $bulan ?? 'null' }};
let _catatanTahun   = {{ $tahun }};
let _catatanMinggu  = {{ $minggu ?? 'null' }};

const inputCatatan  = document.getElementById('inputCatatan');
const charCount     = document.getElementById('charCount');
inputCatatan.addEventListener('input', () => { charCount.textContent = inputCatatan.value.length; });

function openCatatanModal(userId, nama, catatan) {
    _catatanUserId = userId;
    document.getElementById('modalCatatanTitle').textContent = 'Catatan untuk ' + nama;
    inputCatatan.value = catatan || '';
    charCount.textContent = inputCatatan.value.length;
    document.getElementById('btnHapusCatatan').style.display = catatan ? 'block' : 'none';

    const modal = document.getElementById('modalCatatan');
    const card  = document.getElementById('modalCatatanCard');
    modal.style.display = 'flex';
    setTimeout(() => { card.style.transform = 'scale(1)'; card.style.opacity = '1'; }, 10);
}

function closeCatatanModal() {
    const modal = document.getElementById('modalCatatan');
    const card  = document.getElementById('modalCatatanCard');
    card.style.transform = 'scale(0.95)';
    card.style.opacity   = '0';
    setTimeout(() => { modal.style.display = 'none'; }, 250);
}

document.getElementById('modalCatatan').addEventListener('click', function(e) {
    if (e.target === this) closeCatatanModal();
});

function simpanCatatan() {
    const catatan = inputCatatan.value.trim();
    if (!catatan) { inputCatatan.focus(); return; }

    fetch('{{ route("catatan.simpan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id : _catatanUserId,
            periode : _catatanPeriode,
            bulan   : _catatanBulan,
            tahun   : _catatanTahun,
            minggu  : _catatanMinggu,
            catatan : catatan
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeCatatanModal();
            showToast('Catatan berhasil disimpan ✓', '#10b981');
            // reload setelah animasi selesai
            setTimeout(() => location.reload(), 1200);
        }
    })
    .catch(() => showToast('Gagal menyimpan catatan', '#ef4444'));
}

function hapusCatatan() {
    fetch('{{ route("catatan.hapus") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            user_id : _catatanUserId,
            periode : _catatanPeriode,
            bulan   : _catatanBulan,
            tahun   : _catatanTahun,
            minggu  : _catatanMinggu
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeCatatanModal();
            showToast('Catatan berhasil dihapus', '#f59e0b');
            setTimeout(() => location.reload(), 1200);
        }
    })
    .catch(() => showToast('Gagal menghapus catatan', '#ef4444'));
}

function showToast(msg, color) {
    const toast = document.getElementById('toastCatatan');
    const svg   = toast.querySelector('svg');
    svg.setAttribute('stroke', color);
    document.getElementById('toastMsg').textContent = msg;
    toast.style.display = 'flex';
    toast.style.opacity = '1';
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => { toast.style.display = 'none'; }, 400);
    }, 2500);
}
</script>

@endsection
