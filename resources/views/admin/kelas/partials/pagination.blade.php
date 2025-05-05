<div class="pagination-info">
    @if ($kelas->total() > 0)
        Menampilkan {{ $kelas->firstItem() }}-{{ $kelas->lastItem() }} dari {{ $kelas->total() }}
        data
    @else
        Tidak ada data
    @endif
</div>

@if ($kelas->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($kelas->onFirstPage())
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </div>
        @else
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $kelas->currentPage() - 1 }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($kelas->getUrlRange(max(1, $kelas->currentPage() - 2), min($kelas->lastPage(), $kelas->currentPage() + 2)) as $page => $url)
            <div class="page-item {{ $page == $kelas->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $page }}">{{ $page }}</a>
            </div>
        @endforeach

        {{-- Next Page Link --}}
        @if ($kelas->hasMorePages())
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $kelas->currentPage() + 1 }}" aria-label="Next">
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
