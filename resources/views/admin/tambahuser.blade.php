@extends('layouts.dashboard')

@section('title', 'Tambah User')
@section('header', 'Tambah User')

@section('content')
<div class="p-6">

    <!-- TOP -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('master.data') }}"
            class="bg-red-600 text-white px-4 py-1 rounded-lg hover:bg-red-700">
            ← Kembali
        </a>

        <h2 class="text-xl font-semibold">Tambah User</h2>
    </div>

    <!-- FORM -->
    <div class="bg-white rounded-2xl shadow-md p-6 max-w-xl mx-auto">

        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- NAMA -->
            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="name"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- ROLE -->
            <div>
                <label class="block mb-1 font-medium">Role</label>
                <select name="role"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="admin">Admin</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>

            <!-- ALAMAT -->
            <div>
                <label class="block mb-1 font-medium">Alamat</label>
                <textarea name="alamat"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- KELAS -->
             <div>
                <label class="block mb-1 font-medium">Kelas</label>
                <select name="kelas"
                    class="w-full border rounded-lg p-2">
                    <option value="1">Kelas 1</option>
                    <option value="2">Kelas 2</option>
                    <option value="3">Kelas 3</option>
                    <option value="4">Kelas 4</option>
                    <option value="5">Kelas 5</option>
                    <option value="6">Kelas 6</option>
                </select>
            </div>

            <!-- FOTO -->
            <div>
                <label class="block mb-1 font-medium">Foto</label>
                <input type="file" name="foto"
                    class="w-full border rounded-lg p-2">
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between mt-4">
                <a href="{{ route('master.data') }}"
                    class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                    Batal
                </a>

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection