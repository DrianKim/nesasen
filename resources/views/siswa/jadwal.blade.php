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

<body>
    <div class="jadwal-container">
        <!-- Header -->
        <div class="jadwal-header">
            <div class="back-button">
                <a href="{{ route('siswa.beranda') }}" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Jadwal
                </a>
            </div>
            <div class="calendar-button" id="toggle-view">
                <i class="fas fa-calendar"></i>
            </div>
        </div>

        <!-- Semester Info -->
        <div class="semester-info">
            <h4>Tahun Ajaran 2024-2025 - Semester 2</h4>
        </div>

        <!-- Month Header -->
        <div class="month-header">
            <button class="month-nav-btn" onclick="navigateMonth(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h3 id="month-display">Juni 2025</h3>
            <button class="month-nav-btn" onclick="navigateMonth(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- Week Navigation -->
        <div class="week-navigation" id="week-navigation">
            <div class="week-nav-flex">
                <div class="week-arrow-prev" onclick="changeWeek(-1)">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="days-container" id="week-days-container">
                    <!-- Week days will be populated here -->
                </div>
                <div class="week-arrow-next" onclick="changeWeek(1)">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>

        <!-- Month Navigation -->
        <div class="month-navigation" id="month-navigation">
            <div class="month-grid" id="month-grid">
                <!-- Month calendar will be populated here -->
            </div>
        </div>

        <!-- Current Date Display -->
        <div class="current-date">
            <div class="date-text" id="current-date-text">Kamis, 05 Juni 2025</div>
            <div class="today-badge" id="today-badge" style="display: none;">Hari Ini</div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" style="display: none;">
            <i class="fas fa-spinner fa-spin"></i> Memuat jadwal...
        </div>

        <!-- Schedule List -->
        <div class="jadwal-list" id="jadwal-list">
            <div class="empty-jadwal">
                <div class="empty-illustration">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div class="empty-text">Tidak ada jadwal untuk hari ini</div>
            </div>
        </div>

        <!-- Floating Add Button -->
        <div class="floating-button">
            <i class="fas fa-plus"></i>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        class JadwalCalendar {
            constructor() {
                this.currentDate = new Date();
                this.selectedDate = new Date();
                this.viewMode = 'week';
                this.isLoading = false;
                this.realtimeInterval = null;

                this.monthNames = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                this.dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                this.dayNamesShort = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

                this.routes = {
                    jadwal: '/siswa/jadwal',
                    jadwalPerhari: '/siswa/jadwal/perhari',
                    jadwalPerminggu: '/siswa/jadwal/perminggu',
                    jadwalPerbulan: '/siswa/jadwal/perbulan'
                };

                this.init();
            }

            init() {
                this.updateDisplay();
                this.bindEvents();
                this.loadSchedule();
                this.startRealtimeUpdates();
            }

            bindEvents() {
                $('#toggle-view').on('click', () => this.toggleView());
            }

            startRealtimeUpdates() {
                this.realtimeInterval = setInterval(() => {
                    this.updateJadwalStatus();
                }, 30000);
            }

            updateJadwalStatus() {
                if (this.isLoading) return;

                $.ajax({
                    url: this.routes.jadwalPerhari,
                    method: 'GET',
                    data: {
                        tanggal: this.formatDateISO(this.selectedDate)
                    },
                    success: (response) => {
                        this.updateJadwalDisplay(response.jadwal);
                    },
                    error: (xhr, status, error) => {
                        console.error('Error updating jadwal status:', error);
                    }
                });
            }

            toggleView() {
                this.viewMode = this.viewMode === 'week' ? 'month' : 'week';

                if (this.viewMode === 'month') {
                    $('#week-navigation').hide();
                    $('#month-navigation').show();
                    this.loadBulanView(this.formatDateISO(this.selectedDate));
                } else {
                    $('#month-navigation').hide();
                    $('#week-navigation').show();
                    this.loadMingguView(this.formatDateISO(this.selectedDate));
                }
            }

            updateDisplay() {
                const monthYear = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                $('#month-display').text(monthYear);

                if (this.viewMode === 'week') {
                    this.loadMingguView(this.formatDateISO(this.currentDate));
                } else {
                    this.loadBulanView(this.formatDateISO(this.currentDate));
                }
            }

            loadMingguView(date) {
                $.ajax({
                    url: this.routes.jadwalPerminggu,
                    method: 'GET',
                    data: {
                        tanggal: date
                    },
                    success: (response) => {
                        $('#week-days-container').html(response.daysHtml);
                    },
                    error: (xhr) => {
                        console.error('Error loading week view:', xhr);
                        this.renderWeekView();
                    }
                });
            }

            loadBulanView(date) {
                $.ajax({
                    url: this.routes.jadwalPerbulan,
                    method: 'GET',
                    data: {
                        tanggal: date
                    },
                    success: (response) => {
                        $('#month-grid').html(response.daysHtml);
                    },
                    error: (xhr) => {
                        console.error('Error loading month view:', xhr);
                        this.renderMonthView();
                    }
                });
            }

            renderMonthView() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                const firstDay = new Date(year, month, 1);

                const startDate = this.getStartOfWeek(firstDay);

                let html = '';

                const dayHeaders = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                dayHeaders.forEach(day => {
                    html += `<div class="month-day-name">${day}</div>`;
                });

                for (let i = 0; i < 42; i++) {
                    const currentDate = new Date(startDate);
                    currentDate.setDate(startDate.getDate() + i);

                    const isCurrentMonth = currentDate.getMonth() === month;
                    const isActive = this.isSameDay(currentDate, this.selectedDate);
                    const isToday = this.isSameDay(currentDate, new Date());

                    let classes = 'day-cell';
                    if (isActive) classes += ' active';
                    if (isToday) classes += ' today';
                    if (!isCurrentMonth) classes += ' other-month disabled';

                    // Jangan kasih onclick kalau other-month
                    const clickHandler = isCurrentMonth ?
                        `onclick="changeDate('${this.formatDateISO(currentDate)}'))"` : '';

                    html += `
                <div class="${classes}" ${clickHandler}>
                    ${currentDate.getDate()}
                </div>`;
                }

                // console.log('Generated HTML:', html);
                // console.log('After render:', $('#month-grid').html());
                $('#month-grid').html(html);
                $('#month-grid')[0].offsetHeight;
                // $('#month-grid').css('display', 'grid');
                // $('#month-grid').hide().show();
            }

            loadSchedule() {
                if (this.isLoading) return;

                this.isLoading = true;
                $('#loading-indicator').show();
                $('#jadwal-list').hide();

                const dateToSend = this.formatDateISO(this.selectedDate);
                console.log('Loading schedule for date:', dateToSend);
                console.log('Selected date object in loadSchedule:', this.selectedDate);

                $.ajax({
                    url: this.routes.jadwal,
                    method: 'GET',
                    data: {
                        tanggal: dateToSend
                    },
                    success: (response) => {
                        console.log('Server response:', response);
                        $('#current-date-text').text(response.selectedDate);
                        $('#today-badge').toggle(response.isToday);
                        $('#month-display').text(response.monthName);

                        this.updateJadwalDisplay(response.jadwal);

                        $('#loading-indicator').hide();
                        $('#jadwal-list').show();
                        this.isLoading = false;
                    },
                    error: (xhr, status, error) => {
                        console.error('loadJadwal ERROR:', xhr.responseText || error);
                        $('#loading-indicator').hide();
                        $('#jadwal-list').show();
                        this.isLoading = false;

                        $('#jadwal-list').html(`
                <div class="empty-jadwal">
                    <div class="empty-illustration">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="empty-text">Error memuat jadwal</div>
                </div>
            `);
                    }
                });
            }

            updateJadwalDisplay(jadwalData) {
                const jadwalList = $('#jadwal-list');

                if (!jadwalData || jadwalData.length === 0) {
                    jadwalList.html(`
                        <div class="empty-jadwal">
                            <div class="empty-illustration">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div class="empty-text">Tidak ada jadwal untuk hari ini</div>
                        </div>
                    `);
                    return;
                }

                let html = '';
                jadwalData.forEach((jadwal) => {
                    const completedClass = jadwal.is_selesai ? 'completed' : '';
                    const ongoingClass = jadwal.is_belum_selesai ? 'ongoing' : '';
                    const upcomingClass = (!jadwal.is_selesai && !jadwal.is_belum_selesai) ? 'upcoming' : '';

                    let statusIcon = '';
                    if (jadwal.is_selesai) {
                        statusIcon = '<div class="status-check"><i class="fas fa-check-circle"></i></div>';
                    } else if (jadwal.is_belum_selesai) {
                        statusIcon = '<div class="status-ongoing"><i class="fas fa-clock"></i></div>';
                    } else {
                        statusIcon =
                            '<div class="status-upcoming"><i class="fas fa-hourglass-start"></i></div>';
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

            getStartOfWeek(date) {
                const d = new Date(date);
                const day = d.getDay();

                const diff = day === 0 ? -6 : 1 - day;

                const monday = new Date(d);
                monday.setDate(d.getDate() + diff);

                return monday;
            }

            isSameDay(date1, date2) {
                return date1.getDate() === date2.getDate() &&
                    date1.getMonth() === date2.getMonth() &&
                    date1.getFullYear() === date2.getFullYear();
            }

            formatDate(date) {
                const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                const months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];

                return `${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
            }

            formatDateISO(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            }

            cleanup() {
                if (this.realtimeInterval) {
                    clearInterval(this.realtimeInterval);
                }
            }
            selectDate(dateString) {
                const dateParts = dateString.split('-');
                this.selectedDate = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[
                    2]));
                this.updateDisplay();
                this.loadSchedule();
            }
        }

        function changeDate(date) {
            const dateParts = date.split('-');
            const newSelectedDate = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));

            console.log('Selected date object:', newSelectedDate);
            console.log('Selected date ISO:', calendar.formatDateISO(newSelectedDate));

            const currentMonth = calendar.currentDate.getMonth();
            const selectedMonth = newSelectedDate.getMonth();

            calendar.selectedDate = newSelectedDate;

            if (selectedMonth !== currentMonth) {
                calendar.currentDate = new Date(newSelectedDate);

                const monthYear =
                    `${calendar.monthNames[calendar.currentDate.getMonth()]} ${calendar.currentDate.getFullYear()}`;
                $('#month-display').text(monthYear);

                if (calendar.viewMode === 'month') {
                    calendar.renderMonthView();
                } else {
                    calendar.renderWeekView();
                }
            }

            calendar.loadSchedule();

            $('.day-item-week, .day-cell').removeClass('active');

            $(`.day-item-week[onclick*="${date}"], .day-cell[onclick*="${date}"]`).addClass('active');
        }

        function changeWeek(direction) {
            const currentDateObj = new Date(calendar.formatDateISO(calendar.currentDate));
            const newDate = new Date(currentDateObj);
            newDate.setDate(currentDateObj.getDate() + (direction * 7));

            const newDateString = calendar.formatDateISO(newDate);
            calendar.currentDate = newDate;

            $.ajax({
                url: calendar.routes.jadwalPerminggu,
                method: 'GET',
                data: {
                    tanggal: newDateString
                },
                success: (response) => {
                    $('#week-days-container').html(response.daysHtml);
                    calendar.loadSchedule();
                },
                error: (xhr) => {
                    console.error('Error changing week:', xhr);
                }
            });
        }

        function navigateMonth(direction) {
            calendar.currentDate.setMonth(calendar.currentDate.getMonth() + direction);
            calendar.updateDisplay();
        }

        function goBack() {
            console.log('Go back');
        }

        let calendar;
        $(document).ready(function() {
            calendar = new JadwalCalendar();
        });

        $(window).on('beforeunload', function() {
            if (calendar) {
                calendar.cleanup();
            }
        });
    </script>
</body>

</html>
