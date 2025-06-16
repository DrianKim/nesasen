<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($mapel->total() > 0)
            Menampilkan {{ $mapel->firstItem() }}-{{ $mapel->lastItem() }} dari {{ $mapel->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($mapel->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($mapel->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $mapel->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $mapel->currentPage() - 1); $i <= min($mapel->lastPage(), max(1, $mapel->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $mapel->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($mapel->hasMorePages())
                <button class="page-btn" data-page="{{ $mapel->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
