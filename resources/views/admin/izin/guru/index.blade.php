@extends('layouts.app')

@section('content')
    <div class="p-0 container-fluid">
        <!-- Main content container -->
        <div class="skul-container">
            <!-- Student List Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2>Data Pengajuan Izin Guru SMKN 1 Subang</h2>
                    <div class="action-buttons">
                        <a href="#" class="btn btn-primary btn-circle">
                            <i class="ml-2 fas fa-plus"></i>
                            <span class="button-label"></span>
                        </a>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-section">
                    <form id="izin_guruFilterForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal:</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ request('tanggal') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                {{-- <div class="form-group">
                                    <label for="kelas">Kelas:</label>
                                    <select class="form-select" id="kelas" name="kelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($kelasFilter as $kelas)
                                            <option value="{{ $kelas->id }}"
                                                {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                                {{ $kelas->tingkat }} {{ $kelas->jurusan->kode_jurusan }}
                                                {{ $kelas->no_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="perPage">Tampilkan:</label>
                                    <select class="form-select" id="perPage" name="perPage">
                                        <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group search-box">
                                    <label for="searchInput">Cari:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" id="searchInput"
                                            placeholder="Cari guru..." value="{{ request('search') }}">
                                        <button type="button" class="btn btn-primary" id="resetFilter">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Hidden fields for sorting -->
                        <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="sort_direction" id="sort_direction"
                            value="{{ request('sort_direction', 'asc') }}">
                        <input type="hidden" name="page" id="current_page" value="{{ request('page', 1) }}">
                    </form>
                </div>

                <!-- Loading Indicator -->
                <div id="loading-indicator" style="display:none;">
                    <div class="my-3 d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-responsive">

                    <div id="table-container">
                        <!-- Konten tabel akan diisi dengan AJAX -->
                        @include('admin.izin.guru.partials.table')
                        <div class="mt-2 d-flex justify-content-end">
                            {{-- {{ $izin_guru->links() }}   --}}
                        </div>
                    </div>
                </div>

                <!-- Pagination Section -->
                <div class="pagination-section" id="pagination-container">
                    <!-- Pagination akan diisi dengan AJAX -->
                    @include('admin.izin.guru.partials.pagination')

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            const filterForm = document.getElementById('izin_guruFilterForm');
            const tableContainer = document.getElementById('table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const searchInput = document.getElementById('searchInput');
            const resetButton = document.getElementById('resetFilter');
            const filters = ['kelas', 'perPage'];
            const tanggalInput = document.getElementById('tanggal');

            let searchTimer;
            let currentRequest = null;

            if (tanggalInput) {
                tanggalInput.addEventListener('change', function() {
                    // Reset to page 1 when changing filters
                    document.getElementById('current_page').value = 1;
                    loadData();
                });
            }

            // Function to load data via AJAX
            function loadData() {
                // Show loading indicator
                loadingIndicator.style.display = 'block';

                // If there's a pending request, abort it
                if (currentRequest) {
                    currentRequest.abort();
                }

                // Get form data
                const formData = new FormData(filterForm);

                // Create AJAX request
                currentRequest = new XMLHttpRequest();
                currentRequest.open('GET', '{{ route('admin_izin_guru.filter') }}?' + new URLSearchParams(
                        formData)
                    .toString(), true);
                currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                currentRequest.onload = function() {
                    if (this.status >= 200 && this.status < 400) {
                        // Success response
                        const response = JSON.parse(this.response);
                        tableContainer.innerHTML = response.table;
                        paginationContainer.innerHTML = response.pagination;

                        // Rebind sorting events
                        bindSortingEvents();
                        // Rebind pagination events
                        bindPaginationEvents();

                        // Update URL without reloading the page
                        updateURL(formData);
                    } else {
                        // Error response
                        console.error('Request failed');
                    }

                    // Hide loading indicator
                    loadingIndicator.style.display = 'none';
                    currentRequest = null;
                };

                currentRequest.onerror = function() {
                    console.error('Connection error');
                    loadingIndicator.style.display = 'none';
                    currentRequest = null;
                };

                currentRequest.send();
            }

            // Function to update URL with current filters
            function updateURL(formData) {
                if (history.pushState) {
                    const searchParams = new URLSearchParams(formData);
                    const newURL = window.location.protocol + '//' + window.location.host +
                        window.location.pathname + '?' + searchParams.toString();
                    window.history.pushState({
                        path: newURL
                    }, '', newURL);
                }
            }

            // Bind events to filter elements
            filters.forEach(filter => {
                const element = document.getElementById(filter);
                if (element) {
                    element.addEventListener('change', function() {
                        // Reset to page 1 when changing filters
                        document.getElementById('current_page').value = 1;
                        loadData();
                    });
                }
            });

            // Handle search input with debounce
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        // Reset to page 1 when searching
                        document.getElementById('current_page').value = 1;
                        loadData();
                    }, 500); // 500ms debounce
                });
            }

            // Handle reset button
            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    // Reset all form fields
                    filterForm.reset();
                    // Reset hidden fields
                    document.getElementById('sort_by').value = '';
                    document.getElementById('sort_direction').value = 'asc';
                    document.getElementById('current_page').value = 1;
                    // Load data
                    loadData();
                });
            }

            // Function to bind sorting events
            function bindSortingEvents() {
                const sortableHeaders = document.querySelectorAll('.sortable');
                sortableHeaders.forEach(header => {
                    header.addEventListener('click', function() {
                        const column = this.getAttribute('data-column');
                        const sortBy = document.getElementById('sort_by');
                        const sortDirection = document.getElementById('sort_direction');

                        // Toggle sort direction if clicking the same column
                        if (sortBy.value === column) {
                            sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
                        } else {
                            // Default to ascending for new column
                            sortBy.value = column;
                            sortDirection.value = 'asc';
                        }

                        // Load data with new sorting
                        loadData();
                    });
                });
            }

            // Function to bind pagination events
            function bindPaginationEvents() {
                const paginationLinks = document.querySelectorAll('.pagination .page-link[data-page]');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        document.getElementById('current_page').value = page;
                        loadData();

                        // Scroll to top of table
                        window.scrollTo({
                            top: document.querySelector('.content-section').offsetTop,
                            behavior: 'smooth'
                        });
                    });
                });
            }

            // Initial binding
            bindSortingEvents();
            bindPaginationEvents();
        });

        // Bulk actions function (tetap sama)
        function bulkAction(action) {
            const checkedBoxes = document.querySelectorAll('input[name="selected_guru[]"]:checked');
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

        // Toggle all checkboxes (tetap sama)
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('input[name="selected_guru[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Use event delegation for all buttons to handle dynamically created elements
            document.addEventListener('click', function(event) {
                // Handle edit button clicks
                if (event.target.classList.contains('edit-btn') || event.target.closest('.edit-btn')) {
                    const button = event.target.classList.contains('edit-btn') ? event.target : event.target
                        .closest('.edit-btn');
                    handleEditButtonClick(button);
                }

                // Handle save button clicks
                if (event.target.classList.contains('save-btn') || event.target.closest('.save-btn')) {
                    const button = event.target.classList.contains('save-btn') ? event.target : event.target
                        .closest('.save-btn');
                    handleSaveButtonClick(button);
                }

                // Handle cancel button clicks
                if (event.target.classList.contains('cancel-btn') || event.target.closest('.cancel-btn')) {
                    const button = event.target.classList.contains('cancel-btn') ? event.target : event
                        .target.closest('.cancel-btn');
                    handleCancelButtonClick(button);
                }
            });

            function handleEditButtonClick(button) {
                const row = button.closest('tr');
                const cells = row.querySelectorAll('td.editable-cell');

                // Store original values in data attributes
                cells.forEach(cell => {
                    const value = cell.textContent.trim();
                    cell.innerHTML =
                        `<input type="text" class="form-control" value="${value}" data-original="${value}">`;
                });

                const actionButtons = row.querySelector('.action-buttons');
                actionButtons.innerHTML = `
            <button type="button" class="btn btn-sm btn-success save-btn">
                <i class="fas fa-save"></i> Save
            </button>
            <button type="button" class="btn btn-sm btn-danger cancel-btn">
                <i class="fas fa-times"></i> Cancel
            </button>`;
            }

            function handleSaveButtonClick(button) {
                const row = button.closest('tr');
                const cells = row.querySelectorAll('td.editable-cell');
                const actionButtons = row.querySelector('.action-buttons');

                const updatedData = {};
                cells.forEach(cell => {
                    const field = cell.dataset.field;
                    updatedData[field] = cell.querySelector('input').value;
                });

                // Get the ID from the row's id attribute
                const id = row.id.replace('row-', '');

                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                button.disabled = true;

                fetch(`/admin/guru/inline-update/${id}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify(updatedData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil', 'Data berhasil diperbarui', 'success');
                            cells.forEach(cell => {
                                const field = cell.dataset.field;
                                cell.textContent = updatedData[field];
                            });

                            resetActionButtons(actionButtons, id);
                        } else {
                            Swal.fire('Gagal', data.message || 'Data gagal diperbarui', 'error');
                            button.innerHTML = '<i class="fas fa-save"></i> Save';
                            button.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Gagal', 'Terjadi kesalahan, coba lagi', 'error');
                        button.innerHTML = '<i class="fas fa-save"></i> Save';
                        button.disabled = false;
                    });
            }

            function handleCancelButtonClick(button) {
                const row = button.closest('tr');
                const cells = row.querySelectorAll('td.editable-cell');
                const actionButtons = row.querySelector('.action-buttons');

                cells.forEach(cell => {
                    const originalValue = cell.querySelector('input').getAttribute('data-original');
                    cell.textContent = originalValue;
                });

                const id = row.id.replace('row-', '');
                resetActionButtons(actionButtons, id);
            }

            function resetActionButtons(actionButtons, id) {
                actionButtons.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-primary edit-btn">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                data-target="#modalGuruDestroy${id}">
                <i class="fas fa-trash"></i>
            </button>
        `;
            }
        });/

        $(document).ready(function() {
            $('#formGuru').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin_izin_guru.store') }}", // route store
                    method: 'POST',
                    data: $(this).serialize(), // Serialize form data
                    success: function(response) {
                        alert('Guru berhasil ditambahkan!');
                        $('#modalGuruCreate').modal('hide');
                        location.reload(); // Reload halaman untuk update data
                    },
                    error: function(xhr) {
                        alert('Ada kesalahan. Gagal menyimpan data.');
                    }
                });
            });
        });
    </script>
@endsection
