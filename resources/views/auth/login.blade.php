<x-guest-layout>

<div class="min-h-screen flex">

    <!-- KIRI -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-700 to-indigo-800 text-white items-center justify-center p-12">

        <div class="max-w-md text-center">

            <h1 class="text-5xl font-bold mb-6">
                JARENG
            </h1>

            <p class="text-xl text-blue-100 leading-relaxed">
                Sistem Absensi Digital berbasis QR Code dan Face Recognition
                untuk mendukung proses absensi yang cepat, aman, dan modern.
            </p>

            <div class="mt-10 flex justify-center">

                <img src="{{ asset('image/JARENG.png') }}"
                    class="w-56 drop-shadow-2xl">

            </div>

        </div>

    </div>

    <!-- KANAN -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-slate-100 p-6">

        <div class="w-full max-w-md">

            <div class="bg-white rounded-3xl shadow-2xl p-8">

                <!-- LOGO -->
                <div class="text-center mb-8">

                    <img src="{{ asset('image/JARENG.png') }}"
                        class="w-24 h-24 mx-auto mb-4">

                    <h2 class="text-3xl font-bold text-slate-800">
                        Login
                    </h2>

                    <p class="text-slate-500 mt-2">
                        Masuk ke Sistem Absensi JARENG
                    </p>

                </div>

                <!-- STATUS -->
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')" />

                <form method="POST"
                    action="{{ route('login') }}">

                    @csrf

                    <!-- EMAIL -->
                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2" />

                    </div>

                    <!-- PASSWORD -->
                    <div class="mt-5">

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2" />

                    </div>

                    <!-- REMEMBER -->
                    <div class="flex justify-between items-center mt-5">

                        <label class="flex items-center">

                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-blue-600">

                            <span class="ml-2 text-sm text-slate-600">
                                Ingat Saya
                            </span>

                        </label>

                        @if(Route::has('password.request'))

                            <a href="{{ route('password.request') }}"
                                class="text-sm text-blue-600 hover:text-blue-700">

                                Lupa Password?

                            </a>

                        @endif

                    </div>

                    <!-- BUTTON -->
                    <button
                        type="submit"
                        class="w-full mt-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90 text-white font-semibold py-3 rounded-xl shadow-lg transition">

                        Masuk

                    </button>

                </form>

            </div>

            <p class="text-center text-slate-500 text-sm mt-6">
                © {{ date('Y') }} JARENG - Sistem Absensi Digital
            </p>

        </div>

    </div>

</div>

</x-guest-layout>