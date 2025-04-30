<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\WalasController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PengajarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Login
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::post('register', [AuthController::class, 'registerProses'])->name('registerProses');

Route::middleware(['isLogin'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // profil
    Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::get('profil/edit', [ProfilController::class, 'index'])->name('profil.edit');
    Route::post('profil/update', [ProfilController::class, 'update'])->name('profil.update');

    // data kelas
    Route::get('data_kelas', [WalasController::class, 'data_kelas_index'])->name('data_kelas.index');

    // rekap presensi
    Route::get('rekap_presensi', [WalasController::class, 'rekap_presensi_index'])->name('rekap_presensi.index');

    Route::get('jadwal_mengajar', [PengajarController::class, 'index_jadwal_mengajar'])->name('jadwal_mengajar.index');

    Route::get('jadwal_pelajaran', [MuridController::class, 'index_jadwal_pelajaran'])->name('jadwal_pelajaran.index');

    Route::middleware(['auth', 'isGuru'])->group(function () {});

    Route::middleware(['auth', 'isWalas'])->group(function () {});

    Route::middleware(['auth', 'isAdmin'])->group(function () {

        // umum kelas
        Route::get('admin/umum/kelas', [AdminController::class, 'umum_kelas'])->name('admin_umum_kelas.index');
        Route::get('admin/umum/kelas/create', [AdminController::class, 'create_kelas'])->name('admin_umum_kelas.create');
        Route::post('admin/umum/kelas/store', [AdminController::class, 'store_kelas'])->name('admin_umum_kelas.store');
        Route::get('admin/umum/kelas/edit/{id}', [AdminController::class, 'edit_kelas'])->name('admin_umum_kelas.edit');
        Route::post('admin/umum/kelas/update/{id}', [AdminController::class, 'update_kelas'])->name('admin_umum_kelas.update');
        Route::delete('admin/umum/kelas/destroy/{id}', [AdminController::class, 'destroy_kelas'])->name('admin_umum_kelas.destroy');

        // siswa
        Route::get('admin/siswa', [AdminController::class, 'data_siswa'])->name('admin_siswa.index');
        Route::get('admin/siswa/create', [AdminController::class, 'create_siswa'])->name('admin_siswa.create');
        Route::post('admin/siswa/store', [AdminController::class, 'store_siswa'])->name('admin_siswa.store');
        Route::get('admin/siswa/edit/{id}', [AdminController::class, 'edit_siswa'])->name('admin_siswa.edit');
        Route::post('admin/siswa/update/{id}', [AdminController::class, 'update_siswa'])->name('admin_siswa.update');
        Route::delete('admin/siswa/destroy/{id}', [AdminController::class, 'destroy_siswa'])->name('admin_siswa.destroy');

        // guru
        Route::get('admin/guru', [AdminController::class, 'data_guru'])->name('admin_guru.index');
        Route::get('admin/guru/create', [AdminController::class, 'create_guru'])->name('admin_guru.create');
        Route::post('admin/guru/store', [AdminController::class, 'store_guru'])->name('admin_guru.store');
        Route::get('admin/guru/edit/{id}', [AdminController::class, 'edit_guru'])->name('admin_guru.edit');
        Route::post('admin/guru/update/{id}', [AdminController::class, 'update_guru'])->name('admin_guru.update');
        Route::delete('admin/guru/destroy/{id}', [AdminController::class, 'destroy_guru'])->name('admin_guru.destroy');

        // walas
        Route::get('admin/walas', [AdminController::class, 'data_walas'])->name('admin_walas.index');
        Route::get('admin/walas/create', [AdminController::class, 'create_walas'])->name('admin_walas.create');
        Route::post('admin/walas/store', [AdminController::class, 'store_walas'])->name('admin_walas.store');
        Route::get('admin/walas/edit/{id}', [AdminController::class, 'edit_walas'])->name('admin_walas.edit');
        Route::post('admin/walas/update/{id}', [AdminController::class, 'update_walas'])->name('admin_walas.update');
        Route::delete('admin/walas/destroy/{id}', [AdminController::class, 'destroy_walas'])->name('admin_walas.destroy');

        // umum jurusan
        Route::get('admin/umum/jurusan', [AdminController::class, 'umum_jurusan'])->name('admin_umum_jurusan.index');
        Route::get('admin/umum/jurusan/create', [AdminController::class, 'create_jurusan'])->name('admin_umum_jurusan.create');
        Route::post('admin/umum/jurusan/store', [AdminController::class, 'store_jurusan'])->name('admin_umum_jurusan.store');
        Route::get('admin/umum/jurusan/edit/{id}', [AdminController::class, 'edit_jurusan'])->name('admin_umum_jurusan.edit');
        Route::post('admin/umum/jurusan/update/{id}', [AdminController::class, 'update_jurusan'])->name('admin_umum_jurusan.update');
        Route::delete('admin/umum/jurusan/destroy/{id}', [AdminController::class, 'destroy_jurusan'])->name('admin_umum_jurusan.destroy');

        // umum mapel
        Route::get('admin/umum/mapel', [AdminController::class, 'umum_mapel'])->name('admin_umum_mapel.index');
        Route::get('admin/umum/mapel/create', [AdminController::class, 'create_mapel'])->name('admin_umum_mapel.create');
        Route::post('admin/umum/mapel/store', [AdminController::class, 'store_mapel'])->name('admin_umum_mapel.store');
        Route::get('admin/umum/mapel/edit/{id}', [AdminController::class, 'edit_mapel'])->name('admin_umum_mapel.edit');
        Route::post('admin/umum/mapel/update/{id}', [AdminController::class, 'update_mapel'])->name('admin_umum_mapel.update');
        Route::delete('admin/umum/mapel/destroy/{id}', [AdminController::class, 'destroy_mapel'])->name('admin_umum_mapel.destroy');

        // Route::get('admin/umum/semester', [AdminController::class, 'umum_semester'])->name('admin_umum_semester.index');
        // Route::get('admin/umum/tahunAjaran', [AdminController::class, 'umum_tahunAjaran'])->name('admin_umum_tahunAjaran.index');

        // Route::resource('tahunAjaran', controller: AdminController::class);
        // Route::resource('semester', controller: AdminController::class);

        Route::get('admin/walas', [AdminController::class, 'data_walas'])->name('admin_walas.index');

        // Excel
        Route::post('/siswa/import', [AdminController::class, 'import_siswa'])->name('siswa.import');
        Route::post('/guru/import', [AdminController::class, 'import_guru'])->name('guru.import');
    });
});
