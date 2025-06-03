<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Akun</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon">
</head>

<body>
    <header>
        <div class="logo">
            <img src="{{ asset('img/ls-logo.png') }}" alt="Nesasen Logo" />
        </div>
    </header>

    <div class="register-box">
        <div class="image">
            <img id="role-image" alt="Role Image" src="{{ asset('img/siswa-vector.png') }}"
                data-siswa="{{ asset('img/siswa-vector.png') }}" data-guru="{{ asset('img/guru-vector.png') }}" />
        </div>
        <div class="form-container">
            <h2 id="role-title">Daftar Sebagai Siswa</h2>
            <p>Masukkan username dan password untuk akun Anda</p>

            <form id="register-form" action="{{ route('register.user.store') }}" method="POST">
                @csrf

                <!-- Input Username -->
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required
                        class="@error('username') is-invalid @enderror" />
                    <span class="input-icon3">
                        <i class="fa fa-user"></i>
                    </span>
                    <small>
                        @error('username')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Input Password -->
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required
                        class="@error('password') is-invalid @enderror" />
                    <span class="input-icon3">
                        <i class="fa fa-lock"></i>
                    </span>
                    <span class="eye-icon" id="toggle-password">
                        <i class="fa fa-eye"></i>
                    </span>
                    <small>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </small>
                </div>

                <!-- Input Confirm Password -->
                <div class="input-group">
                    <label for="confirm-password">Konfirmasi Password</label>
                    <input type="password" id="confirm-password" name="password_confirmation"
                        placeholder="Konfirmasi Password" required />
                    <span class="input-icon3">
                        <i class="fa fa-lock"></i>
                    </span>
                    <span class="eye-icon" id="toggle-confirm-password">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

                <input type="hidden" name="role" id="roleInput" value="">
                <input type="hidden" id="email" name="email" value="{{ session('register_email') }}" readonly>
                <input type="hidden" name="nama" value="{{ session('register_nama') }}">
                <input type="hidden" name="tanggal_lahir" value="{{ session('register_tanggal_lahir') }}">
                <input type="hidden" name="no_hp" value="{{ session('register_no_hp') }}">

                <!-- Error message for password mismatch -->
                <div id="password-error" class="error-message"></div>

                <button class="button-regist3" type="submit" id="register-button" disabled>
                    DAFTAR
                </button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Made with ❤️ by P & R.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil role dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const role = urlParams.get("role");

            document.getElementById("roleInput").value = role;

            // Ganti heading dan gambar sesuai role
            const heading = document.getElementById("role-title");
            const roleImage = document.getElementById("role-image");

            if (role === "siswa") {
                heading.textContent = "Daftar Sebagai Siswa";
                roleImage.src = roleImage.dataset.siswa;
            } else if (role === "guru") {
                heading.textContent = "Daftar Sebagai Guru";
                roleImage.src = roleImage.dataset.guru;
            }

            // Toggle visibility untuk password
            const togglePassword = document.querySelector("#toggle-password");
            const passwordInput = document.querySelector("#password");

            togglePassword.addEventListener("click", function() {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });

            // Toggle visibility untuk konfirmasi password
            const toggleConfirmPassword = document.querySelector("#toggle-confirm-password");
            const confirmPasswordInput = document.querySelector("#confirm-password");

            toggleConfirmPassword.addEventListener("click", function() {
                const type = confirmPasswordInput.type === "password" ? "text" : "password";
                confirmPasswordInput.type = type;
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });

            // Validasi password dan konfirmasi password cocok
            const registerButton = document.querySelector("#register-button");
            const passwordError = document.getElementById("password-error");

            confirmPasswordInput.addEventListener("input", function() {
                if (passwordInput.value === confirmPasswordInput.value) {
                    passwordError.textContent = "";
                    registerButton.disabled = false;
                } else {
                    passwordError.textContent = "Password dan Konfirmasi Password tidak cocok";
                    passwordError.style.color = "red";
                    registerButton.disabled = true;
                }
            });

            // Submit form -> redirect ke login sesuai role
            document.getElementById("register-form").addEventListener("submit", function(e) {});
        });
    </script>

</body>

</html>
