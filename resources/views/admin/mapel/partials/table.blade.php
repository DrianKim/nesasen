<table class="data-kelas-table">
    <thead>
        <tr>
            <th width="2%"><input type="checkbox" id="select-all" class="item-checkbox-all"></th>
            <th width="30%" class="sortable" data-column="Nama Mapel">Nama Mapel<i class="fas fa-sort"
                    style="margin-left: 4px"></i></th>
            <th width="30%" class="sortable" data-column="Kode Mapel">Kode Mapel<i class="fas fa-sort"
                    style="margin-left: 4px"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($mapel as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_mapel[]" class="item-checkbox" value="{{ $item->id }}">
                </td>
                <td>{{ $item->nama_mapel ?? '-' }}
                <td>{{ $item->kode_mapel ?? '-' }}</td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        @include('admin.mapel.modal.edit', ['id' => $item->id, 'mapel' => $item])
                        <button type="button" class="btn-edit"
                            onclick="openModalEdit('modalMapelEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-toggle="modal" data-target="#modalMapeltroy{{ $item->id }}">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </div>
                    @include('admin.mapel.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data mapel yang ditemukan</p>
                        @include('admin.mapel.modal-create')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalMapelCreate">
                            <i class="mr-1 fas fa-plus"></i>
                            <span class="button-label">Tambah Mapel</span>
                        </button>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
