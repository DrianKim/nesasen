<!-- Modal Selfie Validation -->
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
