<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen relative">

    <!-- BUTTON MOBILE -->
    <button
        id="menuBtn"
        class="lg:hidden fixed top-4 left-4 z-50 bg-blue-600 text-white p-3 rounded-xl shadow-lg">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>

        </svg>

    </button>

    <!-- SIDEBAR -->
    <aside
        id="sidebar"
        class="fixed lg:static inset-y-0 left-0 z-40
        w-72 bg-gradient-to-b from-blue-700 to-indigo-700
        text-white flex flex-col shadow-xl
        transform -translate-x-full lg:translate-x-0
        transition duration-300">

        <!-- LOGO -->
        <div class="px-6 py-6 border-b border-white/20">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>

                    </svg>

                </div>

                <div>

                    <h1 class="font-bold text-2xl">
                        JARENG
                    </h1>

                    <p class="text-xs text-blue-100">
                        Panel Siswa
                    </p>

                </div>

            </div>

        </div>

        <!-- MENU -->
        <nav class="flex-1 px-4 py-5 space-y-2 overflow-y-auto">

            <a href="{{ route('siswa.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
                {{ request()->routeIs('siswa.dashboard')
                    ? 'bg-white text-blue-700 font-semibold shadow-lg'
                    : 'hover:bg-white/20 text-white' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 10l9-7 9 7v10a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1V10z"/>

                </svg>

                Dashboard

            </a>

            <a href="{{ route('siswa.riwayat') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
                {{ request()->routeIs('siswa.riwayat')
                    ? 'bg-white text-blue-700 font-semibold shadow-lg'
                    : 'hover:bg-white/20 text-white' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 17v-6m4 6V7m4 10v-3M5 21h14"/>

                </svg>

                Riwayat Absensi

            </a>

            <a href="{{ route('siswa.rekap') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
                {{ request()->routeIs('siswa.rekap')
                    ? 'bg-white text-blue-700 font-semibold shadow-lg'
                    : 'hover:bg-white/20 text-white' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 17v-6m4 6V7m4 10v-3M5 21h14"/>

                </svg>

                Rekap Saya

            </a>

            <a href="{{ route('siswa.qr') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
                {{ request()->routeIs('siswa.qr')
                    ? 'bg-white text-blue-700 font-semibold shadow-lg'
                    : 'hover:bg-white/20 text-white' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM17 17h3v3h-3z"/>

                </svg>

                QR Saya

            </a>

            <a href="{{ route('siswa.profil') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200
                {{ request()->routeIs('siswa.profil')
                    ? 'bg-white text-blue-700 font-semibold shadow-lg'
                    : 'hover:bg-white/20 text-white' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>

                </svg>

                Profil Saya

            </a>

        </nav>

        <!-- LOGOUT -->
        <div class="p-4 border-t border-white/20">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 py-3 rounded-xl font-semibold transition shadow-lg">

                    Logout

                </button>

            </form>

        </div>

    </aside>

    <!-- OVERLAY -->
    <div
        id="overlay"
        class="fixed inset-0 bg-black/50 hidden z-30 lg:hidden">
    </div>

    <!-- MAIN -->
    <main class="flex-1 flex flex-col min-w-0">

        <!-- HEADER -->
        <header class="bg-white border-b border-gray-200 shadow-sm px-4 md:px-8 py-5 flex flex-col md:flex-row md:justify-between md:items-center gap-3">

            <div class="ml-14 lg:ml-0">

                <h2 class="text-xl md:text-2xl font-bold text-slate-800">
                    @yield('header')
                </h2>

                <p class="text-sm text-slate-500">
                    Sistem Absensi Digital JARENG
                </p>

            </div>

            <div class="text-left md:text-right">

                <div class="font-semibold text-slate-700">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>

                <div id="clock" class="text-sm text-slate-500">
                    --
                </div>

            </div>

        </header>

        <!-- CONTENT -->
        <div class="p-4 md:p-8">

            @yield('content')

        </div>

    </main>

</div>

<script>

function updateClock() {

    const now = new Date();

    const jam = String(now.getHours()).padStart(2, '0');
    const menit = String(now.getMinutes()).padStart(2, '0');
    const detik = String(now.getSeconds()).padStart(2, '0');

    document.getElementById('clock').innerHTML =
        `${jam}:${menit}:${detik} WIB`;

}

updateClock();
setInterval(updateClock, 1000);

const menuBtn = document.getElementById('menuBtn');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

menuBtn.addEventListener('click', () => {

    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');

});

overlay.addEventListener('click', () => {

    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');

});

</script>

</body>
</html>