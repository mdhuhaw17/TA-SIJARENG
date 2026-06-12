@extends('layouts.siswa')

@section('title','Profil Saya')
@section('header','Profil Saya')

@section('content')

<div class="max-w-6xl mx-auto">

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- COVER -->
        <div class="h-48 bg-gradient-to-r from-blue-600 to-indigo-700 relative">

            <div class="absolute inset-0 bg-black/10"></div>

        </div>

        <!-- PROFIL -->
        <div class="relative px-8 pb-8">

            <div class="flex flex-col items-center">

                <div class="-mt-16">

                @if(Auth::user()->foto)

                    <img
                        src="{{ asset('storage/' . Auth::user()->foto) }}"
                        class="w-40 h-40 rounded-full border-[6px] border-white object-cover shadow-xl">

                @else

                    <div class="w-40 h-40 rounded-full bg-blue-100 border-[6px] border-white flex items-center justify-center text-5xl font-bold text-blue-700 shadow-xl">

                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}

                    </div>

                @endif

                <h2 class="text-3xl font-bold text-gray-800 mt-5">
                    {{ Auth::user()->name }}
                </h2>

                <p class="text-gray-500 mt-1">
                    {{ Auth::user()->email }}
                </p>

                </div>
            </div>

            <!-- DATA -->
            <div class="grid md:grid-cols-2 gap-6 mt-10">

                <!-- Nama -->
                <div class="bg-gray-50 rounded-2xl p-5 border">

                    <div class="flex items-center gap-3">

                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>

                            </svg>

                        </div>

                        <div>

                            <div class="text-sm text-gray-500">
                                Nama Lengkap
                            </div>

                            <div class="font-semibold text-gray-800">
                                {{ Auth::user()->name }}
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Email -->
                <div class="bg-gray-50 rounded-2xl p-5 border">

                    <div class="flex items-center gap-3">

                        <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-indigo-600"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 12H8m8 0a4 4 0 10-8 0m8 0v1a4 4 0 11-8 0v-1"/>

                            </svg>

                        </div>

                        <div>

                            <div class="text-sm text-gray-500">
                                Email
                            </div>

                            <div class="font-semibold text-gray-800">
                                {{ Auth::user()->email }}
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Kelas -->
                <div class="bg-gray-50 rounded-2xl p-5 border">

                    <div class="flex items-center gap-3">

                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">

                            📚

                        </div>

                        <div>

                            <div class="text-sm text-gray-500">
                                Kelas
                            </div>

                            <div class="font-semibold text-gray-800">
                                {{ Auth::user()->kelas }}
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Role -->
                <div class="bg-gray-50 rounded-2xl p-5 border">

                    <div class="flex items-center gap-3">

                        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">

                            🎓

                        </div>

                        <div>

                            <div class="text-sm text-gray-500">
                                Role
                            </div>

                            <div class="font-semibold text-gray-800">
                                Siswa
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ALAMAT -->
            <div class="bg-gray-50 rounded-2xl p-6 border mt-6">

                <div class="text-sm text-gray-500 mb-2">
                    Alamat
                </div>

                <div class="font-medium text-gray-800 leading-relaxed">

                    {{ Auth::user()->alamat ?? 'Alamat belum tersedia' }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
