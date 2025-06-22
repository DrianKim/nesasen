{{-- <!-- Modal Selfie Validation -->
<div class="modal fade" id="selfieModal" tabindex="-1" role="dialog" aria-labelledby="selfieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="py-4 text-center modal-body">
                <!-- Initial View: Before taking selfie -->
                <div id="initial-view">
                    <div class="mb-3 selfie-illustration">
                        <img src="{{ asset('assets/img/selfie-illustration.png') }}" alt="Selfie Icon" class="img-fluid"
                            style="max-height: 150px;">
                    </div>
                    <h5 class="mb-2 modal-title" id="selfieModalLabel">Ketentuan Validasi</h5>
                    <p class="mb-4 text-muted">Buat verifikasi kehadiran, yuk ambil selfie kamu dulu
                    </p>
                </div>

                <!-- Camera View -->
                <div id="camera-container" class="mb-3" style="display: none;">
                    <video id="camera-preview" width="100%" autoplay></video>
                    <canvas id="selfie-canvas" style="display: none;"></canvas>
                </div>

                <!-- After Selfie View: Match the design -->
                <div id="after-selfie-view" style="display: none;">
                    <!-- Check In/Out Time Display -->
                    <div class="mb-3 Check-in-header">
                        <span class="Check-in-label" id="checkStatusLabel">
                        </span>
                        <span class="Check-in-time" id="CheckInTime" style="display: none;"></span>
                        <span class="Check-out-time" id="CheckOutTime" style="display: none;"></span>
                    </div>

                    <!-- Selfie Preview with rounded corners and proper sizing -->
                    <div class="mb-3 selfie-preview-container">
                        <img id="selfie-preview" class="selfie-image" style="display: none;">
                    </div>

                    <!-- Selfie file -->
                    <input type="file" id="selfieInput" accept="image/*" capture="user" hidden />

                    <!-- Foto Ulang button -->
                    <button id="fotoUlangBtn" class="mb-3 text-danger foto-ulang-btn">
                        <i class="fas fa-camera"></i>
                        Foto Ulang
                    </button>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button id="ambilSelfieBtn" class="btn btn-primary btn-block btn-lg">
                        AMBIL SELFIE
                    </button>
                    <button id="kirimPresensiBtn" class="btn btn-success btn-block btn-lg submit-btn"
                        style="display: none;">
                        SUBMIT CHECK IN
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ketentuan -->
<div class="modal-alert" id="modalAlert">
    <div class="modal-alert-content">
        <!-- Tambahkan initial view yang hilang -->
        <div id="initial-view">
            <h3>Ketentuan Validasi</h3>
            <p>Buat verifikasi kehadiran, yuk ambil selfie kamu dulu</p>
        </div>

        <button class="btn-pengumuman-style btn-presensi" id="ambilSelfieBtn">
            Lanjut
        </button>
    </div>
</div> --}}

<!-- Modal Ketentuan / Alert -->
<div class="modal-alert" id="modalAlert" style="display: none;">
    <div class="modal-alert-content">
        <div id="alert-initial-view">
            <h3>Ketentuan Validasi</h3>
            <p>Buat verifikasi kehadiran, yuk ambil selfie kamu dulu</p>
        </div>
        <button id="lanjutSelfieBtn" class="btn-pengumuman-style btn-presensi">
            Lanjut
        </button>
    </div>
</div>

<!-- Modal Presensi -->
<div class="modal-presensi" id="modalPresensi">
    <div class="modal-wrapper">
        <div class="modal-content-presensi">
            <!-- Tambahkan modal-body class untuk script -->
            <div class="modal-body">
                <button class="close-modal-inside" onclick="closeModalPresensi()">
                    <span class="material-icons-sharp">close</span>
                </button>


                <!-- Camera Container -->
                <div id="camera-container" style="display: none;">
                    <h2>Ambil Selfie Untuk Presensi</h2>
                    <div class="camera-preview">
                        <video id="camera-preview" autoplay playsinline></video>
                        <canvas id="selfie-canvas" style="display: none;"></canvas>
                    </div>
                    <!-- Tombol ambil foto saat kamera aktif -->
                    <button id="ambilSelfieBtn" class="btn-pengumuman-style btn-presensi">
                        {{-- <span class="material-icons-sharp">photo_camera</span> --}}
                        <i class="fas fa-camera"></i>

                        {{-- AMBIL FOTO --}}
                    </button>
                </div>

                <!-- After Selfie View -->
                <div id="after-selfie-view" style="display: none;">
                    <!-- Check In/Out Time Display -->
                    <div class="mb-3 Check-in-header" style="margin-bottom: 1rem;">
                        <span class="Check-in-label" id="checkStatusLabel"></span>
                        <span class="Check-in-time" id="CheckInTime" style="display: none;"></span>
                        <span class="Check-out-time" id="CheckOutTime" style="display: none;"></span>
                    </div>

                    <!-- Selfie Preview -->
                    <div class="mb-3 selfie-preview-container">
                        <img id="selfie-preview" class="selfie-image" style="display: none;">
                    </div>

                    <!-- Foto Ulang button -->
                    <button id="fotoUlangBtn" class="mb-3 text-danger foto-ulang-btn"
                    style="background-color: transparent; color: red; margin-bottom: 0.75rem;">
                        <i class="fas fa-camera"></i>
                        Foto Ulang
                    </button>

                    <!-- Submit Button -->
                    <button id="kirimPresensiBtn" class="btn-pengumuman-style btn-presensi">
                        SUBMIT CHECK IN
                    </button>
                </div>

                <!-- Hidden file input -->
                <input type="file" id="selfieInput" accept="image/*" capture="user" hidden />
            </div>
        </div>
    </div>
</div>
