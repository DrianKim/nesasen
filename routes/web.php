<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KurikulumController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard
Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');

Route::get('login',[AuthController::class,'index'])->name('login');
Route::post('login',[AuthController::class,'loginProses'])->name('loginProses');
Route::get('logout',[AuthController::class,'logout'])->name('logout');

Route::get('murid',[KurikulumController::class,'data_murid'])->name('kurikulum_data_murid');
Route::get('data_guru',[KurikulumController::class,'data_guru'])->name('kurikulum_data_guru');
Route::get('data_walas',[KurikulumController::class,'data_walas'])->name('kurikulum_data_walas');
