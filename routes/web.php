<?php

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WalasController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PengajarController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Http\Request;

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Select Role Page
Route::get('/pilih-role', function () {
    return view('auth.select-role');
})->name('selectRole');

// Login Page
Route::get('/login', [AuthController::class, 'index'])->name('login');
// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// Regist OTP Page
Route::get('/regist-otp', function () {
    return view('auth.regist-otp');
})->name('registerOtp');

// Regist Data Page
Route::get('/regist-data', function () {
    return view('auth.regist-data');
})->name('registerData');


// Login
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::get('/regist-user', [RegisterController::class, 'showRegisterUser'])->name('register.user');
Route::post('/regist-data', [RegisterController::class, 'simpanData'])->name('register.data.store');
Route::post('/register/user', [RegisterController::class, 'simpanAkun'])->name('register.user.store');

// Otp
Route::post('/send-otp', [OtpController::class, 'kirimOtp'])->name('sendOtp');
Route::post('/verifikasi-otp', [OtpController::class, 'verifikasiOtp'])->name('verifikasiOtp');

// Forgot Password
Route::get('/forgot-password', function (Request $request) {
    $role = $request->query('role');

    if ($role === 'admin') {
        return redirect()->route('forgot.reset', ['role' => 'admin']);
    } else {
        return app(ForgotPasswordController::class)->showOtpPage($request);
    }
})->name('forgot.password');

Route::post('/forgot/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('forgot.sendOtp');
Route::post('/forgot/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot.verifyOtp');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('forgot.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('forgot.reset.store');



Route::middleware(['isLogin'])->group(function () {

    // profil
    Route::get('profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::get('profil/edit', [ProfilController::class, 'index'])->name('profil.edit');
    Route::post('profil/update', [ProfilController::class, 'update'])->name('profil.update');

    // rekap presensi
    Route::get('rekap_presensi', [WalasController::class, 'rekap_presensi_index'])->name('rekap_presensi.index');

    Route::get('jadwal_mengajar', [PengajarController::class, 'index_jadwal_mengajar'])->name('jadwal_mengajar.index');

    Route::get('jadwal_pelajaran', [SiswaController::class, 'index_jadwal_pelajaran'])->name('jadwal_pelajaran.index');

    Route::middleware(['auth', 'isGuru'])->group(function () {});

    Route::middleware(['auth', 'isWalas'])->group(function () {});

    // siswa
    // beranda
    Route::get('siswa.beranda', [SiswaController::class, 'beranda_index'])->name('siswa.beranda');

    // presensi
    Route::get('siswa/presensi', [SiswaController::class, 'presensi_index'])->name('siswa.presensi');

    // izin
    Route::get('siswa/izin', [SiswaController::class, 'izin_index'])->name('siswa.izin');
    Route::post('siswa/izin/store', [SiswaController::class, 'izin_store'])->name('siswa.izin.store');

    // kelasKu
    Route::get('siswa/kelasKu', [SiswaController::class, 'index_kelasKu'])->name('siswa.kelasKu.index');

    // jadwal
    Route::get('siswa/jadwal', [SiswaController::class, 'index_jadwal'])->name('siswa.jadwal');

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // umum kelas
        Route::get('admin/kelas', [AdminController::class, 'index_kelas'])->name('admin_kelas.index');
        Route::get('admin/kelas/filter', [AdminController::class, 'index_kelas'])->name('admin_kelas.filter');
        Route::get('admin/kelas/create', [AdminController::class, 'create_kelas'])->name('admin_kelas.create');
        Route::post('admin/kelas/store', [AdminController::class, 'store_kelas'])->name('admin_kelas.store');
        Route::get('admin/kelas/edit/{id}', [AdminController::class, 'edit_kelas'])->name('admin_kelas.edit');
        Route::post('admin/kelas/update/{id}', [AdminController::class, 'update_kelas'])->name('admin_kelas.update');
        Route::delete('admin/kelas/destroy/{id}', [AdminController::class, 'destroy_kelas'])->name('admin_kelas.destroy');
        Route::delete('admin/kelas/bulk_action', [AdminController::class, 'bulkAction_kelas'])->name('admin_kelas.bulk_action');

        // umum kelasKu
        Route::get('admin/kelasKu', [AdminController::class, 'index_kelasKu'])->name('admin_kelasKu.index');
        Route::get('admin/kelasKu/filter', [AdminController::class, 'index_kelasKu'])->name('admin_kelasKu.filter');
        Route::get('admin/kelasKu/create', [AdminController::class, 'create_kelasKu'])->name('admin_kelasKu.create');
        Route::post('admin/kelasKu/store', [AdminController::class, 'store_kelasKu'])->name('admin_kelasKu.store');
        Route::get('admin/kelasKu/edit/{id}', [AdminController::class, 'edit_kelasKu'])->name('admin_kelasKu.edit');
        Route::post('admin/kelasKu/update/{id}', [AdminController::class, 'update_kelasKu'])->name('admin_kelasKu.update');
        Route::delete('admin/kelasKu/destroy/{id}', [AdminController::class, 'destroy_kelasKu'])->name('admin_kelasKu.destroy');
        Route::delete('admin/kelasKu/bulk_action', [AdminController::class, 'bulkAction_kelasKu'])->name('admin_kelasKu.bulk_action');

        // siswa
        Route::get('admin/siswa', [AdminController::class, 'index_siswa'])->name('admin_siswa.index');
        Route::get('admin/siswa/filter', [AdminController::class, 'index_siswa'])->name('admin_siswa.filter');
        Route::get('admin/siswa/create', [AdminController::class, 'create_siswa'])->name('admin_siswa.create');
        Route::post('admin/siswa/store', [AdminController::class, 'store_siswa'])->name('admin_siswa.store');
        Route::post('admin/siswa/inline-update/{$id}', [AdminController::class, 'inline_update_siswa'])->name('admin_siswa.inline_update');
        Route::get('admin/siswa/edit/{id}', [AdminController::class, 'edit_siswa'])->name('admin_siswa.edit');
        Route::post('admin/siswa/update/{id}', [AdminController::class, 'update_siswa'])->name('admin_siswa.update');
        Route::delete('admin/siswa/destroy/{id}', [AdminController::class, 'destroy_siswa'])->name('admin_siswa.destroy');
        Route::delete('admin/siswa/bulk_action', [AdminController::class, 'bulkAction_siswa'])->name('admin_siswa.bulk_action');

        // guru
        Route::get('admin/guru', [AdminController::class, 'index_guru'])->name('admin_guru.index');
        Route::get('admin/guru/filter', [AdminController::class, 'index_guru'])->name('admin_guru.filter');
        Route::get('admin/guru/create', [AdminController::class, 'create_guru'])->name('admin_guru.create');
        Route::post('admin/guru/store', [AdminController::class, 'store_guru'])->name('admin_guru.store');
        Route::get('admin/guru/edit/{id}', [AdminController::class, 'edit_guru'])->name('admin_guru.edit');
        Route::post('admin/guru/update/{id}', [AdminController::class, 'update_guru'])->name('admin_guru.update');
        Route::delete('admin/guru/destroy/{id}', [AdminController::class, 'destroy_guru'])->name('admin_guru.destroy');
        Route::delete('admin/guru/bulk_action', [AdminController::class, 'bulkAction_guru'])->name('admin_guru.bulk_action');

        Route::get('admin/jadwal_pelajaran', [AdminController::class, 'index_jadwal_pelajaran'])->name('admin_jadwal_pelajaran.index');

        // umum jurusan
        Route::get('admin/jurusan', [AdminController::class, 'index_jurusan'])->name('admin_jurusan.index');
        Route::get('admin/jurusan/filter', [AdminController::class, 'index_jurusan'])->name('admin_jurusan.filter');
        Route::get('admin/jurusan/create', [AdminController::class, 'create_jurusan'])->name('admin_jurusan.create');
        Route::post('admin/jurusan/store', [AdminController::class, 'store_jurusan'])->name('admin_jurusan.store');
        Route::get('admin/jurusan/edit/{id}', [AdminController::class, 'edit_jurusan'])->name('admin_jurusan.edit');
        Route::post('admin/jurusan/update/{id}', [AdminController::class, 'update_jurusan'])->name('admin_jurusan.update');
        Route::delete('admin/jurusan/destroy/{id}', [AdminController::class, 'destroy_jurusan'])->name('admin_jurusan.destroy');
        Route::delete('admin/jurusan/bulk_action', [AdminController::class, 'bulkAction_jurusan'])->name('admin_jurusan.bulk_action');

        // umum mapel
        Route::get('admin/mapel', [AdminController::class, 'index_mapel'])->name('admin_mapel.index');
        Route::get('admin/mapel/filter', [AdminController::class, 'index_mapel'])->name('admin_mapel.filter');
        Route::get('admin/mapel/create', [AdminController::class, 'create_mapel'])->name('admin_mapel.create');
        Route::post('admin/mapel/store', [AdminController::class, 'store_mapel'])->name('admin_mapel.store');
        Route::get('admin/mapel/edit/{id}', [AdminController::class, 'edit_mapel'])->name('admin_mapel.edit');
        Route::post('admin/mapel/update/{id}', [AdminController::class, 'update_mapel'])->name('admin_mapel.update');
        Route::delete('admin/mapel/destroy/{id}', [AdminController::class, 'destroy_mapel'])->name('admin_mapel.destroy');
        Route::delete('admin/mapel/bulk_action', [AdminController::class, 'bulkAction_mapel'])->name('admin_mapel.bulk_action');

        // presensi siswa
        Route::get('admin/presensi/siswa', [AdminController::class, 'index_presensi_siswa'])->name('admin_presensi_siswa.index');
        Route::get('admin/presensi/siswa/filter', [AdminController::class, 'index_presensi_siswa'])->name('admin_presensi_siswa.filter');
        Route::get('admin/presensi/siswa/create', [AdminController::class, 'create_presensi_siswa'])->name('admin_presensi_siswa.create');
        Route::post('admin/presensi/siswa/store', [AdminController::class, 'store_presensi_siswa'])->name('admin_presensi_siswa.store');
        Route::get('admin/presensi/siswa/edit/{id}', [AdminController::class, 'edit_presensi_siswa'])->name('admin_presensi_siswa.edit');
        Route::post('admin/presensi/siswa/update/{id}', [AdminController::class, 'update_presensi_siswa'])->name('admin_presensi_siswa.update');
        Route::delete('admin/presensi/siswa/destroy/{id}', [AdminController::class, 'destroy_presensi_siswa'])->name('admin_presensi_siswa.destroy');
        Route::delete('admin/presensi/siswa/bulk_action', [AdminController::class, 'bulkAction_presensi_siswa'])->name('admin_presensi_siswa.bulk_action');

        // presensi guru
        Route::get('admin/presensi/guru', [AdminController::class, 'index_presensi_guru'])->name('admin_presensi_guru.index');
        Route::get('admin/presensi/guru/filter', [AdminController::class, 'index_presensi_guru'])->name('admin_presensi_guru.filter');
        Route::get('admin/presensi/guru/create', [AdminController::class, 'create_presensi_guru'])->name('admin_presensi_guru.create');
        Route::post('admin/presensi/guru/store', [AdminController::class, 'store_presensi_guru'])->name('admin_presensi_guru.store');
        Route::get('admin/presensi/guru/edit/{id}', [AdminController::class, 'edit_presensi_guru'])->name('admin_presensi_guru.edit');
        Route::post('admin/presensi/guru/update/{id}', [AdminController::class, 'update_presensi_guru'])->name('admin_presensi_guru.update');
        Route::delete('admin/presensi/guru/destroy/{id}', [AdminController::class, 'destroy_presensi_guru'])->name('admin_presensi_guru.destroy');
        Route::delete('admin/presensi/guru/bulk_action', [AdminController::class, 'bulkAction_presensi_guru'])->name('admin_presensi_guru.bulk_action');

        // presensi per mapel
        Route::get('admin/presensi/per_mapel', [AdminController::class, 'index_presensi_per_mapel'])->name('admin_presensi_per_mapel.index');

        // izin siswa
        Route::get('admin/izin/siswa', [AdminController::class, 'index_izin_siswa'])->name('admin_izin_siswa.index');
        Route::get('admin/izin/siswa/filter', [AdminController::class, 'index_izin_siswa'])->name('admin_izin_siswa.filter');
        Route::get('admin/izin/siswa/create', [AdminController::class, 'create_izin_siswa'])->name('admin_izin_siswa.create');
        Route::post('admin/izin/siswa/store', [AdminController::class, 'store_izin_siswa'])->name('admin_izin_siswa.store');
        Route::get('admin/izin/siswa/edit/{id}', [AdminController::class, 'edit_izin_siswa'])->name('admin_izin_siswa.edit');
        Route::post('admin/izin/siswa/update/{id}', [AdminController::class, 'update_izin_siswa'])->name('admin_izin_siswa.update');
        Route::delete('admin/izin/siswa/destroy/{id}', [AdminController::class, 'destroy_izin_siswa'])->name('admin_izin_siswa.destroy');
        Route::delete('admin/izin/siswa/bulk_action', [AdminController::class, 'bulkAction_izin_siswa'])->name('admin_izin_siswa.bulk_action');

        // izin guru
        Route::get('admin/izin/guru', [AdminController::class, 'index_izin_guru'])->name('admin_izin_guru.index');
        Route::get('admin/izin/guru/filter', [AdminController::class, 'filter_izin_guru'])->name('admin_izin_guru.filter');
        Route::get('admin/izin/guru/create', [AdminController::class, 'create_izin_guru'])->name('admin_izin_guru.create');
        Route::post('admin/izin/guru/store', [AdminController::class, 'store_izin_guru'])->name('admin_izin_guru.store');
        Route::get('admin/izin/guru/edit/{id}', [AdminController::class, 'edit_izin_guru'])->name('admin_izin_guru.edit');
        Route::post('admin/izin/guru/update/{id}', [AdminController::class, 'update_izin_guru'])->name('admin_izin_guru.update');
        Route::delete('admin/izin/guru/destroy/{id}', [AdminController::class, 'destroy_izin_guru'])->name('admin_izin_guru.destroy');
        Route::delete('admin/izin/guru/bulk_action', [AdminController::class, 'bulkAction_izin_guru'])->name('admin_izin_guru.bulk_action');

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
