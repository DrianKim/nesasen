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
            <h3>
                {{ $monthName }}
                {{-- Mei --}}
            </h3>
        </div>

        <!-- Navigasi Minggu -->
        <div class="week-navigation">
            <div class="nav-arrow-prev">
                <a href="{{ route('siswa.jadwal', ['tanggal' => $selectedDate->copy()->subWeek()->format('Y-m-d')]) }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>

            <div class="days-container">
                @foreach ($daysOfWeek as $day)
                    <div class="day-item {{ $day['tanggal']->isSameDay($selectedDate) ? 'active' : '' }}">
                        <a href="{{ route('siswa.jadwal', ['tanggal' => $day['tanggal']->format('Y-m-d')]) }}">
                            <div class="day-name">{{ $day['nama_hari'] }}</div>
                            <div class="day-number">{{ $day['tanggal']->format('d') }}</div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="nav-arrow-next">
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
            {{-- @if ($jadwalHariIni->count() > 0)
                @foreach ($jadwalHariIni as $jadwal) --}}
                    <div class="jadwal-item">
                        <div class="waktu-container">
                            <div class="waktu">
                                {{-- {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} --}}
                                09:00
                                -
                                {{-- {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} --}}
                                09:40
                            </div>
                        </div>
                        <div class="mata-pelajaran-container">
                            <div class="mata-pelajaran">
                                {{-- {{ $jadwal->mapelKelas->mataPelajaran->nama_mapel}} --}}
                                Bahasa Indonesia
                            </div>
                            <div class="guru">
                                {{-- {{ $jadwal->mapelKelas->guru->nama}} --}}
                                Dede
                            </div>
                            <div class="kelas">
                                Kelas
                                {{-- {{ $jadwal->mapelKelas->kelas->tingkat }}
                                {{ $jadwal->mapelKelas->kelas->jurusan->kode_jurusan }}
                                {{ $jadwal->mapelKelas->kelas->no_kelas }} --}}
                            </div>
                        </div>
                    </div>
                {{-- @endforeach
            @else
                <div class="empty-jadwal">
                    <div class="empty-illustration">
                        <img src="{{ asset('assets/img/ygy.png') }}" alt="Tidak ada jadwal">
                    </div>
                    <div class="empty-text">Tidak ada jadwal</div>
                </div>
            @endif --}}

        </div>

        <!-- Tombol Tambah Jadwal -->
        <div class="floating-button">
            <a href="#">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>
@endsection
