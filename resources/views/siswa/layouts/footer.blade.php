<!-- Custom scripts for all pages-->
{{-- <script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}"></script> --}}

<!-- Utility scripts -->
<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    console.log("jQuery version:", typeof jQuery !== "undefined" ? jQuery.fn.jquery : "jQuery NOT loaded");
    console.log("Select2 loaded:", typeof $.fn.select2 !== "undefined");
</script>

<script>
    const sideMenu = document.querySelector("aside");
    const menuBtn = document.getElementById("menu-btn");
    const closeBtn = document.getElementById("close-btn");
    const darkMode = document.querySelector(".dark-mode");

    // menuBtn?.addEventListener("click", () => (sideMenu.style.display = "block"));
    // closeBtn?.addEventListener("click", () => (sideMenu.style.display = "none"));

    menuBtn?.addEventListener("click", () => {
        sideMenu.classList.add("active");
    });

    closeBtn?.addEventListener("click", () => {
        sideMenu.classList.remove("active");
    });

    // Saat toggle diklik
    darkMode?.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode-variables");
        document.documentElement.classList.toggle("dark-mode-variables");

        const isDark = document.body.classList.contains("dark-mode-variables");
        localStorage.setItem("darkMode", isDark ? "true" : "false");

        darkMode.querySelector("span:nth-child(1)")?.classList.toggle("active", !isDark);
        darkMode.querySelector("span:nth-child(2)")?.classList.toggle("active", isDark);
    });

    if (localStorage.getItem("darkMode") === "true") {
        document.body.classList.add("dark-mode-variables");
        document.documentElement.classList.toggle("dark-mode-variables");

        const lightIcon = document.querySelector(".dark-mode span:nth-child(1)");
        const darkIcon = document.querySelector(".dark-mode span:nth-child(2)");

        lightIcon?.classList.remove("active");
        darkIcon?.classList.add("active");
    }

    window.addEventListener("load", () => {
        sessionStorage.removeItem("darkModeOverride");
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.getElementById('logout-btn-siswa');

        logoutBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            const isDark = document.body.classList.contains('dark-mode-variables');

            // sideMenu.style.display = "none";
            sideMenu.classList.remove("active");

            setTimeout(() => {
                Swal.fire({
                    title: 'Apakah Anda yakin ingin logout?',
                    text: "Anda akan keluar dari sesi saat ini.",
                    icon: 'warning',
                    showCancelButton: true,
                    iconColor: '#e7586e',
                    confirmButtonColor: '#e7586e',
                    cancelButtonColor: '#43c6c9',
                    confirmButtonText: 'Ya, logout!',
                    cancelButtonText: 'Batal',
                    background: isDark ? getComputedStyle(document.body)
                        .getPropertyValue(
                            '--color-background') : '#fff',
                    color: isDark ? getComputedStyle(document.body).getPropertyValue(
                        '--color-dark') : '#000',
                    customClass: {
                        popup: isDark ? 'swal-dark' : ''
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href =
                            "{{ route('logout') }}"; // Redirect ke route logout
                    }
                });
            }, 100);
        });
    });
</script>

@if (session('success'))
    <script>
        const isDark = document.body.classList.contains('dark-mode-variables')

        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            confirmButtonColor: '#43c6c9',
            background: isDark ? getComputedStyle(document.body).getPropertyValue('--color-background') : '#fff',
            color: isDark ? getComputedStyle(document.body).getPropertyValue('--color-dark') : '#000',
            customClass: {
                popup: isDark ? 'swal-dark' : ''
            }
        });
    </script>
@endif

@if (session('error'))
    <script>
        const isDark = document.body.classList.contains('dark-mode-variables')

        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
            confirmButtonColor: '#43c6c9',
            background: isDark ? getComputedStyle(document.body).getPropertyValue('--color-background') : '#fff',
            color: isDark ? getComputedStyle(document.body).getPropertyValue('--color-dark') : '#000',
            customClass: {
                popup: isDark ? 'swal-dark' : ''
            }
        });
    </script>
@endif
</body>

</html>
