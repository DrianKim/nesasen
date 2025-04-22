<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['isLogin'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        // murid
        Route::get('admin/murid', [KurikulumController::class, 'data_murid'])->name('admin_murid.index');
        Route::get('admin/murid/create', [KurikulumController::class, 'create_murid'])->name('admin_murid.create');
        Route::post('admin/murid/store', [KurikulumController::class, 'store_murid'])->name('admin_murid.store');
        Route::get('admin/murid/edit/{id}', [KurikulumController::class, 'edit_murid'])->name('admin_murid.edit');
        Route::post('admin/murid/update/{id}', [KurikulumController::class, 'update_murid'])->name('admin_murid.update');
        Route::delete('admin/murid/destroy/{id}', [KurikulumController::class, 'destroy_murid'])->name('admin_murid.destroy');

        // guru
        Route::get('admin/guru', [KurikulumController::class, 'data_guru'])->name('admin_guru.index');
        Route::get('admin/guru/create', [KurikulumController::class, 'create_guru'])->name('admin_guru.create');
        Route::post('admin/guru/store', [KurikulumController::class, 'store_guru'])->name('admin_guru.store');
        Route::get('admin/guru/edit/{id}', [KurikulumController::class, 'edit_guru'])->name('admin_guru.edit');
        Route::post('admin/guru/update/{id}', [KurikulumController::class, 'update_guru'])->name('admin_guru.update');
        Route::delete('admin/guru/destroy/{id}', [KurikulumController::class, 'destroy_guru'])->name('admin_guru.destroy');

        // walas
        Route::get('admin/walas', [KurikulumController::class, 'data_walas'])->name('admin_walas.index');
        Route::get('admin/walas/create', [KurikulumController::class, 'create_walas'])->name('admin_walas.create');
        Route::post('admin/walas/store', [KurikulumController::class, 'store_walas'])->name('admin_walas.store');
        Route::get('admin/walas/edit/{id}', [KurikulumController::class, 'edit_walas'])->name('admin_walas.edit');
        Route::post('admin/walas/update/{id}', [KurikulumController::class, 'update_walas'])->name('admin_walas.update');
        Route::delete('admin/walas/destroy/{id}', [KurikulumController::class, 'destroy_walas'])->name('admin_walas.destroy');

        // umum jurusan
        Route::get('admin/umum/jurusan', [KurikulumController::class, 'umum_jurusan'])->name('admin_umum_jurusan.index');
        Route::get('admin/umum/jurusan/create', [KurikulumController::class, 'create_jurusan'])->name('admin_umum_jurusan.create');
        Route::post('admin/umum/jurusan/store', [KurikulumController::class, 'store_jurusan'])->name('admin_umum_jurusan.store');
        Route::get('admin/umum/jurusan/edit/{id}', [KurikulumController::class, 'edit_jurusan'])->name('admin_umum_jurusan.edit');
        Route::post('admin/umum/jurusan/update/{id}', [KurikulumController::class, 'update_jurusan'])->name('admin_umum_jurusan.update');
        Route::delete('admin/umum/jurusan/destroy/{id}', [KurikulumController::class, 'destroy_jurusan'])->name('admin_umum_jurusan.destroy');

        // umum kelas
        Route::get('admin/umum/kelas', [KurikulumController::class, 'umum_kelas'])->name('admin_umum_kelas.index');
        Route::get('admin/umum/kelas/create', [KurikulumController::class, 'create_kelas'])->name('admin_umum_kelas.create');
        Route::post('admin/umum/kelas/store', [KurikulumController::class, 'store_kelas'])->name('admin_umum_kelas.store');
        Route::get('admin/umum/kelas/edit/{id}', [KurikulumController::class, 'edit_kelas'])->name('admin_umum_kelas.edit');
        Route::post('admin/umum/kelas/update/{id}', [KurikulumController::class, 'update_kelas'])->name('admin_umum_kelas.update');
        Route::delete('admin/umum/kelas/destroy/{id}', [KurikulumController::class, 'destroy_kelas'])->name('admin_umum_kelas.destroy');

        // umum mapel
        Route::get('admin/umum/mapel', [KurikulumController::class, 'umum_mapel'])->name('admin_umum_mapel.index');
        Route::get('admin/umum/mapel/create', [KurikulumController::class, 'create_mapel'])->name('admin_umum_mapel.create');
        Route::post('admin/umum/mapel/store', [KurikulumController::class, 'store_mapel'])->name('admin_umum_mapel.store');
        Route::get('admin/umum/mapel/edit/{id}', [KurikulumController::class, 'edit_mapel'])->name('admin_umum_mapel.edit');
        Route::post('admin/umum/mapel/update/{id}', [KurikulumController::class, 'update_mapel'])->name('admin_umum_mapel.update');
        Route::delete('admin/umum/mapel/destroy/{id}', [KurikulumController::class, 'destroy_mapel'])->name('admin_umum_mapel.destroy');

        // Route::get('admin/umum/semester', [KurikulumController::class, 'umum_semester'])->name('admin_umum_semester.index');
        // Route::get('admin/umum/tahunAjaran', [KurikulumController::class, 'umum_tahunAjaran'])->name('admin_umum_tahunAjaran.index');

        // Route::resource('tahunAjaran', controller: KurikulumController::class);
        // Route::resource('semester', controller: KurikulumController::class);

        Route::get('admin/walas', [KurikulumController::class, 'data_walas'])->name('admin_walas.index');
    });
});

// Login
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::post('register', [AuthController::class, 'registerProses'])->name('registerProses');
