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

        // umum
        Route::get('admin/umum/kelas', [KurikulumController::class, 'umum_kelas'])->name('admin_umum_kelas.index');
        Route::get('admin/umum/mataPelajaran', [KurikulumController::class, 'umum_mataPelajaran'])->name('admin_umum_mataPelajaran.index');
        // Route::get('admin/umum/semester', [KurikulumController::class, 'umum_semester'])->name('admin_umum_semester.index');
        // Route::get('admin/umum/tahunAjaran', [KurikulumController::class, 'umum_tahunAjaran'])->name('admin_umum_tahunAjaran.index');

        // guru
        Route::get('admin_guru', [KurikulumController::class, 'data_guru'])->name('admin_guru.index');
        Route::get('admin/guru/create', [KurikulumController::class, 'create_guru'])->name('admin_guru.create');
        Route::post('admin/guru/store', [KurikulumController::class, 'store_guru'])->name('admin_guru.store');
        Route::get('admin/guru/edit/{id}', [KurikulumController::class, 'edit_guru'])->name('admin_guru.edit');
        Route::post('admin/guru/update/{id}', [KurikulumController::class, 'update_guru'])->name('admin_guru.update');
        Route::delete('admin/guru/destroy/{id}', [KurikulumController::class, 'destroy_guru'])->name('admin_guru.destroy');


        Route::get('admin_walas'    , [KurikulumController::class, 'data_walas'])->name('admin_walas.index');
    });
});

// Login
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::post('register', [AuthController::class, 'registerProses'])->name('registerProses');
