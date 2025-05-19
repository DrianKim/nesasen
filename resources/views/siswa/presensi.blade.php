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
            <div class="clock-buttons">
                <button id="clockInBtn" class="clock-btn clock-in active">
                    <i class="fas fa-check-circle"></i> Check In --:--
                </button>
                <button id="clockOutBtn" class="clock-btn clock-out">
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
                <p>Jalan Arief Rahman Hakim No 35, Kabupaten Subang, Jawa Barat 41213, Indonesia</p>
            </div>
        </div>

        <!-- Location Status -->
        <div class="location-status">
            <p class="location-warning" id="locationWarning"></p>
            <div class="time-display">
                <i class="far fa-clock"></i>
                <span id="currentTime"></span>
            </div>
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

        #map {
            height: 100%;
            width: 100%;
            background-color: #e5e5e5;
        }

        .clock-buttons {
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 0 20px;
        }

        .clock-btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .clock-in {
            background-color: #4CAF50;
        }

        .clock-out {
            background-color: #FF5252;
        }

        .clock-btn.active {
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
    </style>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        const schoolPosition = L.latLng(-6.555879634402878, 107.75989081030457); // titik sekolah

        let userMarker;

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
        ], {
            color: 'blue ',
            fillOpacity: 0.1
        }).addTo(map);

        // Tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marker sekolah
        L.marker(schoolPosition).addTo(map)
            .bindPopup("Lokasi Sekolah")
            .openPopup();

        // Ambil posisi user
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);

                // Tambahkan marker user
                L.marker(userLatLng).addTo(map).bindPopup("Lokasi Kamu").openPopup();

                // Validasi lokasi
                if (sekolahPolygon.getBounds().contains(userLatLng)) {
                    // Dalam area polygon
                    document.getElementById('locationWarning').textContent = '✅ Kamu berada di area presensi';
                    document.getElementById('locationWarning').style.color = 'green';
                } else {
                    // Di luar area polygon
                    document.getElementById('locationWarning').textContent = '❌ Kamu di luar area presensi';
                    document.getElementById('locationWarning').style.color = 'red';
                }
            });
        }

        // Update current time
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            setInterval(updateTime, 1000);
            updateTime();

            const clockInBtn = document.getElementById('clockInBtn');
            const clockOutBtn = document.getElementById('clockOutBtn');

            clockInBtn.addEventListener('click', function() {
                clockInBtn.classList.add('active');
                clockOutBtn.classList.remove('active');
            });

            clockOutBtn.addEventListener('click', function() {
                clockOutBtn.classList.add('active');
                clockInBtn.classList.remove('active');
            });

            document.getElementById('requestCheckIn').addEventListener('click', function() {
                alert('Request for clock in has been sent.');
            });
        });
    </script>
@endsection
