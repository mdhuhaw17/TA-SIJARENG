@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')
@section('header', 'Dashboard Siswa')

@section('content')

<div class="space-y-6">

    <!-- WELCOME CARD -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl shadow-xl overflow-hidden">

        <div class="p-8 flex items-center justify-between">

            <div>

                <h1 class="text-3xl font-bold text-white">
                    Halo, {{ Auth::user()->name }}
                </h1>

                <p class="text-blue-100 mt-2 text-lg">
                    Selamat datang di Sistem Absensi Digital JARENG
                </p>

                <div class="mt-5 flex items-center gap-3">

                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm text-white">
                        Kelas {{ Auth::user()->kelas }}
                    </span>

                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm text-white">
                        Siswa Aktif
                    </span>

                </div>

            </div>

            <div>

                @if(Auth::user()->foto)

                    <img src="{{ asset('storage/' . Auth::user()->foto) }}"
                        class="w-32 h-32 rounded-3xl object-cover border-4 border-white shadow-xl">

                @else

                    <div class="w-32 h-32 rounded-3xl bg-white flex items-center justify-center text-blue-600 text-5xl font-bold shadow-xl">

                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}

                    </div>

                @endif

            </div>

        </div>

    </div>

    <!-- STATISTIK -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- HADIR -->
        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Total Hadir
                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-2">
                        {{ $hadir }}
                    </h2>

                </div>

                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">

                    ✓

                </div>

            </div>

        </div>

        <!-- IZIN -->
        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Total Izin
                    </p>

                    <h2 class="text-4xl font-bold text-yellow-500 mt-2">
                        {{ $izin }}
                    </h2>

                </div>

                <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center">

                    !

                </div>

            </div>

        </div>

        <!-- ALFA -->
        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Total Alfa
                    </p>

                    <h2 class="text-4xl font-bold text-red-500 mt-2">
                        {{ $alfa }}
                    </h2>

                </div>

                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">

                    ✕

                </div>

            </div>

        </div>

    </div>

</div>
@endsection