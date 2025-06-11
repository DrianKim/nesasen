<table class="table table-hover">
    <thead>
        <tr>
            {{-- <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th> --}}
            <th width="15%" class="sortable" data-column="Kelas">Tanggal<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Absen Masuk">Total Guru<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Hadir">Hadir<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Izin">Izin<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Sakit">Sakit<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Sakit">Alpa<i
                    class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- @forelse ($presensi_guru as $item) --}}
            <tr>
                {{-- <td>
                    <input type="checkbox" name="selected_presensi_guru[]" value="{{ $item->id }}">
                </td> --}}
                <td>13/05/2025</td>
                <td>543</td>
                <td>540</td>
                <td>2</td>
                <td>1</td>
                <td>0</td>
                {{-- <td>{{ $item['kelas'] ?? '-' }}</td>
                <td>{{ $item['masuk'] ?? 0 }}</td>
                <td>{{ $item['pulang'] ?? 0 }}</td>
                <td>{{ $item['hadir'] ?? 0 }}</td>
                <td>{{ $item['izin'] ?? 0 }}</td>
                <td>{{ $item['sakit'] ?? 0 }}</td> --}}
                <td class="text-center">
                    <div class="action-buttons">
                        {{-- <button type="button" class="btn btn-sm btn-outline-primary"
                            data-toggle="modal"
                            data-target="#modalPresensiSiswaShow{{ $item->id }}">
                            <i class="fas fa-edit"></i>
                        </button> --}}
                        {{-- <button type="button" class="btn btn-sm btn-outline-danger"
                            data-toggle="modal"
                            data-target="#modalPresensiSiswaDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i> --}}
                        </button>
                    </div>
                    {{-- @include('admin.presensi.guru.modal') --}}
                </td>
            </tr>
        {{-- @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data"
                            width="120">
                        <p>Tidak ada data presensi guru yang ditemukan</p>
                        <a href="{{ route('admin_presensi_guru.create') }}"
                            class="btn btn-primary btn-sm">
                            <i class="mr-1 fas fa-plus"></i> Tambah PresensiSiswa
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse --}}
    </tbody>
</table>

