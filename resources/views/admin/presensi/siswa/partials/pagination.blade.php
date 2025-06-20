<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($presensi_siswa->total() > 0)
            Menampilkan {{ $presensi_siswa->firstItem() }}-{{ $presensi_siswa->lastItem() }} dari {{ $presensi_siswa->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($presensi_siswa->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($presensi_siswa->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $presensi_siswa->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $presensi_siswa->currentPage() - 1); $i <= min($presensi_siswa->lastPage(), max(1, $presensi_siswa->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $presensi_siswa->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($presensi_siswa->hasMorePages())
                <button class="page-btn" data-page="{{ $presensi_siswa->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
