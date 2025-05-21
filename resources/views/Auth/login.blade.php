<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="{{ asset('enno/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('enno/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
</head>

<body>
    <!-- Header: Logo dan Panah Kembali -->
    <header>
        <a href="{{ route('selectRole') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{ asset('img/ls-logo.png') }}" alt="Nesasen Logo" />
        </div>
    </header>

    <div class="login-box">
        <!-- Gambar Login -->
        <div class="image">
            <img id="login-image" src="{{ asset('img/siswa-vector.png') }}"
                data-admin="{{ asset('img/atmin-vector.png') }}" data-guru="{{ asset('img/guru-vector.png') }}"
                data-murid="{{ asset('img/siswa-vector.png') }}" alt="Login Image" />
        </div>

        <!-- Form Login -->
        <div class="form-container">
            <h2 id="login-heading">Login Siswa</h2>
            <div class="signup-link">
                <p>
                    Belum Memiliki Akun?
                    <a href="{{ route('registerOtp') }}?role=" id="signup-link">Daftar Sekarang</a>
                </p>
            </div>

            <form method="POST" action="{{ route('loginProses') }}">
                @csrf

                <div class="input-group">
                    <input type="text" id="username" name="username" placeholder="Username"
                        value="{{ old('username') }}" required />
                    <span class="input-icon"><i class="fa fa-user"></i></span>
                    @error('username')
                        <span style="color:red;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    <span class="input-icon"><i class="fa fa-lock"></i></span>
                    <span class="eye-icon" id="toggle-password">
                        <i class="fa fa-eye"></i>
                    </span>
                    @error('password')
                        <span style="color:red;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Forgot Password -->
                <a href="#" class="forgot-password">Lupa Password?</a>

                <div class="checkbox-group">
                    <input type="checkbox" id="privacy" />
                    <label for="privacy">
                        Dengan login menggunakan username atau metode lain, saya setuju
                        dengan <a href="#">Ketentuan Pengguna & Kebijakan Privasi</a>
                    </label>
                </div>

                <input type="hidden" name="role" id="roleInput" value="">

                <button type="submit" id="login-button" disabled>LOGIN</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Made with ❤️ by P & R.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil role duluan
            const urlParams = new URLSearchParams(window.location.search);
            const role = urlParams.get("role");

            const roleInput = document.getElementById("roleInput");
            if (roleInput) {
                roleInput.value = role;
            }

            // Update link pendaftaran sesuai role
            const signupAnchor = document.getElementById("signup-link");
            if (signupAnchor && role !== "admin") {
                signupAnchor.href = signupAnchor.href + role;
            }

            // Elements for heading, image and signup link
            const loginImage = document.getElementById("login-image");
            const heading = document.getElementById("login-heading");
            const signupLink = document.querySelector(".signup-link");

            // Ganti heading, gambar, dan signup link sesuai role
            if (role === "admin") {
                heading.textContent = "Login Admin Sekolah";
                loginImage.src = loginImage.dataset.admin;
                signupLink.style.display = "none";
            } else if (role === "guru") {
                heading.textContent = "Login Guru";
                loginImage.src = loginImage.dataset.guru;
            } else {
                heading.textContent = "Login Siswa";
                loginImage.src = loginImage.dataset.murid;
            }

            // Toggle Password Visibility
            const togglePassword = document.querySelector("#toggle-password");
            const passwordInput = document.querySelector("#password");

            togglePassword.addEventListener("click", function() {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });

            // Enable login button saat checkbox dicentang
            const checkbox = document.querySelector("#privacy");
            const loginButton = document.querySelector("#login-button");

            checkbox.addEventListener("change", function() {
                loginButton.disabled = !this.checked;
            });
        });
    </script>

    <script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

</body>

</html>
