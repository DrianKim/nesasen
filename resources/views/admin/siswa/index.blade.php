@extends('layouts.app')

@section('content')
    <div class="p-0 container-fluid">
        <!-- Main content container -->
        <div class="skul-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>Kelola Data Siswa di Sekolahmu</h1>
            </div>

            <!-- Student List Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Daftar Siswa SMKN 1 Subang</h2>
                    <div class="action-buttons">
                        <a href="{{ route('admin_siswa.create') }}" class="btn btn-primary btn-circle">
                            <i class="fas fa-plus"></i>
                            <span class="button-label"></span>
                        </a>
                        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data"
                            id="importSiswaForm" class="d-inline">
                            @csrf
                            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" style="display: none;"
                                onchange="document.getElementById('importSiswaForm').submit();">
                            <button type="button" class="btn btn-success btn-circle"
                                onclick="document.getElementById('fileInput').click();">
                                <i class="fas fa-file-import"></i>
                                <span class="button-label"></span>
                            </button>
                        </form>
                        <button type="button" class="btn btn-info btn-circle">
                            <i class="fas fa-file-export"></i>
                            <span class="button-label"></span>
                        </button>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <form action="{{ route('admin_siswa.index') }}" method="GET" id="siswaFilterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="kelas">Kelas:</label>
                                    <select class="form-select" id="kelas" name="kelas" onchange="submitFilter()">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($kelasFilter as $kelas)
                                            <option value="{{ $kelas->id }}"
                                                {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                                {{ $kelas->no_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tahun_ajaran">Tahun Ajaran:</label>
                                    <select class="form-select" id="tahun_ajaran" name="tahun_ajaran"
                                        onchange="submitFilter()">
                                        @foreach ($tahunAjaranFilter as $tahun)
                                            <option value="{{ $tahun }}"
                                                {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select class="form-select" id="status" name="status" onchange="submitFilter()">
                                        <option value="">Semua</option>
                                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>
                                            Non-aktif</option>
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <l abel for="perPage">Tampilkan:</l>
                                    <select class="form-select" id="perPage" name="perPage" onchange="submitFilter()">
                                        <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <div class="form-group search-box">
                                    <input type="text" class="form-control" name="search" id="searchInput"
                                        placeholder="Cari siswa..." value="{{ request('search') }}">
                                    <button type="submit" class="search-button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table Section -->
                <div class="table-responsive">
                    <form id="bulk_form" action="{{ route('admin_siswa.bulk_action') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bulk_action" id="bulk_action" value="">

                        <div class="bulk-actions">
                            <div class="bulk-buttons">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkAction('delete')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="3%">
                                        <input type="checkbox" onclick="toggleAll(this)">
                                    </th>
                                    <th width="10%">NISN <i class="fas fa-sort"></i></th>
                                    <th width="10%">NIS <i class="fas fa-sort"></i></th>
                                    <th width="30%">Nama Siswa <i class="fas fa-sort"></i></th>
                                    <th width="15%">No. HP <i class="fas fa-sort"></i></th>
                                    <th width="15%">Kelas <i class="fas fa-sort"></i></th>
                                    <th class="text-center" width="12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswa as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_students[]"
                                                value="{{ $item->id }}">
                                        </td>
                                        <td>{{ $item->nisn ?? '-' }}</td>
                                        <td>{{ $item->nis ?? '-' }}</td>
                                        <td>{{ $item->nama ?? '-' }}</td>
                                        <td>{{ $item->no_hp ?? '-' }}</td>
                                        <td>{{ $item->kelas ? $item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-toggle="modal" data-target="#modalSiswaShow{{ $item->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-toggle="modal"
                                                    data-target="#modalSiswaDestroy{{ $item->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            @include('admin.siswa.modal')
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 text-center">
                                            <div class="empty-state">
                                                <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data"
                                                    width="120">
                                                <p>Tidak ada data siswa yang ditemukan</p>
                                                <a href="{{ route('admin_siswa.create') }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="mr-1 fas fa-plus"></i> Tambah Siswa
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
                        @if ($siswa->total() > 0)
                            Menampilkan {{ $siswa->firstItem() }}-{{ $siswa->lastItem() }} dari {{ $siswa->total() }}
                            data
                        @else
                            Tidak ada data
                        @endif
                    </div>

                    @if ($siswa->hasPages())
                        <div class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($siswa->onFirstPage())
                                <div class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                </div>
                            @else
                                <div class="page-item">
                                    <a class="page-link" href="{{ $siswa->previousPageUrl() }}" aria-label="Previous">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </div>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($siswa->getUrlRange(max(1, $siswa->currentPage() - 2), min($siswa->lastPage(), $siswa->currentPage() + 2)) as $page => $url)
                                <div class="page-item {{ $page == $siswa->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </div>
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($siswa->hasMorePages())
                                <div class="page-item">
                                    <a class="page-link" href="{{ $siswa->nextPageUrl() }}" aria-label="Next">
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
                        const form = document.getElementById('siswaFilterForm');
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
                        document.getElementById('siswaFilterForm').submit();
                    }, 500); // 500ms debounce
                });
            }
        });

        // Submit filter form
        function submitFilter() {
            document.getElementById('siswaFilterForm').submit();
        }

        // Handle bulk actions
        function bulkAction(action) {
            const checkedBoxes = document.querySelectorAll('input[name="selected_students[]"]:checked');
            if (checkedBoxes.length === 0) {
                alert('Silahkan pilih siswa terlebih dahulu');
                return;
            }

            if (action === 'delete' && !confirm('Anda yakin ingin menghapus siswa yang dipilih?')) {
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
