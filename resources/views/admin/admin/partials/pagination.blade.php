<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($admin->total() > 0)
            Menampilkan {{ $admin->firstItem() }}-{{ $admin->lastItem() }} dari {{ $admin->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($admin->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($admin->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $admin->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $admin->currentPage() - 1); $i <= min($admin->lastPage(), max(1, $admin->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $admin->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($admin->hasMorePages())
                <button class="page-btn" data-page="{{ $admin->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
