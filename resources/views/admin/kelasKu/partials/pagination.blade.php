<div class="pagination-info">
    @if ($kelasKu->total() > 0)
        Menampilkan {{ $kelasKu->firstItem() }}-{{ $kelasKu->lastItem() }} dari {{ $kelasKu->total() }}
        data
    @else
        Tidak ada data
    @endif
</div>

@if ($kelasKu->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($kelasKu->onFirstPage())
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </div>
        @else
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $kelasKu->currentPage() - 1 }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($kelasKu->getUrlRange(max(1, $kelasKu->currentPage() - 2), min($kelasKu->lastPage(), $kelasKu->currentPage() + 2)) as $page => $url)
            <div class="page-item {{ $page == $kelasKu->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $page }}">{{ $page }}</a>
            </div>
        @endforeach

        {{-- Next Page Link --}}
        @if ($kelasKu->hasMorePages())
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $kelasKu->currentPage() + 1 }}" aria-label="Next">
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

