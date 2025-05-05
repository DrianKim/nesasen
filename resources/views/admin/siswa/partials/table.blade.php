<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="10%" class="sortable" data-column="NISN">NISN <i
                    class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="NIS">NIS <i
                    class="fas fa-sort"></i></th>
            <th width="30%" class="sortable" data-column="Nama Siswa">Nama Siswa <i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="No. HP">No. HP <i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Kelas">Kelas <i
                    class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($siswa as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_murids[]"
                        value="{{ $item->id }}">
                </td>
                <td>{{ $item->nisn ?? '-' }}</td>
                <td>{{ $item->nis ?? '-' }}</td>
                <td>{{ $item->nama ?? '-' }}</td>
                <td>{{ $item->no_hp ?? '-' }}</td>
                <td>{{ $item->kelas ? $item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas : '-' }}
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary"
                            data-toggle="modal"
                            data-target="#modalSiswaShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-toggle="modal"
                            data-target="#modalSiswaDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @include('admin.siswa.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data"
                            width="120">
                        <p>Tidak ada data siswa yang ditemukan</p>
                        <a href="{{ route('admin_siswa.create') }}"
                            class="btn btn-primary btn-sm">
                            <i class="mr-1 fas fa-plus"></i> Tambah Siswa
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
