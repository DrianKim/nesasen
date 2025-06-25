@extends('guru.layouts.app')
@php
    $nama = explode(' ', Auth::user()->guru->nama);
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
                    <small class="text-muted">Guru</small>
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

            @include('guru.modal.reminder')
            <div class="notification add-reminder">
                <div onclick="openReminderModal()" style="cursor: pointer;">
                    <span class="material-icons-sharp">add</span>
                    <h3>Add Reminder</h3>
                </div>
            </div>
        </div>
    </div>

    @include('guru.modal.modal-presensi')

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
                    jadwal: '/guru/jadwal',
                    jadwalPerhari: '/guru/jadwal/perhari',
                    jadwalPerminggu: '/guru/jadwal/perminggu',
                    jadwalPerbulan: '/guru/jadwal/perbulan'
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

                    const mapelKelas = jadwal.mapel_kelas;
                    const mataPelajaran = mapelKelas?.mata_pelajaran;
                    const guru = mapelKelas?.guru;
                    const kelas = mapelKelas?.kelas;
                    const jurusan = kelas?.jurusan;
                    const totalSiswa = jadwal.total_siswa_kelas;
                    const totalHadirSiswa = jadwal.total_hadir_kelas;
                    const tanggalFormatted = formatTanggalIndonesia(jadwal.tanggal);    

                    const jamMulai = (jadwal.jam_mulai ?? '00:00').substring(0, 5);
                    const jamSelesai = (jadwal.jam_selesai ?? '00:00').substring(0, 5);

                    html += `
        <div class="jadwal-card ${statusClass}">
            <div class="jadwal-waktu">
                <span>${jamMulai} - ${jamSelesai}</span>
                ${statusIcon}
            </div>
            <div class="jadwal-detail">
                <h5>${mataPelajaran?.nama_mapel ?? '-'}</h5>
                <p>Guru: ${guru?.nama ?? '-'}</p>
                <small>
                    ${kelas ? `${kelas.tingkat} ${jurusan?.kode_jurusan ?? ''} ${kelas.no_kelas}` : '-'}
                    — Total ${totalSiswa} siswa
                </small>

                <button
                    class="btn-detail-presensi"
                    data-presensi='${encodeURIComponent(JSON.stringify(jadwal.siswa_presensi ?? []))}'
                    data-kelas='${kelas ? `${kelas.tingkat} ${jurusan?.kode_jurusan ?? ''} ${kelas.no_kelas}` : "-"}'
                    data-tanggal='${tanggalFormatted ?? "-"}'
                    style="display: flex; align-items: center; gap: 4px;"
                >
                    <span class="material-icons-sharp" style="font-size:0.8rem; vertical-align: middle;">visibility</span>
                    <span style="vertical-align: middle;">Lihat Detail Presensi</span>
                </button>
            </div>
        </div>`;
                });

                jadwalListWeek.html(html);
                jadwalListMonth.html(html);

                $('.btn-detail-presensi').on('click', function() {
                    const presensiData = JSON.parse(decodeURIComponent($(this).data('presensi')));
                    const namaKelas = $(this).data('kelas');
                    const tanggal = $(this).data('tanggal');

                    openModalPresensi(presensiData, namaKelas, tanggal);
                });
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

        function formatTanggalIndonesia(tanggalStr) {
            const tanggal = new Date(tanggalStr);
            const options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            return tanggal.toLocaleDateString('id-ID', options);
        }

        function openModalPresensi(presensiData, namaKelas, tanggal) {
            const statusGroups = {
                hadir: [],
                terlambat: [],
                izin: [],
                alpa: [],
            };

            presensiData.forEach((siswa) => {
                const status = siswa.status?.toLowerCase() || 'alpa';
                if (statusGroups[status]) {
                    statusGroups[status].push(siswa);
                } else {
                    statusGroups.alpa.push(siswa);
                }
            });

            Object.keys(statusGroups).forEach(key => {
                statusGroups[key].sort((a, b) => a.nama.localeCompare(b.nama));
            });

            $('#judulPresensi').text(`Data Presensi Siswa Kelas ${namaKelas}`);
            $('#tanggalPresensi').text(tanggal);

            let html = '';
            const iconMap = {
                hadir: '✅',
                terlambat: '🕒',
                izin: '📄',
                alpa: '❌',
            };
            const labelMap = {
                hadir: 'Hadir',
                terlambat: 'Terlambat',
                izin: 'Izin',
                alpa: 'Belum Presensi',
            };

            for (let status of ['hadir', 'terlambat', 'izin', 'alpa']) {
                const list = statusGroups[status];
                if (list.length === 0) continue;

                html += `<h4>${iconMap[status]} ${labelMap[status]} (${list.length})</h4><ul>`;
                list.forEach(s => {
                    const alasan = s.alasan ? ` <small style="color:gray;">(${s.alasan})</small>` : '';
                    html += `<li>${iconMap[status]} ${s.nama}${alasan}</li>`;
                });
                html += '</ul>';
            }

            $('#modalPresensiContent').html(html);
            $('#modalPresensi').fadeIn(200);
        }

        function closeModalPresensi() {
            $('#modalPresensi').fadeOut(200);
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
@endsection
