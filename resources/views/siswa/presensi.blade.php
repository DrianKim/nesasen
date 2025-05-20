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
            <textarea id="alasanText" class="border rounded form-control border-opacity-30" rows="2" placeholder="Masukkan alasan kamu..."
                style="resize: none;"></textarea>
        </div>

        <!-- Action Button -->
        <button class="action-button" id="requestCheckIn">
            REQUEST CHECK IN
        </button>
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
    </style>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const schoolPosition = L.latLng(-6.555879634402878, 107.75989081030457);

        const map = L.map('map').setView(schoolPosition, 18);

        const sekolahPolygon = L.polygon([
            [-6.555454600479895, 107.75975103728187],
            [-6.555898966792278, 107.76041729459291],
            [-6.556318358309657, 107.76076425871443],
            [-6.55645958567214, 107.76068915686167],
            [-6.556299705636153, 107.76008565983054],
            [-6.556800228746744, 107.7598384112381],
            [-6.556471142506854, 107.75911823811627],
            [-6.556321921140308, 107.75919602217854],
            [-6.55618317319476, 107.759241215537],
            [-6.556085194523248, 107.75918811050764],
            [-6.555882642397315, 107.7593436323789],
            [-6.555720551529119, 107.75915516205691],
            [-6.555186284742653, 107.75938717313718],
            // urban
            // [-6.947725124439583, 107.62709247541171],
            // [-6.947747048686173, 107.62725812363563],
            // [-6.947949847918558, 107.62722361358898],
            // [-6.947916961562483, 107.62704278094454],
        ], {
            color: 'red',
            fillOpacity: 0.2
        }).addTo(map);

        // Map Tile
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marker sekolah
        L.marker(schoolPosition).addTo(map).bindPopup("SMKN 1 Subang").openPopup();

        let userLatLng = null;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);

                L.marker(userLatLng).addTo(map).bindPopup("Lokasi Kamu").openPopup();

                if (sekolahPolygon.getBounds().contains(userLatLng)) {
                    document.getElementById('locationWarning').textContent = 'Kamu berada di area presensi';
                    document.getElementById('locationWarning').style.color = '#28a745';
                    document.getElementById('alasanWrapper').style.display = 'none';
                } else {
                    document.getElementById('locationWarning').textContent = 'Kamu di luar area presensi';
                    document.getElementById('locationWarning').style.color = '#dc3545';
                    document.getElementById('alasanWrapper').style.display = 'block';
                }
            });
        }

        // Checkin dan Checkout jam
        const checkinTime = null;
        const checkoutTime = null;

        const checkInBtn = document.getElementById('checkInBtn');
        const checkOutBtn = document.getElementById('checkOutBtn');

        checkInBtn.innerHTML = `<i class="fas fa-check-circle"></i> Check In ${checkinTime || '--:--'}`;
        checkOutBtn.innerHTML = `<i class="fas fa-times-circle"></i> Check Out ${checkoutTime || '--:--'}`;

        function checkIn(alasan = null) {
            const jam = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('checkInBtn').innerHTML = `<i class="fas fa-check-circle"></i> Check In ${jam}`;

            if (alasan) {
                console.log("Presensi luar area dengan alasan:", alasan);
            } else {
                console.log("Presensi dalam area.");
            }

            // TODO: Simpan ke server via AJAX / fetch
        }

        document.getElementById('checkInBtn').addEventListener('click', function() {
            const diLuarArea = document.getElementById('alasanWrapper').style.display === 'block';
            let alasan = null;

            if (diLuarArea) {
                alasan = document.getElementById('alasanText').value.trim();
                if (alasan === '') {
                    alert("Kamu harus isi alasan kenapa presensi di luar area bro!");
                    return;
                }
            }

            checkIn(alasan);
        });

        document.getElementById('checkOutBtn').addEventListener('click', function() {
            const jam = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('checkOutBtn').innerHTML =
                `<i class="fas fa-times-circle"></i> Check Out ${jam}`;
            console.log("Check out berhasil");
        });

        document.getElementById('requestCheckIn').addEventListener('click', function() {
            alert('Request check-in berhasil dikirim!');
        });

        // Waktu sekarang
        function updateTime() {
            const now = new Date();
            const jam = now.toLocaleTimeString('id-ID', {
                hour12: false
            });
            document.getElementById('currentTime').textContent = jam;
        }

        document.addEventListener('DOMContentLoaded', function() {
            setInterval(updateTime, 1000);
            updateTime();
        });
    </script>
@endsection
