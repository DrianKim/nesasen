<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($siswa->total() > 0)
            Menampilkan {{ $siswa->firstItem() }}-{{ $siswa->lastItem() }} dari {{ $siswa->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($siswa->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($siswa->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $siswa->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $siswa->currentPage() - 1); $i <= min($siswa->lastPage(), max(1, $siswa->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $siswa->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($siswa->hasMorePages())
                <button class="page-btn" data-page="{{ $siswa->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
