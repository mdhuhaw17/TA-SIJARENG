@extends('layouts.dashboard')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<div class="p-6">

    <!-- TOP -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('master.data') }}"
            class="bg-red-500 text-white px-4 py-1 rounded-full hover:bg-red-600">
            ← Kembali
        </a>

        <h2 class="text-xl font-semibold">Edit User</h2>
    </div>

    <!-- FORM -->
    <div class="bg-white rounded-2xl shadow-md p-6 max-w-xl mx-auto">

        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- NAMA -->
            <div>
                <label class="block mb-1 font-medium">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block mb-1 font-medium">Password</label>
                <input type="password" name="password"
                    placeholder="Kosongkan jika tidak diubah"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- ROLE -->
            <div>
                <label class="block mb-1 font-medium">Role</label>
                <select name="role"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>

            <!-- ALAMAT -->
            <div>
                <label class="block mb-1 font-medium">Alamat</label>
                <textarea name="alamat"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $user->alamat }}</textarea>
            </div>

            <!-- KELAS -->
            <div>
                <label class="block mb-1 font-medium">Kelas</label>
                <input type="text" name="kelas" value="{{ $user->kelas }}"
                    class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- FOTO -->
            <div>
                <label class="block mb-2 font-medium">Foto</label>

                <!-- FOTO SAAT INI -->
                @if($user->foto)
                    <img src="{{ asset('storage/' . $user->foto) }}"
                        class="w-24 h-24 rounded-full object-cover mb-3">
                @else
                    <p class="text-gray-500 mb-2">Belum ada foto</p>
                @endif

                <!-- INPUT FOTO BARU -->
                <input type="file" name="foto"
                    class="w-full border rounded-lg p-2">
                
                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah foto</small>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between mt-4">
                <a href="{{ route('master.data') }}"
                    class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                    Batal
                </a>

                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>

        </form>

    </div>

</div>
@endsection