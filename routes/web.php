<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// === AUTH ===
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('register', [AuthController::class, 'registerProses'])->name('registerProses');


// === DASHBOARD + ADMIN AREA ===
Route::middleware(['isLogin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        // === MURID ===
        Route::prefix('admin/murid')->group(function () {
            Route::get('/', [KurikulumController::class, 'data_murid'])->name('admin_murid.index');
            Route::get('create', [KurikulumController::class, 'create_murid'])->name('admin_murid.create');
            Route::post('store', [KurikulumController::class, 'store_murid'])->name('admin_murid.store');
            Route::get('edit/{id}', [KurikulumController::class, 'edit_murid'])->name('admin_murid.edit');
            Route::post('update/{id}', [KurikulumController::class, 'update_murid'])->name('admin_murid.update');
            Route::delete('destroy/{id}', [KurikulumController::class, 'destroy_murid'])->name('admin_murid.destroy');
        });

        // === UMUM: JURUSAN, KELAS, MAPEL ===
        Route::prefix('admin/umum')->group(function () {
            // Jurusan
            Route::prefix('jurusan')->group(function () {
                Route::get('/', [KurikulumController::class, 'umum_jurusan'])->name('admin_umum_jurusan.index');
                Route::get('create', [KurikulumController::class, 'create_jurusan'])->name('admin_umum_jurusan.create');
                Route::post('store', [KurikulumController::class, 'store_jurusan'])->name('admin_umum_jurusan.store');
                Route::get('edit/{id}', [KurikulumController::class, 'edit_jurusan'])->name('admin_umum_jurusan.edit');
                Route::post('update/{id}', [KurikulumController::class, 'update_jurusan'])->name('admin_umum_jurusan.update');
                Route::delete('destroy/{id}', [KurikulumController::class, 'destroy_jurusan'])->name('admin_umum_jurusan.destroy');
            });

            // Kelas
            Route::prefix('kelas')->group(function () {
                Route::get('/', [KurikulumController::class, 'umum_kelas'])->name('admin_umum_kelas.index');
                Route::get('create', [KurikulumController::class, 'create_kelas'])->name('admin_umum_kelas.create');
                Route::post('store', [KurikulumController::class, 'store_kelas'])->name('admin_umum_kelas.store');
                Route::get('edit/{id}', [KurikulumController::class, 'edit_kelas'])->name('admin_umum_kelas.edit');
                Route::post('update/{id}', [KurikulumController::class, 'update_kelas'])->name('admin_umum_kelas.update');
                Route::delete('destroy/{id}', [KurikulumController::class, 'destroy_kelas'])->name('admin_umum_kelas.destroy');
            });

            // Mapel
            Route::prefix('mapel')->group(function () {
                Route::get('/', [KurikulumController::class, 'umum_mapel'])->name('admin_umum_mapel.index');
                Route::get('create', [KurikulumController::class, 'create_mapel'])->name('admin_umum_mapel.create');
                Route::post('store', [KurikulumController::class, 'store_mapel'])->name('admin_umum_mapel.store');
                Route::get('edit/{id}', [KurikulumController::class, 'edit_mapel'])->name('admin_umum_mapel.edit');
                Route::post('update/{id}', [KurikulumController::class, 'update_mapel'])->name('admin_umum_mapel.update');
                Route::delete('destroy/{id}', [KurikulumController::class, 'destroy_mapel'])->name('admin_umum_mapel.destroy');
            });
        });

        // === GURU ===
        Route::prefix('admin/guru')->group(function () {
            Route::get('/', [KurikulumController::class, 'data_guru'])->name('admin_guru.index');
            Route::get('create', [KurikulumController::class, 'create_guru'])->name('admin_guru.create');
            Route::post('store', [KurikulumController::class, 'store_guru'])->name('admin_guru.store');
            Route::get('edit/{id}', [KurikulumController::class, 'edit_guru'])->name('admin_guru.edit');
            Route::post('update/{id}', [KurikulumController::class, 'update_guru'])->name('admin_guru.update');
            Route::delete('destroy/{id}', [KurikulumController::class, 'destroy_guru'])->name('admin_guru.destroy');
        });

        // === WALAS ===
        Route::get('admin/walas', [KurikulumController::class, 'data_walas'])->name('admin_walas.index');
    });
});

Route::post('/cek-username', [AuthController::class, 'cekUsername'])->name('cekUsername');
