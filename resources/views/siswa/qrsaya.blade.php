@extends('layouts.siswa')

@section('title','QR Saya')
@section('header','QR Saya')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">

            <h2 class="text-2xl font-bold text-white">
                QR Absensi Saya
            </h2>

            <p class="text-blue-100 mt-1">
                Tunjukkan QR ini saat melakukan absensi
            </p>

        </div>

        <!-- BODY -->
        <div class="p-8">

            <div class="flex flex-col items-center">

                <div id="qrContainer">

                    <div class="w-64 h-64 flex items-center justify-center">

                        Memuat QR...

                    </div>

                </div>

                <h3 class="mt-6 text-2xl font-bold text-gray-800">
                    {{ Auth::user()->name }}
                </h3>

                <p class="text-gray-500">
                    {{ Auth::user()->kelas }}
                </p>

            </div>

        </div>

    </div>

</div>

<script>

async function loadQr() {

    try {

        const response = await fetch('/user/qr/{{ Auth::id() }}');

        const data = await response.json();

        document.getElementById('qrContainer').innerHTML = `
            <img
                src="data:image/svg+xml;base64,${data.qr}"
                class="w-64 h-64 mx-auto"
            >
        `;

    } catch (error) {

        console.log(error);

        document.getElementById('qrContainer').innerHTML = `
            <div class="text-red-500">
                QR gagal dimuat
            </div>
        `;

    }

}

loadQr();

</script>

@endsection