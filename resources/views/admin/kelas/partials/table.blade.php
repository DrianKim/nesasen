<table class="data-kelas-table">
    <thead>
        <tr>
            <th width="2%"><input type="checkbox" id="select-all" class="item-checkbox-all"></th>
            <th class="sortable" data-column="nama_kelas" width="15%">Kelas
                <i class="fa-solid fa-sort" style="margin-left: 4px"></i>
            </th>
            <th class="sortable" data-column="wali_kelas" width="30%">
                Wali Kelas
                <i class="fa-solid fa-sort" style="margin-left: 4px"></i>
            </th>
            <th data-column="jumlah_siswa" width="15%">
                Jumlah Siswa
            </th>
            <th width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kelas as $item)
            <tr>
                <td data-label="Pilih">
                    <input type="checkbox" name="selected_kelas[]" class="item-checkbox" value="{{ $item->id }}">
                </td>
                <td data-label="Kelas">
                    {{ $item->tingkat . ' ' . $item->jurusan->kode_jurusan . ' ' . $item->no_kelas ?? '-' }}
                </td>
                <td data-label="Wali Kelas">
                    {{ $item->walas->user->guru->nama ?? '-' }}
                </td>
                <td data-label="Jumlah Siswa">
                    {{ $item->siswa->count() ?? 0 }}
                </td>
                <td data-label="Action">
                    <div class="action-buttons">
                        @include('admin.kelas.modal.edit', ['id' => $item->id, 'kelas' => $item])
                        <button type="button" class="btn-edit"
                            onclick="openModalEdit('modalKelasEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-id="{{ $item->id }}"
                            data-nama="{{ $item->tingkat }} {{ $item->jurusan->kode_jurusan }} {{ $item->no_kelas }}">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data kelas yang ditemukan</p>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalKelasCreate">
                            <i class="fas fa-plus" style="margin-left: 0,25rem"></i>
                            <span class="button-label">Tambah Kelas</span>
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
            const namaKelas = button.getAttribute('data-nama');
            const isDark = document.body.classList.contains('dark-mode-variables')

            Swal.fire({
                title: `Hapus kelas ${namaKelas}?`,
                text: 'Data kelas ini bakal dihapus permanen!',
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
                    fetch(`/admin/kelas/destroy/${id}`, {
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
