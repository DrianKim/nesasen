<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style-register.css') }}">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link href="{{ asset('enno/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('enno/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
    <!-- Header dengan Logo dan Panah Kembali -->
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
        <img src="{{ asset('img/siswa-vector.png') }}"
          id="role-image"
          data-siswa="{{ asset('img/siswa-vector.png') }}"
          data-guru="{{ asset('img/guru-vector.png') }}"
          alt="Gambar Peran" />
      </div>
      <div class="form-container">
        <h2 id="role-title">Daftar Sebagai Siswa</h2>
        <p>Masukkan email untuk verifikasi</p>

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

          <div class="checkbox-group">
            <input type="checkbox" id="privacy" />
            <label for="privacy">
              Saya telah membaca dan menyetujui
              <a href="#">Ketentuan Pengguna & Kebijakan Privasi</a>
            </label>
          </div>

          <button type="submit" id="register-button" disabled>DAFTAR</button>
        </form>
      </div>
    </div>

    <footer>
      <p>&copy; 2025 Made with ❤️ by P & R.</p>
    </footer>

    <script>  
      document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const role = urlParams.get("role");

        if (role === "admin") {
            alert("Admin tidak bisa mendaftar, silakan login.");
            window.location.href = `/login?role=admin`;
            return;
        }

        const otpInputs = document.querySelectorAll(".otp-input");
        const registerButton = document.querySelector("#register-button");
        const sendOtpButton = document.querySelector("#send-otp");
        const checkbox = document.querySelector("#privacy");
        const emailInput = document.getElementById("email");
        const otpError = document.getElementById("otp-error");
        const otpSuccess = document.getElementById("otp-success");

        // Autofill OTP logic
        otpInputs.forEach((input, index) => {
            input.addEventListener("input", function () {
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
                }
                validateForm();
            });

            input.addEventListener("keydown", function (e) {
                if (e.key === "Backspace" && this.value === "" && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        checkbox.addEventListener("change", validateForm);

        function validateForm() {
            const isOtpFilled = Array.from(otpInputs).every(i => i.value.length === 1);
            registerButton.disabled = !(isOtpFilled && checkbox.checked);
        }

        // Countdown & Kirim OTP
        let otpCooldown = false;
        let countdown = 60;
        sendOtpButton.addEventListener("click", function (e) {
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
            fetch('/send-otp', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({ email })
            })
            .then(res => res.json())
            .then(data => {
              if (data.status === "success") {
                alert("OTP berhasil dikirim ke email.");
                startCountdown();
              } else {
                alert("Gagal mengirim OTP.");
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

        // Validasi OTP saat submit
        registerButton.addEventListener("click", function (e) {
            e.preventDefault();
            const email = emailInput.value.trim();
            const otp = Array.from(otpInputs).map(i => i.value).join("");

            fetch('/verifikasi-otp', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              credentials: 'same-origin',
              body: JSON.stringify({ email: email, otp: otp })
            })

            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    otpSuccess.textContent = "OTP valid!";
                    otpSuccess.style.color = "green";

                    setTimeout(() => {
                        window.location.href = `/regist-data?role=${role}&email=${encodeURIComponent(email)}`;
                    }, 1000);
                } else if (data.status === "expired") {
                    otpError.textContent = "OTP sudah kadaluarsa. Silakan kirim ulang.";
                    otpError.style.color = "red";
                } else if (data.status === "invalid") {
                    otpError.textContent = "OTP salah. Coba lagi.";
                    otpError.style.color = "red";
                } else {
                    otpError.textContent = "Terjadi kesalahan. Silakan coba lagi.";
                    otpError.style.color = "red";
                }
            })
        });

        // Update gambar dan heading
        const roleImage = document.getElementById("role-image");
        const roleTitle = document.getElementById("role-title");

        if (role === "siswa") {
            roleTitle.textContent = "Daftar Sebagai Siswa";
            roleImage.src = roleImage.dataset.siswa;
        } else if (role === "guru") {
            roleTitle.textContent = "Daftar Sebagai Guru";
            roleImage.src = roleImage.dataset.guru;
        }

        // Back button
        const backBtn = document.getElementById("backBtn");
        backBtn.addEventListener("click", function (e) {
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
