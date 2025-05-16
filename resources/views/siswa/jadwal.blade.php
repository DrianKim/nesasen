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
            <h3>{{ $monthName }}</h3>
        </div>

        <!-- Navigasi Minggu -->
        <div class="week-navigation">
            <div class="nav-arrow prev">
                <a href="{{ route('siswa.jadwal', ['tanggal' => $selectedDate->copy()->subWeek()->format('Y-m-d')]) }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>

            <div class="days-container">
                @foreach ($daysOfWeek as $day)
                    <div class="day-item {{ $day->isSameDay($selectedDate) ? 'active' : '' }}">
                        <a href="{{ route('siswa.jadwal', ['tanggal' => $day->format('Y-m-d')]) }}">
                            <div class="day-name">{{ $day->translatedFormat('dddd') }}</div> <!-- Full nama hari -->
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="nav-arrow next">
                <a href="{{ route('siswa.jadwal', ['tanggal' => $selectedDate->copy()->addWeek()->format('Y-m-d')]) }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

        <!-- Tanggal yang ditampilkan -->
        <div class="current-date">
            <div class="date-text">{{ $selectedDate->translatedFormat('l, d F Y') }}</div>
            @if ($selectedDate->isToday())
                <div class="today-badge">Hari Ini</div>
            @endif
        </div>

        <!-- Daftar Jadwal -->
        <div class="jadwal-list">
            @if ($jadwalHariIni->count() > 0)
                @foreach ($jadwalHariIni as $jadwal)
                    <div class="jadwal-item">
                        <div class="waktu-container">
                            <div class="waktu">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </div>
                        </div>
                        <div class="mata-pelajaran-container">
                            <div class="mata-pelajaran">
                                {{ $jadwal->mapelKelas->mataPelajaran->nama_mapel ?? '-' }}
                            </div>
                            <div class="guru">
                                {{ $jadwal->mapelKelas->guru->user->nama ?? '-' }}
                            </div>
                            <div class="ruangan">
                                Ruang {{ $jadwal->mapelKelas->kelas->jurusan->kode_jurusan ?? '' }}
                                {{ $jadwal->mapelKelas->kelas->no_kelas }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-jadwal">
                    <div class="empty-illustration">
                        <img src="{{ asset('assets/img/ygy.png') }}" alt="Tidak ada jadwal">
                    </div>
                    <div class="empty-text">Tidak ada jadwal</div>
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

    <style>
        .jadwal-container {
            position: relative;
            padding-bottom: 80px;
        }

        .jadwal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }

        .back-button a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 16px;
        }

        .back-button i {
            margin-right: 8px;
        }

        .semester-info {
            background-color: #f0f7ff;
            padding: 10px 15px;
            text-align: center;
        }

        .semester-info h4 {
            margin: 0;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        .month-header {
            background-color: #ff4b7d;
            color: white;
            padding: 12px 15px;
            text-align: center;
        }

        .month-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .week-navigation {
            display: flex;
            background-color: #ff4b7d;
            padding: 0 10px 15px;
        }

        .nav-arrow {
            display: flex;
            align-items: center;
            color: white;
            padding: 0 5px;
        }

        .nav-arrow a {
            color: white;
        }

        .days-container {
            display: flex;
            flex: 1;
            justify-content: space-between;
        }

        .day-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            padding: 5px 0;
            width: 40px;
        }

        .day-item.active {
            background-color: #333;
            border-radius: 50%;
        }

        .day-name {
            font-size: 12px;
            margin-bottom: 3px;
        }

        .day-number {
            font-size: 16px;
            font-weight: 500;
        }

        .current-date {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .date-text {
            font-weight: 500;
        }

        .today-badge {
            background-color: #e0f7ff;
            color: #0099cc;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .jadwal-list {
            padding: 15px;
        }

        .jadwal-item {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .waktu-container {
            width: 80px;
            margin-right: 15px;
        }

        .waktu {
            background-color: #f8f8f8;
            padding: 8px;
            border-radius: 5px;
            font-size: 12px;
            text-align: center;
        }

        .mata-pelajaran-container {
            flex: 1;
        }

        .mata-pelajaran {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .guru {
            color: #666;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .ruangan {
            color: #999;
            font-size: 12px;
        }

        .empty-jadwal {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 0;
        }

        .empty-illustration {
            width: 200px;
            margin-bottom: 20px;
        }

        .empty-illustration img {
            width: 100%;
        }

        .empty-text {
            color: #999;
            font-size: 14px;
        }

        .floating-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #1bc5bd;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .floating-button a {
            color: white;
            font-size: 24px;
        }
    </style>
@endsection
