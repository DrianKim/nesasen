@extends('admin.layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="content-wrapper">
        <h2 class="table-title">Data Kelas SMKN 1 Subang</h2>
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
                                    {{-- <input type="text" id="search" placeholder="Cari kelas..." /> --}}
                                    <input type="text" class="form-control" name="search" id="searchInput"
                                        placeholder="Cari kelas / nama wali kelas..." value="{{ request('search') }}">
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
                            @include('admin.kelas.modal.create')
                            @include('admin.kelas.modal.import')
                            @include('admin.kelas.modal.export')
                            <button id="btnHapusKelasSelect" class="btn-hapus">
                                <span class="material-icons-sharp">delete</span> Hapus
                            </button>
                            <button class="btn-tambah" onclick="openModal('modalKelasTambah')">
                                <span class="material-icons-sharp">add</span>
                            </button>
                            <button class="btn-import" onclick="openModalImport('modalKelasImport')">
                                <span class="material-icons-sharp">file_present</span>
                            </button>
                            <button class="btn-export-1" onclick="openModalExport('modalKelasExport')">
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
                    {{-- <form id="bulk_form" action="{{ route('admin_kelas.bulk_action') }}" method="POST"> --}}
                    {{-- @csrf --}}
                    <input type="hidden" name="bulk_action" id="bulk_action" value="">

                    <div id="table-container">
                        <!-- Konten tabel akan diisi dengan AJAX -->

                        @include('admin.kelas.partials.table')
                        <div class="mt-2 d-flex justify-content-end">
                            {{-- {{ $kelas->links() }} --}}
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>

                <!-- Pagination Section -->
                <div class="pagination-section" id="pagination-container">
                    <!-- Pagination akan diisi dengan AJAX -->
                    @include('admin.kelas.partials.pagination')
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any() && old('from_edit_kelas'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalKelasEdit{{ old('from_edit') }}');
            });
        </script>
    @elseif ($errors->any() && old('from_tambah_kelas'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalKelasTambah');
            });
        </script>
    @endif

    @foreach ($kelas as $item)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setupEditFormListenersKelas({{ $item->id }});
            });
        </script>
    @endforeach

    <script>
        document.getElementById('formTambahKelas').addEventListener('submit', function() {
            document.getElementById('btnTambahKelas').disabled = true;
            document.getElementById('spinnerTambahKelas').style.display = 'inline-block';
            document.getElementById('textBtnTambahKelas').textContent = 'Menyimpan...';
        });

        document.getElementById('formImportKelas').addEventListener('submit', function() {
            document.getElementById('btnImportKelas').disabled = true;
            document.getElementById('spinnerImportKelas').style.display = 'inline-block';
            document.getElementById('textBtnImportKelas').textContent = 'Mengimport...';
        });

        function setupEditFormListenersKelas(id) {
            const form = document.getElementById('formEditKelas' + id);
            const btn = document.getElementById('btnEditKelas' + id);
            const spinner = document.getElementById('spinnerEditKelas' + id);
            const text = document.getElementById('textBtnEditKelas' + id);

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
            const modal = document.getElementById("modalKelasTambah");
            if (modal) {
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);

                $('#guru').select2({
                    placeholder: 'Cari nama guru...',
                    // dropdownParent: $('#modalKelasTambah .modal-content'),
                    width: '100%'
                });

                $('#jurusan').select2({
                    placeholder: 'Pilih Jurusan',
                    // dropdownParent: $('#modalKelasTambah .modal-content'),
                    width: '100%'
                });
            }
        }

        function closeModal() {
            const modal = document.getElementById("modalKelasTambah");
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        }

        function openModalImport() {
            const modal = document.getElementById("modalKelasImport");
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show')
                }, 10);
            }
        }

        function closeModalImport() {
            const modal = document.getElementById("modalKelasImport");
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        }

        function openModalExport() {
            const modal = document.getElementById("modalKelasExport");
            if (modal) {
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show')
                }, 10);
            }
        }

        function closeModalExport() {
            const modal = document.getElementById("modalKelasExport");
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

                const id = modalId.replace('modalKelasEdit', '');
                setupEditFormListenersKelas(id);

                $(modal).find('.select-guru-edit').select2({
                    placeholder: 'Cari nama guru...',
                    dropdownParent: $(modal),
                    width: '100%',
                    allowClear: true
                });
                $(modal).find('.select-jurusan-edit').select2({
                    placeholder: 'Cari jurusan...',
                    dropdownParent: $(modal),
                    width: '100%',
                    allowClear: true
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
                        closeModal('modalKelasImport');
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
            // const filterForm = document.getElementById('kelasFilterForm');
            const tableContainer = document.getElementById('table-container');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            const searchInput = document.getElementById('searchInput');
            const resetButton = document.querySelector('.resetFilter');

            const tahunAjaranSelect = document.getElementById('tahun-ajaran');
            const perPageSelect = document.getElementById('perPage');
            const sortByInput = document.getElementById('sort_by');
            const sortDirectionInput = document.getElementById('sort_direction');
            const currentPageInput = document.getElementById('current_page')

            let searchTimer;
            let currentRequest = null;


            function getFilterData() {
                const data = new URLSearchParams();

                if (tahunAjaranSelect.value) data.append('tahun_ajaran', tahunAjaranSelect.value);
                if (perPageSelect.value) data.append('perPage', perPageSelect.value);
                if (searchInput.value) data.append('search', searchInput.value);
                if (sortByInput.value) data.append('sort_by', sortByInput.value);
                if (sortDirectionInput.value) data.append('sort_direction', sortDirectionInput.value);
                if (currentPageInput.value) data.append('page', currentPageInput.value);

                return data;
            }

            // Function to load data via AJAX
            function loadData() {
                // document.getElementById('loading-indicator').style.display = 'flex';
                // Show loading indicator
                loadingIndicator.style.display = 'flex';

                // If there's a pending request, abort it
                if (currentRequest) {
                    currentRequest.abort();
                }

                // Get form data
                // const formData = new FormData(filterForm);

                // Get filter data
                const filterData = getFilterData();

                // Create AJAX request
                currentRequest = new XMLHttpRequest();
                currentRequest.open('GET', '{{ route('admin_kelas.filter') }}?' + filterData.toString(), true);
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

            // Bind events to filter elements
            // filters.forEach(filter => {
            //     const element = document.getElementById(filter);
            //     if (element) {
            //         element.addEventListener('change', function() {
            //             // Reset to page 1 when changing filters
            //             document.getElementById('current_page').value = 1;
            //             loadData();
            //         });
            //     }
            // });

            if (tahunAjaranSelect) {
                tahunAjaranSelect.addEventListener('change', function() {
                    currentPageInput.value = 1;
                    loadData();
                });
            }

            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    currentPageInput.value = 1;
                    loadData();
                });
            }

            // Handle search input with debounce
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        // Reset to page 1 when searching
                        currentPageInput.value = 1;
                        loadData();
                    }, 500); // 500ms debounce
                });
            }

            // Handle reset button
            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Reset all filters
                    if (tahunAjaranSelect) tahunAjaranSelect.value = '';
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
                            sortDirectionInput.value = sortDirectionInput.value === 'asc' ? 'desc' :
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
                        const contentSection = document.querySelector('.content-section');
                        if (contentSection) {
                            window.scrollTo({
                                top: contentSection.offsetTop,
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            }

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
