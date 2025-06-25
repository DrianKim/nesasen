@php
    function getHariKe($tanggal)
    {
        $hari = \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd');
        $map = ['Senin' => 2, 'Selasa' => 3, 'Rabu' => 4, 'Kamis' => 5, 'Jumat' => 6, 'Sabtu' => 7, 'Minggu' => 8];
        return $map[$hari] ?? 0;
    }

    function getBarisGrid($jam)
    {
        [$hour, $minute] = explode(':', $jam);
        $totalMenit = intval($hour) * 60 + intval($minute);
        $startMenit = 6 * 60; // 06:00
        return intval(($totalMenit - $startMenit) / 10) + 2; // +2 karena row 1: header, row 2: 06:00
    }

    function getDurasiGrid($mulai, $selesai)
    {
        [$h1, $m1] = explode(':', $mulai);
        [$h2, $m2] = explode(':', $selesai);
        $start = intval($h1) * 60 + intval($m1);
        $end = intval($h2) * 60 + intval($m2);
        return intval(($end - $start) / 10); // Durasi per 10 menit
    }

@endphp

{{-- Jadwal Grid --}}
<div class="jadwal-grid">
    <div class="jam-kolom header">Jam</div>
    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
        <div class="hari-kolom header">{{ $hari }}</div>
    @endforeach

    {{-- Buat semua slot (84 baris untuk 06:00-19:50, per 10 menit) --}}
    @for ($i = 0; $i < 84; $i++)
        @php
            $totalMenit = $i * 10 + 360; // 360 = 6 jam * 60 menit (mulai dari 06:00)
            $jam = floor($totalMenit / 60);
            $menit = $totalMenit % 60;
            $isJamBulat = $menit === 0; // Hanya jam bulat yang ditampilin
            $isFirstInHour = $i % 6 === 0; // Setiap 6 slot = awal jam baru
        @endphp

        {{-- Kolom jam (kiri) --}}
        @if ($isFirstInHour)
            <div class="jam-kolom slot-waktu jam-border" style="grid-column: 1; grid-row: {{ $i + 2 }} / span 6;">
                {{ str_pad($jam, 2, '0', STR_PAD_LEFT) }}:00
            </div>
        @endif

        {{-- Kolom hari (Senin-Minggu) --}}
        @for ($j = 2; $j <= 8; $j++)
            <div class="slot-jadwal {{ $isFirstInHour ? 'jam-border' : '' }}"
                style="grid-column: {{ $j }}; grid-row: {{ $i + 2 }};"></div>
        @endfor
    @endfor

    {{-- Jadwal Items --}}
    @foreach ($jadwals as $jadwal)
        @include('admin.jadwal_pelajaran.modal.edit')

        @php
            $col = getHariKe($jadwal->tanggal);
            $row = getBarisGrid($jadwal->jam_mulai);
            $span = getDurasiGrid($jadwal->jam_mulai, $jadwal->jam_selesai);
        @endphp

        <div class="jadwal-item"
            style="grid-column: {{ $col }}; grid-row: {{ $row }} / span {{ $span }};">
            <strong>{{ $jadwal->mapelKelas->mataPelajaran->nama_mapel ?? '-' }}</strong><br>
            {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}

            <div class="jadwal-actions">
                <button class="btn-edit" onclick="openModalEdit('modalJadwalEdit{{ $jadwal->id }}')">
                    <i class="material-icons-sharp">edit</i>
                </button>
                <button class="btn-delete" onclick="hapusJadwal({{ $jadwal->id }})">
                    <i class="material-icons-sharp">delete</i>
                </button>
            </div>
        </div>
    @endforeach
</div>

<script>
    function hapusJadwal(id) {
        const isDark = document.body.classList.contains('dark-mode-variables');

        Swal.fire({
            title: 'Hapus jadwal?',
            text: 'Jadwal ini akan dihapus permanen!',
            icon: 'warning',
            iconColor: '#e7586e',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e7586e',
            cancelButtonColor: '#43c6c9',
            background: isDark ? getComputedStyle(document.body).getPropertyValue('--color-background') :
                '#fff',
            color: isDark ? getComputedStyle(document.body).getPropertyValue('--color-dark') : '#000',
            customClass: {
                popup: isDark ? 'swal-dark' : ''
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/jadwal_pelajaran/destroy/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: data.success ? 'success' : 'error',
                            title: data.message,
                            iconColor: '#e7586e',
                            confirmButtonColor: '#e7586e',
                            background: isDark ? getComputedStyle(document.body).getPropertyValue(
                                '--color-background') : '#fff',
                            color: isDark ? getComputedStyle(document.body).getPropertyValue(
                                '--color-dark') : '#000',
                            customClass: {
                                popup: isDark ? 'swal-dark' : ''
                            }
                        }).then(() => {
                            if (data.success) location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal menghapus jadwal!',
                            iconColor: '#e7586e',
                            confirmButtonColor: '#e7586e',
                            background: isDark ? getComputedStyle(document.body).getPropertyValue(
                                '--color-background') : '#fff',
                            color: isDark ? getComputedStyle(document.body).getPropertyValue(
                                '--color-dark') : '#000',
                            customClass: {
                                popup: isDark ? 'swal-dark' : ''
                            }
                        });
                    });
            }
        });
    }
</script>
