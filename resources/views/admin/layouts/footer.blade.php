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

    menuBtn.addEventListener("click", () => {
        sideMenu.style.display = "block";
    });

    closeBtn.addEventListener("click", () => {
        sideMenu.style.display = "none";
    });

    darkMode.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode-variables");
        darkMode.querySelector("span:nth-child(1)").classList.toggle("active");
        darkMode.querySelector("span:nth-child(2)").classList.toggle("active");
    });
</script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('error') }}',
        });
    </script>
@endif
</body>

</html>
