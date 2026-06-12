@extends('layouts.dashboard')

@section('title', 'Detail Absen')
@section('header', 'Detail Absen')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex items-center gap-4 mb-8">

        <a href="{{ route('absenmanual') }}"
            class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-2xl shadow">

            ← Kembali

        </a>

        <div>

            <h2 class="text-3xl font-bold text-gray-800">
                {{ $title }}
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Presensi siswa
            </p>

        </div>

    </div>

    <!-- FORM -->
    <form action="{{ route('absensi.store') }}" method="POST">

        @csrf

        <!-- TABLE -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <table class="w-full">

                <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">

                    <tr>

                        <th class="p-4 text-center">
                            No
                        </th>

                        <th class="p-4 text-left">
                            Nama
                        </th>

                        <th class="p-4 text-center">
                            Kelas
                        </th>

                        <th class="p-4 text-center">
                            Kehadiran
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($users as $user)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-4 text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-4 font-semibold text-gray-700">
                            {{ $user->name }}
                        </td>

                        <td class="p-4 text-center">
                            {{ $user->kelas }}
                        </td>

                        <td class="p-4">

                            <div class="flex justify-center gap-6">

                                <!-- HADIR -->
                                <label class="flex items-center gap-2">

                                    <input type="radio"
                                        name="status[{{ $user->id }}]"
                                        value="hadir">

                                    Hadir

                                </label>

                                <!-- IZIN -->
                                <label class="flex items-center gap-2">

                                    <input type="radio"
                                        name="status[{{ $user->id }}]"
                                        value="izin">

                                    Izin

                                </label>

                                <!-- ALFA -->
                                <label class="flex items-center gap-2">

                                    <input type="radio"
                                        name="status[{{ $user->id }}]"
                                        value="alfa">

                                    Alfa

                                </label>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end mt-6">

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg">

                Simpan Absensi

            </button>

        </div>

    </form>

</div>

@endsection