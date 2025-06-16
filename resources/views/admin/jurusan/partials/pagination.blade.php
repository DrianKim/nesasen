<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($jurusan->total() > 0)
            Menampilkan {{ $jurusan->firstItem() }}-{{ $jurusan->lastItem() }} dari {{ $jurusan->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($jurusan->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($jurusan->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $jurusan->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $jurusan->currentPage() - 1); $i <= min($jurusan->lastPage(), max(1, $jurusan->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $jurusan->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($jurusan->hasMorePages())
                <button class="page-btn" data-page="{{ $jurusan->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
