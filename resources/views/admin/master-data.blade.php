@extends('layouts.dashboard')

@section('title', 'Master Data')
@section('header', 'Master Data')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <!-- LEFT -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl shadow transition">
                ← Kembali
            </a>
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Master Data User
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Kelola data pengguna sistem absensi
                </p>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-3">
            <a href="{{ route('tambah.user') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl shadow-md transition">
                + Tambah User
            </a>
            <a href="{{ route('group.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl shadow-md transition">
                + Buat Kelas
            </a>
        </div>
    </div>

    <!-- SEARCH & FILTER -->
    <form method="GET" action="{{ route('master.data') }}" class="mb-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- SEARCH -->
            <div class="relative md:col-span-3">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama, email, alamat, atau kelas..."
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 pr-11 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span class="absolute right-4 top-3 text-gray-400">
                    🔍
                </span>
            </div>

            <!-- FILTER ROLE -->
            <div>
                <select name="role"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">
                        Semua Role
                    </option>

                    <option value="admin"
                        {{ request('role') == 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option value="siswa"
                        {{ request('role') == 'siswa' ? 'selected' : '' }}>
                        Siswa
                    </option>
                </select>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="flex gap-3 mt-4">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl shadow">
                Cari Data
            </button>

            <a href="{{ route('master.data') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-xl shadow">
                Reset
            </a>
        </div>
    </form>

    <!-- CARD TABLE -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="w-full">

                <!-- HEAD -->
                <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <tr>
                        <th class="p-4 text-center">
                            No
                        </th>

                        <th class="p-4 text-left">
                            Nama
                        </th>

                        <th class="p-4 text-left">
                            Email
                        </th>

                        <th class="p-4 text-left">
                            Alamat
                        </th>

                        <th class="p-4 text-center">
                            Kelas
                        </th>

                        <th class="p-4 text-center">
                            Foto
                        </th>

                        <th class="p-4 text-center">
                            Role
                        </th>

                        <th class="p-4 text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $index => $user)
                    <tr class="hover:bg-blue-50 transition duration-150 user-row">

                        <!-- NO -->
                        <td class="p-4 text-center text-gray-600">
                            {{ $users->firstItem() + $index }}
                        </td>

                        <!-- NAMA -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}"
                                        class="w-11 h-11 rounded-full object-cover border">
                                @else
                                    <div class="w-11 h-11 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                        👤
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-800 nama-user">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- EMAIL -->
                        <td class="p-4 text-gray-600 email-user">
                            {{ $user->email }}
                        </td>

                        <!-- ALAMAT -->
                        <td class="p-4 text-gray-600 alamat-user">
                            {{ $user->alamat ?? '-' }}
                        </td>

                        <!-- KELAS -->
                        <td class="p-4 text-center">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $user->kelas ?? '-' }}
                            </span>
                        </td>

                        <!-- FOTO -->
                        <td class="p-4 text-center">
                            @if($user->foto)
                                <span class="text-green-600 font-semibold">
                                    Ada
                                </span>
                            @else
                                <span class="text-gray-400">
                                    Tidak Ada
                                </span>
                            @endif
                        </td>

                        <!-- ROLE -->
                        <td class="p-4 text-center role-user">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold text-white
                                {{ $user->role == 'admin'
                                    ? 'bg-green-500'
                                    : 'bg-gray-500' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td class="p-4">
                            <div class="flex justify-center gap-2">

                                <!-- EDIT -->
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-sm transition">
                                    Edit
                                </a>

                                <!-- DELETE -->
                                <form action="{{ route('users.destroy', $user->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-sm transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="8"
                            class="text-center py-10 text-gray-400">
                            Belum ada data user
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-5 border-t bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection