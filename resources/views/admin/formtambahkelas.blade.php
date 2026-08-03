@extends('layouts.dashboard')

@section('title', 'Form Tambah Kelas')
@section('header', 'Form Tambah Kelas')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">

            <!-- BACK -->
            <a href="{{ route('group.create') }}"
                class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-2xl shadow-md transition">
                ← Kembali
            </a>

            <!-- TITLE -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800">
                    Form Tambah Kelas
                </h2>

                <p class="text-gray-500 text-sm mt-1">
                    Tambahkan kelas baru ke sistem
                </p>
            </div>
        </div>
    </div>


    <!-- FORM CARD -->
    <div class="max-w-3xl">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

            <!-- TOP -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h3 class="text-white text-xl font-bold">
                    Tambah Data Kelas
                </h3>

                <p class="text-blue-100 text-sm mt-1">
                    Isi nama kelas dengan benar
                </p>
            </div>

            <!-- FORM -->
            <form action="{{ route('group.store') }}"
                method="POST"
                class="p-8">
                @csrf

                <!-- INPUT -->
                <div class="mb-8">
                    <label class="block text-gray-700 font-semibold mb-3">
                        Nama Kelas
                    </label>
                    <input type="text"
                        name="nama_group"
                        placeholder="Contoh : Kelas 1"
                        class="w-full border border-gray-300 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        required>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('group.create') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-3 rounded-2xl shadow transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg transition">
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection