<table class="data-kelas-table">
    <thead>
        <tr>
            <th width="2%"><input type="checkbox" id="select-all" class="item-checkbox-all"></th>
            <th width="30%" class="sortable" data-column="Nama Jurusan">Nama Jurusan<i class="fas fa-sort"
                    style="margin-left: 4px"></i></th>
            <th width="30%" class="sortable" data-column="Kode Jurusan">Kode Jurusan<i class="fas fa-sort"
                    style="margin-left: 4px"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($jurusan as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_jurusan[]" class="item-checkbox" value="{{ $item->id }}">
                </td>
                <td>{{ $item->nama_jurusan ?? '-' }}
                <td>{{ $item->kode_jurusan ?? '-' }}</td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        @include('admin.jurusan.modal.edit', ['id' => $item->id, 'jurusan' => $item])
                        <button type="button" class="btn-edit"
                            onclick="openModalEdit('modalJurusanEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-id="{{ $item->id }}" data-nama="{{ $item->nama_jurusan }}"
                            data-kode="{{ $item->kode_jurusan }}">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </div>
                    {{-- @include('admin.jurusan.modal') --}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data jurusan yang ditemukan</p>
                        {{-- @include('admin.jurusan.modal-create') --}}
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalJurusanCreate">
                            <i class="mr-1 fas fa-plus"></i>
                            <span class="button-label">Tambah Jurusan</span>
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
            const namaJurusan = button.getAttribute('data-nama');
            const kodeJurusan = button.getAttribute('data-kode');
            const isDark = document.body.classList.contains('dark-mode-variables')

            Swal.fire({
                title: `Hapus jurusan ${namaJurusan}(${kodeJurusan})?`,
                text: 'Data jurusan ini bakal dihapus permanen!',
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
                    fetch(`/admin/jurusan/destroy/${id}`, {
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
        const btnHapusJurusanSelect = document.getElementById('btnHapusJurusanSelect');

        if (btnHapusJurusanSelect) {
            btnHapusJurusanSelect.addEventListener('click', function() {
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
                    title: `Hapus ${selected.length} data jurusan?`,
                    text: 'Data jurusan ini bakal dihapus permanen!',
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
                        fetch(`{{ route('admin_jurusan.bulk_action') }}`, {
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
