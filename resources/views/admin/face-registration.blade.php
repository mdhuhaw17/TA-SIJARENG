@extends('layouts.dashboard')

@section('title', 'Registrasi Wajah')
@section('header', 'Registrasi Wajah')

@section('content')

<style>
    /* ===== LAYOUT ===== */
    .scan-layout {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 20px;
        align-items: start;
    }

    /* ===== CARDS ===== */
    .camera-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .camera-card-header {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
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
        max-width: 480px;
        margin: 0 auto;
        aspect-ratio: 4/3;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        border: 3px solid #2563eb;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #webcamFeed {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Corner brackets for face scan effect */
    .corner {
        position: absolute;
        width: 20px;
        height: 20px;
        border-color: #10b981;
        border-style: solid;
        z-index: 10;
        pointer-events: none;
    }
    .corner-tl { top: 12px; left: 12px; border-width: 3px 0 0 3px; }
    .corner-tr { top: 12px; right: 12px; border-width: 3px 3px 0 0; }
    .corner-bl { bottom: 12px; left: 12px; border-width: 0 0 3px 3px; }
    .corner-br { bottom: 12px; right: 12px; border-width: 0 3px 3px 0; }

    .camera-off {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #64748b;
        gap: 12px;
        text-align: center;
        padding: 20px;
    }

    /* ===== STATUS BAR ===== */
    .status-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 10px;
        margin: 14px 0;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
    }
    .status-bar.capturing .status-dot { background: #3b82f6; animation: pulse 1s infinite alternate; }
    .status-bar.training .status-dot { background: #8b5cf6; animation: pulse 0.5s infinite alternate; }
    .status-bar.success .status-dot { background: #10b981; }
    .status-bar.error .status-dot { background: #ef4444; }

    @keyframes pulse {
        from { opacity: 0.4; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1.1); }
    }

    /* ===== PROGRESS BAR ===== */
    .progress-container {
        margin-top: 14px;
        background: #e2e8f0;
        border-radius: 8px;
        height: 10px;
        overflow: hidden;
        display: none;
    }
    .progress-bar {
        height: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        width: 0%;
        transition: width 0.1s ease-out;
    }

    /* ===== BUTTONS ===== */
    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 14px;
    }
    .btn-start, .btn-stop {
        flex: 1;
        padding: 11px 16px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: 0.2s;
    }
    .btn-start {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        box-shadow: 0 4px 10px rgba(37,99,235,0.2);
    }
    .btn-start:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(37,99,235,0.3);
    }
    .btn-start:disabled {
        background: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
    }
    .btn-stop {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .btn-stop:hover:not(:disabled) {
        background: #e2e8f0;
    }
    .btn-stop:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ===== TABLE STYLING ===== */
    .info-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .info-card-header {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        padding: 16px 20px;
        font-size: 15px;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-card-body {
        padding: 15px;
    }
    .table-container {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: left;
    }
    th {
        background: #f8fafc;
        padding: 12px;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }
    td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    tr:hover {
        background: #f8fafc;
    }
    .badge-status {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: bold;
    }
    .badge-registered { background: #d1fae5; color: #065f46; }
    .badge-unregistered { background: #fee2e2; color: #991b1b; }

    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 11px;
        font-weight: bold;
        transition: 0.15s;
    }
    .btn-delete:hover {
        background: #dc2626;
        transform: scale(1.03);
    }
    
    /* Custom Dropdown List Styling */
    .dropdown-item:hover {
        background: #f1f5f9;
        color: #2563eb !important;
    }
</style>

<!-- HEADER BAR -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:bold; color:#1e293b;">
            <svg style="display:inline;vertical-align:middle;margin-right:6px" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
                <path d="M7 3H5a2 2 0 0 0-2 2v2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                <path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M17 21h2a2 2 0 0 0 2-2v-2"/>
                <circle cx="12" cy="10" r="3"/><path d="M8 17c1.5-2 6.5-2 8 0"/>
            </svg>
            Registrasi Wajah Siswa
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

    <!-- ===== KIRI: WEBCAM PANEL ===== -->
    <div class="camera-card">
        <div class="camera-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/>
            </svg>
            <h3>Kamera Registrasi</h3>
        </div>
        <div class="camera-card-body">
            
            <!-- DROPDOWN PENGGUNA (CUSTOM SEARCHABLE) -->
            <div style="margin-bottom:16px; position: relative;" id="searchableDropdownContainer">
                <label style="font-weight:bold;font-size:13px;color:#475569;display:block;margin-bottom:6px;">Nama Siswa / Pengguna:</label>
                
                <!-- Input Pencarian -->
                <div style="position: relative; width: 100%;">
                    <svg style="position: absolute; left: 14px; top: 13px; color: #94a3b8; pointer-events: none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    <input type="text" id="userSearchInput" placeholder="Ketik nama siswa..." style="width:100%;padding:11px 40px 11px 38px;border:1.5px solid #cbd5e1;border-radius:10px;font-size:14px;outline:none;background:white;transition:0.15s;color:#1e293b;box-sizing:border-box;" onfocus="showDropdownList()" oninput="filterDropdownList()">
                    <span style="position: absolute; right: 14px; top: 13px; color: #94a3b8; pointer-events: none;" id="dropdownChevron">▼</span>
                </div>
                
                <!-- Hidden Input untuk menyimpan ID terpilih -->
                <input type="hidden" id="userSelect" name="user_id" value="">
                
                <!-- Dropdown List -->
                <div id="dropdownList" style="display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: white; border: 1.5px solid #cbd5e1; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); max-height: 220px; overflow-y: auto; z-index: 1000; box-sizing: border-box;">
                    <div style="padding: 12px 14px; color: #94a3b8; font-size: 13px; font-style: italic; display: none;" id="noUsersFound">Siswa tidak ditemukan</div>
                    @foreach($dropdownUsers as $u)
                        <div class="dropdown-item" data-id="{{ $u->id }}" data-name="{{ $u->name }}" style="padding: 11px 14px; cursor: pointer; font-size: 14px; color: #1e293b; border-bottom: 1px solid #f1f5f9; transition: all 0.15s; display: flex; justify-content: space-between; align-items: center;" onclick="selectUser(this)">
                            <span style="font-weight: 500;">{{ $u->name }}</span>
                            <span style="font-size: 11px; color: #475569; background: #e2e8f0; padding: 2px 8px; border-radius: 6px; font-weight: 600;">{{ $u->kelas ?? 'Siswa' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- CAMERA BOX -->
            <div class="video-wrapper">
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>
                
                <img id="webcamFeed" style="display:none;" src="" alt="Webcam Feed">
                
                <div class="camera-off" id="cameraOff">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/>
                    </svg>
                    <span style="font-weight:bold;">Kamera Tidak Aktif</span>
                    <span style="font-size:12px; max-width:260px; opacity:0.8;">Pilih nama pengguna di atas terlebih dahulu, lalu klik "Mulai Registrasi Wajah".</span>
                </div>
            </div>

            <!-- STATUS BAR -->
            <div class="status-bar" id="statusBar">
                <div class="status-dot"></div>
                <span id="statusText">Menunggu pilihan pengguna...</span>
            </div>

            <!-- PROGRESS BAR -->
            <div class="progress-container" id="progressContainer">
                <div class="progress-bar" id="progressBar"></div>
            </div>

            <!-- CONTROL BUTTONS -->
            <div class="btn-group">
                <button class="btn-start" id="btnStart" onclick="startRegistration()" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/>
                    </svg>
                    Mulai Registrasi Wajah
                </button>
                <button class="btn-stop" id="btnStop" onclick="stopRegistration()" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><rect x="9" y="9" width="6" height="6"/>
                    </svg>
                    Stop
                </button>
            </div>

            <!-- TIPS -->
            <div style="margin-top:14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:10px 14px; font-size:12px; color:#1e40af; display:flex; gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span><b>Tips:</b> Saat kamera aktif, arahkan wajah ke kamera dengan tegak. Ubah posisi kepala Anda sedikit ke kiri, kanan, atas, dan bawah secara perlahan untuk melatih deteksi sudut wajah yang optimal.</span>
            </div>

        </div>
    </div>

    <!-- ===== KANAN: MANAGEMENT REGISTERED USERS ===== -->
    <div class="info-card">
        <div class="info-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                <path d="M12 6v6l4 2"/>
            </svg>
            Status Registrasi Database
        </div>
        <div class="info-card-body">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Total Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><b>{{ $user->name }}</b></td>
                                <td>{{ $user->kelas ?? '&mdash;' }}</td>
                                <td>
                                    @if($user->faceRegistration)
                                        <span class="badge-status badge-registered">Terdaftar</span>
                                    @else
                                        <span class="badge-status badge-unregistered">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->faceRegistration ? $user->faceRegistration->total_images . ' gambar' : '0' }}
                                </td>
                                <td>
                                    @if($user->faceRegistration)
                                        <form action="{{ route('face-registration.destroy', $user->faceRegistration->id) }}" method="POST" onsubmit="event.preventDefault(); const form = this; showConfirmModal('Hapus Data Wajah', 'Apakah Anda yakin ingin menghapus data wajah {{ addslashes($user->name) }}?', () => { form.submit(); });">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:#94a3b8;padding:20px;">Tidak ada data pengguna</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 16px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>

</div>

<script>
    let pollInterval = null;
    let selectedUserId = null;
    let selectedUserName = "";
    const pythonHost = "http://localhost:5000";

    const userSelect        = document.getElementById('userSelect');
    const userSearchInput   = document.getElementById('userSearchInput');
    const btnStart          = document.getElementById('btnStart');
    const btnStop           = document.getElementById('btnStop');
    const webcamFeed        = document.getElementById('webcamFeed');
    const cameraOff         = document.getElementById('cameraOff');
    const statusBar         = document.getElementById('statusBar');
    const statusText        = document.getElementById('statusText');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar       = document.getElementById('progressBar');

    function setStatus(type, text) {
        statusBar.className = 'status-bar ' + type;
        statusText.innerText = text;
    }

    // SEARCHABLE DROPDOWN LOGIC
    function showDropdownList() {
        const list = document.getElementById('dropdownList');
        list.style.display = 'block';
        document.getElementById('dropdownChevron').textContent = '▲';
        filterDropdownList(); // reset filter view
    }

    function hideDropdownList() {
        setTimeout(() => {
            const list = document.getElementById('dropdownList');
            if (list) {
                list.style.display = 'none';
                document.getElementById('dropdownChevron').textContent = '▼';
            }
            
            // Check if the current search input text matches the selected user name
            if (selectedUserId && selectedUserName) {
                if (userSearchInput.value !== selectedUserName) {
                    userSearchInput.value = selectedUserName;
                }
            } else {
                userSearchInput.value = "";
                userSelect.value = "";
                selectedUserId = null;
                selectedUserName = "";
                btnStart.disabled = true;
                setStatus('', 'Menunggu pilihan pengguna...');
            }
        }, 200); // delay to allow clicks to register
    }

    // Close list when clicking outside
    window.addEventListener('click', function(e) {
        const container = document.getElementById('searchableDropdownContainer');
        if (container && !container.contains(e.target)) {
            const list = document.getElementById('dropdownList');
            if (list && list.style.display === 'block') {
                hideDropdownList();
            }
        }
    });

    function filterDropdownList() {
        const input = document.getElementById('userSearchInput');
        const filter = input.value.toLowerCase();
        const items = document.querySelectorAll('.dropdown-item');
        let count = 0;

        items.forEach(item => {
            const text = item.getAttribute('data-name').toLowerCase();
            if (text.includes(filter)) {
                item.style.display = 'flex';
                count++;
            } else {
                item.style.display = 'none';
            }
        });

        const noUsers = document.getElementById('noUsersFound');
        if (count === 0) {
            noUsers.style.display = 'block';
        } else {
            noUsers.style.display = 'none';
        }

        // If user clears the input, reset selected user
        if (filter.trim() === "") {
            userSelect.value = "";
            selectedUserId = null;
            selectedUserName = "";
            btnStart.disabled = true;
            setStatus('', 'Menunggu pilihan pengguna...');
        }
    }

    function selectUser(element) {
        const id = element.getAttribute('data-id');
        const name = element.getAttribute('data-name');
        
        userSearchInput.value = name;
        userSelect.value = id;
        selectedUserId = id;
        selectedUserName = name;
        
        btnStart.disabled = false;
        setStatus('', 'Siswa terpilih. Klik "Mulai Registrasi Wajah" untuk merekam.');
        
        hideDropdownList();
    }

    function startRegistration() {
        if (!selectedUserId) return;

        // Reset state di Flask untuk user ini
        fetch(`${pythonHost}/reset/${selectedUserId}`, { method: 'POST' })
            .then(() => {
                // Aktifkan streaming MJPEG ke element img
                webcamFeed.src = `${pythonHost}/register-feed/${selectedUserId}`;
                webcamFeed.style.display = 'block';
                cameraOff.style.display = 'none';

                userSearchInput.disabled = true;
                btnStart.disabled = true;
                btnStop.disabled = false;
                progressContainer.style.display = 'block';
                progressBar.style.width = "0%";

                setStatus('capturing', 'Mengaktifkan webcam, memproses deteksi...');

                // Mulai polling status registrasi
                startPolling();
            })
            .catch(err => {
                setStatus('error', 'Gagal menghubungi server Python. Jalankan flask API di port 5000.');
                console.error(err);
            });
    }

    function stopRegistration() {
        // Hentikan polling
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }

        // Matikan image stream
        webcamFeed.src = "";
        webcamFeed.style.display = 'none';
        cameraOff.style.display = 'flex';

        userSearchInput.disabled = false;
        userSearchInput.value = "";
        userSelect.value = "";
        selectedUserId = null;
        selectedUserName = "";

        btnStart.disabled = true;
        btnStop.disabled = true;
        progressContainer.style.display = 'none';

        setStatus('', 'Registrasi dihentikan.');
    }

    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);

        pollInterval = setInterval(() => {
            if (!selectedUserId) return;

            fetch(`${pythonHost}/status/${selectedUserId}`)
                .then(res => res.json())
                .then(data => {
                    const status = data.status;
                    const current = data.current || 0;
                    const total = data.total || 100;
                    const percent = Math.round((current / total) * 100);

                    progressBar.style.width = percent + "%";

                    if (status === 'capturing') {
                        setStatus('capturing', `Mengambil dataset wajah: ${current} / ${total} (${percent}%)`);
                    } else if (status === 'training') {
                        setStatus('training', 'Training Model LBPH sedang berjalan... Harap tunggu.');
                    } else if (status === 'completed') {
                        clearInterval(pollInterval);
                        pollInterval = null;
                        setStatus('success', '✓ Capture Selesai & Model Berhasil Dilatih!');
                        
                        // Simpan hasil ke Laravel database
                        saveToLaravel(selectedUserId, current);
                    } else if (status === 'failed') {
                        clearInterval(pollInterval);
                        pollInterval = null;
                        setStatus('error', `✗ Gagal: ${data.message || 'Terjadi kesalahan'}`);
                        stopRegistration();
                    }
                })
                .catch(err => {
                    console.error("Polling error:", err);
                });
        }, 1000);
    }

    function saveToLaravel(userId, totalImages) {
        // Kirim AJAX ke Laravel controller untuk menyimpan record di DB
        fetch("{{ route('face-registration.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                dataset_path: `python_services/dataset/${userId}`,
                total_images: totalImages
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotificationModal('Registrasi Wajah Berhasil', data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                setStatus('error', 'Gagal menyimpan data registrasi ke database Laravel.');
                showNotificationModal('Penyimpanan Gagal', 'Gagal menyimpan ke Laravel: ' + data.message, 'error');
            }
        })
        .catch(err => {
            setStatus('error', 'Koneksi error ke Laravel saat menyimpan registrasi.');
            console.error("Laravel save error:", err);
        });
    }
</script>

@endsection
