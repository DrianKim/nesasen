document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("container");
    const registerBtn = document.getElementById("register");
    const loginBtn = document.getElementById("login");
    const checkBtn = document.getElementById("checkUsernameBtn");
    const usernameCheck = document.getElementById("usernameCheck");
    const usernameStatus = document.getElementById("usernameStatus");
    const confirmedUsername = document.getElementById("confirmedUsername");
    const roleText = document.getElementById("roleText");
    const roleInput = document.getElementById("roleHidden");
    const dateInput = document.querySelector('input[name="tanggal_lahir"]');
    const step1 = document.getElementById("signupStep1");
    const step2 = document.getElementById("signupStep2");
    const toggleText = document.getElementById("dynamicToggleText");
    const mobileSwitchBtn = document.getElementById("mobileSwitchBtn");
    const signIn = document.querySelector(".sign-in");
    const signUp = document.querySelector(".sign-up");
    let isSignIn = true;
    if (dateInput) {
        const today = new Date();
        const minAge = 10;
        const maxDate = new Date(
            today.getFullYear() - minAge,
            today.getMonth(),
            today.getDate()
        );
        dateInput.max = maxDate.toISOString().split("T")[0];
    }
    registerBtn?.addEventListener("click", () => {
        container.classList.add("active");
        updateToggleToLogin();
    });
    loginBtn?.addEventListener("click", () => {
        container.classList.remove("active");
        resetToggleDefault();
    });
    if (window.innerWidth <= 768) {
        updateMobileState();
        mobileSwitchBtn?.addEventListener("click", () => {
            isSignIn = !isSignIn;
            updateMobileState();
        });
    }
    function updateMobileState() {
        if (isSignIn) {
            signIn.classList.add("active-mobile");
            signUp.classList.remove("active-mobile");
            step1.classList.add("active");
            step2.classList.remove("active");
            mobileSwitchBtn.textContent = "Sign Up";
        } else {
            signUp.classList.add("active-mobile");
            signIn.classList.remove("active-mobile");
            step1.classList.add("active");
            step2.classList.remove("active");
            mobileSwitchBtn.textContent = "Sign In";
        }
    }
    checkBtn?.addEventListener("click", () => {
        const username = usernameCheck.value.trim();
        if (!username) {
            usernameStatus.textContent = "Harap masukkan username.";
            usernameStatus.style.color = "red";
            return;
        }
        usernameStatus.textContent = "⏳ Mengecek username...";
        usernameStatus.style.color = "#333";
        fetch("/cek-username", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({ username }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "valid") {
                    confirmedUsername.value = data.username;
                    roleInput.value = data.role;
                    roleText.textContent = `Login sebagai: ${data.deskripsi}`;
                    step1.classList.remove("active");
                    step2.classList.add("active");
                    updateToggleToBack();
                    usernameStatus.textContent = "✅ Username tersedia.";
                    usernameStatus.style.color = "green";
                } else {
                    usernameStatus.textContent = "❌ Username tidak ditemukan.";
                    usernameStatus.style.color = "red";
                }
            })
            .catch((err) => {
                console.error("❌ Fetch error:", err);
                usernameStatus.textContent =
                    "❌ Terjadi kesalahan. Cek console.";
                usernameStatus.style.color = "red";
            });
    });
    function updateToggleToBack() {
        toggleText.innerHTML = `<h1>Inputan Salah?</h1><p>Klik tombol di bawah untuk cek username lagi.</p><button class="hidden" id="backToStep1">Cek Ulang Username</button>`;
        setTimeout(() => {
            const backBtn = document.getElementById("backToStep1");
            backBtn?.addEventListener("click", () => {
                step2.classList.remove("active");
                step1.classList.add("active");
                usernameCheck.value = "";
                usernameStatus.textContent = "";
                updateToggleToLogin();
            });
        }, 100);
    }
    function updateToggleToLogin() {
        toggleText.innerHTML = `<h1>Sudah punya akun?</h1><p>Klik tombol di bawah untuk kembali login.</p><button class="hidden" id="loginToggleInside">Sign In</button>`;
        setTimeout(() => {
            const loginToggleBtn = document.getElementById("loginToggleInside");
            loginToggleBtn?.addEventListener("click", () => {
                container.classList.remove("active");
                resetToggleDefault();
            });
        }, 100);
    }
    function resetToggleDefault() {
        toggleText.innerHTML = `<h1>Belum Punya Akun?</h1><p>Gunakan username dari admin untuk daftar</p><button class="hidden" id="register">Sign Up</button>`;
        setTimeout(() => {
            const registerToggleBtn = document.getElementById("register");
            registerToggleBtn?.addEventListener("click", () => {
                container.classList.add("active");
                updateToggleToLogin();
            });
        }, 100);
    }
});
