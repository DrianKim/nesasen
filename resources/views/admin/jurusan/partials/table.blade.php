<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="30%" class="sortable" data-column="Nama Jurusan">Nama Jurusan<i
                    class="fas fa-sort"></i></th>
            <th width="30%" class="sortable" data-column="Kode Jurusan">Kode Jurusan<i
                    class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($jurusan as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_jurusan[]" value="{{ $item->id }}">
                </td>
                <td>{{ $item->nama_jurusan ?? '-' }}
                <td>{{ $item->kode_jurusan ?? '-' }}</td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary"
                            data-toggle="modal"
                            data-target="#modalJurusanShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-toggle="modal"
                            data-target="#modalJurusanDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @include('admin.jurusan.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data"
                            width="120">
                        <p>Tidak ada data jurusan yang ditemukan</p>
                        <a href="{{ route('admin_jurusan.create') }}"
                            class="btn btn-primary btn-sm">
                            <i class="mr-1 fas fa-plus"></i> Tambah Jurusan
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
