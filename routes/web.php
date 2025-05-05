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

    // rekap presensi
    Route::get('rekap_presensi', [WalasController::class, 'rekap_presensi_index'])->name('rekap_presensi.index');

    Route::get('jadwal_mengajar', [PengajarController::class, 'index_jadwal_mengajar'])->name('jadwal_mengajar.index');

    Route::get('jadwal_pelajaran', [MuridController::class, 'index_jadwal_pelajaran'])->name('jadwal_pelajaran.index');

    Route::middleware(['auth', 'isGuru'])->group(function () {});

    Route::middleware(['auth', 'isWalas'])->group(function () {});

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        // umum kelas
        Route::get('admin/kelas', [AdminController::class, 'index_kelas'])->name('admin_kelas.index');
        Route::get('admin/kelas/filter', [AdminController::class, 'index_kelas'])->name('admin_kelas.filter');
        Route::get('admin/kelas/create', [AdminController::class, 'create_kelas'])->name('admin_kelas.create');
        Route::post('admin/kelas/store', [AdminController::class, 'store_kelas'])->name('admin_kelas.store');
        Route::get('admin/kelas/edit/{id}', [AdminController::class, 'edit_kelas'])->name('admin_kelas.edit');
        Route::post('admin/kelas/update/{id}', [AdminController::class, 'update_kelas'])->name('admin_kelas.update');
        Route::delete('admin/kelas/destroy/{id}', [AdminController::class, 'destroy_kelas'])->name('admin_kelas.destroy');
        Route::post('admin/kelas/bulk_action', [AdminController::class, 'bulkAction_kelas'])->name('admin_kelas.bulk_action');

        // umum kelasKu
        Route::get('admin/kelasKu', [AdminController::class, 'index_kelasKu'])->name('admin_kelasKu.index');
        Route::get('admin/kelasKu/create', [AdminController::class, 'create_kelasKu'])->name('admin_kelasKu.create');
        Route::post('admin/kelasKu/store', [AdminController::class, 'store_kelasKu'])->name('admin_kelasKu.store');
        Route::get('admin/kelasKu/edit/{id}', [AdminController::class, 'edit_kelasKu'])->name('admin_kelasKu.edit');
        Route::post('admin/kelasKu/update/{id}', [AdminController::class, 'update_kelasKu'])->name('admin_kelasKu.update');
        Route::delete('admin/kelasKu/destroy/{id}', [AdminController::class, 'destroy_kelasKu'])->name('admin_kelasKu.destroy');

        // siswa
        Route::get('admin/siswa', [AdminController::class, 'index_siswa'])->name('admin_siswa.index');
        Route::get('admin/siswa/filter', [AdminController::class, 'index_siswa'])->name('admin_siswa.filter');
        Route::get('admin/siswa/create', [AdminController::class, 'create_siswa'])->name('admin_siswa.create');
        Route::post('admin/siswa/store', [AdminController::class, 'store_siswa'])->name('admin_siswa.store');
        Route::get('admin/siswa/edit/{id}', [AdminController::class, 'edit_siswa'])->name('admin_siswa.edit');
        Route::post('admin/siswa/update/{id}', [AdminController::class, 'update_siswa'])->name('admin_siswa.update');
        Route::delete('admin/siswa/destroy/{id}', [AdminController::class, 'destroy_siswa'])->name('admin_siswa.destroy');
        Route::post('admin/siswa/bulk_action', [AdminController::class, 'bulkAction_siswa'])->name('admin_siswa.bulk_action');

        // guru
        Route::get('admin/guru', [AdminController::class, 'index_guru'])->name('admin_guru.index');
        Route::get('admin/guru/create', [AdminController::class, 'create_guru'])->name('admin_guru.create');
        Route::post('admin/guru/store', [AdminController::class, 'store_guru'])->name('admin_guru.store');
        Route::get('admin/guru/edit/{id}', [AdminController::class, 'edit_guru'])->name('admin_guru.edit');
        Route::post('admin/guru/update/{id}', [AdminController::class, 'update_guru'])->name('admin_guru.update');
        Route::delete('admin/guru/destroy/{id}', [AdminController::class, 'destroy_guru'])->name('admin_guru.destroy');
        Route::post('admin/guru/bulk_action', [AdminController::class, 'bulkAction_guru'])->name('admin_guru.bulk_action');

        Route::get('admin/jadwal_pelajaran', [AdminController::class, 'index_jadwal_pelajaran'])->name('admin_jadwal_pelajaran.index');

        // umum jurusan
        Route::get('admin/jurusan', [AdminController::class, 'index_jurusan'])->name('admin_jurusan.index');
        Route::get('admin/jurusan/create', [AdminController::class, 'create_jurusan'])->name('admin_jurusan.create');
        Route::post('admin/jurusan/store', [AdminController::class, 'store_jurusan'])->name('admin_jurusan.store');
        Route::get('admin/jurusan/edit/{id}', [AdminController::class, 'edit_jurusan'])->name('admin_jurusan.edit');
        Route::post('admin/jurusan/update/{id}', [AdminController::class, 'update_jurusan'])->name('admin_jurusan.update');
        Route::delete('admin/jurusan/destroy/{id}', [AdminController::class, 'destroy_jurusan'])->name('admin_jurusan.destroy');

        // umum mapel
        Route::get('admin/mapel', [AdminController::class, 'index_mapel'])->name('admin_mapel.index');
        Route::get('admin/mapel/create', [AdminController::class, 'create_mapel'])->name('admin_mapel.create');
        Route::post('admin/mapel/store', [AdminController::class, 'store_mapel'])->name('admin_mapel.store');
        Route::get('admin/mapel/edit/{id}', [AdminController::class, 'edit_mapel'])->name('admin_mapel.edit');
        Route::post('admin/mapel/update/{id}', [AdminController::class, 'update_mapel'])->name('admin_mapel.update');
        Route::delete('admin/mapel/destroy/{id}', [AdminController::class, 'destroy_mapel'])->name('admin_mapel.destroy');

        // presensi siswa
        Route::get('admin/presensi/siswa', [AdminController::class, 'index_presensi_siswa'])->name('admin_presensi_siswa.index');

        // presensi guru
        Route::get('admin/presensi/guru', [AdminController::class, 'index_presensi_guru'])->name('admin_presensi_guru.index');

        // presensi per mapel
        Route::get('admin/presensi/per_mapel', [AdminController::class, 'index_presensi_per_mapel'])->name('admin_presensi_per_mapel.index');

        // izin siswa
        Route::get('admin/izin/siswa', [AdminController::class, 'index_izin_siswa'])->name('admin_izin_siswa.index');

        // izin guru
        Route::get('admin/izin/guru', [AdminController::class, 'index_izin_guru'])->name('admin_izin_guru.index');

        // Route::get('admin/semester', [AdminController::class, 'semester'])->name('admin_semester.index');
        // Route::get('admin/tahunAjaran', [AdminController::class, 'tahunAjaran'])->name('admin_tahunAjaran.index');

        // Route::resource('tahunAjaran', controller: AdminController::class);
        // Route::resource('semester', controller: AdminController::class);

        // Route::get('admin/walas', [AdminController::class, 'index_walas'])->name('admin_walas.index');

        // Excel
        Route::post('/siswa/import', [AdminController::class, 'import_siswa'])->name('siswa.import');
        Route::post('/guru/import', [AdminController::class, 'import_guru'])->name('guru.import');
    });
});
// walas
        // Route::get('admin/walas', [AdminController::class, 'index_walas'])->name('admin_walas.index');
        // Route::get('admin/walas/create', [AdminController::class, 'create_walas'])->name('admin_walas.create');
        // Route::post('admin/walas/store', [AdminController::class, 'store_walas'])->name('admin_walas.store');
        // Route::get('admin/walas/edit/{id}', [AdminController::class, 'edit_walas'])->name('admin_walas.edit');
        // Route::post('admin/walas/update/{id}', [AdminController::class, 'update_walas'])->name('admin_walas.update');
        // Route::delete('admin/walas/destroy/{id}', [AdminController::class, 'destroy_walas'])->name('admin_walas.destroy');
