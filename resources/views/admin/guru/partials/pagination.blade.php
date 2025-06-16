<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($guru->total() > 0)
            Menampilkan {{ $guru->firstItem() }}-{{ $guru->lastItem() }} dari {{ $guru->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($guru->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($guru->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $guru->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $guru->currentPage() - 1); $i <= min($guru->lastPage(), max(1, $guru->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $guru->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($guru->hasMorePages())
                <button class="page-btn" data-page="{{ $guru->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
