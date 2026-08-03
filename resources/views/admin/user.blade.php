@extends('layouts.dashboard')

@section('title', 'User')
@section('header', 'User')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<style>
    #qrModal {
        animation: fadeBg 0.2s ease;
    }

    #qrModal > div {
        animation: popup 0.25s ease;
    }

    @keyframes popup {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes fadeBg {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h2 class="text-3xl font-bold text-gray-800">
                Data User
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Daftar seluruh siswa yang terdaftar di sistem
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
            class="bg-red-500 hover:bg-red-600 text-white px-5 py-3 rounded-2xl shadow-lg transition">

            ← Kembali
        </a>

    </div>

    <!-- SEARCH -->
    <div class="mb-7">

        <form method="GET"
            action="{{ route('user.page') }}">

            <div class="bg-white border border-gray-200 rounded-3xl shadow-lg p-3 flex items-center gap-3">

                <!-- ICON -->
                <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center shrink-0">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />

                    </svg>

                </div>

                <!-- INPUT -->
                <div class="flex-1">

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama siswa, kelas, atau alamat..."
                        class="w-full border-none focus:ring-0 text-gray-700 placeholder-gray-400 text-[15px] bg-transparent">

                </div>

                <!-- RESET -->
                @if(request('search'))

                <a href="{{ route('user.page') }}"
                    class="px-4 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold transition">

                    Reset
                </a>

                @endif

                <!-- BUTTON -->
                <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90 text-white px-6 py-3 rounded-2xl font-semibold shadow-lg transition">

                    Cari
                </button>

            </div>

        </form>

    </div>

    <!-- CARD -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <!-- TABLE HEADER -->
        <div class="px-6 py-5 border-b bg-gradient-to-r from-blue-600 to-indigo-600">

            <h3 class="text-white text-lg font-semibold">
                Tabel Data User
            </h3>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-gray-600 text-sm uppercase">

                        <th class="px-6 py-4 text-center">
                            No
                        </th>

                        <th class="px-6 py-4 text-left">
                            Nama
                        </th>

                        <th class="px-6 py-4 text-center">
                            Kelas
                        </th>

                        <th class="px-6 py-4 text-left">
                            Alamat
                        </th>

                        <th class="px-6 py-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $user)

                    <tr class="border-b hover:bg-blue-50 transition duration-200">

                        <!-- NO -->
                        <td class="px-6 py-5 text-center font-semibold text-gray-700">
                            {{ $loop->iteration }}
                        </td>

                        <!-- NAMA -->
                        <td class="px-6 py-5">

                            <div class="flex items-center gap-4">

                                <!-- FOTO -->
                                @if($user->foto)

                                    <img src="{{ asset('storage/' . $user->foto) }}"
                                        class="w-12 h-12 rounded-full object-cover border">

                                @else

                                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">

                                        {{ strtoupper(substr($user->name, 0, 1)) }}

                                    </div>

                                @endif

                                <div>

                                    <div class="font-semibold text-gray-800">
                                        {{ $user->name }}
                                    </div>

                                    <div class="text-sm text-gray-500">
                                        {{ $user->email }}
                                    </div>

                                </div>

                            </div>

                        </td>

                        <!-- KELAS -->
                        <td class="px-6 py-5 text-center">

                            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold">

                                {{ $user->kelas }}

                            </span>

                        </td>

                        <!-- ALAMAT -->
                        <td class="px-6 py-5 text-gray-600">

                            {{ $user->alamat ?? '-' }}

                        </td>

                        <!-- AKSI -->
                        <td class="px-6 py-5">

                            <div class="flex justify-center gap-3">

                                <!-- QR -->
                                <button
                                    onclick="showQr({{ $user->id }})"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm shadow transition">

                                    Cetak QR
                                </button>

                                <!-- DETAIL -->
                                <button
                                    type="button"
                                    data-detail="{{ json_encode([
                                        'name' => $user->name,
                                        'email' => $user->email,
                                        'role' => $user->role,
                                        'kelas' => $user->kelas ? 'Kelas ' . $user->kelas : 'Tidak Ada Kelas',
                                        'alamat' => $user->alamat ?? 'Alamat belum diisi',
                                        'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
                                        'terdaftar' => $user->created_at ? $user->created_at->format('d M Y') : '-'
                                    ]) }}"
                                    onclick="showDetail(this)"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-sm shadow transition">

                                    Detail
                                </button>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-10 text-gray-400">

                            Tidak ada data user

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <!-- PAGINATION -->
    <div class="p-6 border-t bg-gray-50">

        {{ $users->links() }}

    </div>

    </div>

</div>

<!-- MODAL QR -->
<div id="qrModal"
    class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center">

        <div class="bg-white w-[700px] rounded-[25px] shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 px-6 py-4 flex justify-between items-center">

            <div>
                <h2 class="text-2xl font-bold text-white">
                    JARENG
                </h2>

                <p class="text-blue-100 text-sm">
                    Kartu QR Absensi Siswa
                </p>
            </div>

            <button onclick="closeQr()"
                class="bg-white/20 hover:bg-white/30 text-white w-10 h-10 rounded-full">
                ✕
            </button>

        </div>

        <!-- BODY -->
        <div id="kartuQr" class="flex">

            <!-- KIRI -->
            <div class="w-2/3 p-6">

                <div class="flex items-center gap-5">

                    <!-- FOTO -->
                    <img id="userFoto"
                        class="w-28 h-28 rounded-2xl object-cover border-4 border-blue-500 shadow">

                    <!-- DATA -->
                    <div>

                        <div class="text-xs text-gray-500 uppercase">
                            Nama Lengkap
                        </div>

                        <div id="userNama"
                            class="text-2xl font-bold text-gray-800 mb-4">
                        </div>

                        <div class="text-xs text-gray-500 uppercase">
                            Kelas
                        </div>

                        <div id="userKelas"
                            class="text-lg font-semibold text-gray-700 mb-4">
                        </div>

                        <div class="text-xs text-gray-500 uppercase">
                            Alamat
                        </div>

                        <div id="userAlamat"
                            class="text-sm text-gray-700 leading-relaxed max-w-md">
                        </div>

                    </div>

                </div>

                <!-- INFO -->
                <div class="mt-6 border-t pt-4">

                    <div class="text-sm text-gray-500">
                        Sistem Absensi Digital
                    </div>

                    <div class="font-semibold text-blue-700 mt-1">
                        Scan QR untuk melakukan absensi
                    </div>

                </div>

            </div>

            <!-- KANAN -->
            <div class="w-1/3 bg-gray-50 border-l flex flex-col items-center justify-center p-5">

                <div class="font-bold text-gray-700 mb-3">
                    QR ABSENSI
                </div>

                <div class="bg-white p-3 rounded-2xl shadow border">

                    <div id="qrImage"></div>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">

            <button onclick="cetakPdf()"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-xl font-semibold">
                Cetak PDF
            </button>

            <button onclick="closeQr()"
                class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl font-semibold">
                Tutup
            </button>

        </div>

    </div>

</div>

<!-- MODAL DETAIL -->
<div id="detailModal"
    class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">

    <div class="bg-white w-[550px] rounded-[25px] shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div style="background: #ffffff; border-bottom: 1px solid #e2e8f0;" class="px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div style="background: #eff6ff;" class="w-10 h-10 rounded-xl flex items-center justify-center text-xl">
                    👤
                </div>
                <div>
                    <h3 style="color: #1e293b; font-weight: 800; font-size: 1.25rem; margin: 0;">Detail Data User</h3>
                    <p style="color: #64748b; margin-top: 2px;" class="text-xs">Informasi lengkap pengguna sistem</p>
                </div>
            </div>
            <button onclick="closeDetail()"
                style="background: transparent; color: #64748b; font-size: 1.25rem;"
                class="hover:text-gray-900 font-bold transition border-none cursor-pointer p-1">
                ✕
            </button>
        </div>

        <!-- BODY -->
        <div class="p-7">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6 pb-6 border-b border-gray-100">
                <!-- FOTO -->
                <img id="detailFoto"
                    src=""
                    class="w-28 h-28 rounded-2xl object-cover border-4 border-emerald-500/30 shadow-lg shrink-0">

                <div class="text-center sm:text-left flex-1">
                    <h4 id="detailNama" style="color: #1e293b;" class="text-2xl font-extrabold mb-1"></h4>
                    <div id="detailEmail" style="color: #64748b;" class="text-sm font-medium mb-3"></div>
                    <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                        <span id="detailRole" style="background-color: #d1fae5; color: #065f46;" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide"></span>
                        <span id="detailKelas" style="background-color: #dbeafe; color: #1e40af;" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide"></span>
                    </div>
                </div>
            </div>

            <!-- DETAIL INFORMATION -->
            <div class="space-y-4">
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0;" class="p-4 rounded-2xl">
                    <div style="color: #64748b;" class="text-xs font-bold uppercase tracking-wider mb-1">Alamat Tempat Tinggal</div>
                    <div id="detailAlamat" style="color: #1e293b;" class="text-sm font-semibold leading-relaxed"></div>
                </div>

                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0;" class="p-4 rounded-2xl">
                    <div style="color: #64748b;" class="text-xs font-bold uppercase tracking-wider mb-1">Tanggal Bergabung / Terdaftar</div>
                    <div id="detailTerdaftar" style="color: #1e293b;" class="text-sm font-semibold"></div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;" class="px-6 py-4 flex justify-end">
            <button onclick="closeDetail()"
                style="background-color: #e2e8f0; color: #334155;"
                class="hover:bg-gray-300 px-6 py-2.5 rounded-xl font-bold text-sm transition border-none cursor-pointer">
                Tutup
            </button>
        </div>

    </div>

</div>

<script>
    function showDetail(el) {
        const data = JSON.parse(el.getAttribute('data-detail'));
        const modal = document.getElementById('detailModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('detailNama').innerText = data.name || '-';
        document.getElementById('detailEmail').innerText = data.email || '-';
        document.getElementById('detailRole').innerText = 'Role: ' + (data.role || '-').toUpperCase();
        document.getElementById('detailKelas').innerText = data.kelas || '-';
        document.getElementById('detailAlamat').innerText = data.alamat || '-';
        document.getElementById('detailTerdaftar').innerText = data.terdaftar || '-';

        const fotoEl = document.getElementById('detailFoto');
        if (data.foto) {
            fotoEl.src = data.foto;
        } else {
            fotoEl.src = "https://ui-avatars.com/api/?name=" + encodeURIComponent(data.name) + "&background=10b981&color=fff&size=150";
        }
    }

    function closeDetail() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function showQr(id) {

        try {

            const response = await fetch(`/user/qr/${id}`);

            const data = await response.json();

            const modal = document.getElementById('qrModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // QR
            document.getElementById('qrImage')
                .innerHTML = `
                    <img 
                        src="data:image/svg+xml;base64,${data.qr}" 
                        class="w-36 h-36"
                    >
                `;

            // NAMA
            document.getElementById('userNama')
                .innerText = data.name;

            // KELAS
            document.getElementById('userKelas')
                .innerText = data.kelas;

            // ALAMAT
            document.getElementById('userAlamat')
                .innerText = data.alamat ?? '-';

            // FOTO
            if (data.foto) {

                document.getElementById('userFoto')
                    .src = data.foto;

            } else {

                document.getElementById('userFoto')
                    .src =
                    "https://ui-avatars.com/api/?name=" + data.name;

            }

        } catch (error) {

            console.log(error);

            showNotificationModal('Gagal', 'Kartu QR gagal dimuat dari server.', 'error');

        }

    }

    function closeQr() {

        const modal = document.getElementById('qrModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }

    function cetakPdf() {

        const element = document.getElementById('kartuQr');

        const nama =
            document.getElementById('userNama').innerText;

        const opt = {

            margin: 0.2,

            filename: 'Kartu-QR-' + nama + '.pdf',

            image: {
                type: 'jpeg',
                quality: 1
            },

            html2canvas: {
                scale: 3,
                useCORS: true
            },

            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'landscape'
            }

        };

        html2pdf()
            .set(opt)
            .from(element)
            .save();
    }

</script>

@endsection