<div class="pagination-info">
    @if ($jurusan->total() > 0)
        Menampilkan {{ $jurusan->firstItem() }}-{{ $jurusan->lastItem() }} dari {{ $jurusan->total() }}
        data
    @else
        Tidak ada data
    @endif
</div>

@if ($jurusan->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($jurusan->onFirstPage())
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </div>
        @else
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $jurusan->currentPage() - 1 }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($jurusan->getUrlRange(max(1, $jurusan->currentPage() - 2), min($jurusan->lastPage(), $jurusan->currentPage() + 2)) as $page => $url)
            <div class="page-item {{ $page == $jurusan->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $page }}">{{ $page }}</a>
            </div>
        @endforeach

        {{-- Next Page Link --}}
        @if ($jurusan->hasMorePages())
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)"
                    data-page="{{ $jurusan->currentPage() + 1 }}" aria-label="Next">
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
