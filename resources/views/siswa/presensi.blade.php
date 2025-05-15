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
            <p>CQV6+J32, Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41211, Indonesia</p>
        </div>
    </div>

    <!-- Location Status -->
    <div class="location-status">
        <p class="location-warning" id="locationWarning">Anda berada di luar lokasi presensi</p>
        <div class="time-display">
            <i class="far fa-clock"></i>
            <span id="currentTime">09:06:50</span>
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

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
    // Global variables
    let map;
    let marker;
    let schoolMarker;
    let radiusCircle;
    let userPosition;
    let schoolPosition;
    const RADIUS = 100; // Radius in meters

    // Initialize map
    function initMap() {
        // Default position (school position)
        schoolPosition = { lat: -6.557178, lng: 107.759193 }; // Example coordinates, replace with actual school coordinates
        userPosition = { lat: -6.557478, lng: 107.758193 }; // Example user position outside radius

        // Create map centered at school position
        map = new google.maps.Map(document.getElementById('map'), {
            center: schoolPosition,
            zoom: 17,
            disableDefaultUI: true,
            zoomControl: true,
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                }
            ]
        });

        // Create school marker
        schoolMarker = new google.maps.Marker({
            position: schoolPosition,
            map: map,
            icon: {
                url: '{{ asset("images/school-marker.png") }}',
                scaledSize: new google.maps.Size(40, 40)
            },
            title: 'Lokasi Sekolah'
        });

        // Create radius circle
        radiusCircle = new google.maps.Circle({
            strokeColor: '#FF6384',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF6384',
            fillOpacity: 0.15,
            map: map,
            center: schoolPosition,
            radius: RADIUS
        });

        // Create user marker
        marker = new google.maps.Marker({
            position: userPosition,
            map: map,
            icon: {
                url: '{{ asset("images/user-marker.png") }}',
                scaledSize: new google.maps.Size(40, 40)
            },
            title: 'Lokasi Saya'
        });

        // Add info label
        const infoLabel = document.createElement('div');
        infoLabel.className = 'location-label';
        infoLabel.textContent = 'Lokasi Saya';

        const infoWindow = new google.maps.InfoWindow({
            content: infoLabel,
            disableAutoPan: true
        });

        infoWindow.open(map, marker);

        // Check if user is within radius
        checkUserInRadius();

        // Get user's actual location if available
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userPosition = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Update user marker position
                    marker.setPosition(userPosition);

                    // Recenter map to show both user and school
                    const bounds = new google.maps.LatLngBounds();
                    bounds.extend(userPosition);
                    bounds.extend(schoolPosition);
                    map.fitBounds(bounds);

                    // Check if user is within radius
                    checkUserInRadius();
                },
                (error) => {
                    console.error('Error getting user location:', error);
                }
            );
        }
    }

    // Check if user is within school radius
    function checkUserInRadius() {
        const userLatLng = new google.maps.LatLng(userPosition.lat, userPosition.lng);
        const schoolLatLng = new google.maps.LatLng(schoolPosition.lat, schoolPosition.lng);

        // Calculate distance in meters
        const distance = google.maps.geometry.spherical.computeDistanceBetween(userLatLng, schoolLatLng);

        const locationWarning = document.getElementById('locationWarning');
        const requestBtn = document.getElementById('requestCheckIn');

        if (distance <= RADIUS) {
            // User is within radius
            locationWarning.textContent = 'Anda berada di dalam lokasi presensi';
            locationWarning.style.color = '#4CAF50';
            requestBtn.textContent = 'CHECK IN';
            requestBtn.style.backgroundColor = '#4CAF50';
        } else {
            // User is outside radius
            locationWarning.textContent = 'Anda berada di luar lokasi presensi';
            locationWarning.style.color = '#FF5252';
            requestBtn.textContent = 'REQUEST CHECK IN';
            requestBtn.style.backgroundColor = '#00BFA5';
        }
    }

    // Update current time
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Document ready function
    document.addEventListener('DOMContentLoaded', function() {
        // Update time every second
        setInterval(updateTime, 1000);
        updateTime();

        // Handle Check In/Out toggle
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

        // Handle request button click
        document.getElementById('requestCheckIn').addEventListener('click', function() {
            // Here you would handle the clock in/out action
            // For example, make an AJAX request to your server
            alert('Request for clock in has been sent.');
        });
    });
</script>
@endsection
