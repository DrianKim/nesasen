@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <h2 class="table-title">Data KelasKu SMKN 1 Subang</h2>
        <!-- Main content container -->
        <div class="skul-container">
            <!-- Student List Section -->
            <div class="content-section">
                <div class="filter-wrapper">
                    <div class="filter-top">
                        <div class="filter-bar">
                            {{-- <div class="filter-group">
                                <label for="tahun-ajaran">Tahun Ajaran:</label>
                                <select id="tahun-ajaran">
                                    <option value="">-</option>
                                    <option value="2024/2025">2024/2025</option>
                                </select>
                            </div> --}}

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

                            <div class="search-group">
                                <label for="search">Cari:</label>
                                <div class="search-wrapper">
                                    {{-- <input type="text" id="search" placeholder="Cari kelasKu..." /> --}}
                                    <input type="text" class="form-control" name="search" id="searchInput"
                                        placeholder="Cari KelasKu / Guru..." value="{{ request('search') }}">
                                    <button class="resetFilter btn-refresh">
                                        <span class="material-icons-sharp">refresh</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by') }}">
                        <input type="hidden" name="sort_direction" id="sort_direction"
                            value="{{ request('sort_direction', 'asc') }}">
                        <input type="hidden" name="page" id="current_page" value="{{ request('page', 1) }}">

                        <div class="right-actions">
                            @include('admin.kelasKu.modal.create')
                            @include('admin.kelasKu.modal.import')
                            @include('admin.kelasKu.modal.export')
                            <button id="btnHapusKelasKuSelect" class="btn-hapus">
                                <span class="material-icons-sharp">delete</span> Hapus
                            </button>
                            <button class="btn-tambah" onclick="openModal('modalKelasKuTambah')">
                                <span class="material-icons-sharp">add</span>
                            </button>
                            <button class="btn-import" onclick="openModalImport('modalKelasKuImport')">
                                <span class="material-icons-sharp">file_present</span>
                            </button>
                            <button class="btn-export-1" onclick="openModalExport('modalKelasKuExport')">
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
                    {{-- <form id="bulk_form" action="{{ route('admin_kelasKu.bulk_action') }}" method="POST"> --}}
                    {{-- @csrf --}}
                    <input type="hidden" name="bulk_action" id="bulk_action" value="">

                    <div id="table-container">
                        <!-- Konten tabel akan diisi dengan AJAX -->

                        @include('admin.kelasKu.partials.table')
                        <div class="mt-2 d-flex justify-content-end">
                            {{-- {{ $kelasKu->links() }} --}}
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>

                <!-- Pagination Section -->
                <div class="pagination-section" id="pagination-container">
                    <!-- Pagination akan diisi dengan AJAX -->
                    @include('admin.kelasKu.partials.pagination')
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any() && old('from_edit_kelasKu'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalKelasKuEdit{{ old('from_edit') }}');
            });
        </script>
    @elseif ($errors->any() && old('from_tambah_kelasKu'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalKelasKuTambah');
            });
        </script>
    @endif

    @foreach ($kelasKu as $item)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setupEditFormListenersKelasKu({{ $item->id }});
            });
        </script>
    @endforeach

    <script>
        document.getElementById('formTambahKelasKu').addEventListener('submit', function() {
            document.getElementById('btnTambahKelasKu').disabled = true;
            document.getElementById('spinnerTambahKelasKu').style.display = 'inline-block';
            document.getElementById('textBtnTambahKelasKu').textContent = 'Menyimpan...';
        });

        document.getElementById('formImportKelasKu').addEventListener('submit', function() {
            document.getElementById('btnImportKelasKu').disabled = true;
            document.getElementById('spinnerImportKelasKu').style.display = 'inline-block';
            document.getElementById('textBtnImportKelasKu').textContent = 'Mengimport...';
        });

        function setupEditFormListenersKelasKu(id) {
            const form = document.getElementById('formEditKelasKu' + id);
            const btn = document.getElementById('btnEditKelasKu' + id);
            const spinner = document.getElementById('spinnerEditKelasKu' + id);
            const text = document.getElementById('textBtnEditKelasKu' + id);

            if (form) {
                form.addEventListener('submit', function() {
                    if (btn && spinner && text) {
                        btn.disabled = true;
                        spinner.style.display = 'inline-block';
                        text.textContent = 'Menyimpan...';
                    }
                });
            }
        }
    </script>

    <script>
        function openModal() {
            const modal = document.getElementById("modalKelasKuTambah");
            if (modal) {
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                $('.mapel_kelasKu').select2({
                    placeholder: 'Cari nama mapel...',
                    // dropdownParent: $('#modalKelasKuTambah .modal-content'),
                    width: '100%'
                });

                $('.kelas_kelasKu').select2({
                    placeholder: 'Cari kelas...',
                    // dropdownParent: $('#modalKelasKuTambah .modal-content'),
                    width: '100%'
                });

                $('.guru_kelasKu').select2({
                    placeholder: 'Cari nama guru...',
                    // dropdownParent: $('#modalKelasKuTambah .modal-content'),
                    width: '100%'
                });
            }
        }

        function closeModal() {
            const modal = document.getElementById("modalKelasKuTambah");
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        }

        function openModalImport() {
            const modal = document.getElementById("modalKelasKuImport");
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show')
                }, 10);
            }
        }

        function closeModalImport() {
            const modal = document.getElementById("modalKelasKuImport");
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        }

        function openModalExport() {
            const modal = document.getElementById("modalKelasKuExport");
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show')
                }, 10);
            }
        }

        function closeModalExport() {
            const modal = document.getElementById("modalKelasKuExport");
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        }

        function openModalEdit(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                const id = modalId.replace('modalKelasKuEdit', '');
                setupEditFormListenersKelasKu(id);

                $('.mapel_kelasKu_edit').select2({
                    placeholder: 'Cari nama mapel...',
                    // dropdownParent: $('#modalKelasKuTambah .modal-content'),
                    width: '100%'
                });

                $('.kelas_kelasKu_edit').select2({
                    placeholder: 'Cari kelas...',
                    // dropdownParent: $('#modalKelasKuTambah .modal-content'),
                    width: '100%'
                });

                $('.guru_kelasKu_edit').select2({
                    placeholder: 'Cari nama guru...',
                    // dropdownParent: $('#modalKelasKuTambah .modal-content'),
                    width: '100%'
                });
            }
        }

        function closeModalEdit(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);

                // Destroy Select2 dulu
                $(modal).find('.select-guru-edit').select2('destroy');
                $(modal).find('.select-jurusan-edit').select2('destroy');
            }
        }
    </script>

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
        document.getElementById("form-import").addEventListener("submit", function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                        });
                        closeModal('modalKelasKuImport');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat impor.',
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Server error atau format salah!',
                    });
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            const tableContainer = document.getElementById('table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const searchInput = document.getElementById('searchInput');
            const resetButton = document.querySelector('.resetFilter');
            const perPageSelect = document.getElementById('perPage');
            const sortByInput = document.getElementById('sort_by');
            const sortDirectionInput = document.getElementById('sort_direction');
            const currentPageInput = document.getElementById('current_page');

            let searchTimer;
            let currentRequest = null;

            function getFilterData() {
                const data = new URLSearchParams();

                if (perPageSelect && perPageSelect.value) data.append('perPage', perPageSelect.value);
                if (searchInput && searchInput.value) data.append('search', searchInput.value);
                if (sortByInput && sortByInput.value) data.append('sort_by', sortByInput.value);
                if (sortDirectionInput && sortDirectionInput.value) data.append('sort_direction', sortDirectionInput
                    .value);
                if (currentPageInput && currentPageInput.value) data.append('page', currentPageInput.value);

                return data;
            }

            // Function to load data via AJAX
            function loadData() {
                // Show loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'flex';

                // If there's a pending request, abort it
                if (currentRequest) {
                    currentRequest.abort();
                }

                // Get filter data
                const filterData = getFilterData();

                // Create AJAX request
                currentRequest = new XMLHttpRequest();
                currentRequest.open('GET', '{{ route('admin_kelasKu.filter') }}?' + filterData.toString(), true);
                currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                // Add CSRF token if available
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    currentRequest.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
                }

                currentRequest.onload = function() {
                    if (this.status >= 200 && this.status < 400) {
                        try {
                            const response = JSON.parse(this.response);

                            // Update table and pagination
                            if (tableContainer && response.table) {
                                tableContainer.innerHTML = response.table;
                            }
                            if (paginationContainer && response.pagination) {
                                paginationContainer.innerHTML = response.pagination;
                            }

                            // Rebind events
                            bindSortingEvents();
                            bindPaginationEvents();
                            bindCheckboxEvents();

                            // Update URL
                            updateURL(filterData);
                        } catch (error) {
                            console.error('Error parsing response:', error);
                        }
                    } else {
                        console.error('Request failed with status:', this.status);
                    }

                    if (loadingIndicator) loadingIndicator.style.display = 'none';
                    currentRequest = null;
                };

                currentRequest.onerror = function() {
                    console.error('Connection error');
                    if (loadingIndicator) loadingIndicator.style.display = 'none';
                    currentRequest = null;
                };

                currentRequest.send();
            }

            // Function to update URL with current filters
            function updateURL(filterData) {
                if (history.pushState) {
                    const newURL = window.location.protocol + '//' + window.location.host +
                        window.location.pathname + '?' + filterData.toString();
                    window.history.pushState({
                        path: newURL
                    }, '', newURL);
                }
            }

            // Handle perPage select change
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    if (currentPageInput) currentPageInput.value = 1;
                    loadData();
                });
            }

            // Handle search input with debounce
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        if (currentPageInput) currentPageInput.value = 1;
                        loadData();
                    }, 500);
                });
            }

            // Handle reset button
            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Reset all filters
                    if (perPageSelect) perPageSelect.value = '10';
                    if (searchInput) searchInput.value = '';
                    if (sortByInput) sortByInput.value = '';
                    if (sortDirectionInput) sortDirectionInput.value = 'asc';
                    if (currentPageInput) currentPageInput.value = '1';

                    loadData();
                });
            }

            // Function to bind sorting events
            function bindSortingEvents() {
                const sortableHeaders = document.querySelectorAll('.sortable');
                sortableHeaders.forEach(header => {
                    header.addEventListener('click', function() {
                        const column = this.getAttribute('data-column');

                        if (sortByInput && sortDirectionInput) {
                            // Toggle sort direction if clicking the same column
                            if (sortByInput.value === column) {
                                sortDirectionInput.value = sortDirectionInput.value === 'asc' ?
                                    'desc' : 'asc';
                            } else {
                                sortByInput.value = column;
                                sortDirectionInput.value = 'asc';
                            }

                            loadData();
                        }
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
                        if (currentPageInput) currentPageInput.value = page;
                        loadData();

                        // Scroll to top of table
                        const contentSection = document.querySelector('.content-section');
                        if (contentSection) {
                            contentSection.scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            }

            // Function to bind checkbox events
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

            // Handle browser back/forward
            window.addEventListener('popstate', function(event) {
                location.reload();
            });

            // Initial binding
            bindSortingEvents();
            bindPaginationEvents();
            bindCheckboxEvents();
        });
    </script>
@endsection
