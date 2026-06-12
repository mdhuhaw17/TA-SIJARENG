@extends('layouts.dashboard')

@section('title', 'Scan QR')
@section('header', 'Scan QR')

@section('content')

<script src="https://unpkg.com/html5-qrcode"></script>

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

        <div>

            <h2 class="text-3xl font-bold text-gray-800">
                Scan QR Absensi
            </h2>

            <p class="text-gray-500 mt-2">
                Arahkan QR Code siswa ke kamera
            </p>

        </div>

        <!-- BUTTON KEMBALI -->
        <a href="{{ route('admin.dashboard') }}"
            class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-2xl shadow-lg transition font-semibold">

            ← Kembali

        </a>

    </div>

    <!-- CARD -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

        <!-- TOP -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">

            <h3 class="text-white text-lg font-semibold">
                Kamera Scanner
            </h3>

        </div>

        <!-- BODY -->
        <div class="p-6">

            <!-- SCANNER -->
            <div class="max-w-xl mx-auto">

                <div id="reader"
                    class="border-4 border-dashed border-blue-300 rounded-3xl overflow-hidden">
                </div>

            </div>

            <!-- RESULT -->
            <div id="resultBox"
                class="hidden mt-8 max-w-xl mx-auto bg-gray-50 rounded-3xl p-5 border">

                <div class="flex items-center gap-4">

                    <img id="fotoUser"
                        class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow">

                    <div>

                        <div id="namaUser"
                            class="text-2xl font-bold text-gray-800">
                        </div>

                        <div id="kelasUser"
                            class="text-gray-500 mt-1">
                        </div>

                        <div id="statusText"
                            class="mt-3 inline-block px-4 py-2 rounded-full text-sm font-bold">
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

    let sudahScan = false;

    function onScanSuccess(decodedText) {

        if (sudahScan) return;

        sudahScan = true;

        fetch("{{ route('scan.qr.process') }}", {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },

            body: JSON.stringify({
                qr_code: decodedText
            })

        })

        .then(res => res.json())

        .then(data => {

            const resultBox = document.getElementById('resultBox');

            resultBox.classList.remove('hidden');

            document.getElementById('namaUser')
                .innerText = data.user?.name ?? '-';

            document.getElementById('kelasUser')
                .innerText = data.user?.kelas ?? '-';

            document.getElementById('fotoUser')
                .src = data.user?.foto ??
                'https://ui-avatars.com/api/?name=User';

            const statusText = document.getElementById('statusText');

            statusText.innerText = data.message;

            if (data.success) {

                statusText.className =
                    'mt-3 inline-block px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700';

            } else {

                statusText.className =
                    'mt-3 inline-block px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-700';

            }

            // scan ulang setelah 3 detik
            setTimeout(() => {

                sudahScan = false;

            }, 3000);

        })

        .catch(error => {

            console.log(error);

            sudahScan = false;

        });

    }

    const html5QrCode = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then(devices => {

        if (devices && devices.length) {

            html5QrCode.start(

                devices[0].id,

                {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },

                onScanSuccess

            );

        }

    });

</script>

@endsection