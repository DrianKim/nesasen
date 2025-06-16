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
                <td>
                    <input type="checkbox" name="selected_kelas[]" class="item-checkbox" value="{{ $item->id }}">
                </td>
                <td>{{ $item->tingkat . ' ' . $item->jurusan->kode_jurusan . ' ' . $item->no_kelas ?? '-' }} </td>
                <td>{{ $item->walas->user->guru->nama ?? '-' }}</td>
                <td>{{ $item->siswa->count() ?? 0 }}</td>
                <td>
                    <div class="action-buttons">
                        @include('admin.kelas.modal.edit', ['id' => $item->id, 'kelas' => $item])
                        <button type="button" class="btn-edit"
                            onclick="openModalEdit('modalKelasEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-toggle="modal"
                            data-target="#modalKelasDestroy{{ $item->id }}">
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
                        {{-- @include('admin.kelas.modal-create') --}}
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
