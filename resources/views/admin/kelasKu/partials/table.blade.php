<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="25%" class="sortable" data-column="KelasKu">KelasKu<i class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Kelas">Kelas<i class="fas fa-sort"></i></th>
            <th width="30%" class="sortable" data-column="Guru">Guru<i class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kelasKu as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_kelasKu[]" value="{{ $item->id }}">
                </td>
                <td>{{ $item->mataPelajaran->nama_mapel ?? '-' }}</td>
                <td>{{ $item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas ?? '-' }}
                <td>{{ $item->guru->nama ?? '-' }}</td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                            data-target="#modalKelasKuShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                            data-target="#modalKelasKuDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @include('admin.kelasKu.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data kelasKu yang ditemukan</p>
                        @include('admin.kelasKu.modal-create')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalKelasKuCreate">
                            <i class="mr-1 fas fa-plus"></i>
                            <span class="button-label">Tambah KelasKu</span>
                        </button>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
