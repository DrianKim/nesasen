<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Login + Register View (gabung jadi 1 halaman)
Route::get('login', [AuthController::class, 'index'])->name('login');

// Proses Login
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');

// Proses Register
Route::post('register', [AuthController::class, 'registerProses'])->name('registerProses');

// Logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Data Kurikulum
Route::get('murid', [KurikulumController::class, 'data_murid'])->name('kurikulum_data_murid');
Route::get('data_guru', [KurikulumController::class, 'data_guru'])->name('kurikulum_data_guru');
Route::get('data_walas', [KurikulumController::class, 'data_walas'])->name('kurikulum_data_walas');
