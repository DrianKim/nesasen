<div class="pagination-info">
    @if ($guru->total() > 0)
        Menampilkan {{ $guru->firstItem() }}-{{ $guru->lastItem() }} dari {{ $guru->total() }}
        data
    @else
        Tidak ada data
    @endif
</div>

@if ($guru->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($guru->onFirstPage())
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </div>
        @else
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $guru->currentPage() - 1 }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($guru->getUrlRange(max(1, $guru->currentPage() - 2), min($guru->lastPage(), $guru->currentPage() + 2)) as $page => $url)
            <div class="page-item {{ $page == $guru->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $page }}">{{ $page }}</a>
            </div>
        @endforeach

        {{-- Next Page Link --}}
        @if ($guru->hasMorePages())
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $guru->currentPage() + 1 }}" aria-label="Next">
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
