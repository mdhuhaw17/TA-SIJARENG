@extends('layouts.dashboard')

@section('title', 'Kelas')
@section('header', 'Kelas')

@section('content')

<style>

/* GRID */
.group-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}

/* VARIASI WARNA */
.card-1 { border-top: 5px solid #3b82f6; }
.card-2 { border-top: 5px solid #10b981; }
.card-3 { border-top: 5px solid #f59e0b; }
.card-4 { border-top: 5px solid #ef4444; }
.card-5 { border-top: 5px solid #8b5cf6; }

/* CARD */
.group-card {
    background: white;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: 0.3s;
    position: relative;
    cursor: pointer;
    overflow: hidden;
}

.group-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.12);
}

/* HEADER */
.group-header {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

/* SISWA */
.group-siswa {
    font-size: 15px;
    font-weight: 600;
    color: #4b5563;
}

/* BADGE */
.group-badge {
    position: absolute;
    top: 18px;
    right: 18px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

/* ICON */
.group-icon {
    width: 60px;
    height: 60px;
    background: #eff6ff;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
}

/* MODAL */
.modal-bg {
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,0.6);
    backdrop-filter: blur(5px);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 999;
    padding: 20px;
}

.modal-box {
    width: 100%;
    max-width: 850px;
    background: white;
    border-radius: 28px;
    overflow: hidden;
    animation: muncul .25s ease;
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
}

@keyframes muncul {
    from {
        opacity: 0;
        transform: scale(.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* MODAL HEADER */
.modal-header {
    padding: 24px 30px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 26px;
    font-weight: bold;
}

.modal-sub {
    font-size: 14px;
    opacity: .9;
    margin-top: 4px;
}

/* CLOSE */
.close-btn {
    background: rgba(255,255,255,0.2);
    width: 42px;
    height: 42px;
    border-radius: 12px;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    transition: .2s;
}

.close-btn:hover {
    background: rgba(255,255,255,0.3);
}

/* BODY */
.modal-body {
    padding: 30px;
    max-height: 500px;
    overflow-y: auto;
}

/* TABLE */
.table-siswa {
    width: 100%;
    border-collapse: collapse;
}

.table-siswa thead {
    background: #f3f4f6;
}

.table-siswa th {
    padding: 16px;
    text-align: left;
    color: #374151;
    font-size: 14px;
}

.table-siswa td {
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
    color: #4b5563;
}

.table-siswa tbody tr:hover {
    background: #f9fafb;
}

/* EMPTY */
.empty-box {
    text-align: center;
    padding: 50px 20px;
    color: #9ca3af;
    font-size: 15px;
}

</style>

<div class="p-6">

    <!-- TOP -->
    <div class="flex justify-between items-center mb-8">

        <a href="{{ route('admin.dashboard') }}"
            class="bg-red-500 text-white px-5 py-3 rounded-xl shadow hover:bg-red-600 transition">
            ← Kembali
        </a>

        <div class="text-right">
            <h2 class="text-3xl font-bold text-gray-800">
                Daftar Kelas
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Klik kartu kelas untuk melihat daftar siswa
            </p>
        </div>

    </div>

    <!-- CARD -->
    <div class="group-container">

        @forelse ($groups as $index => $group)

        <div class="group-card card-{{ ($index % 5) + 1 }}"
            onclick="openModal('modal{{ $group->id }}')">

            <div class="group-badge">
                {{ $group->users_count }} Siswa
            </div>

            <div class="group-icon">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24">

                    <path d="M3 10L12 4L21 10"></path>
                    <path d="M5 10V20H19V10"></path>
                    <path d="M9 20V14H15V20"></path>

                </svg>
            </div>

            <div class="group-header">
                {{ $group->nama_group }}
            </div>

            <div class="group-siswa">
                👥 {{ $group->users_count }} siswa terdaftar
            </div>

        </div>

        <!-- MODAL -->
        <div id="modal{{ $group->id }}" class="modal-bg">

            <div class="modal-box">

                <!-- HEADER -->
                <div class="modal-header">

                    <div>
                        <div class="modal-title">
                            {{ $group->nama_group }}
                        </div>

                        <div class="modal-sub">
                            Daftar siswa dalam kelas
                        </div>
                    </div>

                    <button class="close-btn"
                        onclick="closeModal('modal{{ $group->id }}')">
                        ✕
                    </button>

                </div>

                <!-- BODY -->
                <div class="modal-body">

                    @if($group->users->count() > 0)

                    <table class="table-siswa">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($group->users as $user)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td class="font-semibold">
                                    {{ $user->name }}
                                </td>

                                <td>
                                    {{ $user->kelas ?? '-' }}
                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                    @else

                    <div class="empty-box">
                        Belum ada siswa di kelas ini
                    </div>

                    @endif

                </div>

            </div>

        </div>

        @empty

        <div class="col-span-full">

            <div class="bg-white rounded-3xl shadow-lg p-14 text-center text-gray-400">

                <div class="text-5xl mb-4">
                    📚
                </div>

                <div class="text-lg font-semibold">
                    Belum ada kelas dibuat
                </div>

            </div>

        </div>

        @endforelse

    </div>

</div>

<script>

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

/* CLOSE KLIK LUAR */
window.onclick = function(event) {

    document.querySelectorAll('.modal-bg').forEach(modal => {

        if (event.target === modal) {
            modal.style.display = 'none';
        }

    });
}

</script>

@endsection