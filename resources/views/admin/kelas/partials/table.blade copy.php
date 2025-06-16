<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="15%" class="sortable" data-column="Nama Kelas">Kelas<i class="fas fa-sort"></i></th>
            <th width="30%" class="sortable" data-column="No. HP">Wali Kelas<i class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Kelas">Jumlah Siswa<i class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kelas as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_kelas[]" value="{{ $item->id }}">
                </td>
                <td>{{ $item->tingkat . ' ' . $item->jurusan->kode_jurusan . ' ' . $item->no_kelas ?? '-' }}
                <td>{{ $item->walas->user->guru->nama ?? '-' }}</td>
                <td>{{ $item->siswa->count() ?? 0 }}</td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                            data-target="#modalKelasShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                            data-target="#modalKelasDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @include('admin.kelas.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data kelas yang ditemukan</p>
                        @include('admin.kelas.modal-create')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalKelasCreate">
                            <i class="mr-1 fas fa-plus"></i>
                            <span class="button-label">Tambah Kelas</span>
                        </button>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
