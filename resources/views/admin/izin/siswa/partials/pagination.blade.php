<div class="pagination-info">
    @if ($izin_siswa->total() > 0)
        Menampilkan {{ $izin_siswa->firstItem() }}-{{ $izin_siswa->lastItem() }} dari {{ $izin_siswa->total() }}
        data
    @else
        Tidak ada data
    @endif
</div>
@if ($izin_siswa->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($izin_siswa->onFirstPage())
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </div>
        @else
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $izin_siswa->currentPage() - 1 }}"
                    aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($izin_siswa->getUrlRange(max(1, $izin_siswa->currentPage() - 2), min($izin_siswa->lastPage(), $izin_siswa->currentPage() + 2)) as $page => $url)
            <div class="page-item {{ $page == $izin_siswa->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $page }}">{{ $page }}</a>
            </div>
        @endforeach

        {{-- Next Page Link --}}
        @if ($izin_siswa->hasMorePages())
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $izin_siswa->currentPage() + 1 }}"
                    aria-label="Next">
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
