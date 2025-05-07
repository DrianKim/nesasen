<div class="pagination-info">
    @if ($presensi_siswa->total() > 0)
        Menampilkan {{ $presensi_siswa->firstItem() }}-{{ $presensi_siswa->lastItem() }} dari {{ $presensi_siswa->total() }}
        data
    @else
        Tidak ada data
    @endif
</div>

@if ($presensi_siswa->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($presensi_siswa->onFirstPage())
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </div>
        @else
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $presensi_siswa->currentPage() - 1 }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($presensi_siswa->getUrlRange(max(1, $presensi_siswa->currentPage() - 2), min($presensi_siswa->lastPage(), $presensi_siswa->currentPage() + 2)) as $page => $url)
            <div class="page-item {{ $page == $presensi_siswa->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $page }}">{{ $page }}</a>
            </div>
        @endforeach

        {{-- Next Page Link --}}
        @if ($presensi_siswa->hasMorePages())
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $presensi_siswa->currentPage() + 1 }}" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        @else
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </div>
        @endif
    </div>
@endif
