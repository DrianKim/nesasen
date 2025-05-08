<table class="table table-hover">
    <thead>
        <tr>
            <th width="3%">
                <input type="checkbox" onclick="toggleAll(this)">
            </th>
            <th width="10%" class="sortable" data-column="NISN">NISN <i class="fas fa-sort"></i></th>
            <th width="10%" class="sortable" data-column="NIS">NIS <i class="fas fa-sort"></i></th>
            <th width="30%" class="sortable" data-column="Nama Siswa">Nama Siswa <i class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="No. HP">No. HP <i class="fas fa-sort"></i></th>
            <th width="15%" class="sortable" data-column="Kelas">Kelas <i class="fas fa-sort"></i></th>
            <th class="text-center" width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($siswa as $item)
            <tr id="row-{{ $item->id }}">
                <td>
                    <input type="checkbox" name="selected_siswa[]" value="{{ $item->id }}">
                </td>
                <td class="editable-cell" data-field="nisn">{{ $item->nisn ?? '-' }}</td>
                <td class="editable-cell" data-field="nis">{{ $item->nis ?? '-' }}</td>
                <td class="editable-cell" data-field="nama">{{ $item->nama ?? '-' }}</td>
                <td class="editable-cell" data-field="no_hp">{{ $item->no_hp ?? '-' }}</td>
                <td>{{ $item->kelas ? $item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas : '-' }}
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
                    @include('admin.siswa.modal')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets/images/empty-data.svg') }}" alt="No Data" width="120">
                        <p>Tidak ada data siswa yang ditemukan</p>
                        <a href="{{ route('admin_siswa.create') }}" class="btn btn-primary btn-sm">
                            <i class="mr-1 fas fa-plus"></i> Tambah Siswa
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@if ($errors->has('username'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Username Sudah Dipakai!',
            text: '{{ $errors->first('username') }}',
        });
    </script>
@endif

@if ($errors->has('email'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Email Sudah Terdaftar!',
            text: '{{ $errors->first('email') }}',
        });
    </script>
@endif
@if ($errors->has('nisn'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'NISN Sudah Terdaftar!',
            text: '{{ $errors->first('nisn') }}',
        });
    </script>
@endif
@if ($errors->has('nis'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'NIS Sudah Terdaftar!',
            text: '{{ $errors->first('nis') }}',
        });
    </script>
@endif
@if ($errors->has('no_hp'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'No HP Sudah Terdaftar!',
            text: '{{ $errors->first('no_hp') }}',
        });
    </script>
@endif
