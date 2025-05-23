@extends('layouts.app')

@section('content')
    <div class="presensi-container">
        <!-- Header with Back Button -->
        <div class="presensi-header">
            <a href="{{ route('siswa.beranda') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Presensi Online
            </a>
            <div class="history-button">
                <i class="fas fa-history"></i>
            </div>
        </div>

        <!-- Map Container -->
        <div class="map-container">
            <div id="map"></div>

            <!-- Check In/Out Buttons -->
            <div class="check-buttons">
                <button id="checkInBtn" class="check-btn check-in active btn-sm">
                    <i class="fas fa-check-circle"></i> Check In --:--
                </button>
                <button id="checkOutBtn" class="check-btn check-out btn-sm">
                    <i class="fas fa-times-circle"></i> Check Out --:--
                </button>
            </div>
        </div>

        <!-- School Info -->
        <div class="school-info">
            <div class="school-logo">
                <img src="{{ asset('assets\img\smeapng.png') }}" alt="School Logo">
            </div>
            <div class="school-details">
                <h3>SMKN 1 SUBANG</h3>
                <p>CQV6+J32, Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41211, Indonesia</p>
            </div>
        </div>

        <!-- Location Status -->
        <div class="location-status">
            <p class="location-warning" id="locationWarning"></p>
            <div class="time-display">
                <i class="far fa-check"></i>
                <span id="currentTime"></span>
            </div>
        </div>

        <!-- Reason for Check In -->
        <div id="alasanWrapper" class="mb-2" style="display: none;">
            <textarea id="alasanText" class="border rounded form-control border-opacity-30" rows="2"
                placeholder="Masukkan alasan kamu..." style="resize: none;" required></textarea>
        </div>

        <!-- Action Button -->
        <button class="action-button" id="requestCheckIn">
            REQUEST CHECK IN
        </button>
    </div>

    <!-- Modal Selfie Validation -->
    <div class="modal fade" id="selfieModal" tabindex="-1" role="dialog" aria-labelledby="selfieModalLabel"
        aria-hidden="true">
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
                        <p class="mb-4 text-muted">Untuk menjaga keamanan dan integritas presensi, kami butuh selfie kamu
                            ya.
                        </p>
                    </div>

                    <!-- Camera View -->
                    <div id="camera-container" class="mb-3" style="display: none;">
                        <video id="camera-preview" width="100%" autoplay></video>
                        <canvas id="selfie-canvas" style="display: none;"></canvas>
                    </div>

                    <!-- After Selfie View: Match the design -->
                    <div id="after-selfie-view" style="display: none;">
                        <!-- Check In Time Display -->
                        <div class="mb-3 Check-in-header">
                            <span class="Check-in-label">
                                <i class="fas fa-circle text-success" style="font-size: 8px; margin-right: 8px;"></i>
                                Check In
                            </span>
                            <span class="Check-in-time" id="CheckInTime">03:42</span>
                        </div>

                        <!-- Selfie Preview with rounded corners and proper sizing -->
                        <div class="mb-3 selfie-preview-container">
                            <img id="selfie-preview" class="selfie-image" style="display: none;">
                        </div>

                        <!-- Foto Ulang button -->
                        <button id="fotoUlangBtn" class="mb-3 text-danger btn btn-link foto-ulang-btn">
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

    <style>
        .presensi-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f0f7ff;
        }

        .presensi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #fff;
        }

        .back-button {
            display: flex;
            align-items: center;
            font-size: 16px;
            color: #333;
            text-decoration: none;
        }

        .back-button i {
            margin-right: 10px;
        }

        .history-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .map-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

        .check-buttons {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 1000;
            flex-wrap: wrap;
            justify-content: center;
            width: 90%;
        }

        .check-btn {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
            color: #333;
            cursor: default;
            font-weight: bold;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            background-color: #fff;
            color: #333;
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: center;
        }

        .check-btn:hover {
            background-color: #e0e0e0;
        }

        @media (min-width: 768px) {
            .check-btn {
                width: auto;
                padding: 8px 15px;
                font-size: 14px;
            }
        }

        #map {
            height: 100%;
            width: 100%;
            background-color: #e5e5e5;
        }

        .check-in {
            /* background-color: 4px solid #28a745; */
            color: #28a745;
        }

        .check-out {
            /* background-color: 4px solid #dc3545; */
            color: #dc3545;
        }

        .check-btn.active {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .school-info {
            display: flex;
            align-items: center;
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .school-logo {
            width: 50px;
            height: 50px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-logo img {
            width: 100%;
            height: auto;
        }

        .school-details h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .school-details p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .school-details h3 {
                margin: 0;
                font-size: 14px;
                font-weight: bold;
                color: #333;
            }

            .school-details p {
                margin: 5px 0 0;
                font-size: 11px;
                color: #666;
                line-height: 1.4;
            }
        }

        .location-status {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            margin-top: 10px;
        }

        .location-warning {
            color: #FF5252;
            font-size: 14px;
            font-weight: 500;
        }

        .time-display {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .action-button {
            background-color: #00BFA5;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px;
            margin: 15px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .action-button:hover {
            background-color: #00AB91;
        }

        #alasanWrapper textarea {
            /* border: 2px solid #201c1c; */
            font-size: 14px;
        }

        .modal-content {
            border-radius: 15px;
        }

        #ambilSelfieBtn {
            background-color: #00BFA5;
            border: none;
            border-radius: 6px;
            padding: 8px;
            font-weight: bold;
        }

        #ambilSelfieBtn:hover {
            background-color: #00AB91;
        }

        #kirimPresensiBtn {
            background-color: #28a745;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
        }

        #camera-container {
            position: relative;
        }

        #camera-preview {
            border-radius: 10px;
            background-color: #f0f0f0;
        }

        #selfie-canvas {
            display: none;
            width: 100%;
            max-width: 400px;
        }

        .Check-in-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 20px;
        }

        .Check-in-label {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #28a745;
            font-weight: 500;
        }

        .Check-in-time {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .selfie-preview-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .selfie-image {
            width: 200px;
            height: 300px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid #f0f0f0;
        }

        .foto-ulang-btn {
            background: none;
            border: none;
            color: #666;
            font-size: 14px;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .foto-ulang-btn:hover {
            background-color: #e9ecef;
            color: #495057;
            text-decoration: none;
        }

        .foto-ulang-btn i {
            margin-right: 8px;
            font-size: 12px;
        }

        .submit-btn {
            background-color: #20c997 !important;
            border-color: #20c997 !important;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: #1ba085 !important;
            border-color: #1ba085 !important;
        }

        #camera-preview {
            border-radius: 20px;
            background-color: #f0f0f0;
            max-width: 100%;
            height: auto;
        }

        .camera-active #initial-view {
            display: none !important;
        }

        @media (max-width: 576px) {
            .selfie-image {
                width: 180px;
                height: 270px;
            }

            .Check-in-header {
                padding: 0 10px;
            }

            .modal-body {
                padding: 20px 15px;
            }
        }
    </style>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const schoolPosition = L.latLng(-6.555879634402878, 107.75989081030457);
        const map = L.map('map').setView(schoolPosition, 18);

        const sekolahPolygon = L.polygon([
            [-6.5554546, 107.759751],
            [-6.555899, 107.760417],
            [-6.556318, 107.760764],
            [-6.556460, 107.760689],
            [-6.556300, 107.760086],
            [-6.556800, 107.759838],
            [-6.556471, 107.759118],
            [-6.556322, 107.759196],
            [-6.556183, 107.759241],
            [-6.556085, 107.759188],
            [-6.555883, 107.759344],
            [-6.555721, 107.759155],
            [-6.555186, 107.759387],
        ], {
            color: 'red',
            fillOpacity: 0.2
        }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        L.marker(schoolPosition).addTo(map).bindPopup("SMKN 1 Subang").openPopup();

        let userLatLng = null;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);

                L.marker(userLatLng).addTo(map).bindPopup("Lokasi Kamu").openPopup();

                const insideArea = sekolahPolygon.getBounds().contains(userLatLng);
                document.getElementById('locationWarning').textContent = insideArea ?
                    'Kamu berada di area presensi' : 'Kamu di luar area presensi';
                document.getElementById('locationWarning').style.color = insideArea ? '#28a745' : '#dc3545';
                document.getElementById('alasanWrapper').style.display = insideArea ? 'none' : 'block';
            });
        }

        // Tombol jam awal
        document.getElementById('checkInBtn').innerHTML = `<i class="fas fa-check-circle"></i> Check In --:--`;
        document.getElementById('checkOutBtn').innerHTML = `<i class="fas fa-times-circle"></i> Check Out --:--`;

        // Data selfie dan elemen DOM
        let selfieData = null;
        let stream = null;

        const cameraContainer = document.getElementById('camera-container');
        const cameraPreview = document.getElementById('camera-preview');
        const selfieCanvas = document.getElementById('selfie-canvas');
        const selfiePreview = document.getElementById('selfie-preview');
        const ambilSelfieBtn = document.getElementById('ambilSelfieBtn');
        const kirimPresensiBtn = document.getElementById('kirimPresensiBtn');
        const fotoUlangBtn = document.getElementById('fotoUlangBtn');
        const initialView = document.getElementById('initial-view');
        const afterSelfieView = document.getElementById('after-selfie-view');
        const CheckInTime = document.getElementById('CheckInTime');
        const modalBody = document.querySelector('.modal-body');

        function setCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            if (CheckInTime) {
                CheckInTime.textContent = timeString;
            }
        }

        function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert("Browser kamu tidak support kamera!");
                return;
            }

            // Hide initial view and after selfie view, show camera
            initialView.style.display = 'none';
            afterSelfieView.style.display = 'none';
            cameraContainer.style.display = 'block';
            modalBody.classList.add('camera-active');

            // Reset buttons properly
            ambilSelfieBtn.style.display = 'block';
            ambilSelfieBtn.textContent = 'AMBIL FOTO';
            kirimPresensiBtn.style.display = 'none';

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: {
                            ideal: 720
                        },
                        height: {
                            ideal: 1280
                        }
                    },
                    audio: false
                })
                .then(s => {
                    stream = s;
                    cameraPreview.srcObject = stream;
                    cameraPreview.play();
                })
                .catch(err => {
                    console.error('Error accessing camera:', err);
                    alert('Tidak dapat mengakses kamera. Pastikan kamu memberikan izin kamera.');
                    resetModalView();
                });
        }

        function takeSelfie() {
            selfieCanvas.width = cameraPreview.videoWidth;
            selfieCanvas.height = cameraPreview.videoHeight;

            const ctx = selfieCanvas.getContext('2d');
            ctx.drawImage(cameraPreview, 0, 0, selfieCanvas.width, selfieCanvas.height);
            selfieData = selfieCanvas.toDataURL('image/jpeg');

            // Stop camera
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }

            // Show after selfie view
            showAfterSelfieView();
        }

        function showAfterSelfieView() {
            // Hide camera and initial view
            cameraContainer.style.display = 'none';
            initialView.style.display = 'none';
            modalBody.classList.remove('camera-active');

            // Show after selfie view
            afterSelfieView.style.display = 'block';
            selfiePreview.src = selfieData;
            selfiePreview.style.display = 'block';

            // Update buttons
            ambilSelfieBtn.style.display = 'none';
            kirimPresensiBtn.style.display = 'block';

            // Set time
            setCurrentTime();
        }

        function resetModalView() {
            // Reset all views
            initialView.style.display = 'block';
            cameraContainer.style.display = 'none';
            afterSelfieView.style.display = 'none';
            modalBody.classList.remove('camera-active');

            // Reset buttons
            ambilSelfieBtn.style.display = 'block';
            ambilSelfieBtn.textContent = 'AMBIL SELFIE';
            kirimPresensiBtn.style.display = 'none';

            // Reset selfie data
            selfieData = null;
            if (selfiePreview) {
                selfiePreview.style.display = 'none';
            }

            // Stop camera if running
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        function checkIn(alasan = null, selfieData = null) {
            const jam = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            document.getElementById('checkInBtn').innerHTML = `<i class="fas fa-check-circle"></i> Check In ${jam}`;

            const data = {
                jam,
                alasan,
                selfie: selfieData,
                lokasi: userLatLng ? {
                    lat: userLatLng.lat,
                    lng: userLatLng.lng
                } : null
            };

            console.log("Data presensi:", data);
            alert('Presensi berhasil dicatat!');

            // TODO: fetch/axios ke controller Laravel
        }

        // Event listeners
        document.getElementById('requestCheckIn').addEventListener('click', () => {
            resetModalView();
            $('#selfieModal').modal('show');
        });

        ambilSelfieBtn.addEventListener('click', () => {
            if (!stream) {
                startCamera();
            } else {
                takeSelfie();
            }
        });

        if (fotoUlangBtn) {
            fotoUlangBtn.addEventListener('click', () => {
                // Reset selfie data
                selfieData = null;

                // Hide after selfie view
                afterSelfieView.style.display = 'none';

                // Start camera again
                startCamera();
            });
        }

        kirimPresensiBtn.addEventListener('click', () => {
            const diLuarArea = document.getElementById('alasanWrapper').style.display === 'block';
            let alasan = null;

            if (diLuarArea) {
                alasan = document.getElementById('alasanText').value.trim();
                if (!alasan) {
                    alert("Isi alasan kenapa presensi di luar area!");
                    return;
                }
            }

            $('#selfieModal').modal('hide');
            checkIn(alasan, selfieData);
        });

        // Modal close event
        $('#selfieModal').on('hidden.bs.modal', () => {
            resetModalView();
        });

        document.getElementById('checkOutBtn').addEventListener('click', () => {
            const jam = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('checkOutBtn').innerHTML =
                `<i class="fas fa-times-circle"></i> Check Out ${jam}`;
            console.log("Check out berhasil");
        });

        function updateTime() {
            const now = new Date();
            const jam = now.toLocaleTimeString('id-ID', {
                hour12: false
            });
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = jam;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            setInterval(updateTime, 1000);
            updateTime();
        });
    </script>
@endsection
