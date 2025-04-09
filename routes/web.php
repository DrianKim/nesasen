<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataGuruController;
use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\DataWalasController;
use App\Http\Controllers\KurikulumController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard
Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');

Route::get('login',[AuthController::class,'index'])->name('login');
Route::post('login',[AuthController::class,'loginProses'])->name('loginProses');

Route::get('data_siswa',[DataSiswaController::class,'index'])->name('kurikulum_data_siswa');
Route::get('data_guru',[DataGuruController::class,'index'])->name('kurikulum_data_guru');
Route::get('data_walas',[DataWalasController::class,'index'])->name('kurikulum_data_walas');
