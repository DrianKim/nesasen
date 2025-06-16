<table class="data-kelas-table">
    <thead>
        <tr>
            <th width="2%"><input type="checkbox" id="select-all" class="item-checkbox-all"></th>
            <th width="30%" class="sortable" data-column="Nama Jurusan">Nama Jurusan<i class="fas fa-sort" style="margin-left: 4px"></i></th>
            <th width="30%" class="sortable" data-column="Kode Jurusan">Kode Jurusan<i class="fas fa-sort" style="margin-left: 4px"></i></th>
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
                        <button class="btn-delete" data-toggle="modal"
                            data-target="#modalJurusantroy{{ $item->id }}">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </div>
                    {{-- @include('admin.jurusan.modal') --}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
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
