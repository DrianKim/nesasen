<table class="data-kelas-table">
    <thead>
        <tr>
            {{-- <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th> --}}
            <th width="10%" class="sortable" data-column="tanggal">Tanggal <i class="fas fa-sort"
                    style="margin-left: 4px;"></i></th>
            <th width="10%" class="sortable" data-column="guru">Guru <i class="fas fa-sort"
                    style="margin-left: 4px;"></i></th>
            <th width="10%" data-column="jenis_izin">Izin</th>
            <th width="10%" data-column="keterangan">Keterangan</th>
            <th width="10%" data-column="lampiran">Lampiran</th>
            {{-- <th class="text-center" width="12%">Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse ($izin_guru as $item)
            <tr id="row-{{ $item->id }}">
                {{-- <td>
                    <input type="checkbox" name="selected_izin_guru[]" value="{{ $item->id }}">
                </td> --}}
                <td data-field="tanggal">{{ $item->tanggal ?? '-' }}</td>
                <td data-field="guru" data-label="Guru">
                    {{ $item->guru->nama }}
                </td>
                <td data-field="jenis_izin">
                    {{ $item->jenis_izin }}
                </td>
                <td data-field="keterangan">{{ $item->keterangan ?? '-' }}</td>
                <td data-field="lampiran">
                    @if ($item->lampiran)
                        <a href="{{ route('admin_izin_lampiran_guru.download', basename($item->lampiran)) }}"
                            target="_blank">Lihat | </a>
                        <a href="{{ route('admin_izin_lampiran_guru.download', basename($item->lampiran)) }}"
                            download>Download</a>
                    @else
                        -
                    @endif
                </td>
                {{-- <td data-label="Action">
                    <div class="action-buttons">
                        @include('admin.kelas.modal.edit', ['id' => $item->id, 'kelas' => $item])
                        <button type="button" class="btn-edit"
                            onclick="openModalEdit('modalKelasEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-id="{{ $item->id }}"
                            data-nama="">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </div>
                </td> --}}
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data izin guru yang ditemukan</p>
                        {{-- @include('admin.izin.guru.modal-create')
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalGuruCreate">
                            <i class="mr-1 fas fa-plus"></i> Tambah Guru
                        </button> --}}
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
