@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <h2 class="table-title">Data Admin SMKN 1 Subang</h2>
        <div class="skul-container">
            <div class="content-section">
                <div class="filter-wrapper">
                    <div class="filter-top">
                        <div class="filter-bar">
                            <div class="filter-group">
                                <label for="perPage">Tampilkan:</label>
                                <select id="perPage" name="perPage">
                                    <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                            <div class="search-group">
                                <label for="search">Cari:</label>
                                <div class="search-wrapper">
                                    <input type="text" class="form-control" name="search" id="searchInput"
                                        placeholder="Cari nama admin..." value="{{ request('search') }}">
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
                            @include('admin.admin.modal.create')
                            <button id="btnHapusAdminSelect" class="btn-hapus">
                                <span class="material-icons-sharp">delete</span> Hapus
                            </button>
                            <button class="btn-tambah" onclick="openModal('modalAdminTambah')">
                                <span class="material-icons-sharp">add</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="position: relative;">
                    <div id="loading-indicator" style="display:none;" class="loading-overlay-table">
                        <div class="my-3 d-flex justify-content-center spinner-wrapper">
                            <div class="spinner-border text-primary" role="status">
                                <span class="loading-text">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div id="table-container">
                        @include('admin.admin.partials.table')
                    </div>
                </div>
                <div class="pagination-section" id="pagination-container">
                    @include('admin.admin.partials.pagination')
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any() && old('from_edit_admin'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalAdminEdit{{ old('from_edit_admin') }}');
            });
        </script>
    @elseif ($errors->any() && old('from_tambah_admin'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('modalAdminTambah');
            });
        </script>
    @endif

    <script>
        document.getElementById('formTambahAdmin').addEventListener('submit', function() {
            document.getElementById('btnTambahAdmin').disabled = true;
            document.getElementById('spinnerTambahAdmin').style.display = 'inline-block';
            document.getElementById('textBtnTambahAdmin').textContent = 'Menyimpan...';
        });

        function setupEditFormListenersAdmin(id) {
            const form = document.getElementById('formEditAdmin' + id);
            const btn = document.getElementById('btnEditAdmin' + id);
            const spinner = document.getElementById('spinnerEditAdmin' + id);
            const text = document.getElementById('textBtnEditAdmin' + id);

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
            const modal = document.getElementById("modalAdminTambah");
            if (modal) {
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }
        }

        function closeModal() {
            const modal = document.getElementById("modalAdminTambah");
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

                const id = modalId.replace('modalAdminEdit', '');
                setupEditFormListenersAdmin(id);
            }
        }

        function closeModalEdit(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
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
        document.addEventListener('DOMContentLoaded', function() {
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
                if (perPageSelect.value) data.append('perPage', perPageSelect.value);
                if (searchInput.value) data.append('search', searchInput.value);
                if (sortByInput.value) data.append('sort_by', sortByInput.value);
                if (sortDirectionInput.value) data.append('sort_direction', sortDirectionInput.value);
                if (currentPageInput.value) data.append('page', currentPageInput.value);
                return data;
            }

            function loadData() {
                loadingIndicator.style.display = 'flex';
                if (currentRequest) currentRequest.abort();
                const filterData = getFilterData();
                currentRequest = new XMLHttpRequest();
                currentRequest.open('GET', '{{ route('admin_data_admin.filter') }}?' + filterData.toString(), true);
                currentRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                currentRequest.onload = function() {
                    if (this.status >= 200 && this.status < 400) {
                        const response = JSON.parse(this.response);
                        tableContainer.innerHTML = response.table;
                        paginationContainer.innerHTML = response.pagination;
                        bindSortingEvents();
                        bindPaginationEvents();
                        bindCheckboxEvents();
                        updateURL(filterData);
                    }
                    loadingIndicator.style.display = 'none';
                    currentRequest = null;
                };
                currentRequest.onerror = function() {
                    loadingIndicator.style.display = 'none';
                    currentRequest = null;
                };
                currentRequest.send();
            }

            function updateURL(filterData) {
                if (history.pushState) {
                    const newURL = window.location.protocol + '//' + window.location.host + window.location
                        .pathname + '?' + filterData.toString();
                    window.history.pushState({
                        path: newURL
                    }, '', newURL);
                }
            }

            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    currentPageInput.value = 1;
                    loadData();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        currentPageInput.value = 1;
                        loadData();
                    }, 500);
                });
            }

            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    perPageSelect.value = '10';
                    searchInput.value = '';
                    sortByInput.value = '';
                    sortDirectionInput.value = 'asc';
                    currentPageInput.value = '1';
                    loadData();
                });
            }

            function bindSortingEvents() {
                const sortableHeaders = document.querySelectorAll('.sortable');
                sortableHeaders.forEach(header => {
                    header.addEventListener('click', function() {
                        const column = this.getAttribute('data-column');
                        if (sortByInput.value === column) {
                            sortDirectionInput.value = sortDirectionInput.value === 'asc' ? 'desc' :
                                'asc';
                        } else {
                            sortByInput.value = column;
                            sortDirectionInput.value = 'asc';
                        }
                        loadData();
                    });
                });
            }

            function bindPaginationEvents() {
                const paginationLinks = document.querySelectorAll('.pagination .page-link[data-page]');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        currentPageInput.value = page;
                        loadData();
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

            bindSortingEvents();
            bindPaginationEvents();
            bindCheckboxEvents();
            loadData();
        });
    </script>
@endsection
