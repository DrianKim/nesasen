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
        Route::get('admin/murid', [KurikulumController::class, 'data_murid'])->name('admin_murid');
        Route::get('admin/murid/create', [KurikulumController::class, 'create_murid'])->name('admin_murid.create');
        Route::post('admin/murid/store', [KurikulumController::class, 'store_murid'])->name('admin_murid.store');

        // guru
        Route::get('admin_guru', [KurikulumController::class, 'data_guru'])->name('admin_guru');
        Route::get('admin/guru/create', [KurikulumController::class, 'create_guru'])->name('admin_guru.create');
        Route::post('admin/guru/store', [KurikulumController::class, 'store_guru'])->name('admin_guru.store');

        Route::get('admin_walas'    , [KurikulumController::class, 'data_walas'])->name('admin_walas');
    });
});

// Login
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::post('register', [AuthController::class, 'registerProses'])->name('registerProses');
