@extends('layouts.dashboard')

@section('title', 'Data Kelas')
@section('header', 'Data Kelas')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <!-- LEFT -->
        <div class="flex items-center gap-4">

            <!-- BACK -->
            <a href="{{ route('master.data') }}"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl shadow transition">
                ← Kembali
            </a>

            <!-- TITLE -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Data Kelas
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Kelola daftar kelas dan siswa
                </p>
            </div>
        </div>

        <!-- BUTTON -->
        <a href="{{ route('form.tambah.kelas') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow-md transition">
            + Tambah Kelas Baru
        </a>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">

                <!-- HEAD -->
                <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <tr>
                        <th class="p-4 text-center">
                            No
                        </th>

                        <th class="p-4 text-left">
                            Nama Kelas
                        </th>

                        <th class="p-4 text-center">
                            Jumlah Siswa
                        </th>

                        <th class="p-4 text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($groups as $index => $group)
                    <tr class="hover:bg-blue-50 transition duration-150">

                        <!-- NO -->
                        <td class="p-4 text-center text-gray-600">
                            {{ $groups->firstItem() + $index }}
                        </td>

                        <!-- NAMA -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                                    📚
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ $group->nama_group }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- JUMLAH -->
                        <td class="p-4 text-center">
                            <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-semibold">
                                {{ $group->users_count ?? 0 }} Siswa
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td class="p-4">
                            <div class="flex justify-center gap-2">

                                <!-- EDIT -->
                                <a href="{{ route('group.edit', $group->id) }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-sm transition">
                                    Edit
                                </a>

                                <!-- DELETE -->
                                <form action="#"
                                    method="POST"
                                    onsubmit="event.preventDefault(); const form = this; showConfirmModal('Hapus Kelas', 'Apakah Anda yakin ingin menghapus kelas ini?', () => { form.submit(); });">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-sm transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="4"
                            class="text-center py-12 text-gray-400">
                            Belum ada kelas dibuat
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-5 border-t bg-gray-50">
            {{ $groups->links() }}
        </div>
    </div>
</div>

@endsection