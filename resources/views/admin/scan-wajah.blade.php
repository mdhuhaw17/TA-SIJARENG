@extends('layouts.dashboard')

@section('title', 'Scan Wajah')
@section('header', 'Scan Wajah')

@section('content')

<link rel="preconnect" href="http://localhost:5000">

<style>
    /* ===== LOADING SPINNER ===== */
    .camera-loading {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #0f172a;
        z-index: 20;
        gap: 14px;
        color: #94a3b8;
        font-size: 13px;
    }
    .camera-loading.hidden { display: none; }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(16,185,129,0.2);
        border-top-color: #10b981;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    /* ===== GREETING BOX ===== */
    .greeting-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 14px;
        color: white;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        margin-bottom: 20px;
    }

    .greeting-title { font-size: 20px; font-weight: bold; }
    .greeting-sub { font-size: 13px; opacity: 0.85; margin-top: 3px; }

    .jam-box {
        text-align: right;
        background: rgba(255,255,255,0.15);
        padding: 10px 15px;
        border-radius: 10px;
        backdrop-filter: blur(6px);
    }
    .jam-text { font-size: 20px; font-weight: bold; letter-spacing: 1px; }
    .tanggal-text { font-size: 12px; opacity: 0.9; }

    /* ===== LAYOUT ===== */
    .scan-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        align-items: start;
    }

    /* ===== CAMERA CARD ===== */
    .camera-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .camera-card-header {
        background: linear-gradient(135deg, #10b981, #059669);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .camera-card-header h3 {
        color: white;
        font-size: 16px;
        font-weight: bold;
        margin: 0;
    }

    .camera-card-body {
        padding: 20px;
    }

    /* ===== VIDEO CONTAINER ===== */
    .video-wrapper {
        position: relative;
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
        aspect-ratio: 4/3;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        border: 3px solid #10b981;
    }

    #videoEl {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scaleX(-1); /* mirror */
    }

    #overlayCanvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        transform: scaleX(-1);
    }

    /* Corner brackets */
    .corner {
        position: absolute;
        width: 28px;
        height: 28px;
        border-color: #10b981;
        border-style: solid;
        z-index: 10;
    }
    .corner-tl { top: 12px; left: 12px; border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
    .corner-tr { top: 12px; right: 12px; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
    .corner-bl { bottom: 12px; left: 12px; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
    .corner-br { bottom: 12px; right: 12px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

    /* Scan line animation */
    .scan-line {
        position: absolute;
        left: 12px; right: 12px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #10b981, transparent);
        box-shadow: 0 0 8px #10b981;
        animation: scanMove 2.5s ease-in-out infinite;
        z-index: 9;
    }

    @keyframes scanMove {
        0%   { top: 15%; }
        50%  { top: 80%; }
        100% { top: 15%; }
    }

    /* Camera off placeholder */
    .camera-off {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #475569;
        gap: 12px;
        font-size: 14px;
    }

    .camera-off svg { opacity: 0.4; }

    /* ===== STATUS BAR ===== */
    .status-bar {
        margin-top: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: #065f46;
        font-weight: 600;
    }

    .status-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #10b981;
        animation: pulse 1.5s ease-in-out infinite;
    }

    .status-bar.idle    { background: #f8fafc; border-color: #e2e8f0; color: #64748b; }
    .status-bar.idle .status-dot { background: #94a3b8; animation: none; }
    .status-bar.error   { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
    .status-bar.error .status-dot { background: #ef4444; animation: none; }
    .status-bar.success { background: #f0fdf4; border-color: #bbf7d0; color: #065f46; }
    .status-bar.success .status-dot { background: #10b981; }
    .status-bar.info    { background: #f0f9ff; border-color: #bae6fd; color: #0369a1; }
    .status-bar.info .status-dot { background: #0284c7; animation: none; }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }

    /* ===== BUTTONS ===== */
    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 14px;
    }

    .btn-start {
        flex: 1;
        padding: 11px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        font-weight: 700;
        font-size: 14px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        box-shadow: 0 4px 10px rgba(16,185,129,0.3);
    }

    .btn-start:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(16,185,129,0.4); }
    .btn-start:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    .btn-stop {
        flex: 1;
        padding: 11px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-weight: 700;
        font-size: 14px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        box-shadow: 0 4px 10px rgba(239,68,68,0.3);
    }

    .btn-stop:hover { transform: translateY(-1px); }
    .btn-stop:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    .btn-back {
        padding: 11px 18px;
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 14px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back:hover { background: #e2e8f0; }

    /* ===== RESULT PANEL ===== */
    .result-panel {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Info card */
    .info-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .info-card-header {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        padding: 14px 18px;
        color: white;
        font-size: 14px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card-body {
        padding: 18px;
    }

    /* Detected user */
    .user-detected {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .user-detected.hidden { display: none; }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #10b981;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }

    .user-info-name { font-size: 16px; font-weight: 800; color: #1e293b; }
    .user-info-kelas { font-size: 13px; color: #64748b; margin-top: 2px; }

    .badge-absen {
        margin-top: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-hadir  { background: #d1fae5; color: #065f46; }
    .badge-gagal  { background: #fee2e2; color: #991b1b; }
    .badge-waiting { background: #dbeafe; color: #1e40af; }
    .badge-info    { background: #e0f2fe; color: #0369a1; }

    /* Placeholder */
    .result-placeholder {
        text-align: center;
        padding: 30px 20px;
        color: #94a3b8;
    }

    .result-placeholder svg { opacity: 0.3; margin-bottom: 10px; }
    .result-placeholder p { font-size: 13px; }

    /* Stats mini */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .stat-item {
        text-align: center;
        padding: 12px 8px;
        border-radius: 12px;
        font-weight: 700;
    }

    .stat-item .num { font-size: 22px; }
    .stat-item .lbl { font-size: 11px; margin-top: 2px; opacity: 0.8; }

    .stat-hadir  { background: linear-gradient(135deg, #10b981, #059669); color: white; }
    .stat-alfa   { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
    .stat-waiting { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }

    /* History list */
    .history-list { max-height: 220px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; }

    .history-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .history-avatar {
        width: 36px; height: 36px;
        border-radius: 8px;
        object-fit: cover;
        background: #e2e8f0;
    }

    .history-name { font-weight: 600; color: #1e293b; }
    .history-time { font-size: 11px; color: #94a3b8; }
    .history-badge { margin-left: auto; padding: 3px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; }

    .empty-history {
        text-align: center;
        padding: 24px;
        color: #cbd5e1;
        font-size: 13px;
    }

    /* ===== CUSTOM DROPDOWN ===== */
    #kelasDropdownBtn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255,255,255,0.15);
        border: 1.5px solid rgba(255,255,255,0.4);
        border-radius: 10px;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        min-width: 160px;
        justify-content: space-between;
        user-select: none;
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        transition: background 0.2s, box-shadow 0.2s;
    }
    #kelasDropdownBtn:hover {
        background: rgba(255,255,255,0.25);
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
    }
    #kelasDropdownBtn .dd-arrow {
        transition: transform 0.25s ease;
        flex-shrink: 0;
    }
    #kelasDropdownList {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: #5b21b6;
        border: 1.5px solid rgba(255,255,255,0.2);
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        min-width: 185px;
        z-index: 9999;
        padding: 5px;
        display: none;
        overflow: hidden;
    }
    #kelasDropdownList .dd-item {
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        color: rgba(255,255,255,0.85);
        border-radius: 8px;
        cursor: pointer;
        white-space: nowrap;
    }
    #kelasDropdownList .dd-item:hover {
        background: rgba(255,255,255,0.18);
        color: white;
    }
    #kelasDropdownList .dd-item.dd-active {
        background: rgba(255,255,255,0.28);
        color: white;
        font-weight: 700;
    }
</style>



<script>
    function updateJam() {
        const jamEl = document.getElementById('jam');
        const tglEl = document.getElementById('tanggal');
        if (!jamEl || !tglEl) return;

        const now = new Date();
        const h = now.getHours().toString().padStart(2,'0');
        const m = now.getMinutes().toString().padStart(2,'0');
        const s = now.getSeconds().toString().padStart(2,'0');
        const tgl = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        jamEl.innerHTML = `${h}:${m}:${s}`;
        tglEl.innerHTML = tgl;
    }
    setInterval(updateJam, 1000);
    updateJam();
</script>

<!-- PAGE HEADER -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:bold; color:#1e293b;">
            <svg style="display:inline;vertical-align:middle;margin-right:6px" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24">
                <path d="M7 3H5a2 2 0 0 0-2 2v2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                <path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M17 21h2a2 2 0 0 0 2-2v-2"/>
                <circle cx="12" cy="10" r="3"/><path d="M8 17c1.5-2 6.5-2 8 0"/>
            </svg>
            Scan Wajah Absensi
        </h2>
       
    </div>
    <a href="{{ route('admin.dashboard') }}" style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;font-weight:700;font-size:13px;border-radius:10px;text-decoration:none;box-shadow:0 4px 12px rgba(239,68,68,0.3);transition:0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
</div>

<!-- MAIN LAYOUT -->
<div class="scan-layout">

    <!-- ===== KIRI: KAMERA ===== -->
    <div class="camera-card">

        <div class="camera-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/>
            </svg>
            <h3>Kamera Live</h3>
        </div>

        <div class="camera-card-body">

            <!-- VIDEO -->
            <div class="video-wrapper" id="videoWrapper">
                <!-- Corners -->
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>
                <!-- Scan line -->
                <div class="scan-line" id="scanLine"></div>

                <img id="webcamFeed" style="width:100%;height:100%;object-fit:cover;display:none;" src="" alt="Webcam Feed">

                <!-- Loading spinner -->
                <div class="camera-loading hidden" id="cameraLoading">
                    <div class="spinner"></div>
                    <span>Menghubungkan kamera...</span>
                </div>

                <!-- Placeholder -->
                <div class="camera-off" id="cameraOff">
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M7 3H5a2 2 0 0 0-2 2v2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                        <path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M17 21h2a2 2 0 0 0 2-2v-2"/>
                        <circle cx="12" cy="10" r="3"/><path d="M8 17c1.5-2 6.5-2 8 0"/>
                    </svg>
                    <span>Kamera belum aktif</span>
                    <span style="font-size:11px;opacity:0.6">Klik "Mulai Kamera" untuk memulai</span>
                </div>
            </div>

            <!-- STATUS -->
            <div class="status-bar idle" id="statusBar">
                <div class="status-dot"></div>
                <span id="statusText">Kamera belum aktif</span>
            </div>

            <!-- BUTTONS -->
            <div class="btn-group">
                <button class="btn-start" id="btnStart" onclick="startCamera()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>
                    </svg>
                    Mulai Kamera
                </button>
                <button class="btn-stop" id="btnStop" onclick="stopCamera()" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6"/>
                    </svg>
                    Stop
                </button>
            </div>

            <!-- INFO TIPS -->
            <div style="margin-top:14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:10px 14px; font-size:12px; color:#1e40af; display:flex; gap:8px; align-items:flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span>Pastikan wajah siswa terlihat jelas dan pencahayaan cukup. Sistem akan mendeteksi wajah secara otomatis.</span>
            </div>

        </div>
    </div>

    <!-- ===== KANAN: PANEL HASIL ===== -->
    <div class="result-panel">

        <!-- HASIL DETEKSI -->
        <div class="info-card">
            <div class="info-card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c1.5-4 14.5-4 16 0"/>
                </svg>
                Hasil Deteksi Wajah
            </div>
            <div class="info-card-body">

                <!-- Terdeteksi -->
                <div class="user-detected hidden" id="userDetected">
                    <img id="detectedAvatar" class="user-avatar" src="" alt="foto">
                    <div>
                        <div class="user-info-name" id="detectedName">—</div>
                        <div class="user-info-kelas" id="detectedKelas">—</div>
                        <div class="badge-absen badge-waiting" id="detectedBadge">Memproses...</div>
                    </div>
                </div>

                <!-- Placeholder -->
                <div class="result-placeholder" id="resultPlaceholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M7 3H5a2 2 0 0 0-2 2v2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                        <path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M17 21h2a2 2 0 0 0 2-2v-2"/>
                        <circle cx="12" cy="10" r="3"/><path d="M8 17c1.5-2 6.5-2 8 0"/>
                    </svg>
                    <p>Belum ada wajah terdeteksi.<br>Aktifkan kamera terlebih dahulu.</p>
                </div>

            </div>
        </div>

        <!-- STATISTIK SESI -->
        <div class="info-card" style="overflow:visible;">
            <div class="info-card-header" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);border-top-left-radius:18px;border-top-right-radius:18px;display:flex;justify-content:space-between;align-items:center;width:100%;position:relative;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 3v18h18"/><path d="M7 14l4-4 4 4 4-8"/>
                    </svg>
                    <span>Sesi Absen Kelas</span>
                </div>
                <div style="position:relative;display:inline-block;">
                    <div id="kelasDropdownBtn" onclick="ddToggle()">
                        <span id="kelasDropdownLabel">Kelas Kecil (1-3)</span>
                        <svg class="dd-arrow" id="ddArrow" xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                    <div id="kelasDropdownList">
                        <div class="dd-item dd-active" data-val="kecil" onclick="ddSelect(this,'Kelas Kecil (1-3)','kecil')">Kelas Kecil (1-3)</div>
                        <div class="dd-item" data-val="besar" onclick="ddSelect(this,'Kelas Besar (4-6)','besar')">Kelas Besar (4-6)</div>
                    </div>
                    <input type="hidden" id="classCategorySelect" value="kecil">
                </div>
            </div>
            <div class="info-card-body">
                <div class="stats-grid">
                    <div class="stat-item stat-hadir">
                        <div class="num" id="statSudah">0</div>
                        <div class="lbl">Sudah Absen</div>
                    </div>
                    <div class="stat-item stat-alfa">
                        <div class="num" id="statBelum">0</div>
                        <div class="lbl">Belum Absen</div>
                    </div>
                    <div class="stat-item stat-waiting">
                        <div class="num" id="statTotal">0</div>
                        <div class="lbl">Total Siswa</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIWAYAT SCAN -->
        <div class="info-card">
            <div class="info-card-header" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                Riwayat Scan Hari Ini
            </div>
            <div class="info-card-body" style="padding: 12px 14px;">
                <div class="history-list" id="historyList">
                    <div class="empty-history" id="emptyHistory">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="#e2e8f0" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 8px">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        Belum ada scan hari ini
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    let isRunning = false;
    let scanCooldown = false;
    let pollInterval = null;
    let statHadir = 0, statGagal = 0, statTotal = 0;
    const pythonHost = "http://localhost:5000";

    const classStats = {
        kecil: {
            total: {{ $totalKecil }},
            sudah: {{ $sudahKecil }},
            belum: {{ $belumKecil }}
        },
        besar: {
            total: {{ $totalBesar }},
            sudah: {{ $sudahBesar }},
            belum: {{ $belumBesar }}
        }
    };

    var _ddOpen = false;

    function ddToggle() {
        var list = document.getElementById('kelasDropdownList');
        var arrow = document.getElementById('ddArrow');
        _ddOpen = !_ddOpen;
        list.style.display = _ddOpen ? 'block' : 'none';
        if (arrow) arrow.style.transform = _ddOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function ddSelect(el, label, val) {
        document.getElementById('kelasDropdownLabel').textContent = label;
        document.getElementById('classCategorySelect').value = val;
        document.querySelectorAll('#kelasDropdownList .dd-item').forEach(function(item) {
            item.classList.toggle('dd-active', item === el);
        });
        _ddOpen = false;
        document.getElementById('kelasDropdownList').style.display = 'none';
        var arrow = document.getElementById('ddArrow');
        if (arrow) arrow.style.transform = 'rotate(0deg)';
        updateStatsView();
    }

    document.addEventListener('click', function(e) {
        if (_ddOpen) {
            var btn = document.getElementById('kelasDropdownBtn');
            var list = document.getElementById('kelasDropdownList');
            if (btn && list && !btn.contains(e.target) && !list.contains(e.target)) {
                _ddOpen = false;
                list.style.display = 'none';
                var arrow = document.getElementById('ddArrow');
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }
    });

    function updateStatsView() {
        const category = document.getElementById('classCategorySelect').value;
        const stats = classStats[category];
        
        document.getElementById('statSudah').textContent = stats.sudah;
        document.getElementById('statBelum').textContent = stats.belum;
        document.getElementById('statTotal').textContent = stats.total;
    }

    function updateClassStats(kelas) {
        const k = String(kelas);
        let category = null;
        
        if (['1', '2', '3'].includes(k)) {
            category = 'kecil';
        } else if (['4', '5', '6'].includes(k)) {
            category = 'besar';
        }
        
        if (category) {
            if (classStats[category].belum > 0) {
                classStats[category].sudah++;
                classStats[category].belum--;
                
                const currentCategory = document.getElementById('classCategorySelect').value;
                if (currentCategory === category) {
                    updateStatsView();
                }
            }
        }
    }

    // Call updateStatsView once on page load to initialize the numbers!
    window.addEventListener('DOMContentLoaded', function() {
        updateStatsView();
        // Pre-warm koneksi ke Flask agar saat klik Mulai Kamera, koneksi sudah siap
        fetch(`${pythonHost}/`, { mode: 'cors' }).catch(() => {});
    });

    const webcamFeed = document.getElementById('webcamFeed');
    const cameraOff = document.getElementById('cameraOff');
    const statusBar = document.getElementById('statusBar');
    const statusText = document.getElementById('statusText');
    const scanLine   = document.getElementById('scanLine');

    function setStatus(type, msg) {
        statusBar.className = 'status-bar ' + type;
        statusText.textContent = msg;
    }

    function startCamera() {
        try {
            setStatus('', 'Menghubungkan ke kamera...');
            
            const loadingEl = document.getElementById('cameraLoading');

            // Langsung tampilkan loading spinner (bukan blank hitam)
            cameraOff.style.display = 'none';
            loadingEl.classList.remove('hidden');
            webcamFeed.style.display = 'none';

            document.getElementById('btnStart').disabled = true;
            document.getElementById('btnStop').disabled = false;

            // Ketika frame pertama sudah dimuat, sembunyikan loading
            webcamFeed.onload = function() {
                loadingEl.classList.add('hidden');
                webcamFeed.style.display = 'block';
                scanLine.style.display = 'block';
                setStatus('success', 'Kamera aktif — Mendeteksi wajah...');
                webcamFeed.onload = null; // Hanya trigger sekali
            };

            webcamFeed.onerror = function() {
                loadingEl.classList.add('hidden');
                cameraOff.style.display = 'flex';
                document.getElementById('btnStart').disabled = false;
                document.getElementById('btnStop').disabled = true;
                setStatus('error', 'Gagal menghubungkan ke kamera');
            };

            // Set source ke endpoint Flask absensi feed
            webcamFeed.src = `${pythonHost}/attendance-feed`;
            isRunning = true;

            // Mulai polling hasil deteksi
            startDetectionLoop();

        } catch (err) {
            setStatus('error', 'Gagal mengakses kamera: ' + err.message);
        }
    }

    function stopCamera() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
        
        webcamFeed.src = "";
        webcamFeed.style.display = 'none';
        webcamFeed.onload = null;
        webcamFeed.onerror = null;
        
        isRunning = false;
        document.getElementById('cameraLoading').classList.add('hidden');
        cameraOff.style.display = 'flex';
        scanLine.style.display = 'none';

        document.getElementById('btnStart').disabled = false;
        document.getElementById('btnStop').disabled = true;

        setStatus('idle', 'Kamera dimatikan');
    }

    function startDetectionLoop() {
        if (pollInterval) clearInterval(pollInterval);
        
        pollInterval = setInterval(() => {
            if (!isRunning || scanCooldown) return;

            fetch(`${pythonHost}/attendance-status`)
                .then(res => res.json())
                .then(data => {
                    if (data.user_id && !scanCooldown) {
                        // Wajah terdeteksi! Jalankan proses absensi
                        onFaceRecognized({ id: data.user_id });
                    }
                })
                .catch(err => {
                    console.error("Error polling attendance status:", err);
                });
        }, 500);
    }

    function onFaceRecognized(userData) {
        if (scanCooldown) return;
        scanCooldown = true;

        statTotal++;

        // Tampilkan info user
        document.getElementById('resultPlaceholder').style.display = 'none';
        const det = document.getElementById('userDetected');
        det.classList.remove('hidden');

        const badge = document.getElementById('detectedBadge');
        badge.className = 'badge-absen badge-waiting';
        badge.textContent = 'Memproses...';

        // Kirim ke server untuk catat absensi
        fetch('/absensi/wajah', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ user_id: userData.id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                badge.className = 'badge-absen badge-hadir';
                badge.textContent = '✓ Absen Berhasil';
                
                statHadir++;
                // Update class-wide counts dynamically
                updateClassStats(data.user.kelas);
                
                setStatus('success', `✓ ${data.user.name} berhasil absen!`);
                
                document.getElementById('detectedAvatar').src = data.user.foto 
                    ? data.user.foto 
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user.name)}&background=10b981&color=fff&size=80`;
                document.getElementById('detectedName').textContent = data.user.name;
                document.getElementById('detectedKelas').textContent = data.user.kelas || '—';
                
                addHistory(data.user, true);
            } else if (data.already_absen) {
                badge.className = 'badge-absen badge-info';
                badge.textContent = 'ℹ Sudah Absen Hari Ini';
                setStatus('info', `ℹ ${data.message}`);
                
                document.getElementById('detectedAvatar').src = data.user.foto 
                    ? data.user.foto 
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user.name)}&background=10b981&color=fff&size=80`;
                document.getElementById('detectedName').textContent = data.user.name;
                document.getElementById('detectedKelas').textContent = data.user.kelas || '—';
                
                addHistory(data.user, true);
            } else {
                badge.className = 'badge-absen badge-gagal';
                badge.textContent = '✗ ' + (data.message || 'Gagal');
                statGagal++;
                setStatus('error', `✗ ${data.message || 'Absensi gagal'}`);
                
                if (data.user) {
                    document.getElementById('detectedAvatar').src = data.user.foto 
                        ? data.user.foto 
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user.name)}&background=10b981&color=fff&size=80`;
                    document.getElementById('detectedName').textContent = data.user.name;
                    document.getElementById('detectedKelas').textContent = data.user.kelas || '—';
                    addHistory(data.user, false);
                } else {
                    document.getElementById('detectedName').textContent = 'Tidak Dikenal';
                    document.getElementById('detectedKelas').textContent = '—';
                    addHistory({name: 'Tidak Dikenal', kelas: '—', foto: null}, false);
                }
            }
        })
        .catch((err) => {
            console.error(err);
            badge.className = 'badge-absen badge-gagal';
            badge.textContent = '✗ Koneksi Error';
            statGagal++;
            setStatus('error', 'Koneksi ke server gagal');
        })
        .finally(() => {
            setTimeout(() => {
                // Beri tahu Python untuk melepas kunci recognition agar wajah berikutnya bisa dideteksi
                fetch(`${pythonHost}/attendance-unlock`, { method: 'POST' })
                    .catch(err => console.warn('Gagal unlock attendance:', err));

                scanCooldown = false;
                if (isRunning) {
                    setStatus('success', 'Kamera aktif — Mendeteksi wajah...');
                }
            }, 2000); // Cooldown 2 detik agar tidak terus menerus mendeteksi orang yang sama
        });
    }

    function addHistory(userData, status) {
        const userId = userData.id || userData.name;
        if (document.getElementById('hist-user-' + userId)) {
            return;
        }

        const list = document.getElementById('historyList');
        document.getElementById('emptyHistory')?.remove();

        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        let badgeClass = 'badge-gagal';
        let badgeText = '✗ Gagal';
        if (status === true) {
            badgeClass = 'badge-hadir';
            badgeText = '✓ Hadir';
        } else if (status === 'info') {
            badgeClass = 'badge-info';
            badgeText = 'ℹ Sudah Absen';
        }

        const item = document.createElement('div');
        item.className = 'history-item';
        item.id = 'hist-user-' + userId;
        item.innerHTML = `
            <img class="history-avatar" src="${userData.foto || `https://ui-avatars.com/api/?name=${encodeURIComponent(userData.name)}&background=10b981&color=fff&size=36`}" alt="">
            <div>
                <div class="history-name">${userData.name}</div>
                <div class="history-time">${time} · ${userData.kelas || '—'}</div>
            </div>
            <span class="history-badge ${badgeClass}">
                ${badgeText}
            </span>
        `;
        list.prepend(item);
    }
</script>

@endsection