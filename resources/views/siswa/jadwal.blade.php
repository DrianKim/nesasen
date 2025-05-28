@extends('layouts.app')

@section('content')
    <div class="jadwal-container">
        <!-- Judul dan Navigasi -->
        <div class="jadwal-header">
            <div class="back-button">
                <a href="{{ route('siswa.beranda') }}">
                    <i class="fas fa-arrow-left"></i> Jadwal
                </a>
            </div>
            <div class="calendar-button">
                <a href="#">
                    <i class="fas fa-calendar"></i>
                </a>
            </div>
        </div>

        <!-- Tahun Ajaran dan Semester -->
        <div class="semester-info">
            <h4>Tahun Ajaran 2024-2025 - Semester 2</h4>
        </div>

        <!-- Bulan dan Tahun -->
        <div class="month-header">
            <h3 id="month-display">{{ $monthName }}</h3>
        </div>

        <!-- Navigasi Minggu -->
        <div class="week-navigation">
            <div class="nav-arrow-prev">
                <a href="#" onclick="changeWeek(-1)">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>

            <div class="days-container" id="days-container">
                @include('siswa.partials.days')
            </div>

            <div class="nav-arrow-next">
                <a href="#" onclick="changeWeek(1)">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Tanggal yang ditampilkan -->
        <div class="current-date">
            <div class="date-text" id="current-date-text">{{ $selectedDate->translatedFormat('l, d F Y') }}</div>
            <div class="today-badge" id="today-badge" style="{{ $selectedDate->isToday() ? '' : 'display: none;' }}">Hari
                Ini</div>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" style="display: none; text-align: center; padding: 20px;">
            <i class="fas fa-spinner fa-spin"></i> Memuat jadwal...
        </div>

        <!-- Daftar Jadwal -->
        <div class="jadwal-list" id="jadwal-list">
            @if ($jadwalHariIni->count() > 0)
                @foreach ($jadwalHariIni as $jadwal)
                    <div
                        class="jadwal-item {{ $jadwal->is_selesai ? 'completed' : '' }} {{ $jadwal->is_belum_selesai ? 'ongoing' : '' }}">
                        <div class="waktu-container">
                            <div class="waktu">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </div>
                            @if ($jadwal->is_selesai)
                                <div class="status-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            @elseif($jadwal->is_belum_selesai)
                                <div class="status-ongoing">
                                    <i class="fas fa-clock"></i>
                                </div>
                            @endif
                        </div>
                        <div class="mata-pelajaran-container {{ $jadwal->is_belum_selesai ? 'belum-selesai' : '' }}">
                            <div class="mata-pelajaran">
                                {{ $jadwal->mapelKelas->mataPelajaran->nama_mapel }}
                            </div>
                            <div class="guru">
                                {{ $jadwal->mapelKelas->guru->nama }}
                            </div>
                            <div class="kelas">
                                Kelas {{ $jadwal->mapelKelas->kelas->tingkat }}
                                {{ $jadwal->mapelKelas->kelas->jurusan->kode_jurusan }}
                                {{ $jadwal->mapelKelas->kelas->no_kelas }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-jadwal">
                    <div class="empty-illustration">
                        <img src="{{ asset('assets/img/not-found.png') }}" alt="Tidak ada jadwal">
                    </div>
                    <div class="empty-text">Tidak ada jadwal untuk hari ini</div>
                </div>
            @endif
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
@endsection

<script>
    let currentDate = '{{ $selectedDate->format('Y-m-d') }}';
    let realtimeInterval;

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

            let statusIcon = '';
            if (jadwal.is_selesai) {
                statusIcon = '<div class="status-check"><i class="fas fa-check-circle"></i></div>';
            } else if (jadwal.is_belum_selesai) {
                statusIcon = '<div class="status-ongoing"><i class="fas fa-clock"></i></div>';
            }

            const ongoingSubjectClass = jadwal.is_belum_selesai ? 'belum-selesai' : '';

            html += `
            <div class="jadwal-item ${completedClass} ${ongoingClass}">
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
                        ${jadwal.mapel_kelas.mata_pelajaran.nama_mapel}
                    </div>
                    <div class="guru">
                        ${jadwal.mapel_kelas.guru.nama}
                    </div>
                    <div class="kelas">
                        Kelas ${jadwal.mapel_kelas.kelas.tingkat}
                        ${jadwal.mapel_kelas.kelas.jurusan.kode_jurusan}
                        ${jadwal.mapel_kelas.kelas.no_kelas}
                    </div>
                </div>
            </div>
        `;
        });

        jadwalList.html(html);
    }

    function changeDate(date) {
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

    // Cleanup interval when leaving page
    $(window).on('beforeunload', function() {
        if (realtimeInterval) {
            clearInterval(realtimeInterval);
        }
    });
</script>
