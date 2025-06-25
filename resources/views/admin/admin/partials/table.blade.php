<table class="data-kelas-table">
    <thead>
        <tr>
            <th width="2%"><input type="checkbox" id="select-all" class="item-checkbox-all"></th>
            <th class="sortable" data-column="Username" width="15%">Username <i class="fa-solid fa-sort"
                    style="margin-left: 4px"></i></th>
            <th class="sortable" data-column="Nama Admin" width="30%">Nama Admin <i class="fa-solid fa-sort"
                    style="margin-left: 4px"></i></th>
            <th width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($admin as $item)
            <tr>
                <td>
    @if ($item->id === auth()->id())
        <div style="font-size: 10px; color: #999;">Akun Anda</div>
    @else
        <input type="checkbox" name="selected_admin[]" class="item-checkbox" value="{{ $item->id }}">
    @endif
</td>
                <td data-field="username">{{ $item->username ?? '-' }}</td>
                <td data-field="nama">{{ $item->name_admin ?? '-' }}</td>
                <td>
                    <div class="action-buttons">
                        @include('admin.admin.modal.edit', ['id' => $item->id, 'admin' => $item])
                        <button class="btn-edit" onclick="openModalEdit('modalAdminEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-id="{{ $item->id }}" data-nama="{{ $item->name_admin }}">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets/img/not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data admin yang ditemukan</p>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalAdminCreate">
                            <i class="mr-1 fas fa-plus"></i>
                            <span class="button-label">Tambah Admin</span>
                        </button>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete')) {
            const button = e.target.closest('.btn-delete')
            const id = button.getAttribute('data-id');
            const namaAdmin = button.getAttribute('data-nama');
            const isDark = document.body.classList.contains('dark-mode-variables')

            Swal.fire({
                title: `Hapus admin dengan nama ${namaAdmin}?`,
                text: 'Data admin ini bakal dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e7586e',
                cancelButtonColor: '#43c6c9',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                background: isDark ? getComputedStyle(document.body).getPropertyValue(
                    '--color-background') : '#fff',
                color: isDark ? getComputedStyle(document.body).getPropertyValue('--color-dark') :
                    '#000',
                customClass: {
                    popup: isDark ? 'swal-dark' : ''
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/admin/destroy/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(() => {
                            location.reload();
                        });
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnHapusAdminSelect = document.getElementById('btnHapusAdminSelect');

        if (btnHapusAdminSelect) {
            btnHapusAdminSelect.addEventListener('click', function() {
                const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(
                    cb => cb.value);

                if (selected.length === 0) {
                    return Swal.fire({
                        title: 'Tidak ada data terpilih',
                        text: 'Pilih setidaknya satu data untuk dihapus.',
                        icon: 'warning',
                        confirmButtonColor: '#43c6c9',
                        background: isDarkMode() ? getBg() : '#fff',
                        color: isDarkMode() ? getColor() : '#000',
                        customClass: {
                            popup: isDarkMode() ? 'swal-dark' : ''
                        }
                    });
                }

                Swal.fire({
                    title: `Hapus ${selected.length} data admin?`,
                    text: 'Data admin ini bakal dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e7586e',
                    cancelButtonColor: '#43c6c9',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    background: isDarkMode() ? getBg() : '#fff',
                    color: isDarkMode() ? getColor() : '#000',
                    customClass: {
                        popup: isDarkMode() ? 'swal-dark' : ''
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ route('admin_admin.bulk_action') }}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    ids: selected
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: data.message,
                                        confirmButtonColor: '#43c6c9',
                                        background: isDarkMode() ? getBg() : '#fff',
                                        color: isDarkMode() ? getColor() : '#000',
                                        customClass: {
                                            popup: isDarkMode() ? 'swal-dark' : ''
                                        }
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: data.message,
                                        confirmButtonColor: '#e7586e',
                                        background: isDarkMode() ? getBg() : '#fff',
                                        color: isDarkMode() ? getColor() : '#000',
                                        customClass: {
                                            popup: isDarkMode() ? 'swal-dark' : ''
                                        }
                                    });
                                }
                            });
                    }
                });
            });
        }

        function isDarkMode() {
            return document.body.classList.contains('dark-mode-variables');
        }

        function getBg() {
            return getComputedStyle(document.body).getPropertyValue('--color-background');
        }

        function getColor() {
            return getComputedStyle(document.body).getPropertyValue('--color-dark');
        }
    });
</script>
