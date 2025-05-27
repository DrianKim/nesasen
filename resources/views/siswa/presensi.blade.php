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

            <!-- Check In/Out Info -->
            <div class="check-buttons">
                <button id="checkInInfo" class="check-btn check-in active btn-sm">
                    <i class="fas fa-check-circle"></i> Check In --:--
                </button>
                <button id="checkOutInfo" class="check-btn check-out btn-sm">
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

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const schoolPosition = L.latLng(-6.555879634402878, 107.75989081030457);
        const map = L.map('map').setView(schoolPosition, 18);

        const sekolahPolygon = L.polygon([
            [-6.5551890749458295, 107.759372533305],
            [-6.555848899619548, 107.76026428227516],
            [-6.555950411029656, 107.76040361804996],
            [-6.55610689992767, 107.76062323055038],
            [-6.556195893182945, 107.76055010478507],
            [-6.556350269201218, 107.76076034136156],
            [-6.556479218543643, 107.76066527786566],
            [-6.556366347621804, 107.76020009831149],
            [-6.556322859036399, 107.76007311476963],
            [-6.556674972416403, 107.7599032393415],
            [-6.556893740959282, 107.75979628073753],
            [-6.556483289424733, 107.75908532060282],
            [-6.556272854749352, 107.75914404297384],
            [-6.556201277790777, 107.75923386517093],
            [-6.556104297520548, 107.75918118208767],
            [-6.556059655802599, 107.75925710770679],
            [-6.556022710929824, 107.75924006318087],
            [-6.555959596766229, 107.75931288979496],
            [-6.555896482593184, 107.75923231566844],
            [-6.555856458967682, 107.75926330571673],
            [-6.555762251179814, 107.75914523597766],
            [-6.555720688172769, 107.7591715775186],
            [-6.555686822016526, 107.75912354294292],
            [-6.555626786551073, 107.75918242403606],
            [-6.555591381017379, 107.75915763199617],
            [-6.55540511706991, 107.75930328524294],
            [-6.555366632776824, 107.7592552506672],
            [-6.555189604989252, 107.75937301285347],
            // [-6.5554546, 107.759751],
            // [-6.555899, 107.760417],
            // [-6.556318, 107.760764],
            // [-6.556460, 107.760689],
            // [-6.556300, 107.760086],
            // [-6.556800, 107.759838],
            // [-6.556471, 107.759118],
            // [-6.556322, 107.759196],
            // [-6.556183, 107.759241],
            // [-6.556085, 107.759188],
            // [-6.555883, 107.759344],
            // [-6.555721, 107.759155],
            // [-6.555186, 107.759387],
        ], {
            color: 'red',
            fillOpacity: 0.2
        }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        L.marker(schoolPosition).addTo(map).bindPopup("SMKN 1 Subang").openPopup();

        let userLatLng = null;
        let insideArea = false;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                    userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);

                    L.marker(userLatLng).addTo(map).bindPopup("Lokasi Kamu").openPopup();

                    const insideArea = sekolahPolygon.getBounds().contains(userLatLng);
                    document.getElementById('locationWarning').textContent = insideArea ?
                        'Kamu berada di area presensi' : 'Kamu di luar area presensi';
                    document.getElementById('locationWarning').style.color = insideArea ? '#28a745' : '#dc3545';
                    document.getElementById('alasanWrapper').style.display = insideArea ? 'none' : 'block';
                },
                error => {
                    console.error("Gagal ambil lokasi:", error);
                    document.getElementById('locationWarning').textContent = 'Tidak bisa mendeteksi lokasi!';
                    document.getElementById('locationWarning').style.color = 'red';
                }
            );
        }

        // Tombol jam awal
        // document.getElementById('checkInInfo').innerHTML = `<i class="fas fa-check-circle"></i> Check In --:--`;
        // document.getElementById('checkOutInfo').innerHTML = `<i class="fas fa-times-circle"></i> Check Out --:--`;

        // Data selfie dan elemen DOM
        let selfieData = null;
        let stream = null;
        let sudahCheckIn = false;

        const cameraContainer = document.getElementById('camera-container');
        const cameraPreview = document.getElementById('camera-preview');
        const selfieCanvas = document.getElementById('selfie-canvas');
        const selfiePreview = document.getElementById('selfie-preview');
        const ambilSelfieBtn = document.getElementById('ambilSelfieBtn')
        const fileinput = document.getElementById('selfieInput');
        const kirimPresensiBtn = document.getElementById('kirimPresensiBtn');
        const fotoUlangBtn = document.getElementById('fotoUlangBtn');
        const initialView = document.getElementById('initial-view');
        const afterSelfieView = document.getElementById('after-selfie-view');
        const CheckInTime = document.getElementById('CheckInTime');
        const CheckOutTime = document.getElementById('CheckOutTime');
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

            if (CheckOutTime) {
                CheckOutTime.textContent = timeString;
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
            // Hide kamera
            cameraContainer.style.display = 'none';
            initialView.style.display = 'none';
            modalBody.classList.remove('camera-active');

            // Tampil view selfie
            afterSelfieView.style.display = 'block';
            selfiePreview.src = selfieData;
            selfiePreview.style.display = 'block';

            const now = new Date();
            const jam = now.getHours().toString().padStart(2, '0') + ':' +
                now.getMinutes().toString().padStart(2, '0') + ':' +
                now.getSeconds().toString().padStart(2, '0');

            if (!sudahCheckIn) {
                // CHECK IN
                $('#checkStatusLabel')
                .removeClass('checkout')
                .addClass('checkin')
                .html(
                    `<i class="fas fa-circle text-success" style="font-size: 8px; margin-right: 8px;"></i>Check In`);
                $('#CheckInTime').text(jam).show();
                $('#CheckOutTime').hide();
            } else {
                // CHECK OUT
                $('#checkStatusLabel')
                .removeClass('checkin')
                .addClass('checkout')
                .html(
                    `<i class="fas fa-circle text-danger" style="font-size: 8px; margin-right: 8px;"></i>Check Out`);
                $('#CheckOutTime').text(jam).show();
                $('#CheckInTime').hide();
            }

            console.log('Sudah Check In:', sudahCheckIn);
            // Tombol
            ambilSelfieBtn.style.display = 'none';
            kirimPresensiBtn.style.display = 'block';
        }

        // Update status berdasarkan hasil presensi
        function handlePresensiResponse(response) {
            if (response.type === 'masuk') {
                sudahCheckIn = true;
                $('#CheckInTime').text(response.jam);
            } else if (response.type === 'keluar') {
                sudahCheckIn = false;
            }
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

        window.addEventListener('DOMContentLoaded', () => {
            fetch('/siswa/presensi/hari-ini')
                .then(response => response.json())
                .then(data => {
                    if (data.jam_masuk && !data.jam_keluar) {
                        sudahCheckIn = true;
                        checkIn(null, null, data.jam_masuk);
                    }
                    if (data.jam_keluar) {
                        sudahCheckIn = false;
                        checkOut(null, null, data.jam_keluar);
                    }
                })
                .catch(err => {
                    console.error('Gagal ambil data presensi:', err);
                });
        });

        function checkIn(alasan = null, selfieData = null, jam = null) {
            const jamTampil = jam ?? new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('checkInInfo').innerHTML =
                `<i class="fas fa-check-circle"></i> Check In ${jamTampil}`;
        }

        function checkOut(alasan = null, selfieData = null, jam = null) {
            const jamTampil = jam ?? new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('checkOutInfo').innerHTML =
                `<i class="fas fa-check-circle"></i> Check Out ${jamTampil}`;
        }

        fileinput.onchange = () => {
            selfieFile = fileinput.files[0];
            if (selfieFile) {
                document.getElementById('kirimPresensiBtn').style.display;
            }
        }

        // Event listeners
        let selfieFile = null;
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
            const now = new Date();
            const batasMasuk = new Date();
            batasMasuk.setHours(7, 30, 0); // Batas masuk jam 07:30

            let alasan = null;
            let statusKehadiran = 'hadir';
            let statusLokasi = 'dalam_area';

            // Tentukan status berdasarkan waktu
            if (now > batasMasuk) {
                statusKehadiran = 'terlambat';
            }

            // Validasi alasan kalau di luar area
            if (document.getElementById('alasanWrapper').style.display === 'block') {
                alasan = document.getElementById('alasanText').value.trim();
                if (!alasan) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Kurang!',
                        text: 'Silakan masukkan alasan kamu.',
                    });
                    return;
                }
            }

            // Fungsi bantu convert base64 ke File
            function base64ToFile(base64Data, filename) {
                const arr = base64Data.split(',');
                const mime = arr[0].match(/:(.*?);/)[1];
                const bstr = atob(arr[1]);
                let n = bstr.length;
                const u8arr = new Uint8Array(n);
                while (n--) {
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new File([u8arr], filename, {
                    type: mime
                });
            }

            // Validasi selfie + lokasi
            if (!selfieData || !userLatLng) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Kurang!',
                    text: 'Pastikan selfie dan lokasi terdeteksi',
                });
                return;
            }

            // Upload presensi
            fetch(selfieData)
                .then(res => res.blob())
                .then(blob => {
                    const formData = new FormData();
                    const selfieBlob = base64ToFile(selfieData, 'selfie.jpg');
                    formData.append('selfie', selfieBlob);
                    formData.append('latitude', userLatLng.lat);
                    formData.append('longitude', userLatLng.lng);
                    formData.append('status_kehadiran', statusKehadiran);
                    formData.append('status_lokasi', statusLokasi);
                    if (alasan) formData.append('alasan', alasan);

                    return fetch('/siswa/presensi/store', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    });
                })

                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Presensi Berhasil!',
                        text: `${data.message}`,
                        footer: data.jam ? `Pada Pukul ${data.jam}` : '',
                        timer: 4000,
                        showConfirmButton: false
                    });

                    // Update tampilan info presensi
                    if (data.type === 'masuk') {
                        checkIn(null, selfieData, data.jam);
                    } else {
                        checkOut(null, selfieData, data.jam);
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Presensi Gagal!',
                        text: 'Coba lagi, mungkin ada masalah koneksi.',
                    });
                });

            $('#selfieModal').modal('hide');
        });


        // Modal close event
        $('#selfieModal').on('hidden.bs.modal', () => {
            resetModalView();
        });

        document.getElementById('checkOutInfo').addEventListener('click', () => {
            const jam = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            // document.getElementById('checkOutBtn').innerHTML =
            //     `<i class="fas fa-times-circle"></i> Check Out ${jam}`;
            // console.log("Check out berhasil");
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
