<table class="table table-hover">
    <thead>
        <tr>
            {{-- <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th> --}}
            <th width="15%" class="sortable" data-column="Kelas">Kelas<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Absen Masuk">Check In<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Absen Pulang">Check Out<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Hadir">Hadir<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Izin">Izin<i
                    class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Sakit">Sakit<i
                    class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($presensi_siswa as $item)
            <tr>
                {{-- <td>
                    <input type="checkbox" name="selected_presensi_siswa[]" value="{{ $item->id }}">
                </td> --}}
                <td>{{ $item['kelas'] ?? '-' }}</td>
                <td>{{ $item['masuk'] ?? 0 }}</td>
                <td>{{ $item['pulang'] ?? 0 }}</td>
                <td>{{ $item['hadir'] ?? 0 }}</td>
                <td>{{ $item['izin'] ?? 0 }}</td>
                <td>{{ $item['sakit'] ?? 0 }}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        {{-- <button type="button" class="btn btn-sm btn-outline-primary"
                            data-toggle="modal"
                            data-target="#modalPresensiSiswaShow{{ $item->id }}">
                            <i class="fas fa-edit"></i> --}}
                        </button>
                        {{-- <button type="button" class="btn btn-sm btn-outline-danger"
                            data-toggle="modal"
                            data-target="#modalPresensiSiswaDestroy{{ $item->id }}">
                            <i class="fas fa-trash"></i> --}}
                        </button>
                    </div>
                    @include('admin.presensi.siswa.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data"
                            width="120">
                        <p>Tidak ada data presensi_siswa yang ditemukan</p>
                        {{-- <a href="{{ route('admin_presensi_siswa.create') }}"
                            class="btn btn-primary btn-sm">
                            <i class="mr-1 fas fa-plus"></i> Tambah PresensiSiswa
                        </a> --}}
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

