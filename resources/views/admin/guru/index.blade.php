@extends('layouts.app')

@section('content')
    <div class="p-0 container-fluid">
        <!-- Main content container -->
        <div class="skul-container">
            <!-- Page Header -->
            {{-- <div class="page-header">
                <h1>Kelola Data Guru di Sekolahmu</h1>
            </div> --}}

            <!-- Teacher List Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Daftar Guru SMKN 1 Subang</h2>
                    <div class="action-buttons">
                        <!-- Add Teacher Button -->
                        <a href="{{ route('admin_guru.create') }}" class="btn btn-primary btn-circle">
                            <i class="fas fa-plus"></i>
                            <span class="button-label"></span>
                        </a>

                        <!-- Import Button -->
                        <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data"
                            id="importGuruForm" class="d-inline">
                            @csrf
                            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" style="display: none;"
                                onchange="document.getElementById('importGuruForm').submit();">
                            <button type="button" class="btn btn-success btn-circle"
                                onclick="document.getElementById('fileInput').click();">
                                <i class="fas fa-file-import"></i>
                                <span class="button-label"></span>
                            </button>
                        </form>

                        <!-- Export Button -->
                        <button type="button" class="btn btn-info btn-circle">
                            <i class="fas fa-file-export"></i>
                            <span class="button-label"></span>
                        </button>
                    </div>
                </div>

                <!-- Table & Filtering Section -->
                <div class="table-section">
                    <!-- Search & Filter Controls -->
                    <div class="mb-3 d-flex justify-content-end">
                        <form action="{{ route('admin_guru.index') }}" method="GET" id="guruFilterForm"
                            class="gap-2 d-flex align-items-end">
                            <div class="form-group">
                                <label for="perPage"></label>
                                <select class="form-select" id="perPage" name="perPage" onchange="submitFilter()">
                                    <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <div class="form-group search-box">
                                    <input type="text" class="form-control" name="search" id="searchInput"
                                        placeholder="Cari guru..." value="{{ request('search') }}">
                                    <button type="submit" class="search-button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Teacher Table -->
                    <div class="table-responsive">
                        <form id="bulk_form" action="{{ route('admin_guru.bulk_action') }}" method="POST">
                            @csrf
                            <input type="hidden" name="bulk_action" id="bulk_action" value="">

                            {{-- <!-- Bulk Action Controls -->
                            <div class="mb-2 bulk-actions">
                                <div class="bulk-buttons">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="bulkAction('delete')">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </div>
                            </div> --}}

                            <!-- Main Table -->
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="3%">
                                            <input type="checkbox" onclick="toggleAll(this)">
                                        </th>
                                        <th width="15%">NUPTK/NIK <i class="fas fa-sort"></i></th>
                                        <th width="40%">Nama Guru <i class="fas fa-sort"></i></th>
                                        <th width="25%">No. HP <i class="fas fa-sort"></i></th>
                                        <th class="text-center" width="12%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($guru as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_students[]" value="{{ $item->id }}">
                                            </td>
                                            <td>{{ $item->nip ?? '-' }}</td>
                                            <td>{{ $item->nama ?? '-' }}</td>
                                            <td>{{ $item->no_hp ?? '-' }}</td>
                                            <td class="text-center">
                                                <div class="action-buttons">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        data-toggle="modal" data-target="#modalGuruShow{{ $item->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-toggle="modal" data-target="#modalGuruDestroy{{ $item->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                @include('admin.guru.modal')
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-4 text-center">
                                                <div class="empty-state">
                                                    <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data"
                                                        width="120">
                                                    <p>Tidak ada data guru yang ditemukan</p>
                                                    <a href="{{ route('admin_guru.create') }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="mr-1 fas fa-plus"></i> Tambah Guru
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <!-- Pagination Section -->
                    <div class="pagination-section">
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
                                        <a class="page-link" href="{{ $guru->previousPageUrl() }}" aria-label="Previous">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </div>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($guru->getUrlRange(max(1, $guru->currentPage() - 2), min($guru->lastPage(), $guru->currentPage() + 2)) as $page => $url)
                                    <div class="page-item {{ $page == $guru->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </div>
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($guru->hasMorePages())
                                    <div class="page-item">
                                        <a class="page-link" href="{{ $guru->nextPageUrl() }}" aria-label="Next">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle sortable columns
            const tableHeaders = document.querySelectorAll('th');
            tableHeaders.forEach(header => {
                if (header.querySelector('i.fas.fa-sort')) {
                    header.addEventListener('click', function() {
                        const columnName = this.textContent.trim().replace(/[\s\n]+$/, '');
                        let currentSort = this.getAttribute('data-sort') || '';
                        let newSort = 'asc';

                        // Toggle sort direction
                        if (currentSort === 'asc') {
                            newSort = 'desc';
                            this.classList.remove('sorted-asc');
                            this.classList.add('sorted-desc');
                        } else {
                            this.classList.remove('sorted-desc');
                            this.classList.add('sorted-asc');
                        }

                        // Reset other headers
                        tableHeaders.forEach(h => {
                            if (h !== this) {
                                h.classList.remove('sorted-asc', 'sorted-desc');
                                h.setAttribute('data-sort', '');
                            }
                        });

                        this.setAttribute('data-sort', newSort);

                        // Update form and submit
                        const form = document.getElementById('guruFilterForm');
                        let sortInput = form.querySelector('input[name="sort_by"]');
                        if (!sortInput) {
                            sortInput = document.createElement('input');
                            sortInput.type = 'hidden';
                            sortInput.name = 'sort_by';
                            form.appendChild(sortInput);
                        }
                        sortInput.value = columnName;

                        let directionInput = form.querySelector('input[name="sort_direction"]');
                        if (!directionInput) {
                            directionInput = document.createElement('input');
                            directionInput.type = 'hidden';
                            directionInput.name = 'sort_direction';
                            form.appendChild(directionInput);
                        }
                        directionInput.value = newSort;

                        form.submit();
                    });
                }
            });

            // Show active sort state on load
            const urlParams = new URLSearchParams(window.location.search);
            const sortBy = urlParams.get('sort_by');
            const sortDir = urlParams.get('sort_direction');

            if (sortBy && sortDir) {
                tableHeaders.forEach(header => {
                    const headerText = header.textContent.trim().replace(/[\s\n]+$/, '');
                    if (headerText === sortBy) {
                        header.classList.add(sortDir === 'asc' ? 'sorted-asc' : 'sorted-desc');
                        header.setAttribute('data-sort', sortDir);
                    }
                });
            }

            // Handle search with debounce
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        document.getElementById('guruFilterForm').submit();
                    }, 500); // 500ms debounce
                });
            }
        });

        // Submit filter form
        function submitFilter() {
            document.getElementById('guruFilterForm').submit();
        }

        // Handle bulk actions
        function bulkAction(action) {
            const checkedBoxes = document.querySelectorAll('input[name="selected_students[]"]:checked');
            if (checkedBoxes.length === 0) {
                alert('Silahkan pilih guru terlebih dahulu');
                return;
            }

            if (action === 'delete' && !confirm('Anda yakin ingin menghapus guru yang dipilih?')) {
                return;
            }

            document.getElementById('bulk_action').value = action;
            document.getElementById('bulk_form').submit();
        }

        // Toggle all checkboxes
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
@endsection
