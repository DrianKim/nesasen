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
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/style-izin.css') }}"> --}}

</head>

<body>
    <div class="jadwal-container">
        <!-- Header -->
        <div class="jadwal-header">
            <div class="back-button">
                <a href="#" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Jadwal
                </a>
            </div>
            <div class="calendar-button" id="toggle-view">
                <i class="fas fa-calendar"></i>
            </div>
        </div>

        <!-- Semester Info -->
        <div class="semester-info">
            <h4>Tahun Ajaran 2024-2025</h4>
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
                    @include('siswa.partials.days_perminggu')
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
            @include('siswa.partials.jadwal-list')
            {{-- <div class="empty-jadwal">
                <div class="empty-illustration">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div class="empty-text">Tidak ada jadwal untuk hari ini</div>
            </div> --}}
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
                this.viewMode = 'week'; // 'week' or 'month'
                this.monthNames = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                this.dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                this.dayNamesShort = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

                this.init();
            }

            init() {
                this.updateDisplay();
                this.bindEvents();
                this.loadSchedule();
            }

            bindEvents() {
                $('#toggle-view').on('click', () => this.toggleView());
            }

            toggleView() {
                this.viewMode = this.viewMode === 'week' ? 'month' : 'week';

                if (this.viewMode === 'month') {
                    $('#week-navigation').hide();
                    $('#month-navigation').show();
                    this.renderMonthView();
                } else {
                    $('#month-navigation').hide();
                    $('#week-navigation').show();
                    this.renderWeekView();
                }
            }

            updateDisplay() {
                const monthYear = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                $('#month-display').text(monthYear);

                const selectedDateText = this.formatDate(this.selectedDate);
                $('#current-date-text').text(selectedDateText);

                const isToday = this.isSameDay(this.selectedDate, new Date());
                $('#today-badge').toggle(isToday);

                if (this.viewMode === 'week') {
                    this.renderWeekView();
                } else {
                    this.renderMonthView();
                }
            }

            renderWeekView() {
                // Call server to get week data
                $.ajax({
                    url: '{{ route('siswa.jadwal.perminggu') }}',
                    method: 'GET',
                    data: {
                        tanggal: this.formatDateISO(this.currentDate)
                    },
                    success: (response) => {
                        $('#week-days-container').html(response.daysHtml);
                    },
                    error: (xhr) => {
                        console.error('Error loading week view:', xhr);
                        // Fallback to client-side rendering
                        this.renderWeekViewFallback();
                    }
                });
            }

            renderWeekViewFallback() {
                const startOfWeek = this.getStartOfWeek(this.currentDate);
                let html = '';

                for (let i = 0; i < 7; i++) {
                    const date = new Date(startOfWeek);
                    date.setDate(startOfWeek.getDate() + i);
                    const
                        isActive = this.isSameDay(date, this.selectedDate);
                    const isToday = this.isSameDay(date, new Date());
                    let
                        classes = 'day-item';
                    if (isActive) classes += ' active';
                    if (isToday) classes += ' today';
                    html += ` <div
        class="${classes}" onclick="changeDate('${this.formatDateISO(date)}')">
        <div class="day-name">${this.dayNamesShort[i]}</div>
        <div class="day-number">${date.getDate()}</div>
        </div>
        `;
                }

                $('#week-days-container').html(html);
            }

            renderMonthView() {
                // Call server to get month data
                $.ajax({
                    url: '{{ route('siswa.jadwal.perbulan') }}',
                    method: 'GET',
                    data: {
                        bulan: this.currentDate.getMonth() + 1,
                        tahun: this.currentDate.getFullYear()
                    },
                    success: (response) => {
                        $('#month-grid').html(response.monthHtml);
                    },
                    error: (xhr) => {
                        console.error('Error loading month view:', xhr);
                        // Fallback to client-side rendering
                        this.renderMonthViewFallback();
                    }
                });
            }

            renderMonthViewFallback() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startDate = this.getStartOfWeek(firstDay);

                let html = '';

                // Add day headers
                this.dayNames.forEach(day => {
                    html += `<div class="month-day-name">${day}</div>`;
                });

                // Add calendar days
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 42); // 6 weeks

                for (let date = new Date(startDate); date < endDate; date.setDate(date.getDate() + 1)) {
                    const
                        isCurrentMonth = date.getMonth() === month;
                    const isActive = this.isSameDay(date, this.selectedDate);
                    const
                        isToday = this.isSameDay(date, new Date());
                    let classes = 'day-cell';
                    if (isActive) classes += ' active';
                    if (isToday) classes += ' today';
                    if (!isCurrentMonth) classes += ' other-month';
                    html += ` <div
            class="${classes}" onclick="changeDate('${this.formatDateISO(date)}')">
            ${date.getDate()}
            </div>
            `;
                }

                $('#month-grid').html(html);
            }

            selectDate(dateString) {
                this.selectedDate = new Date(dateString + 'T00:00:00');
                this.updateDisplay();
                this.loadSchedule();
            }

            getStartOfWeek(date) {
                const d = new Date(date);
                const day = d.getDay();
                const diff = d.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is Sunday
                return new Date(d.setDate(diff));
            }

            isSameDay(date1, date2) {
                return date1.getDate() === date2.getDate() &&
                    date1.getMonth() === date2.getMonth() &&
                    date1.getFullYear() === date2.getFullYear();
            }

            formatDate(date) {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];

                return `${days[date.getDay()]}, ${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
            }

            formatDateISO(date) {
                return date.toISOString().split('T')[0];
            }

            loadSchedule() {
                $('#loading-indicator').show();
                $('#jadwal-list').hide();

                $.ajax({
                    url: '{{ route('siswa.jadwal.perhari') }}',
                    method: 'GET',
                    data: {
                        tanggal: this.formatDateISO(this.selectedDate)
                    },
                    success: (response) => {
                        $('#jadwal-list').html(response.jadwalHtml);
                        $('#loading-indicator').hide();
                        $('#jadwal-list').show();
                    },
                    error: (xhr) => {
                        $('#loading-indicator').hide();
                        $('#jadwal-list').show();
                        console.error('Error loading schedule:', xhr);

                        // Show error message
                        const errorHtml = `
            <div class="empty-jadwal">
                <div class="empty-illustration">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="empty-text">Gagal memuat jadwal. Silakan coba lagi.</div>
            </div>
            `;
                        $('#jadwal-list').html(errorHtml);
                    }
                });
            }
        }

        // Global functions for navigation
        function changeDate(date) {
            calendar.selectedDate = new Date(date + 'T00:00:00');
            calendar.updateDisplay();
            calendar.loadSchedule();

            // Update week/month view to reflect new selected date
            if (calendar.viewMode === 'week') {
                calendar.renderWeekView();
            } else {
                calendar.renderMonthView();
            }
        }

        function changeWeek(direction) {
            // Update currentDate by adding/subtracting 7 days
            calendar.currentDate.setDate(calendar.currentDate.getDate() + (direction * 7));
            // Set selectedDate to the new currentDate
            calendar.selectedDate = new Date(calendar.currentDate);
            calendar.updateDisplay();

            // Fetch new week data from server
            $.ajax({
                url: '{{ route('siswa.jadwal.perminggu') }}',
                method: 'GET',
                data: {
                    tanggal: calendar.formatDateISO(calendar.currentDate)
                },
                beforeSend: function() {
                    $('#loading-indicator').show();
                    $('#jadwal-list').hide();
                },
                success: function(response) {
                    // Update week days container
                    $('#week-days-container').html(response.daysHtml);

                    // Update schedule list for the selected date
                    $('#jadwal-list').html(response.jadwalHtml);

                    $('#loading-indicator').hide();
                    $('#jadwal-list').show();
                },
                error: function(xhr) {
                    $('#loading-indicator').hide();
                    $('#jadwal-list').show();
                    console.error('Error getting week data:', xhr);

                    // Fallback to client-side rendering
                    calendar.renderWeekViewFallback();
                    calendar.loadSchedule();
                }
            });
        }

        function navigateMonth(direction) {
            calendar.currentDate.setMonth(calendar.currentDate.getMonth() + direction);
            calendar.updateDisplay();

            // If in month view, fetch new month data
            if (calendar.viewMode === 'month') {
                calendar.renderMonthView();
            } else {
                // If in week view, fetch new week data for the new month
                calendar.renderWeekView();
            }

            // Load schedule for current selected date
            calendar.loadSchedule();
        }

        function goBack() {
            // Implement back navigation
            if (typeof window !== 'undefined' && window.history && window.history.length > 1) {
                window.history.back();
            } else {
                // Fallback - redirect to dashboard or main page
                window.location.href = '{{ route('siswa.beranda') }}';
            }
        }

        // Initialize calendar
        let calendar;
        $(document).ready(function() {
            calendar = new JadwalCalendar();

            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

</body>

</html>
