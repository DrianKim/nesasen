@if ($jadwalHariIni->count() > 0)
    @foreach ($jadwalHariIni as $jadwal)
        <div
            class="jadwal-item
                @if ($jadwal->is_selesai) completed
                @elseif($jadwal->is_belum_selesai) ongoing
                @else upcoming @endif
            ">
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
                @else
                    <div class="status-upcoming">
                        <i class="fas fa-hourglass-start"></i>
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
                    {{ $jadwal->mapelKelas->kelas->tingkat }}
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
