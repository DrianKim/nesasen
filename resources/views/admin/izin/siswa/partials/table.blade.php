<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="10%" class="sortable" data-column="tanggal">Tanggal <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="siswa_id">Siswa <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="kelas_id">Kelas <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="jenis_izin">Izin <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="keterangan">Keterangan <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="lampiran">Lampiran <i class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($izin_siswa as $item)
            <tr id="row-{{ $item->id }}">
                <td>
                    <input type="checkbox" name="selected_izin_siswa[]" value="{{ $item->id }}">
                </td>
                <td class="editable-cell" data-field="tanggal">{{ $item->tanggal ?? '-' }}</td>
                <td class="editable-cell" data-field="siswa_id">
                    {{ $item->siswa->nama }}
                </td>
                <td class="editable-cell" data-field="kelas_id">
                    {{ $item->siswa->kelas->tingkat . ' ' . $item->siswa->kelas->jurusan->kode_jurusan . ' ' . $item->siswa->kelas->no_kelas }}
                </td>
                <td class="editable-cell" data-field="jenis_izin">
                    {{ $item->jenis_izin->nama }}
                </td>
                <td class="editable-cell" data-field="keterangan">{{ $item->keterangan ?? '-' }}</td>
                <td class="editable-cell" data-field="lampiran">
                    @if ($item->lampiran)
                        <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank">Lihat Lampiran</a>
                    @else
                        -
                    @endif
                </td>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                            data-target="#modalSiswaShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                            data-target="#modalSiswaDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    {{-- @include('admin.izin.siswa.modal') --}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data izin siswa yang ditemukan</p>
                        {{-- @include('admin.izin.siswa.modal-create')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalSiswaCreate">
                            <i class="mr-1 fas fa-plus"></i> Tambah Siswa
                        </button> --}}
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
