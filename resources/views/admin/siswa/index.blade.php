@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <h2 class="table-title">Data Siswa SMKN 1 Subang</h2>
        <!-- Main content container -->
        <div class="skul-container">
            <!-- Student List Section -->
            <div class="content-section">
                <div class="filter-wrapper">
                    <div class="filter-top">
                        <div class="filter-bar">
                            <div class="filter-group">
                                <label for="tahun-ajaran">Tahun Ajaran:</label>
                                <select id="tahun-ajaran">
                                    <option value="">-</option>
                                    <option value="2024/2025">2024/2025</option>
                                </select>
                            </div>

                            <form action="{{ route('admin_siswa.index') }}" method="GET">
                                <div class="filter-group">
                                    <label for="kelas">Kelas:</label>
                                    <select id="kelas" name="kelas" class="form-control">
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
                            </form>

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
                                        placeholder="Cari nama siswa..." value="{{ request('search') }}">
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
                            @include('admin.siswa.modal.create')
                            @include('admin.siswa.modal.import')
                            @include('admin.siswa.modal.export')
                            <button class="btn-hapus">
                                <span class="material-icons-sharp">delete</span> Hapus
                            </button>
                            <button class="btn-tambah" onclick="openModal('modalSiswaTambah')">
                                <span class="material-icons-sharp">add</span>
                            </button>
                            <button class="btn-import" onclick="openModalImport('modalSiswaImport')">
                                <span class="material-icons-sharp">file_present</span>
                            </button>
                            <button class="btn-export-1" onclick="openModalExport('modalSiswaExport')">
                                <span class="material-icons-sharp">upload_file</span>
                            </button>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loading-indicator" style="display:none;">
                        <div class="my-3 d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                {{-- <div class="filter-section">
                    <form id="siswaFilterForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
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
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tahun_ajaran">Tahun Ajaran:</label>
                                    <select class="form-select" id="tahun_ajaran" name="tahun_ajaran">
                                        @foreach ($tahunAjaranFilter as $tahun)
                                            <option value="{{ $tahun }}"
                                                {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
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
                                            placeholder="Cari siswa..." value="{{ request('search') }}">
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
                </div> --}}

                <!-- Table Section -->
                <div class="table-responsive">
                    {{-- <form id="bulk_form" action="{{ route('admin_siswa.bulk_action') }}" method="POST">
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
                        @include('admin.siswa.partials.table')
                        <div class="mt-2 d-flex justify-content-end">
                            {{-- {{ $siswa->links() }}   --}}
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>

                <!-- Pagination Section -->
                <div class="pagination-section" id="pagination-container">
                    <!-- Pagination akan diisi dengan AJAX -->
                    @include('admin.siswa.partials.pagination')

                </div>
            </div>
        </div>
    </div>
    
    @if ($errors->any() && old('from_edit_siswa'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalSiswaEdit{{ old('from_edit_siswa') }}');
            });
        </script>
    @elseif ($errors->any() && old('from_tambah_siswa'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalSiswaTambah');
            });
        </script>
    @endif

    @foreach ($siswa as $item)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setupEditFormListenersSiswa({{ $item->id }});
            });
        </script>
    @endforeach

    <script>
        document.getElementById('formTambahSiswa').addEventListener('submit', function() {
            document.getElementById('btnTambahSiswa').disabled = true;
            document.getElementById('spinnerTambahSiswa').style.display = 'inline-block';
            document.getElementById('textBtnTambahSiswa').textContent = 'Menyimpan...';
        });

        document.getElementById('formImportSiswa').addEventListener('submit', function() {
            document.getElementById('btnImportSiswa').disabled = true;
            document.getElementById('spinnerImportSiswa').style.display = 'inline-block';
            document.getElementById('textBtnImportSiswa').textContent = 'Mengimport...';
        });

        function setupEditFormListenersSiswa(id) {
            const form = document.getElementById('formEditSiswa' + id);
            const btn = document.getElementById('btnEditSiswa' + id);
            const spinner = document.getElementById('spinnerEditSiswa' + id);
            const text = document.getElementById('textBtnEditSiswa' + id);

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
            const modal = document.getElementById("modalSiswaTambah");
            if (modal) {
                modal.style.display = 'block';

                $('#kelasModal').select2({
                    placeholder: 'Cari kelas...',
                    // dropdownParent: $(modal).find('.modal-content'),
                    width: '100%'
                });
            }
        }

        function closeModal() {
            document.getElementById("modalSiswaTambah").style.display = 'none';
        }

        function openModalImport() {
            document.getElementById("modalSiswaImport").style.display = 'flex';
        }

        function closeModalImport() {
            document.getElementById("modalSiswaImport").style.display = 'none';
        }

        function openModalExport() {
            document.getElementById("modalSiswaExport").style.display = 'flex';
        }

        function closeModalExport() {
            document.getElementById("modalSiswaExport").style.display = 'none';
        }

        function openModalEdit(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';

                const id = modalId.replace('modalSiswaEdit', '');
                setupEditFormListenersSiswa(id);

                $(modal).find('.select-kelas-edit').select2({
                    placeholder: 'Cari kelas...',
                    dropdownParent: $(modal),
                    width: '100%',
                    allowClear: true
                });
            }
        }

        function closeModalEdit(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Destroy Select2 dulu
                $(modal).find('.select-kelas-edit').select2('destroy');

                modal.style.display = 'none';
            }
        }

        // function openModalEdit(modalId, id) {
        //     document.getElementById(modalId).style.display = 'block';
        // }
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
                        closeModal('modalSiswaImport');
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
            const kelasListSelect = document.getElementById('kelas');
            const perPageSelect = document.getElementById('perPage');
            const sortByInput = document.getElementById('sort_by');
            const sortDirectionInput = document.getElementById('sort_direction');
            const currentPageInput = document.getElementById('current_page')

            let searchTimer;
            let currentRequest = null;

            function getFilterData() {
                const data = new URLSearchParams();

                if (tahunAjaranSelect.value) data.append('tahun_ajaran', tahunAjaranSelect.value);
                if (kelasListSelect) data.append('kelas', kelasListSelect.value);
                if (perPageSelect.value) data.append('perPage', perPageSelect.value);
                if (searchInput.value) data.append('search', searchInput.value);
                if (sortByInput.value) data.append('sort_by', sortByInput.value);
                if (sortDirectionInput.value) data.append('sort_direction', sortDirectionInput.value);
                if (currentPageInput.value) data.append('page', currentPageInput.value);

                return data;
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
                // const formData = new FormData(filterForm);

                // Get filter data
                const filterData = getFilterData();

                // Create AJAX request
                currentRequest = new XMLHttpRequest();
                currentRequest.open('GET', '{{ route('admin_siswa.filter') }}?' + filterData.toString(), true);
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

            // if (kelasListSelect) {
            //     kelasListSelect.addEventListener('change', function() {
            //         currentPageInput.value = 1;
            //         loadData();
            //     });
            // }

            if (typeof $ !== 'undifined') {
                $('#kelas').select2({
                    placeholder: "Pilih Kelas...",
                    allowClear: true,
                    width: '100%',
                });

                $('#kelas').on('change', function() {
                    currentPageInput.value = 1;
                    loadData();
                });
            } else {
                console.warn('select2 tidak dapat diaktifkan');
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
                    if (kelasListSelect) $('#kelas').val('').trigger('change');
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
