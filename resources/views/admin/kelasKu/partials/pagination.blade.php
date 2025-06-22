<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($kelasKu->total() > 0)
            Menampilkan {{ $kelasKu->firstItem() }}-{{ $kelasKu->lastItem() }} dari {{ $kelasKu->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($kelasKu->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($kelasKu->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $kelasKu->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $kelasKu->currentPage() - 1); $i <= min($kelasKu->lastPage(), max(1, $kelasKu->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $kelasKu->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($kelasKu->hasMorePages())
                <button class="page-btn" data-page="{{ $kelasKu->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
