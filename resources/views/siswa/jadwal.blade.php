@extends('siswa.layouts.app')
@php
    $nama = explode(' ', Auth::user()->siswa->nama);
    $namaPendek = implode(' ', array_slice($nama, 0, 1));
@endphp
@section('content')
    <main>
        <h2 class="izin-title">Jadwal</h2>

        <div class="calendar-container">
            <!-- Toggle Mingguan / Bulanan -->
            <div class="calendar-top-bar">
                <div class="tahun-ajaran">Tahun Ajaran 2024-2025 - Semester 2</div>
                <div class="calendar-toggle-icon">
                    <button class="toggle-icon-btn active" id="week-toggle" onclick="switchView('week')">
                        <span class="material-icons-sharp">calendar_view_week</span>
                    </button>
                    <button class="toggle-icon-btn" id="month-toggle" onclick="switchView('month')">
                        <span class="material-icons-sharp">calendar_view_month</span>
                    </button>
                </div>
            </div>

            <!-- Header Kalender -->
            <div class="calendar-header">
                <button class="nav-btn" id="prev-btn">
                    <span class="material-icons-sharp">chevron_left</span>
                </button>
                <h3 id="month-year-display">Juni 2025</h3>
                <button class="nav-btn" id="next-btn">
                    <span class="material-icons-sharp">chevron_right</span>
                </button>
            </div>

            <!-- Mingguan -->
            <div class="calendar-week-view" id="week-view">
                <div class="week-days" id="week-days-container">
                    <!-- Week days akan di-populate dari JavaScript -->
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="jadwal-today-section">
                    <div class="date-and-badge">
                        <h4 id="selected-date-display">Kamis, 19 Juni 2025</h4>
                        <div id="today-badge" style="display: none;">Hari Ini</div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loading-indicator" style="display: none; text-align: center; padding: 20px;">
                        <i class="fas fa-spinner fa-spin"></i> Memuat jadwal...
                    </div>

                    <div id="jadwal-list-week">
                        <div class="no-schedule" id="empty-jadwal-week">
                            <p>Tidak ada jadwal untuk hari ini</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulanan -->
            <div class="calendar-month-view" id="month-view" style="display: none">
                <div class="month-days-grid" id="month-grid">
                    <!-- Grid bulanan akan di-populate dari JavaScript -->
                </div>

                <div class="jadwal-today-section">
                    <h4 id="selected-date-display-month">Pilih tanggal untuk melihat jadwal</h4>
                    <!-- Loading Indicator -->
                    <div id="loading-indicator" style="display: none; text-align: center; padding: 20px;">
                        <i class="fas fa-spinner fa-spin"></i> Memuat jadwal...
                    </div>
                    <div id="jadwal-list-month">
                        <div class="no-schedule" id="empty-jadwal-month">
                            <p>Tidak ada jadwal untuk hari ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End of Main Content -->

    <!-- Right Section -->
    <div class="right-section">
        <div class="nav">
            <button id="menu-btn">
                <span class="material-icons-sharp"> menu </span>
            </button>

            <div class="date-today">
                <span id="tanggal-hari-ini"></span>
            </div>

            <div class="dark-mode">
                <span class="material-icons-sharp active"> light_mode </span>
                <span class="material-icons-sharp"> dark_mode </span>
            </div>

            <div class="profile">
                <div class="info">
                    <p>Hallo, <b>{{ $namaPendek }}</b></p>
                    <small class="text-muted">Siswa</small>
                </div>
                <div class="profile-photo">
                    <img src="{{ asset('assets/img/smeapng.png') }}" />
                </div>
            </div>
        </div>

        <!-- End of Nav -->

        <div class="news">
            <iframe src="https://www.instagram.com/p/DJWiNpzSK2i/embed" width="100%" height="335" frameborder="0"
                scrolling="no" allowtransparency="true">
            </iframe>
        </div>

        <div class="reminders hide">
            <div class="header">
                <h2>Reminders</h2>
            </div>

            <div class="notification">
                <div class="icon">
                    <span class="material-icons-sharp"> mosque </span>
                </div>
                <div class="content">
                    <div class="info">
                        <h3>Sholat Ashar</h3>
                        <small class="text_muted"> 15:10 - 15:30 </small>
                    </div>
                    <span class="material-icons-sharp"> more_vert </span>
                </div>
            </div>

            @include('siswa.modal.reminder')
            <div class="notification add-reminder">
                <div onclick="openReminderModal()" style="cursor: pointer;">
                    <span class="material-icons-sharp">add</span>
                    <h3>Add Reminder</h3>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        class JadwalCalendarNew {
            constructor() {
                this.currentDate = new Date();
                this.selectedDate = new Date();
                this.viewMode = 'week'; // default ke week view
                this.isLoading = false;
                this.realtimeInterval = null;

                // Indonesian month and day names
                this.monthNames = [
                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                this.dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                this.dayNamesShort = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

                // API Routes - sama seperti yang lama
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
                // Navigation buttons
                $('#prev-btn').on('click', () => this.navigatePrevious());
                $('#next-btn').on('click', () => this.navigateNext());

                // View toggle sudah ada onclick di HTML
            }

            startRealtimeUpdates() {
                // Realtime update setiap 30 detik - sama seperti yang lama
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

            switchView(mode) {
                this.viewMode = mode;

                // Update toggle buttons
                $('.toggle-icon-btn').removeClass('active');
                if (mode === 'week') {
                    $('#week-toggle').addClass('active');
                    $('#week-view').show();
                    $('#month-view').hide();
                    this.loadWeekView();
                } else {
                    $('#month-toggle').addClass('active');
                    $('#month-view').show();
                    $('#week-view').hide();
                    this.loadMonthView();
                }
            }

            navigatePrevious() {
                if (this.viewMode === 'week') {
                    // Navigate ke minggu sebelumnya
                    this.currentDate.setDate(this.currentDate.getDate() - 7);
                    this.selectedDate.setDate(this.selectedDate.getDate() - 7);
                } else {
                    // Navigate ke bulan sebelumnya
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                }
                this.updateDisplay();
            }

            navigateNext() {
                if (this.viewMode === 'week') {
                    // Navigate ke minggu selanjutnya
                    this.currentDate.setDate(this.currentDate.getDate() + 7);
                    this.selectedDate.setDate(this.selectedDate.getDate() + 7);
                } else {
                    // Navigate ke bulan selanjutnya
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                }
                this.updateDisplay();
            }

            updateDisplay() {
                // Update month-year display
                const monthYear = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                $('#month-year-display').text(monthYear);

                // Load appropriate view
                if (this.viewMode === 'week') {
                    this.loadWeekView();
                } else {
                    this.loadMonthView();
                }
            }

            loadWeekView() {
                const startOfWeek = this.getStartOfWeek(this.currentDate);
                let weekHtml = '';

                for (let i = 0; i < 7; i++) {
                    const currentDay = new Date(startOfWeek);
                    currentDay.setDate(startOfWeek.getDate() + i);

                    const isActive = this.isSameDay(currentDay, this.selectedDate);
                    const activeClass = isActive ? 'active' : '';
                    const dayDate = currentDay.getDate();
                    const dayName = this.dayNamesShort[i];

                    weekHtml += `
                        <div class="day ${activeClass}" onclick="selectDate('${this.formatDateISO(currentDay)}')">
                            <p>${dayName}</p>
                            <span>${dayDate}</span>
                        </div>
                    `;
                }

                $('#week-days-container').html(weekHtml);
                this.loadSchedule();
            }

            loadMonthView() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);

                // Start dari hari senin minggu pertama
                const startDate = this.getStartOfWeek(firstDay);

                let monthHtml = '';

                // Generate 42 cells (6 weeks x 7 days)
                for (let i = 0; i < 42; i++) {
                    const currentDate = new Date(startDate);
                    currentDate.setDate(startDate.getDate() + i);

                    const isCurrentMonth = currentDate.getMonth() === month;
                    const isActive = this.isSameDay(currentDate, this.selectedDate);
                    const isToday = this.isSameDay(currentDate, new Date());

                    let classes = 'cell';
                    if (!isCurrentMonth) classes += ' not-now';
                    if (isActive) classes += ' active';
                    if (isToday) classes += ' today';

                    const clickHandler = isCurrentMonth ? `onclick="selectDate('${this.formatDateISO(currentDate)}')"` :
                        '';

                    monthHtml += `
                        <div class="${classes}" ${clickHandler}>
                            ${currentDate.getDate()}
                        </div>
                    `;
                }

                $('#month-grid').html(monthHtml);
            }

            loadSchedule() {
                if (this.isLoading) return;

                this.isLoading = true;
                $('#loading-indicator').show();

                const dateToSend = this.formatDateISO(this.selectedDate);
                console.log('Loading schedule for date:', dateToSend);

                $.ajax({
                    url: this.routes.jadwal,
                    method: 'GET',
                    data: {
                        tanggal: dateToSend
                    },
                    success: (response) => {
                        console.log('Server response:', response);

                        // Update selected date display
                        $('#selected-date-display, #selected-date-display-month').text(response
                            .selectedDate);
                        $('#today-badge').toggle(response.isToday);

                        this.updateJadwalDisplay(response.jadwal);

                        $('#loading-indicator').hide();
                        this.isLoading = false;
                    },
                    error: (xhr, status, error) => {
                        console.error('loadJadwal ERROR:', xhr.responseText || error);
                        $('#loading-indicator').hide();
                        this.isLoading = false;

                        // Show error state
                        const errorHtml = `
                            <div class="no-schedule">
                                <p>Error memuat jadwal</p>
                            </div>
                        `;
                        $('#jadwal-list-week, #jadwal-list-month').html(errorHtml);
                    }
                });
            }

            updateJadwalDisplay(jadwalData) {
                const jadwalListWeek = $('#jadwal-list-week');
                const jadwalListMonth = $('#jadwal-list-month');

                if (!jadwalData || jadwalData.length === 0) {
                    const emptyHtml = `
            <div class="no-schedule">
                <p>Tidak ada jadwal untuk hari ini</p>
            </div>
        `;
                    jadwalListWeek.html(emptyHtml);
                    jadwalListMonth.html(emptyHtml);
                    return;
                }

                let html = '';
                jadwalData.forEach((jadwal) => {
                    const statusClass = jadwal.is_selesai ?
                        'completed' :
                        jadwal.is_berlangsung ?
                        'ongoing' :
                        'upcoming';

                    const statusIcon = jadwal.is_selesai ?
                        '<div class="status-check"><i class="fas fa-check-circle"></i></div>' :
                        jadwal.is_berlangsung ?
                        '<div class="status-ongoing"><i class="fas fa-clock"></i></div>' :
                        '<div class="status-upcoming"><i class="fas fa-hourglass-start"></i></div>';

                    const mapelKelas = jadwal.mapel_kelas ?? {};
                    const mataPelajaran = mapelKelas.mata_pelajaran ?? {};
                    const guru = mapelKelas.guru ?? {};
                    const kelas = mapelKelas.kelas ?? {};
                    const jurusan = kelas.jurusan ?? {};

                    const jamMulai = typeof jadwal.jam_mulai === 'string' ? jadwal.jam_mulai.slice(0, 5) :
                        '--:--';
                    const jamSelesai = typeof jadwal.jam_selesai === 'string' ? jadwal.jam_selesai.slice(0, 5) :
                        '--:--';

                    html += `
            <div class="jadwal-card ${statusClass}">
                <div class="jadwal-waktu">
                    <span>${jamMulai} - ${jamSelesai}</span>
                    ${statusIcon}
                </div>
                <div class="jadwal-detail">
                    <h5>${mataPelajaran.nama_mapel ?? '-'}</h5>
                    <p>Guru: ${guru.nama ?? '-'}</p>
                    <small>${kelas.tingkat ?? ''} ${jurusan.kode_jurusan ?? ''} ${kelas.no_kelas ?? ''}</small>
                </div>
            </div>
        `;
                });

                jadwalListWeek.html(html);
                jadwalListMonth.html(html);
            }

            // Utility functions - sama seperti yang lama
            getStartOfWeek(date) {
                const d = new Date(date);
                const day = d.getDay();
                const diff = day === 0 ? -6 : 1 - day; // Adjust when day is Sunday
                const monday = new Date(d);
                monday.setDate(d.getDate() + diff);
                return monday;
            }

            isSameDay(date1, date2) {
                return date1.getDate() === date2.getDate() &&
                    date1.getMonth() === date2.getMonth() &&
                    date1.getFullYear() === date2.getFullYear();
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
        }

        // Global functions untuk onclick handlers
        function switchView(mode) {
            if (window.jadwalCalendar) {
                window.jadwalCalendar.switchView(mode);
            }
        }

        function selectDate(dateString) {
            if (window.jadwalCalendar) {
                const dateParts = dateString.split('-');
                const newSelectedDate = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[
                    2]));

                jadwalCalendar.selectedDate = newSelectedDate;
                jadwalCalendar.loadSchedule();

                // Update active states
                $('.day, .cell').removeClass('active');
                $(`.day[onclick*="${dateString}"], .cell[onclick*="${dateString}"]`).addClass('active');
            }
        }

        // Initialize calendar when document ready
        $(document).ready(function() {
            window.jadwalCalendar = new JadwalCalendarNew();
        });

        // Cleanup on page unload
        $(window).on('beforeunload', function() {
            if (window.jadwalCalendar) {
                window.jadwalCalendar.cleanup();
            }
        });
    </script>


    {{-- <script>
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
                    if (isActive) classes += 'active';
                    if (isToday) classes += 'today';
                    if (!isCurrentMonth) classes += 'other-month disabled';

                    // Jangan kasih onclick kalau other-month
                    const clickHandler = isCurrentMonth ?
                        `onclick="changeDate('${this.formatDateISO(currentDate)}')"` : '';

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
                    const statusClass = jadwal.is_selesai ?
                        'completed' :
                        jadwal.is_berlangsung ?
                        'ongoing' :
                        'upcoming';

                    const statusIcon = jadwal.is_selesai ?
                        '<div class="status-check"><i class="fas fa-check-circle"></i></div>' :
                        jadwal.is_berlangsung ?
                        '<div class="status-ongoing"><i class="fas fa-clock"></i></div>' :
                        '<div class="status-upcoming"><i class="fas fa-hourglass-start"></i></div>';

                    const mapelKelas = jadwal.mapel_kelas;
                    const mataPelajaran = mapelKelas?.mata_pelajaran;
                    const siswa = mapelKelas?.siswa;
                    const kelas = mapelKelas?.kelas;
                    const jurusan = kelas?.jurusan;

                    const jamMulai = (jadwal.jam_mulai ?? '00:00').substring(0, 5);
                    const jamSelesai = (jadwal.jam_selesai ?? '00:00').substring(0, 5);

                    html += `
            <div class="jadwal-item ${statusClass}">
                <div class="waktu-container">
                    <div class="waktu">${jamMulai} - ${jamSelesai}</div>
                    ${statusIcon}
                </div>
                <div class="mata-pelajaran-container ${statusClass === 'ongoing' ? 'belum-selesai' : ''}">
                    <div class="mata-pelajaran">${mataPelajaran?.nama_mapel ?? '-'}</div>
                    <div class="siswa">${siswa?.nama ?? '-'}</div>
                    <div class="kelas">${kelas ? `${kelas.tingkat} ${jurusan?.kode_jurusan ?? ''} ${kelas.no_kelas}` : '-'}</div>
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

        function switchView(view) {
            if (view === 'week') {
                $('.calendar-week-view').show();
                $('.calendar-month-view').hide();
            } else {
                $('.calendar-week-view').hide();
                $('.calendar-month-view').show();
            }

            $('.toggle-icon-btn').removeClass('active');
            $(`.toggle-icon-btn[onclick*="${view}"]`).addClass('active');
        }

        let currentMonth = new Date();

        function updateMonthDisplay() {
            const bulan = currentMonth.toLocaleDateString('id-ID', {
                month: 'long',
                year: 'numeric'
            });
            $('.calendar-header h3').text(bulan);
            // Nanti panggil ulang AJAX data bulan juga
        }

        $('.nav-btn').eq(0).on('click', () => {
            currentMonth.setMonth(currentMonth.getMonth() - 1);
            updateMonthDisplay();
        });

        $('.nav-btn').eq(1).on('click', () => {
            currentMonth.setMonth(currentMonth.getMonth() + 1);
            updateMonthDisplay();
        });


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

        function loadJadwalPerHari(tanggal) {
            $.get('/siswa/jadwal', {
                tanggal
            }, (res) => {
                let html = '';
                if (res.jadwal.length === 0) {
                    html = '<p>Tidak ada jadwal untuk hari ini</p>';
                } else {
                    res.jadwal.forEach(j => {
                        html += `
                    <div class="jadwal-card">
                        <div class="jadwal-waktu">
                            <span>${j.jam_mulai} - ${j.jam_selesai}</span>
                        </div>
                        <div class="jadwal-detail">
                            <h5>${j.mapel}</h5>
                            <p>Siswa: ${j.siswa}</p>
                            <small>${j.ruangan ?? '-'}</small>
                        </div>
                    </div>
                `;
                    });
                }
                $('.jadwal-today-section').html(html);
            });
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
    </script> --}}
@endsection
