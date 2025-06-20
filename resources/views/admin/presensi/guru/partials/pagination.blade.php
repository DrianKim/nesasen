<div class="pagination-wrapper">
    <div class="pagination-info">
        @if ($presensi_guru->total() > 0)
            Menampilkan {{ $presensi_guru->firstItem() }}-{{ $presensi_guru->lastItem() }} dari
            {{ $presensi_guru->total() }}
            data
        @else
            Tidak ada data
        @endif
    </div>

    @if ($presensi_guru->hasPages())
        <div class="pagination-buttons">
            {{-- Tombol kiri (previous) --}}
            @if ($presensi_guru->onFirstPage())
                <button class="page-btn" disabled>&lt;</button>
            @else
                <button class="page-btn" data-page="{{ $presensi_guru->currentPage() - 1 }}">&lt;</button>
            @endif

            {{-- Nomor halaman --}}
            @for ($i = max(1, $presensi_guru->currentPage() - 1); $i <= min($presensi_guru->lastPage(), max(1, $presensi_guru->currentPage() - 1) + 2); $i++)
                <button class="page-btn {{ $i == $presensi_guru->currentPage() ? 'active' : '' }}"
                    data-page="{{ $i }}">{{ $i }}</button>
            @endfor

            {{-- Tombol kanan (next) --}}
            @if ($presensi_guru->hasMorePages())
                <button class="page-btn" data-page="{{ $presensi_guru->currentPage() + 1 }}">&gt;</button>
            @else
                <button class="page-btn" disabled>&gt;</button>
            @endif
        </div>
    @endif
</div>
