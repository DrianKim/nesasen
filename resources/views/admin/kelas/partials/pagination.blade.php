<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($kelas->total() > 0)
            Menampilkan {{ $kelas->firstItem() }}-{{ $kelas->lastItem() }} dari {{ $kelas->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($kelas->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($kelas->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $kelas->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $kelas->currentPage() - 1); $i <= min($kelas->lastPage(), max(1, $kelas->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $kelas->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($kelas->hasMorePages())
                <button class="page-btn" data-page="{{ $kelas->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
