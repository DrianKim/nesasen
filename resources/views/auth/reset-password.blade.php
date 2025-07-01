<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lupa Password</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon">
</head>

<body>
    <header>
        <a href="{{ route('login', ['role' => request('role')]) }}" class="back-btn"><i
                class="fas fa-arrow-left"></i></a>
        <div class="logo">
            <img src="{{ asset('img/ls-logo.png') }}" alt="Logo Sekolah" />
        </div>
    </header>

    <div class="register-box">
        <div class="image">
            <img id="role-image" src="{{ asset('img/siswa-vector.png') }}"
                data-siswa="{{ asset('img/siswa-vector.png') }}" data-guru="{{ asset('img/guru-vector.png') }}"
                data-admin="{{ asset('img/atmin-vector.png') }}" alt="Ilustrasi Login" />
        </div>

        <div class="form-container">
            <h2 id="role-title">Reset Password</h2>

            <form method="POST" action="{{ route('forgot.reset.store') }}">
                @csrf

                <input type="hidden" name="role" value="{{ $role }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Input Password Baru -->

                @if (request('role') !== 'admin')
                    <div class="input-group">
                        <label for="username">Username Kamu</label>
                        <input type="text" name="username" value="{{ $username }}" readonly
                            class="form-control">
                        <span class="input-icon2"><i class="fa fa-user"></i></span>
                    </div>
                @else
                    <!-- Admin input manual -->
                    <div class="input-group">
                        <label for="username">Username Admin</label>
                        <input type="text" name="username" value="{{ old('username') }}"
                            placeholder="Username Admin" required>
                        <span class="input-icon2"><i class="fa fa-user"></i></span>
                    </div>
                @endif

                <div class="input-group">
                    <label for="new-password">Password Baru</label>
                    <input type="password" id="new-password" name="new_password" placeholder="Password Baru" required />
                    <span class="input-icon3">
                        <i class="fa fa-lock"></i>
                    </span>
                    <span class="eye-icon" id="toggle-new-password">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

                <div class="input-group">
                    <label for="confirm-new-password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm-new-password" name="new_password_confirmation"
                        placeholder="Konfirmasi Password Baru" required />
                    <span class="input-icon3">
                        <i class="fa fa-lock"></i>
                    </span>
                    <span class="eye-icon" id="toggle-confirm-new-password">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>

                <!-- Error message for password mismatch -->
                <div id="password-error" class="error-message"></div>

                <button type="submit" id="reset-button">RESET PASSWORD</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Made with ❤️ by P & R.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const urlParams = new URLSearchParams(window.location.search);
            const role = urlParams.get("role");

            // Ganti gambar dan heading
            const roleTitle = document.getElementById("role-title");
            const roleImage = document.getElementById("role-image");
            if (role === "guru") {
                roleTitle.textContent = "Reset Password Guru";
                roleImage.src = roleImage.dataset.guru;
            } else if (role === "admin") {
                roleTitle.textContent = "Reset Password Admin";
                roleImage.src = roleImage.dataset.admin;
            } else {
                roleTitle.textContent = "Reset Password Siswa";
                roleImage.src = roleImage.dataset.siswa;
            }


            // Perpassword-an
            const toggleNewPassword = document.querySelector("#toggle-new-password");
            const newPasswordInput = document.querySelector("#new-password");
            const toggleConfirmNewPassword = document.querySelector("#toggle-confirm-new-password");
            const confirmNewPasswordInput = document.querySelector("#confirm-new-password");
            const submitButton = document.querySelector("#submit-button");
            const passwordError = document.getElementById("password-error");

            toggleNewPassword.addEventListener("click", function() {
                const type = newPasswordInput.type === "password" ? "text" : "password";
                newPasswordInput.type = type;
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });

            toggleConfirmNewPassword.addEventListener("click", function() {
                const type = confirmNewPasswordInput.type === "password" ? "text" : "password";
                confirmNewPasswordInput.type = type;
                this.querySelector("i").classList.toggle("fa-eye-slash");
            });

            confirmNewPasswordInput.addEventListener("input", function() {
                if (newPasswordInput.value === confirmNewPasswordInput.value) {
                    passwordError.textContent = "";
                    submitButton.disabled = false;
                } else {
                    passwordError.textContent = "Password dan Konfirmasi Password tidak cocok";
                    passwordError.style.color = "red";
                    submitButton.disabled = true;
                }
            });

            // Kirim OTP
            const sendOtpBtn = document.getElementById("send-otp");
            const emailInput = document.getElementById("email");
            const otpSuccess = document.getElementById("otp-success");
            const otpError = document.getElementById("otp-error");

            sendOtpBtn.addEventListener("click", function(e) {
                e.preventDefault();

                const email = emailInput.value.trim();
                if (!email) return alert("Email wajib diisi");

                fetch("{{ route('sendOtp') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            otpSuccess.textContent = "OTP berhasil dikirim.";
                            otpSuccess.style.color = "green";
                        } else {
                            otpError.textContent = "Gagal mengirim OTP.";
                            otpError.style.color = "red";
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        otpError.textContent = "Terjadi kesalahan.";
                        otpError.style.color = "red";
                    });
            });
        });
    </script>
</body>

</html>
