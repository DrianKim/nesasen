<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="30%" class="sortable" data-column="Nama Mapel">Nama Mapel<i
                    class="fas fa-sort"></i></th>
            <th width="30%" class="sortable" data-column="Kode Mapel">Kode Mapel<i
                    class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($mapel as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_mapel[]" value="{{ $item->id }}">
                </td>
                <td>{{ $item->nama_mapel ?? '-' }}
                <td>{{ $item->kode_mapel ?? '-' }}</td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary"
                            data-toggle="modal"
                            data-target="#modalMapelShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-toggle="modal"
                            data-target="#modalMapelDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @include('admin.mapel.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data"
                            width="120">
                        <p>Tidak ada data mapel yang ditemukan</p>
                        <a href="{{ route('admin_mapel.create') }}"
                            class="btn btn-primary btn-sm">
                            <i class="mr-1 fas fa-plus"></i> Tambah Mapel
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
