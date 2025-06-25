<h2 class="table-title">Jadwal Pelajaran -
    @if ($selectedKelas)
        {{ $selectedKelas->tingkat }}
        {{ $selectedKelas->jurusan->kode_jurusan }}
        {{ $selectedKelas->no_kelas }}
    @else
        
    @endif
</h2>
