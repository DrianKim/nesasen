<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Nesasen - Learning Management System for SMKN 1 Subang">
    <meta name="author" content="Development Team">
    <meta name="theme-color" content="#4e73df">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Nesasen | {{ $title }}</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- PWA elements -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Nesasen">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-152x152.png') }}">

    <!-- Css Custom -->
    <link rel="stylesheet" href="{{ asset('assets/css/style-jadwal.css') }}">

</head>

@include('layouts.loading-page')

<body>

    <div class="jadwal-container">
        <!-- Judul dan Navigasi -->
        <div class="jadwal-header">
            <div class="back-button">
                <a href="{{ route('siswa.beranda') }}">
                    <i class="fas fa-arrow-left"></i> Jadwal
                </a>
            </div>
            <div class="calendar-button">
                <a href="#" id="toggle-view">
                    <i class="fas fa-calendar"></i>
                </a>
            </div>
        </div>

        <!-- Tahun Ajaran dan Semester -->
        <div class="semester-info">
            <h4>Tahun Ajaran 2024-2025</h4>
        </div>

        <!-- Bulan dan Tahun -->
        <div class="month-header">
            <h3 id="month-display">{{ $monthName }}</h3>
        </div>

        <!-- Navigasi Mingguan -->
        {{-- <div class="week-navigation" id="week-navigation" style="display: block;">
            <div class="week-arrow-prev">
                <a href="#" onclick="changeWeek(-1)">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>

            <div class="days-container" id="days-container">
                @include('siswa.partials.days_perminggu')
            </div>

            <div class="week-arrow-next">
                <a href="#" onclick="changeWeek(1)">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div> --}}
        {{-- <div class="week-navigation" id="week-navigation" style="display: block;">
            <div class="week-arrow-prev">
                <a href="#" onclick="changeWeek(-1)">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>

            <div class="days-container" id="days-container">
                @include('siswa.partials.days_perminggu')
            </div>

            <div class="week-arrow-next">
                <a href="#" onclick="changeWeek(1)">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div> --}}
        <div class="week-navigation" id="week-navigation" style="display: block;">
            <div class="week-nav-flex" style="display: flex; align-items: center; justify-content: space-between;">
                <div class="week-arrow-prev">
                    <a href="#" onclick="changeWeek(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </div>

                <div class="days-container" id="days-container" style="flex: 1; margin: 0 10px;">
                    @include('siswa.partials.days_perminggu')
                </div>

                <div class="week-arrow-next">
                    <a href="#" onclick="changeWeek(1)">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigasi Bulanan -->
        <div class="month-navigation" id="month-navigation" style="display: none;">
            <div class="month-arrow-prev">
                <a href="#" onclick="changeMonth(-1)">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>

            <div class="days-container" id="days-container">
                @include('siswa.partials.days_perbulan')
            </div>

            <div class="month-arrow-next">
                <a href="#" onclick="changeMonth(1)">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Tanggal yang ditampilkan -->
        <div class="current-date">
            <div class="date-text" id="current-date-text">{{ $selectedDate->translatedFormat('l, d F Y') }}</div>
            <div class="today-badge" id="today-badge" style="{{ $selectedDate->isToday() ? '' : 'display: none;' }}">
                Hari Ini</div>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" style="display: none; text-align: center; padding: 20px;">
            <i class="fas fa-spinner fa-spin"></i> Memuat...
        </div>

        <!-- Daftar Jadwal -->
        <div class="jadwal-list" id="jadwal-list">
            @include('siswa.partials.jadwal-list')
        </div>

        <!-- Tombol Tambah Jadwal -->
        <div class="floating-button">
            <a href="#">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- Hidden input to store current selected date -->
    <input type="hidden" id="current-selected-date" value="{{ $selectedDate->format('Y-m-d') }}">

    @include('layouts.footer-cr')

    @include('layouts.footer')

    <script>
        let currentDate = '{{ $selectedDate->format('Y-m-d') }}';
        let isMonthlyView = false;
        let viewMode = 'minggu';
        let realtimeInterval;

        $(document).ready(function() {
            $('#toggle-view').on('click', function(e) {
                e.preventDefault();

                if (viewMode === 'minggu') {
                    viewMode = 'bulan';
                    $('#week-navigation').hide();
                    $('#month-navigation').show();
                    loadBulanView(currentDate);
                } else {
                    viewMode = 'minggu';
                    $('#month-navigation').hide();
                    $('#week-navigation').show();
                    loadMingguView(currentDate);
                }
            });
        });

        $(document).ready(function() {
            // Start real-time updates
            startRealtimeUpdates();

            // Update time every second
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);
        });

        function startRealtimeUpdates() {
            // Update jadwal status every 30 seconds
            realtimeInterval = setInterval(function() {
                updateJadwalStatus();
            }, 30000);
        }

        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Update any time displays if needed
            $('.current-time').text(timeString);
        }

        function updateJadwalStatus() {
            if (isLoading) return;
            $.ajax({
                url: '{{ route('siswa.jadwal.perhari') }}',
                method: 'GET',
                data: {
                    tanggal: currentDate
                },
                success: function(response) {
                    updateJadwalDisplay(response.jadwal);
                },
                error: function(xhr, status, error) {
                    console.error('Error updating jadwal status:', error);
                }
            });
        }

        jadwalList.fadeOut(200, function() {
            jadwalList.html(html).fadeIn(200);
        })

        function updateJadwalDisplay(jadwalData) {
            const jadwalList = $('#jadwal-list');

            if (jadwalData.length === 0) {
                jadwalList.html(`
            <div class="empty-jadwal">
                <div class="empty-illustration">
                    <img src="{{ asset('assets/img/not-found.png') }}" alt="Tidak ada jadwal">
                </div>
                <div class="empty-text">Tidak ada jadwal untuk hari ini</div>
            </div>
        `);
                return;
            }

            let html = '';
            jadwalData.forEach(function(jadwal) {
                const completedClass = jadwal.is_selesai ? 'completed' : '';
                const ongoingClass = jadwal.is_belum_selesai ? 'ongoing' : '';
                const upcomingClass = (!jadwal.is_selesai && !jadwal.is_belum_selesai) ? 'upcoming' : '';

                let statusIcon = '';
                if (jadwal.is_selesai) {
                    statusIcon = '<div class="status-check"><i class="fas fa-check-circle"></i></div>';
                } else if (jadwal.is_belum_selesai) {
                    statusIcon = '<div class="status-ongoing"><i class="fas fa-clock"></i></div>';
                } else {
                    statusIcon = '<div class="status-upcoming"><i class="fas fa-hourglass-start"></i></div>';
                }

                const ongoingSubjectClass = jadwal.is_belum_selesai ? 'belum-selesai' : (upcomingClass ?
                    'upcoming' : '');

                const mapelKelas = jadwal.mapel_kelas;
                const mataPelajaran = mapelKelas?.mata_pelajaran;
                const guru = mapelKelas?.guru;
                const kelas = mapelKelas?.kelas;
                const jurusan = kelas?.jurusan;

                html += `
            <div class="jadwal-item ${completedClass} ${ongoingClass} ${upcomingClass}">
                <div class="waktu-container">
                    <div class="waktu">
                        ${jadwal.jam_mulai.substring(0, 5)}
                        -
                        ${jadwal.jam_selesai.substring(0, 5)}
                    </div>
                    ${statusIcon}
                </div>
                <div class="mata-pelajaran-container ${ongoingSubjectClass}">
                    <div class="mata-pelajaran">
                ${mataPelajaran ? mataPelajaran.nama_mapel : '-'}
                </div>
                    <div class="guru">
                        ${guru ? guru.nama : '-'}
                    </div>
                    <div class="kelas">
                        ${kelas ? `${kelas.tingkat} ${jurusan?.kode_jurusan ?? ''} ${kelas.no_kelas}` : '-'}
                    </div>
                </div>
            </div>
            `;
            });

            jadwalList.html(html);
        }

        function changeDate(date) {
            let isLoading = false;
            currentDate = date;
            $('#current-selected-date').val(date);

            // Update active day
            $('.day-item').removeClass('active');
            $('.day-item').each(function() {
                if ($(this).attr('onclick').includes(date)) {
                    $(this).addClass('active');
                }
            });

            loadJadwal(date);
        }

        function toggleCalendarView() {
            // const tanggalSekarang = '{{ $selectedDate->format('Y-m-d') }}';
            const url = isMonthlyView ?
                `/siswa/jadwal/perminggu?tanggal=${currentDate}` :
                `/siswa/jadwal/perbulan?tanggal=${currentDate}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('days-container').innerHTML = data.daysHtml;
                    isMonthlyView = !isMonthlyView;
                });
        }

        function loadMingguView(date) {
            $.get("{{ route('siswa.jadwal.perminggu') }}", {
                tanggal: date
            }, function(res) {
                $('#week-navigation #days-container').html(res.daysHtml);
                loadJadwal(date);
            });
        }

        function loadBulanView(date) {
            $.get("{{ route('siswa.jadwal.perbulan') }}", {
                tanggal: date
            }, function(res) {
                $('#month-navigation #days-container').html(res.daysHtml);
                loadJadwal(date);
            });
        }

        function changeWeek(direction) {
            const currentDateObj = new Date(currentDate);
            const newDate = new Date(currentDateObj);
            newDate.setDate(currentDateObj.getDate() + (direction * 7));

            const newDateString = newDate.toISOString().split('T')[0];

            $.ajax({
                url: '{{ route('siswa.jadwal.perminggu') }}',
                method: 'GET',
                data: {
                    tanggal: newDateString
                },
                success: function(response) {
                    $('#days-container').html(response.daysHtml);
                    currentDate = newDateString;
                    $('#current-selected-date').val(newDateString);
                    loadJadwal(newDateString);
                },
                error: function(xhr) {
                    console.error('Error getting week data:', xhr);
                }
            });
        }

        function changeMonth(direction) {
            const currentDateObj = new Date(currentDate);
            const newDate = new Date(currentDateObj);
            newDate.setMonth(currentDateObj.getMonth() + direction);

            const newDateString = newDate.toISOString().split('T')[0];

            $.ajax({
                url: '{{ route('siswa.jadwal.perbulan') }}',
                method: 'GET',
                data: {
                    tanggal: newDateString
                },
                success: function(response) {
                    $('#days-container').html(response.daysHtml);
                    currentDate = newDateString;
                    $('#current-selected-date').val(newDateString);
                    loadJadwal(newDateString);
                },
                error: function(xhr) {
                    console.error('Error getting month data:', xhr);
                }
            });
        }

        function loadJadwal(date) {
            $('#loading-indicator').show();
            $('#jadwal-list').hide();

            $.ajax({
                url: '{{ route('siswa.jadwal') }}',
                method: 'GET',
                data: {
                    tanggal: date
                },
                success: function(response) {
                    $('#current-date-text').text(response.selectedDate);
                    $('#today-badge').toggle(response.isToday);

                    $('#month-display').text(response.monthName);

                    updateJadwalDisplay(response.jadwal);

                    $('#loading-indicator').hide();
                    $('#jadwal-list').show();
                },
                error: function(xhr, status, error) {
                    console.error('loadJadwal ERROR:', xhr.responseText || error);
                    $('#loading-indicator').hide();
                    $('#jadwal-list').show();
                }
            });
        }

        console.log(response.daysHtml);

        // Cleanup interval when leaving page
        $(window).on('beforeunload', function() {
            if (realtimeInterval) {
                clearInterval(realtimeInterval);
            }
        });
    </script>

</body>

</html>
