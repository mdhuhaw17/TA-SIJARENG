@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')

<style>
/* CARD */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.card-box {
    padding: 15px;
    border-radius: 12px;
    color: white;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.card-blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
.card-green { background: linear-gradient(135deg, #10b981, #059669); }
.card-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.card-red { background: linear-gradient(135deg, #ef4444, #dc2626); }
.card-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }

/* MENU */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-top: 20px;
}

.menu-item {
    background: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    font-weight: bold;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: 0.2s;
}

.menu-item:hover {
    transform: translateY(-3px);
}

/* MAP & LIST */
.bottom-section {
    display: grid;
    grid-template-columns: 3fr 1.3fr;
    gap: 20px;
    margin-top: 20px;
    align-items: stretch;
}

.map-box {
    min-height: 420px;
    background: white;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    position: relative;
}

#chartAbsen {
    width: 100% !important;
    height: 360px !important;
}

.list-box {
    background: white;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.feature-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 80px;
    background: #f9fafb;
    border-radius: 10px;
    font-weight: bold;
    text-decoration: none;
    color: black;
    transition: 0.2s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.feature-box:hover {
    background: #e0f2fe;
    transform: scale(1.05);
}

/* GREETING CONTAINER */
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

/* KIRI */
.greeting-title {
    font-size: 20px;
    font-weight: bold;
}

.greeting-sub {
    font-size: 13px;
    opacity: 0.85;
    margin-top: 3px;
}

/* (JAM) */
.jam-box {
    text-align: right;
    background: rgba(255,255,255,0.15);
    padding: 10px 15px;
    border-radius: 10px;
    backdrop-filter: blur(6px);
}

/* JAM */
.jam-text {
    font-size: 20px;
    font-weight: bold;
    letter-spacing: 1px;
}

/* TANGGAL */
.tanggal-text {
    font-size: 12px;
    opacity: 0.9;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- GREETING -->
<div class="greeting-box">

    <div class="greeting-left">
        <h2 class="greeting-title">
            Halo, {{ Auth::user()->name }}
        </h2>
        <p class="greeting-sub">
            Selamat datang kembali, semoga harimu produktif
        </p>
    </div>

    <div class="greeting-right">
        <div class="jam-box">
            <div id="jam" class="jam-text"></div>
            <div id="tanggal" class="tanggal-text"></div>
        </div>
    </div>

</div>

<script>
    function updateJam() {
        const now = new Date();

        const jam = now.getHours().toString().padStart(2, '0');
        const menit = now.getMinutes().toString().padStart(2, '0');
        const detik = now.getSeconds().toString().padStart(2, '0');

        const tanggal = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        document.getElementById('jam').innerHTML = `${jam}:${menit}:${detik}`;
        document.getElementById('tanggal').innerHTML = tanggal;
    }

    setInterval(updateJam, 1000);
    updateJam();
</script>

<!-- CARDS -->
<div class="dashboard-cards">

    <div class="card-box card-blue">
        TOTAL SISWA<br><br>
        <span class="text-2xl">{{ $totalSiswa }}</span>
    </div>

    <div class="card-box card-green">
        HADIR<br><br>
        <span class="text-2xl">{{ $totalHadir }}</span>
    </div>

    <div class="card-box card-red">
        BELUM ABSEN<br><br>
        <span class="text-2xl">{{ $belumAbsen }}</span>
    </div>

    <div class="card-box card-purple">
        TOTAL KELAS<br><br>
        <span class="text-2xl">{{ $totalKelas }}</span>
    </div>

    <div class="card-box card-orange">
        PERSENTASE KEHADIRAN<br><br>
        <span class="text-2xl">{{ $persentase }}%</span>
    </div>

</div>

<!-- DIAGRAM -->
<div class="bottom-section">

    <div class="map-box">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-lg font-bold">Diagram Kehadiran</h2>
                <p class="text-sm text-gray-500">
                    Statistik hadir, alfa, dan izin siswa
                </p>
            </div>
        </div>
        <canvas id="chartAbsen"></canvas>
    </div>
    <script>
        const ctx = document.getElementById('chartAbsen');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Hadir', 'Alfa', 'Izin'],
                datasets: [{
                    label: 'Data Kehadiran',
                    data:  [{{ $totalHadir }},
                            {{ $totalAlfa }},
                            {{ $totalIzin }}], // nanti ambil dari DB
                    backgroundColor: [
                        '#10b981',
                        '#ef4444',
                        '#f59e0b'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt, elements) => {
                    if (elements.length > 0) {
                        let index = elements[0].index;

                        if (index === 0) showDetail('hadir');
                        if (index === 1) showDetail('alfa');
                        if (index === 2) showDetail('izin');
                    }
                }
            }
        });
    </script>

    <!-- MODAL -->
    <div id="modalDetail"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-fadeIn">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-6 py-5 border-b">
                <div>
                    <h2 id="judulModal"
                        class="text-2xl font-bold text-gray-800">
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Detail data kehadiran siswa
                    </p>
                </div>
                <button onclick="closeModal()"
                    class="bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full text-lg">
                    ✕
                </button>
            </div>

            <!-- BODY -->
            <div class="p-6 max-h-[450px] overflow-y-auto">
                <div id="listSiswa"
                    class="space-y-3">
                </div>
            </div>
        </div>
    </div>
    <script>
        const dataAbsen = {
            hadir: [
                @foreach($hadirUsers as $item)
                {
                    nama: "{{ $item->user->name }}",
                    kelas: "{{ $item->user->kelas }}"
                },
                @endforeach
            ],
            izin: [
                @foreach($izinUsers as $item)
                {
                    nama: "{{ $item->user->name }}",
                    kelas: "{{ $item->user->kelas }}"
                },
                @endforeach
            ],
            alfa: [
                @foreach($alfaUsers as $item)
                {
                    nama: "{{ $item->user->name }}",
                    kelas: "{{ $item->user->kelas }}"
                },
                @endforeach
            ]
        };

        function showDetail(type) {
            document
                .getElementById('modalDetail')
                .classList.remove('hidden');
            let title = '';
            if (type === 'hadir') {
                title = 'Siswa Hadir';
            }
            if (type === 'izin') {
                title = 'Siswa Izin';
            }
            if (type === 'alfa') {
                title = 'Siswa Alfa';
            }
            document.getElementById('judulModal')
                .innerText = title;
            let html = '';
            if (dataAbsen[type].length === 0) {
                html = `
                    <div class="text-center text-gray-400 py-10">
                        Tidak ada data siswa
                    </div>
                `;
            }
            dataAbsen[type].forEach((item, index) => {
                html += `
                    <div class="flex items-center justify-between bg-gray-50 hover:bg-blue-50 transition rounded-2xl px-4 py-4 border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-lg">
                                ${index + 1}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">
                                    ${item.nama}
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${item.kelas}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            document.getElementById('listSiswa')
                .innerHTML = html;
        }
        function closeModal() {
            document
                .getElementById('modalDetail')
                .classList.add('hidden');
        }
    </script>

    <div class="list-box">
    <b>Menu Navigasi</b>

        <div class="grid grid-cols-3 gap-3 mt-3">

            <a href="{{ route('scan.qr') }}" class="feature-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="#3b82f6" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4z"></path>
                    <path d="M14 14h3v3h-3z"></path>
                </svg>
                <br>Scan QR
            </a>

            <a href="{{ route('scan.wajah') }}" class="feature-box">
                <svg xmlns="http://www.w3.org/2000/svg"class="w-7 h-7"fill="none"stroke="#10b981"stroke-width="2"viewBox="0 0 24 24">
                    <path d="M7 3H5a2 2 0 0 0-2 2v2"></path>
                    <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                    <path d="M17 21h2a2 2 0 0 0 2-2v-2"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                    <path d="M8 17c1.5-2 6.5-2 8 0"></path>
                </svg>
                <br>Scan Wajah
            </a>

            <a href="{{ route('absenmanual') }}" class="feature-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 11l3 3L22 4"></path>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                <br>Manual
            </a>

            <a href="{{ route('master.data') }}" class="feature-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="#6366f1" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 6h18M3 12h18M3 18h18"></path>
                </svg>
                <br>Master Data
            </a>

            <a href="{{ route('laporan') }}" class="feature-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 3v18h18"></path>
                    <path d="M7 14l4-4 4 4 4-8"></path>
                </svg>
                <br>Laporan
            </a>

            <a href="{{ route('kelas') }}" class="feature-box">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" stroke="#06b6d4" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 10L12 4L21 10"></path>
                    <path d="M5 10V20H19V10"></path>
                    <path d="M9 20V14H15V20"></path>
                </svg>
                <br>Kelas
            </a>

            <a href="{{ route('user.page') }}" class="feature-box">
                <svg xmlns="http://www.w3.org/2000/svg"class="w-7 h-7"fill="none"stroke="#8b5cf6"stroke-width="2"viewBox="0 0 24 24">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M4 20c1.5-4 14.5-4 16 0"></path>
                </svg>
                <br>User
            </a>

        </div>
    </div>

</div>

@endsection