<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="{{ asset('img/favicon.png') }}" rel="icon">
    <link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <header>
        <a href="#" class="back-btn" id="backBtn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{ asset('img/ls-logo.png') }}" alt="Nesasen Logo" />
        </div>
    </header>

    <div class="register-box">
        <div class="image">
            <img src="{{ asset('img/siswa-vector.png') }}" id="role-image"
                data-siswa="{{ asset('img/siswa-vector.png') }}" data-guru="{{ asset('img/guru-vector.png') }}"
                data-admin="{{ asset('img/atmin-vector.png') }}" alt="Gambar Peran" />
        </div>
        <div class="form-container">
            <h2 id="role-title">Reset Password Siswa</h2>
            <p>Masukkan email untuk reset password</p>

            <form id="register-form">
                <!-- Input Email -->
                <div class="input-group">
                    <input type="email" id="email" placeholder="Email" required />
                    <span class="input-icon"><i class="fa fa-envelope"></i></span>
                </div>

                <!-- Input OTP -->
                <div class="otp-group">
                    <input type="text" maxlength="1" id="otp1" class="otp-input" />
                    <input type="text" maxlength="1" id="otp2" class="otp-input" />
                    <input type="text" maxlength="1" id="otp3" class="otp-input" />
                    <input type="text" maxlength="1" id="otp4" class="otp-input" />
                    <input type="text" maxlength="1" id="otp5" class="otp-input" />
                    <input type="text" maxlength="1" id="otp6" class="otp-input" />
                    <a href="#" id="send-otp" class="otp-link">Kirim OTP</a>
                </div>

                <!-- Error message -->
                <div id="otp-error" class="error-message"></div>

                <!-- Success message -->
                <div id="otp-success" class="success-message"></div>

                {{-- <div class="checkbox-group">
                    <input type="checkbox" id="privacy" />
                    <label for="privacy">
                        Saya telah membaca dan menyetujui
                        <a href="#">Ketentuan Pengguna & Kebijakan Privasi</a>
                    </label>
                </div> --}}

                <button type="submit" id="register-button" disabled>RESET</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Made with ❤️ by P & R.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const role = urlParams.get("role");

            const otpInputs = document.querySelectorAll(".otp-input");
            const registerButton = document.querySelector("#register-button");
            const sendOtpButton = document.querySelector("#send-otp");
            const emailInput = document.getElementById("email");
            const otpError = document.getElementById("otp-error");
            const otpSuccess = document.getElementById("otp-success");


            // Autofill OTP logic
            otpInputs.forEach((input, index) => {
                input.addEventListener("input", function() {
                    if (this.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    validateForm();
                });

                input.addEventListener("keydown", function(e) {
                    if (e.key === "Backspace" && this.value === "" && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });


            function validateForm() {
                const isOtpFilled = Array.from(otpInputs).every(i => i.value.length === 1);
                registerButton.disabled = !isOtpFilled;
            }


            // Countdown & Kirim OTP
            let otpCooldown = false;
            let countdown = 60;
            sendOtpButton.addEventListener("click", function(e) {
                e.preventDefault();
                const email = emailInput.value.trim();

                if (!validateEmail(email)) {
                    alert("Email tidak valid.");
                    return;
                }

                if (otpCooldown) {
                    alert(`Tunggu ${countdown} detik untuk mengirim ulang OTP`);
                    return;
                }

                // Kirim OTP
                fetch('/forgot/send-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            email: email,
                            role: role
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert("OTP berhasil dikirim ke email.");
                            startCountdown();
                        } else {
                            alert("Silahkan masukkan email yang terdaftar di akun anda.");
                        }
                    })
                    .catch(err => {
                        console.error("Error kirim OTP:", err);
                        alert("Terjadi kesalahan saat mengirim OTP.");
                    });
            });

            function startCountdown() {
                otpCooldown = true;
                sendOtpButton.textContent = `${countdown} detik`;
                sendOtpButton.style.pointerEvents = "none";
                let interval = setInterval(() => {
                    countdown--;
                    sendOtpButton.textContent = `${countdown} detik`;
                    if (countdown <= 0) {
                        clearInterval(interval);
                        otpCooldown = false;
                        sendOtpButton.textContent = "Kirim OTP";
                        sendOtpButton.style.pointerEvents = "auto";
                        countdown = 60;
                    }
                }, 1000);
            }

            console.log("Redirect to reset-password with:", {
                email,
                role
            });

            // Validasi OTP saat submit
            registerButton.addEventListener("click", function(e) {
                e.preventDefault();
                const email = emailInput.value.trim();
                const otp = Array.from(otpInputs).map(i => i.value).join("");

                fetch('/forgot/verify-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            email: email,
                            otp: otp
                        })
                    })

                    .then(res => res.json())
                    .then(data => {
                        // Bersihin error duluan
                        otpError.textContent = "";
                        clearTimeout(window.otpErrorTimeout);

                        if (data.status === "success") {
                            otpSuccess.textContent = "OTP valid!";
                            otpSuccess.style.color = "green";



                            clearTimeout(window.otpSuccessTimeout);
                            window.otpSuccessTimeout = setTimeout(() => {
                                otpSuccess.textContent = "";
                            }, 3000);

                            setTimeout(() => {
                                window.location.href =
                                    `/reset-password?email=${encodeURIComponent(email)}&role=${role}`;
                            }, 1000);
                        } else if (data.status === "expired") {
                            otpError.textContent = "OTP sudah kadaluarsa. Silakan kirim ulang.";
                            otpError.style.color = "red";
                            window.otpErrorTimeout = setTimeout(() => {
                                otpError.textContent = "";
                            }, 3000);
                        } else if (data.status === "invalid") {
                            otpError.textContent = "OTP salah. Coba lagi.";
                            otpError.style.color = "red";
                            window.otpErrorTimeout = setTimeout(() => {
                                otpError.textContent = "";
                            }, 3000);
                        } else {
                            otpError.textContent = "Terjadi kesalahan. Silakan coba lagi.";
                            otpError.style.color = "red";
                            window.otpErrorTimeout = setTimeout(() => {
                                otpError.textContent = "";
                            }, 3000);
                        }
                    })
            });

            // Update gambar dan heading
            const roleImage = document.getElementById("role-image");
            const roleTitle = document.getElementById("role-title");

            if (role === "siswa") {
                roleTitle.textContent = "Reset Password Siswa";
                roleImage.src = roleImage.dataset.siswa;
            } else if (role === "guru") {
                roleTitle.textContent = "Reset Password Guru";
                roleImage.src = roleImage.dataset.guru;
            } else if (role === "admin") {
                roleTitle.textContent = "Reset Password Admin";
                roleImage.src = roleImage.dataset.admin;
            }

            // Back button
            const backBtn = document.getElementById("backBtn");
            backBtn.addEventListener("click", function(e) {
                e.preventDefault();
                window.location.href = `/login?role=${role}`;
            });

            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(String(email).toLowerCase());
            }
        });
    </script>

</body>

</html>
