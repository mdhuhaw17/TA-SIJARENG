@extends('layouts.dashboard')

@section('title', 'Edit Kelas')
@section('header', 'Edit Kelas')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">

            <!-- BUTTON BACK -->
            <a href="{{ route('group.create') }}"
                class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl shadow-md transition">
                ← Kembali
            </a>

            <!-- TITLE -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Edit Kelas
                </h2>
                <p class="text-gray-500 text-sm mt-1">
                    Tambahkan siswa ke kelas
                    <span class="font-semibold text-blue-600">
                        {{ $group->nama_group }}
                    </span>
                </p>
            </div>
        </div>

        <!-- TOTAL -->
        <div class="bg-blue-50 text-blue-700 px-5 py-3 rounded-2xl shadow-sm">
            <div class="text-sm">Total Siswa</div>
            <div class="text-2xl font-bold">
                {{ $group->users->count() }}
            </div>
        </div>
    </div>

    <!-- CARD -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <!-- TOP FILTER -->
        <div class="p-5 border-b bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- SEARCH -->
                <div class="relative">
                    <input type="text"
                        id="searchInput"
                        placeholder="Cari nama siswa..."
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 pl-11 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span class="absolute left-4 top-3.5 text-gray-400">
                        🔍
                    </span>
                </div>

                <!-- FILTER -->
                <div>
                    <select id="filterKelas"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach($siswas->pluck('kelas')->unique() as $kelas)
                            @if($kelas)
                                <option value="{{ $kelas }}">
                                    {{ $kelas }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <form action="{{ route('group.updateSiswa', $group->id) }}"
            method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full">

                    <!-- HEAD -->
                    <thead class="bg-gradient-to-r from-blue-600 to-blue-500 text-white">
                        <tr>
                            <th class="p-4 text-center w-20">
                                Pilih
                            </th>

                            <th class="p-4 text-left">
                                Nama Siswa
                            </th>

                            <th class="p-4 text-left">
                                Kelas
                            </th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody id="tableBody">
                        @forelse($siswas as $siswa)
                        <tr class="border-b hover:bg-blue-50 transition data-row">

                            <!-- CHECKBOX -->
                            <td class="p-4 text-center">
                                <input type="checkbox"
                                    name="siswa[]"
                                    value="{{ $siswa->id }}"
                                    {{ $group->users->contains($siswa->id) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded text-blue-600">
                            </td>

                            <!-- NAMA -->
                            <td class="p-4">
                                <div class="font-semibold text-gray-800 nama-siswa">
                                    {{ $siswa->name }}
                                </div>
                            </td>

                            <!-- KELAS -->
                            <td class="p-4">
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm kelas-siswa">
                                    {{ $siswa->kelas ?? '-' }}
                                </span>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="3"
                                class="text-center py-10 text-gray-400">
                                Tidak ada data siswa
                            </td>
                        </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- FOOTER -->
            <div class="p-5 bg-gray-50 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Pilih siswa yang ingin dimasukkan ke kelas
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-md transition font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SEARCH & FILTER SCRIPT -->
<script>
    const searchInput = document.getElementById('searchInput');
    const filterKelas = document.getElementById('filterKelas');
    const rows = document.querySelectorAll('.data-row');
    function filterData() {
        const search = searchInput.value.toLowerCase();
        const kelas = filterKelas.value.toLowerCase();
        rows.forEach(row => {
            const nama = row.querySelector('.nama-siswa').innerText.toLowerCase();
            const kelasSiswa = row.querySelector('.kelas-siswa').innerText.toLowerCase();
            const cocokNama = nama.includes(search);
            const cocokKelas = kelas === '' || kelasSiswa.includes(kelas);
            if (cocokNama && cocokKelas) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    searchInput.addEventListener('keyup', filterData);
    filterKelas.addEventListener('change', filterData);
</script>

@endsection