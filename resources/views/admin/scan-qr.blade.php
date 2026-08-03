@extends('layouts.dashboard')

@section('title', 'Scan QR')
@section('header', 'Scan QR')

@section('content')

<style>
    .greeting-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        background: linear-gradient(135deg, #2563eb, #3b82f6);
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

    .scan-layout {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 20px;
        align-items: start;
    }

    /* Camera Card */
    .camera-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .camera-card-header {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .camera-card-header h3 { color: white; font-size: 16px; font-weight: bold; margin: 0; }
    .camera-card-body { padding: 20px; }

    /* QR Reader container */
    #reader-wrapper {
        position: relative;
        width: 100%;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        border: 3px solid #3b82f6;
        min-height: 340px;
    }

    /* Override html5-qrcode internal styles */
    #reader {
        width: 100% !important;
        min-height: 340px;
        border: none !important;
        background: transparent !important;
    }
    #reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        border-radius: 0 !important;
    }
    /* Hide html5-qrcode file upload button */
    #reader__dashboard_section_fileselection,
    #reader__filescan_input,
    #reader__dashboard_section_swaplink {
        display: none !important;
    }
    #reader__scan_region {
        min-height: 340px;
    }
    #reader__dashboard {
        padding: 0 !important;
    }

    /* Corner brackets */
    .corner { position: absolute; width: 28px; height: 28px; border-color: #60a5fa; border-style: solid; z-index: 10; pointer-events:none; }
    .corner-tl { top: 12px; left: 12px; border-width: 3px 0 0 3px; border-radius: 4px 0 0 0; }
    .corner-tr { top: 12px; right: 12px; border-width: 3px 3px 0 0; border-radius: 0 4px 0 0; }
    .corner-bl { bottom: 12px; left: 12px; border-width: 0 0 3px 3px; border-radius: 0 0 0 4px; }
    .corner-br { bottom: 12px; right: 12px; border-width: 0 3px 3px 0; border-radius: 0 0 4px 0; }

    /* Scan line */
    .scan-line {
        position: absolute;
        left: 12px; right: 12px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #60a5fa, transparent);
        box-shadow: 0 0 10px #3b82f6;
        animation: scanMove 2.5s ease-in-out infinite;
        z-index: 9;
        pointer-events: none;
        display: none;
    }
    @keyframes scanMove {
        0%   { top: 15%; }
        50%  { top: 80%; }
        100% { top: 15%; }
    }

    /* Camera off placeholder */
    #camera-placeholder {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #475569;
        gap: 12px;
        font-size: 14px;
        z-index: 5;
        background: #0f172a;
    }

    /* Status bar */
    .status-bar {
        margin-top: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: #1e40af;
        font-weight: 600;
        transition: all 0.3s;
    }
    .status-bar.idle    { background: #f8fafc; border-color: #e2e8f0; color: #64748b; }
    .status-bar.active  { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
    .status-bar.success { background: #f0fdf4; border-color: #bbf7d0; color: #065f46; }
    .status-bar.error   { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
    .status-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #94a3b8;
        flex-shrink: 0;
    }
    .status-bar.active  .status-dot { background: #3b82f6; animation: pulse 1.5s infinite; }
    .status-bar.success .status-dot { background: #10b981; animation: pulse 1.5s infinite; }
    .status-bar.error   .status-dot { background: #ef4444; }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.4); }
    }

    /* Buttons */
    .btn-group { display: flex; gap: 10px; margin-top: 14px; }
    .btn-start {
        flex: 1; padding: 11px;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: white; font-weight: 700; font-size: 14px;
        border: none; border-radius: 10px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        transition: 0.2s;
    }
    .btn-start:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(37,99,235,0.4); }
    .btn-start:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    .btn-stop {
        flex: 1; padding: 11px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white; font-weight: 700; font-size: 14px;
        border: none; border-radius: 10px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        transition: 0.2s;
    }
    .btn-stop:hover { transform: translateY(-1px); }
    .btn-stop:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    .btn-back {
        padding: 11px 18px;
        background: #f1f5f9; color: #475569;
        font-weight: 700; font-size: 14px;
        border: none; border-radius: 10px; cursor: pointer;
        text-decoration: none;
        display: flex; align-items: center; gap: 6px;
        transition: 0.2s;
    }
    .btn-back:hover { background: #e2e8f0; }

    /* Result panel */
    .result-panel { display: flex; flex-direction: column; gap: 16px; }
    .info-card { background: white; border-radius: 18px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; }
    .info-card-header {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        padding: 14px 18px; color: white;
        font-size: 14px; font-weight: bold;
        display: flex; align-items: center; gap: 8px;
    }
    .info-card-body { padding: 18px; }

    /* Detected user */
    .user-detected {
        display: flex; align-items: center; gap: 14px;
        padding: 14px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 12px; margin-bottom: 12px;
        animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .user-avatar {
        width: 60px; height: 60px;
        border-radius: 12px; object-fit: cover;
        border: 3px solid #3b82f6;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    .user-info-name { font-size: 16px; font-weight: 800; color: #1e293b; }
    .user-info-kelas { font-size: 13px; color: #64748b; margin-top: 2px; }
    .badge-absen {
        margin-top: 8px; display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 999px;
        font-size: 12px; font-weight: 700;
    }
    .badge-hadir  { background: #d1fae5; color: #065f46; }
    .badge-gagal  { background: #fee2e2; color: #991b1b; }
    .badge-waiting{ background: #dbeafe; color: #1e40af; }

    .result-placeholder {
        text-align: center; padding: 28px 20px; color: #94a3b8;
    }
    .result-placeholder p { font-size: 13px; margin-top: 10px; }

    /* Stats */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .stat-item { text-align: center; padding: 12px 8px; border-radius: 12px; font-weight: 700; }
    .stat-item .num { font-size: 22px; }
    .stat-item .lbl { font-size: 11px; margin-top: 2px; opacity: 0.85; }
    .stat-hadir  { background: linear-gradient(135deg, #10b981, #059669); color: white; }
    .stat-gagal  { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
    .stat-total  { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }

    /* History */
    .history-list { max-height: 220px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; }
    .history-item {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px;
        background: #f8fafc; border-radius: 10px;
        border: 1px solid #f1f5f9; font-size: 13px;
    }
    .history-avatar { width: 36px; height: 36px; border-radius: 8px; object-fit: cover; }
    .history-name { font-weight: 600; color: #1e293b; }
    .history-time { font-size: 11px; color: #94a3b8; }
    .history-badge { margin-left: auto; padding: 3px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .empty-history { text-align: center; padding: 24px; color: #cbd5e1; font-size: 13px; }
</style>


<!-- PAGE HEADER -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:bold; color:#1e293b;">
            <svg style="display:inline;vertical-align:middle;margin-right:6px" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/><path d="M14 14h3v3h-3z"/>
            </svg>
            Scan QR Absensi
        </h2>
    </div>
    <a href="{{ route('admin.dashboard') }}" style="display:inline-flex;align-items:center;gap:7px;padding:9px 18px;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;font-weight:700;font-size:13px;border-radius:10px;text-decoration:none;box-shadow:0 4px 12px rgba(239,68,68,0.3);transition:0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
</div>

<div class="scan-layout">

    <!-- KIRI: KAMERA -->
    <div class="camera-card">
        <div class="camera-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/><path d="M14 14h3v3h-3z"/>
            </svg>
            <h3>Kamera QR Scanner</h3>
        </div>
        <div class="camera-card-body">

            <!-- VIDEO WRAPPER -->
            <div id="reader-wrapper">
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>
                <div class="scan-line" id="scanLine"></div>

                <!-- html5-qrcode target -->
                <div id="reader"></div>

                <!-- Placeholder saat kamera belum aktif -->
                <div id="camera-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" fill="none" stroke="#475569" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/><path d="M14 14h3v3h-3z"/>
                    </svg>
                    <span style="color:#64748b;font-weight:600">Kamera belum aktif</span>
                    <span style="font-size:11px;color:#94a3b8">Klik "Mulai Kamera" untuk memulai</span>
                </div>
            </div>

            <!-- STATUS -->
            <div class="status-bar idle" id="statusBar">
                <div class="status-dot"></div>
                <span id="statusText">Kamera belum aktif — klik Mulai Kamera</span>
            </div>

            <!-- BUTTONS -->
            <div class="btn-group">
                <button class="btn-start" id="btnStart" onclick="startScanner()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>
                    </svg>
                    Mulai Kamera
                </button>
                <button class="btn-stop" id="btnStop" onclick="stopScanner()" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6"/>
                    </svg>
                    Stop
                </button>
            </div>

            <!-- TIPS -->
            <div style="margin-top:14px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 14px;font-size:12px;color:#1e40af;display:flex;gap:8px;align-items:flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span>Pastikan QR Code siswa terlihat jelas di dalam kotak scanner. Kamera akan otomatis mendeteksi QR Code.</span>
            </div>

        </div>
    </div>

    <!-- KANAN: HASIL -->
    <div class="result-panel">

        <!-- HASIL DETEKSI -->
        <div class="info-card">
            <div class="info-card-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c1.5-4 14.5-4 16 0"/>
                </svg>
                Hasil Scan
            </div>
            <div class="info-card-body">
                <div class="user-detected" id="userDetected" style="display:none;">
                    <img id="detectedAvatar" class="user-avatar" src="" alt="foto">
                    <div>
                        <div class="user-info-name" id="detectedName">—</div>
                        <div class="user-info-kelas" id="detectedKelas">—</div>
                        <div class="badge-absen badge-waiting" id="detectedBadge">Memproses...</div>
                    </div>
                </div>
                <div class="result-placeholder" id="resultPlaceholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"/><path d="M14 14h3v3h-3z"/>
                    </svg>
                    <p>Belum ada QR yang terdeteksi.<br>Aktifkan kamera dan arahkan QR Code.</p>
                </div>
            </div>
        </div>

        <!-- STATISTIK -->
        <div class="info-card">
            <div class="info-card-header" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 3v18h18"/><path d="M7 14l4-4 4 4 4-8"/>
                </svg>
                Statistik Sesi
            </div>
            <div class="info-card-body">
                <div class="stats-grid">
                    <div class="stat-item stat-hadir">
                        <div class="num" id="statHadir">0</div>
                        <div class="lbl">Berhasil</div>
                    </div>
                    <div class="stat-item stat-gagal">
                        <div class="num" id="statGagal">0</div>
                        <div class="lbl">Gagal</div>
                    </div>
                    <div class="stat-item stat-total">
                        <div class="num" id="statTotal">0</div>
                        <div class="lbl">Total Scan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIWAYAT -->
        <div class="info-card">
            <div class="info-card-header" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                Riwayat Scan
            </div>
            <div class="info-card-body" style="padding:12px 14px;">
                <div class="history-list" id="historyList">
                    <div class="empty-history" id="emptyHistory">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="#e2e8f0" stroke-width="1.5" viewBox="0 0 24 24" style="display:block;margin:0 auto 8px">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        Belum ada scan
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- html5-qrcode CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>



    // ===== SCANNER =====
    let html5QrCode = null;
    let isRunning = false;
    let sudahScan = false;
    let statHadir = 0, statGagal = 0, statTotal = 0;

    function setStatus(type, msg) {
        const bar = document.getElementById('statusBar');
        bar.className = 'status-bar ' + type;
        document.getElementById('statusText').textContent = msg;
    }

    async function startScanner() {
        document.getElementById('btnStart').disabled = true;

        try {
            html5QrCode = new Html5Qrcode("reader");

            const devices = await Html5Qrcode.getCameras();

            if (!devices || devices.length === 0) {
                setStatus('error', 'Tidak ada kamera yang ditemukan');
                document.getElementById('btnStart').disabled = false;
                return;
            }

            // Sembunyikan placeholder
            document.getElementById('camera-placeholder').style.display = 'none';

            await html5QrCode.start(
                { facingMode: "environment" }, // coba kamera belakang dulu
                {
                    fps: 10,
                    qrbox: { width: 220, height: 220 },
                    aspectRatio: 1.333
                },
                onScanSuccess,
                (errorMsg) => { /* abaikan scan errors */ }
            ).catch(async () => {
                // Fallback ke kamera pertama yang tersedia
                await html5QrCode.start(
                    devices[0].id,
                    {
                        fps: 10,
                        qrbox: { width: 220, height: 220 },
                        aspectRatio: 1.333
                    },
                    onScanSuccess,
                    (errorMsg) => { /* abaikan */ }
                );
            });

            isRunning = true;
            document.getElementById('scanLine').style.display = 'block';
            document.getElementById('btnStop').disabled = false;
            setStatus('active', 'Kamera aktif — Scan QR Code siswa');

        } catch (err) {
            console.error(err);
            document.getElementById('camera-placeholder').style.display = 'flex';
            document.getElementById('btnStart').disabled = false;

            if (err.name === 'NotAllowedError') {
                setStatus('error', 'Akses kamera ditolak — Izinkan kamera di browser');
            } else if (err.name === 'NotFoundError') {
                setStatus('error', 'Kamera tidak ditemukan di perangkat ini');
            } else {
                setStatus('error', 'Gagal mengakses kamera: ' + err.message);
            }
        }
    }

    async function stopScanner() {
        if (html5QrCode && isRunning) {
            await html5QrCode.stop();
            isRunning = false;
        }
        document.getElementById('scanLine').style.display = 'none';
        document.getElementById('camera-placeholder').style.display = 'flex';
        document.getElementById('btnStart').disabled = false;
        document.getElementById('btnStop').disabled = true;
        setStatus('idle', 'Kamera dimatikan');
    }

    function onScanSuccess(decodedText) {
        if (sudahScan) return;
        sudahScan = true;
        statTotal++;
        document.getElementById('statTotal').textContent = statTotal;

        setStatus('active', 'QR terdeteksi — Memproses absensi...');

        // Tampilkan panel hasil
        document.getElementById('resultPlaceholder').style.display = 'none';
        const det = document.getElementById('userDetected');
        det.style.display = 'flex';
        document.getElementById('detectedName').textContent = 'Memproses...';
        document.getElementById('detectedKelas').textContent = decodedText;
        document.getElementById('detectedAvatar').src = 'https://ui-avatars.com/api/?name=...&background=3b82f6&color=fff&size=80';
        const badge = document.getElementById('detectedBadge');
        badge.className = 'badge-absen badge-waiting';
        badge.textContent = '⏳ Memproses...';

        fetch("{{ route('scan.qr.process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_code: decodedText })
        })
        .then(res => res.json())
        .then(data => {
            const userName = data.user?.name ?? '—';
            const userKelas = data.user?.kelas ?? '—';
            const userFoto = data.user?.foto ?? `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=3b82f6&color=fff&size=80`;

            document.getElementById('detectedName').textContent = userName;
            document.getElementById('detectedKelas').textContent = userKelas;
            document.getElementById('detectedAvatar').src = userFoto;

            if (data.success) {
                badge.className = 'badge-absen badge-hadir';
                badge.textContent = '✓ Absensi Berhasil';
                statHadir++;
                document.getElementById('statHadir').textContent = statHadir;
                setStatus('success', `✓ ${userName} berhasil absen!`);
                det.style.background = '#f0fdf4';
                det.style.borderColor = '#bbf7d0';
            } else {
                badge.className = 'badge-absen badge-gagal';
                badge.textContent = '✗ ' + data.message;
                statGagal++;
                document.getElementById('statGagal').textContent = statGagal;
                setStatus('error', `✗ ${data.message}`);
                det.style.background = '#fef2f2';
                det.style.borderColor = '#fecaca';
            }

            addHistory({ name: userName, kelas: userKelas, foto: userFoto }, data.success);

            setTimeout(() => {
                sudahScan = false;
                if (isRunning) setStatus('active', 'Kamera aktif — Scan QR Code siswa');
            }, 3000);
        })
        .catch(err => {
            console.error(err);
            badge.className = 'badge-absen badge-gagal';
            badge.textContent = '✗ Koneksi Error';
            statGagal++;
            document.getElementById('statGagal').textContent = statGagal;
            setStatus('error', 'Gagal terhubung ke server');
            setTimeout(() => { sudahScan = false; }, 3000);
        });
    }

    function addHistory(user, success) {
        document.getElementById('emptyHistory')?.remove();
        const list = document.getElementById('historyList');
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const item = document.createElement('div');
        item.className = 'history-item';
        item.innerHTML = `
            <img class="history-avatar" src="${user.foto}" alt="">
            <div>
                <div class="history-name">${user.name}</div>
                <div class="history-time">${time} · ${user.kelas}</div>
            </div>
            <span class="history-badge ${success ? 'badge-hadir' : 'badge-gagal'}">
                ${success ? '✓ Hadir' : '✗ Gagal'}
            </span>
        `;
        list.prepend(item);
    }
</script>

@endsection