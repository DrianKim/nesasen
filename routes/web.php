<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Login
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::post('register', [AuthController::class, 'registerProses'])->name('registerProses');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    // murid
    Route::get('admin_murid', [KurikulumController::class, 'data_murid'])->name('admin_murid');
    Route::get('admin_murid_create', [KurikulumController::class, 'create_murid'])->name('admin_murid_create');

    Route::get('admin_guru', [KurikulumController::class, 'data_guru'])->name('admin_guru');
    Route::get('admin_walas', [KurikulumController::class, 'data_walas'])->name('admin_walas');
});
