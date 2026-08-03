<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Models\User;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\FaceRegistrationController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CatatanController;

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return view('auth.login');
});

//CRUD
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
});
Route::get('/tambah-user', function () {
    return view('admin.tambahuser');
})->name('tambah.user');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');

    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
    ->name('siswa.dashboard');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/siswa/profil', [SiswaController::class, 'profil'])
        ->name('siswa.profil');

    Route::get('/siswa/qr', [SiswaController::class, 'qrSaya'])
        ->name('siswa.qr');

    Route::get('/siswa/riwayat', [SiswaController::class, 'riwayat'])
        ->name('siswa.riwayat');

    Route::get('/siswa/rekap', [SiswaDashboardController::class, 'rekap'])
        ->name('siswa.rekap');

    Route::get('/siswa/rekap/pdf', [SiswaDashboardController::class, 'downloadRekapPdf'])
        ->name('siswa.rekap.pdf');
});

// NAVIGASI 
Route::middleware('auth')->group(function () {

    Route::get('/scan-qr', fn() => view('admin.scan-qr'))->name('scan.qr');
    Route::get('/scan-wajah', function () {
        // Hitung statistik kelas kecil (1, 2, 3)
        $totalKecil = \App\Models\User::where('role', 'siswa')->whereIn('kelas', ['1', '2', '3'])->count();
        $sudahKecil = \App\Models\User::where('role', 'siswa')->whereIn('kelas', ['1', '2', '3'])
            ->whereHas('absensis', function($q) {
                $q->whereDate('tanggal', \Carbon\Carbon::today())->where('status', 'hadir');
            })->count();
        $belumKecil = $totalKecil - $sudahKecil;

        // Hitung statistik kelas besar (4, 5, 6)
        $totalBesar = \App\Models\User::where('role', 'siswa')->whereIn('kelas', ['4', '5', '6'])->count();
        $sudahBesar = \App\Models\User::where('role', 'siswa')->whereIn('kelas', ['4', '5', '6'])
            ->whereHas('absensis', function($q) {
                $q->whereDate('tanggal', \Carbon\Carbon::today())->where('status', 'hadir');
            })->count();
        $belumBesar = $totalBesar - $sudahBesar;

        return view('admin.scan-wajah', compact(
            'totalKecil', 'sudahKecil', 'belumKecil',
            'totalBesar', 'sudahBesar', 'belumBesar'
        ));
    })->name('scan.wajah');
    Route::get('/absenmanual', fn() => view('admin.absenmanual'))->name('absenmanual');
    Route::get('/master-data', [UserController::class, 'index'])->name('master.data');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.pdf');
    Route::post('/catatan/simpan', [CatatanController::class, 'simpan'])->name('catatan.simpan');
    Route::post('/catatan/hapus', [CatatanController::class, 'hapus'])->name('catatan.hapus');
    Route::get('/kelas', [GroupController::class, 'index'])->name('kelas');
    Route::get('/user', [UserController::class, 'userPage'])->name('user.page');

    // Face Registration
    Route::get('/admin/face-registration', [FaceRegistrationController::class, 'index'])->name('face-registration.index');
    Route::post('/admin/face-registration/store', [FaceRegistrationController::class, 'store'])->name('face-registration.store');
    Route::delete('/admin/face-registration/{id}', [FaceRegistrationController::class, 'destroy'])->name('face-registration.destroy');

    // QR Process — harus login
    Route::post('/scan-qr/process', [AbsensiController::class, 'scanQr'])
        ->name('scan.qr.process');

    // Face Recognition process
    Route::post('/absensi/wajah', [AbsensiController::class, 'scanWajah'])
        ->name('absensi.wajah');

});

// QR Code display (boleh publik untuk generate QR)
Route::get('/user/qr/{id}', [UserController::class, 'showQr'])
    ->name('user.qr');

Route::get('/form-tambah-kelas', function () {
    return view('admin.formtambahkelas');
})->name('form.tambah.kelas');

// EDIT KELAS
Route::get('/group/{id}/edit', [GroupController::class, 'edit'])
    ->name('group.edit');

Route::post('/group/{id}/update-siswa', [GroupController::class, 'updateSiswa'])
    ->name('group.updateSiswa');

// GRUP
Route::get('/group/create', [GroupController::class, 'create'])->name('group.create');
Route::post('/group/store', [GroupController::class, 'store'])->name('group.store');

// ABSENSI
Route::get('/absenmanual', [GroupController::class, 'absenManual'])
    ->name('absenmanual');

Route::get('/absenmanual/{kategori}', [GroupController::class, 'detailAbsen'])
    ->name('absenmanual.detail');

Route::post('/absensi/store', [AbsensiController::class, 'simpanAbsensi'])
    ->name('absensi.store');

require __DIR__.'/auth.php';
