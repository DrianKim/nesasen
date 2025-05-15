<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="10%" class="sortable" data-column="tanggal">Tanggal <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="guru_id">Siswa <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="jenis_izin">Izin <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="keterangan">Keterangan <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="lampiran">Lampiran <i class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($izin_guru as $item)
            <tr id="row-{{ $item->id }}">
                <td>
                    <input type="checkbox" name="selected_izin_guru[]" value="{{ $item->id }}">
                </td>
                <td class="editable-cell" data-field="tanggal">{{ $item->tanggal ?? '-' }}</td>
                <td class="editable-cell" data-field="guru_id">
                    {{ $item->guru->nama }}
                </td>
                <td class="editable-cell" data-field="kelas_id">
                    {{ $item->guru->kelas->tingkat . ' ' . $item->guru->kelas->jurusan->kode_jurusan . ' ' . $item->guru->kelas->no_kelas }}
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
                    {{-- @include('admin.izin.guru.modal') --}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data izin guru yang ditemukan</p>
                        {{-- @include('admin.izin.guru.modal-create')
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
