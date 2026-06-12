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

Route::get('/', function () {
    return view('auth/login');
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

    Route::get('/siswa/dashboard', function () {
        return view('siswa.dashboard');
    })->name('siswa.dashboard');

});

Route::get('/siswa/dashboard',
    [SiswaDashboardController::class, 'index']
)->name('siswa.dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/siswa/profil', [SiswaController::class, 'profil'])
        ->name('siswa.profil');

    Route::get('/siswa/qr', [SiswaController::class, 'qrSaya'])
        ->name('siswa.qr');

    Route::get('/siswa/riwayat', [SiswaController::class, 'riwayat'])
        ->name('siswa.riwayat');
});

// NAVIGASI 
Route::middleware('auth')->group(function () {

    Route::get('/scan-qr', fn() => view('admin.scan-qr'))->name('scan.qr');
    Route::get('/scan-wajah', fn() => view('admin.scan-wajah'))->name('scan.wajah');
    Route::get('/absenmanual', fn() => view('admin.absenmanual'))->name('absenmanual');
    Route::get('/master-data', [UserController::class, 'index'])->name('master.data');
    Route::get('/laporan', fn() => view('admin.laporan'))->name('laporan');
    Route::get('/kelas', [GroupController::class, 'index'])->name('kelas');
    Route::get('/user', [UserController::class, 'userPage'])->name('user.page');

});

// QR 
Route::get('/user/qr/{id}', [UserController::class, 'showQr'])
    ->name('user.qr');

Route::post('/scan-qr/process', [AbsensiController::class, 'scanQr'])
    ->name('scan.qr.process');

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
