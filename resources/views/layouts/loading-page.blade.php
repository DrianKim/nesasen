<!-- Loading Overlay -->
<div id="page-loading-overlay">
    <div class="loading-spinner">
        <div class="board">Mengantar Anda ke Halaman Tujuan...</div>

        <div class="graduation-cap"></div>

        <svg viewBox="0 0 100 100" class="spinner-p" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="40" stroke="#3498db" stroke-width="8" fill="none"
                stroke-linecap="round" stroke-dasharray="200" stroke-dashoffset="150">
                <animateTransform attributeName="transform" type="rotate" from="0 50 50" to="360 50 50" dur="1s"
                    repeatCount="indefinite" />
                <animate attributeName="stroke-dashoffset" values="150;100;150" dur="1.5s"
                    repeatCount="indefinite" />
            </circle>
        </svg>
        {{-- <svg viewBox="0 0 100 100" class="spinner-p">
<path d="M20,90 V10 H60 A20,20 0 1,1 20,30" fill="none" stroke="#3498db" stroke-width="8"
    stroke-linecap="round" />
<circle cx="60" cy="22" r="5" fill="#f1c40f" opacity="0.7">
    <animate attributeName="opacity" values="0.7;0.3;0.7" dur="2s" repeatCount="indefinite" />
</circle>
</svg> --}}

        <div class="book"></div>

        <div class="pencil"></div>

        <p class="loading-text">LOADING...</p>

        <div class="academic-stars">
            <span class="star" style="top: 20%; left: 85%;">★</span>
            <span class="star" style="top: 15%; left: 10%;">★</span>
            <span class="star" style="top: 70%; left: 15%;">★</span>
            <span class="star" style="top: 60%; left: 90%;">★</span>
            <span class="star" style="top: 40%; left: 40%;">★</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pageLoadingOverlay = document.getElementById('page-loading-overlay');

        // const hariIndo = [
        //     'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        // ];
        // const bulanIndo = [
        //     'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        //     'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        // ];

        // const now = new Date();
        // const hari = hariIndo[now.getDay()];
        // const tanggal = now.getDate();
        // const bulan = bulanIndo[now.getMonth()];
        // const tahun = now.getFullYear();

        // const keterangan = `${hari}, ${tanggal} ${bulan} ${tahun}`;
        // console.log(keterangan);

        // document.getElementById("tanggalSekarang").textContent = keterangan;
        // Cek apakah submit dari modal form
        const isModalSubmit = sessionStorage.getItem('modalSubmit') === 'true';

        if (isModalSubmit) {
            // Jangan tampilkan loading
            pageLoadingOverlay.classList.add('hide');
            pageLoadingOverlay.style.display = 'none';
            sessionStorage.removeItem('modalSubmit');
        } else {
            // Tampilkan loading hanya untuk normal reload/page load
            pageLoadingOverlay.classList.remove('hide');

            window.addEventListener('load', function() {
                pageLoadingOverlay.classList.add('hide');
                pageLoadingOverlay.addEventListener('transitionend', function() {
                    pageLoadingOverlay.style.display = 'none';
                });
            });
        }

        // Tambahkan event untuk semua form yg pakai modal atau ajax-like submit
        const modalForms = document.querySelectorAll('form.form-modal, form.form-delete');
        modalForms.forEach(form => {
            form.addEventListener('submit', function() {
                sessionStorage.setItem('modalSubmit', 'true');
            });
        });
    });
</script>
