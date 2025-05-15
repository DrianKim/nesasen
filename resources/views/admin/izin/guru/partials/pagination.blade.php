<div class="pagination-info">
    @if ($izin_guru->total() > 0)
        Menampilkan {{ $izin_guru->firstItem() }}-{{ $izin_guru->lastItem() }} dari {{ $izin_guru->total() }}
        data
    @else
        Tidak ada data
    @endif
</div>
@if ($izin_guru->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($izin_guru->onFirstPage())
            <div class="page-item disabled">
                <span class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </div>
        @else
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $izin_guru->currentPage() - 1 }}"
                    aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </div>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($izin_guru->getUrlRange(max(1, $izin_guru->currentPage() - 2), min($izin_guru->lastPage(), $izin_guru->currentPage() + 2)) as $page => $url)
            <div class="page-item {{ $page == $izin_guru->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $page }}">{{ $page }}</a>
            </div>
        @endforeach

        {{-- Next Page Link --}}
        @if ($izin_guru->hasMorePages())
            <div class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $izin_guru->currentPage() + 1 }}"
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
