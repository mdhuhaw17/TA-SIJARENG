@extends('layouts.siswa')

@section('title','Riwayat Absensi')
@section('header','Riwayat Absensi')

@section('content')

<div class="space-y-6">

    <!-- STATISTIK -->
    <div class="grid md:grid-cols-3 gap-5">

        <div class="bg-white rounded-2xl p-5 shadow border border-gray-100">

            <div class="text-sm text-gray-500">
                Total Hadir
            </div>

            <div class="text-3xl font-bold text-green-600 mt-2">
                {{ $riwayat->where('status','hadir')->count() }}
            </div>

        </div>

        <div class="bg-white rounded-2xl p-5 shadow border border-gray-100">

            <div class="text-sm text-gray-500">
                Total Izin
            </div>

            <div class="text-3xl font-bold text-yellow-500 mt-2">
                {{ $riwayat->where('status','izin')->count() }}
            </div>

        </div>

        <div class="bg-white rounded-2xl p-5 shadow border border-gray-100">

            <div class="text-sm text-gray-500">
                Total Alfa
            </div>

            <div class="text-3xl font-bold text-red-500 mt-2">
                {{ $riwayat->where('status','alfa')->count() }}
            </div>

        </div>

    </div>

    <!-- TABEL -->
    <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100">

        <div class="px-6 py-5 border-b bg-gray-50">

            <h2 class="text-xl font-bold text-gray-800">
                Data Kehadiran
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Riwayat absensi yang telah tercatat.
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-blue-50">

                    <tr>

                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Tanggal
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($riwayat as $item)

                    <tr class="border-b hover:bg-blue-50 transition duration-200">

                        <td class="px-6 py-4 text-center font-semibold text-gray-700">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 text-gray-700">

                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}

                        </td>

                        <td class="px-6 py-4">

                            @if($item->status == 'hadir')

                                <span class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">

                                    ● Hadir

                                </span>

                            @elseif($item->status == 'izin')

                                <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold">

                                    ● Izin

                                </span>

                            @else

                                <span class="inline-flex items-center gap-2 bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold">

                                    ● Alfa

                                </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="3" class="py-16 text-center">

                            <div class="flex flex-col items-center">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-16 h-16 text-gray-300 mb-3"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M9 17v-6m4 6V7m4 10v-3M5 21h14"/>

                                </svg>

                                <p class="text-gray-400 font-medium">
                                    Belum ada riwayat absensi
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($riwayat->count())

        <div class="px-6 py-5 border-t bg-gray-50">

            {{ $riwayat->links() }}

        </div>

        @endif

    </div>

</div>

@endsection