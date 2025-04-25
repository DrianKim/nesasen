<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Manajemen Kelas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
<div class="container" id="container">

    <!-- SIGN IN -->
    <div class="form-container sign-in">
        <form method="POST" action="{{ route('loginProses') }}">
            @csrf
            <h1>Sign In</h1>
            <span>Masuk dengan username & password</span>
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Sign In</button>
            <a class="small back-home" href="{{ route('welcome') }}">← Kembali ke Beranda</a>
        </form>
    </div>

    <!-- SIGN UP (Step 1 & 2) -->
    <div class="form-container sign-up">
        <div class="signup-wrapper">
            <!-- Step 1 -->
            <form id="signupStep1" class="signup-step active" onsubmit="return false">
                <h1>Sign Up</h1>
                <span>Masukkan username yang diberikan oleh admin</span>
                <input type="text" id="usernameCheck" placeholder="Username" required />
                <button type="button" id="checkUsernameBtn">Cek Username</button>
                <span id="usernameStatus" style="font-size: 12px; margin-top: 8px;"></span>
            </form>

            <!-- Step 2 -->
            <form id="signupStep2" class="signup-step" method="POST" action="{{ route('registerProses') }}">
                @csrf
                <input type="hidden" name="username" id="confirmedUsername" />
                <input type="hidden" name="role" id="roleHidden" />
                
                <p id="roleText" style="font-weight: bold;"></p>
                
                <input type="text" name="nisnip" id="nisnipInput" placeholder="NIS/NIP" required />
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" required max="">
                
                <input type="email" name="email" placeholder="Email" required />
                
                <select name="jenis_kelamin" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            
                {{-- Tambahan: Password --}}
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required />
                
                <button type="submit">Lanjutkan Pendaftaran</button>
            </form>
            
        </div>
    </div>

    <!-- TOGGLE -->
    <div class="toggle-container">
        <div class="toggle" id="toggleEffect">
            <div class="toggle-panel toggle-left" id="dynamicToggleText">
                <h1>Selamat Datang!</h1>
                <p>Silakan login untuk melanjutkan</p>
                <button class="hidden" id="login">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Belum Punya Akun?</h1>
                <p>Gunakan username dari admin untuk daftar</p>
                <button class="hidden" id="register">Sign Up</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputTanggal = document.getElementById("tanggal_lahir");
        const today = new Date();
        const minAge = 10;

        // Hitung tanggal maksimal (10 tahun ke belakang)
        const maxDate = new Date(today.getFullYear() - minAge, today.getMonth(), today.getDate());
        const formatted = maxDate.toISOString().split("T")[0];

        inputTanggal.max = formatted;
    });
</script>

</body>
</html>
