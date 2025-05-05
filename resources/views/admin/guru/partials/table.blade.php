<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="15%" class="sortable" data-column="NIP">NIP<i
                    class="fas fa-sort"></i></th>
            <th width="30%" class="sortable" data-column="Nama Guru">Nama Guru<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="No. HP">No. HP<i
                    class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($guru as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_guru[]" value="{{ $item->id }}">
                </td>
                <td>{{ $item->nip ?? '-' }}
                <td>{{ $item->nama ?? '-' }}</td>
                <td>{{ $item->no_hp ?? '-' }}</td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary"
                            data-toggle="modal"
                            data-target="#modalGuruShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-toggle="modal"
                            data-target="#modalGuruDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @include('admin.guru.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data"
                            width="120">
                        <p>Tidak ada data guru yang ditemukan</p>
                        <a href="{{ route('admin_guru.create') }}"
                            class="btn btn-primary btn-sm">
                            <i class="mr-1 fas fa-plus"></i> Tambah Guru
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
