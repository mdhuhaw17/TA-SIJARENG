@extends('layouts.dashboard')

@section('title', 'Absen Manual')
@section('header', 'Absen Manual')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex items-center gap-4 mb-8">

        <a href="{{ route('admin.dashboard') }}"
            class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-2xl shadow">

            ← Kembali

        </a>

        <div>

            <h2 class="text-3xl font-bold text-gray-800">
                Absen Manual
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Pilih kategori kelas untuk melakukan presensi
            </p>

        </div>

    </div>

    <!-- CARD -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- KELAS KECIL -->
            <a href="{{ route('absenmanual.detail', 'kecil') }}"
                class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-3xl p-8 shadow-xl hover:scale-105 transition duration-300">

                <div class="text-white">

                    <h3 class="text-2xl font-bold mb-3">
                        Kelas Kecil
                    </h3>

                    <p class="text-green-100">
                        Kelas 1, 2, dan 3
                    </p>

                </div>

            </a>

        <!-- KELAS BESAR -->
        <a href="{{ route('absenmanual.detail', 'besar') }}"
            class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-8 shadow-xl hover:scale-105 transition duration-300">

            <div class="text-white">

                <h3 class="text-2xl font-bold mb-3">
                    Kelas Besar
                </h3>

                <p class="text-blue-100">
                    Kelas 4, 5, dan 6
                </p>

            </div>

        </a>

    </div>

</div>

@endsection