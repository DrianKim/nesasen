@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <h2 class="table-title">Data Presensi Guru SMKN 1 Subang</h2>
        <!-- Main content container -->
        <div class="skul-container">
            <!-- Student List Section -->
            <div class="content-section">
                <div class="filter-wrapper">
                    <div class="filter-top">
                        <div class="filter-bar">
                            <div class="filter-group" id="filterTanggalRange">
                                <label for="range_tanggal">Tanggal:</label>
                                <input type="text" class="form-control" id="range_tanggal" name="range_tanggal"
                                    value="{{ request('range_tanggal') }}">
                            </div>

                            <div class="filter-group">
                                <label for="perPage">Tampilkan:</label>
                                <select id="perPage" name="perPage">
                                    <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10
                                    </option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100
                                    </option>
                                </select>
                            </div>

                            {{-- <div class="search-group">
                                <label for="search">Cari:</label>
                                <div class="search-wrapper">
                                    <input type="text" class="form-control" name="search" id="searchInput"
                                        placeholder="Cari nama guru..." value="{{ request('search') }}">
                                    <button class="resetFilter btn-refresh">
                                        <span class="material-icons-sharp">refresh</span>
                                    </button>
                                </div>
                            </div> --}}
                        </div>
                        <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="sort_direction" id="sort_direction"
                            value="{{ request('sort_direction', 'asc') }}">
                        <input type="hidden" name="page" id="current_page" value="{{ request('page', 1) }}">

                        <div class="right-actions">
                            {{-- @include('admin.guru.modal.create')
                            @include('admin.guru.modal.import')
                            @include('admin.guru.modal.export') --}}
                            {{-- <button class="btn-hapus">
                                <span class="material-icons-sharp">delete</span> Hapus
                            </button>
                            <button class="btn-tambah" onclick="openModal('modalSiswaTambah')">
                                <span class="material-icons-sharp">add</span>
                            </button>
                            <button class="btn-import" onclick="openModalImport('modalSiswaImport')">
                                <span class="material-icons-sharp">file_present</span>
                            </button> --}}
                            <button class="btn-export-1" onclick="openModalExport('modalSiswaExport')">
                                <span class="material-icons-sharp">upload_file</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="table-responsive" style="position: relative;">
                    <!-- Loading Indicator -->
                    <div id="loading-indicator" style="display:none;" class="loading-overlay-table">
                        <div class="my-3 d-flex justify-content-center spinner-wrapper">
                            <div class="spinner-border text-primary" role="status">
                                <span class="loading-text">Loading...</span>
                            </div>
                        </div>
                    </div>
                    {{-- <form id="bulk_form" action="{{ route('admin_guru.bulk_action') }}" method="POST">
                        @csrf
                        <input type="hidden" name="bulk_action" id="bulk_action" value=""> --}}

                    {{-- <div class="bulk-actions">
                            <div class="bulk-buttons">
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="bulkAction('delete')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div> --}}

                    <div id="table-container">
                        <!-- Konten tabel akan diisi dengan AJAX -->
                        @include('admin.presensi.guru.partials.table')
                        <div class="mt-2 d-flex justify-content-end">
                            {{-- {{ $guru->links() }}   --}}
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>

                <!-- Pagination Section -->
                <div class="pagination-section" id="pagination-container">
                    <!-- Pagination akan diisi dengan AJAX -->
                    @include('admin.presensi.guru.partials.pagination')

                </div>
            </div>
        </div>
    </div>

    <script>
        function fetchData(page = 1) {
            const url = new URL(window.location.href);
            url.searchParams.set('page', page);

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.querySelector('#table-container').innerHTML = data.table;
                    document.querySelector('#pagination-container').innerHTML = data.pagination;
                })
                .catch(err => console.error(err));
        }

        // Optional: bind otomatis ke semua tombol pagination
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-page]')) {
                const page = e.target.getAttribute('data-page');
                fetchData(page);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            // const filterForm = document.getElementById('presensi_guruFilterForm');
            const tableContainer = document.getElementById('table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const searchInput = document.getElementById('searchInput');
            const resetButton = document.getElementById('resetFilter');

            const tanggalInput = document.getElementById('tanggal');
            const rangeTanggalInput = document.getElementById('range_tanggal');
            const perPageSelect = document.getElementById('perPage');
            const sortByInput = document.getElementById('sort_by');
            const sortDirectionInput = document.getElementById('sort_direction');
            const currentPageInput = document.getElementById('current_page')

            let searchTimer;
            let currentRequest = null;

            const filterTanggalRange = document.getElementById('filterTanggalRange');

            function getFilterData() {
                const data = new URLSearchParams();

                if (perPageSelect.value) data.append('perPage', perPageSelect.value);
                if (sortByInput.value) data.append('sort_by', sortByInput.value);
                if (sortDirectionInput.value) data.append('sort_direction', sortDirectionInput.value);
                if (currentPageInput.value) data.append('page', currentPageInput.value);

                const rangeTanggalVal = rangeTanggalInput?.value;
                if (rangeTanggalVal) data.append('range_tanggal', rangeTanggalVal);

                return data;
            }

            $(document).ready(function() {
                $('#range_tanggal').daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD',
                        separator: ' - ',
                        applyLabel: 'Terapkan',
                        cancelLabel: 'Batal',
                        daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        monthNames: [
                            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ],
                    },
                    autoUpdateInput: true,
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(
                        picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                            'YYYY-MM-DD')
                    );

                    currentPageInput.value = 1;
                    loadData();
                });
            });

            // Function to load data via AJAX
            function loadData() {
                // Show loading indicator
                loadingIndicator.style.display = 'flex';

                // If there's a pending request, abort it
                if (currentRequest) {
                    currentRequest.abort();
                }

                // Get form data
                const filterData = getFilterData();

                // Create AJAX request
                currentRequest = new XMLHttpRequest();
                currentRequest.open('GET', '{{ route('admin_presensi_guru.filter') }}?' + filterData
                    .toString(),
                    true);
                currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                currentRequest.onload = function() {
                    if (this.status >= 200 && this.status < 400) {
                        try {
                            // Success response
                            const response = JSON.parse(this.response);
                            tableContainer.innerHTML = response.table;
                            paginationContainer.innerHTML = response.pagination;


                            // Rebind sorting events
                            bindSortingEvents();
                            // Rebind pagination events
                            bindPaginationEvents();

                            bindCheckboxEvents();

                            // Update URL without reloading the page
                            updateURL(filterData);
                        } catch (error) {
                            console.error('Error parsing response', error);;
                        }
                    } else {
                        console.error('Request failed with status:',
                            this.status);
                    }

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
            function updateURL(filterData) {
                if (history.pushState) {
                    // const searchParams = new URLSearchParams(formData);
                    const newURL = window.location.protocol + '//' + window.location.host +
                        window.location.pathname + '?' + filterData.toString();
                    window.history.pushState({
                        path: newURL
                    }, '', newURL);
                }
            }

            const filters = ['range_tanggal'];

            filters.forEach(filterId => {
                const element = document.getElementById(filterId);
                if (element) {
                    element.addEventListener('change', function() {
                        document.getElementById('current_page').value = 1;
                        loadData();
                    });
                }
            });

            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    currentPageInput.value = 1;
                    loadData();
                });
            }

            // Handle reset button
            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Reset all filters
                    if (lihatBerdasarkan) lihatBerdasarkan.value = 'kelas';
                    if (filterTanggal) filterTanggal.value = '';
                    if (perPageSelect) perPageSelect.value = '10';
                    if (searchInput) searchInput.value = '';
                    sortByInput.value = '';
                    sortDirectionInput.value = 'asc';
                    currentPageInput.value = '1';

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
                        if (sortByInput.value === column) {
                            sortDirectionInput.value = sortDirectionInput.value ===
                                'asc' ? 'desc' :
                                'asc';
                        } else {
                            // Default to ascending for new column
                            sortByInput.value = column;
                            sortDirectionInput.value = 'asc';
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
                        currentPageInput.value = page;
                        loadData();

                        // Scroll to top of table
                        const contentSection = document.querySelector(
                            '.content-section');
                        if (contentSection) {
                            window.scrollTo({
                                top: contentSection.offsetTop,
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            }

            // Initial binding
            bindSortingEvents();
            bindPaginationEvents();
        });

        function bindCheckboxEvents() {
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }

            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (selectAllCheckbox) {
                        const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);

                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    }
                });
            });
        }

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

        window.addEventListener('popstate', function(event) {
            location.reload();
        });

        // Initial binding
        bindSortingEvents();
        bindPaginationEvents();
        bindCheckboxEvents();
    </script>
@endsection
