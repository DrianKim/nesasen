<table class="data-kelas-table">
    <thead>
        <tr>
            <th width="2%"><input type="checkbox" id="select-all" class="item-checkbox-all"></th>
            <th class="sortable" data-column="NISN" width="10%">NISN <i class="fa-solid fa-sort"
                    style="margin-left: 4px"></i></th>
            <th class="sortable" data-column="NIS" width="10%">NIS <i class="fa-solid fa-sort"
                    style="margin-left: 4px"></i></th>
            <th class="sortable" data-column="Nama Siswa" width="30%">Nama Siswa<i class="fa-solid fa-sort"
                    style="margin-left: 4px"></i></th>
            <th class="sortable" data-column="No. HP" width="15%">No. HP<i class="fa-solid fa-sort"
                    style="margin-left: 4px"></i></th>
            <th class="sortable" data-column="Kelas" width="15%">Kelas<i class="fa-solid fa-sort"
                    style="margin-left: 4px"></i></th>
            <th width="12%">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($siswa as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected_siswa[]" class="item-checkbox" value="{{ $item->id }}">
                </td>
                <td data-field="nisn">{{ $item->nisn ?? '-' }}</td>
                <td data-field="nis">{{ $item->nis ?? '-' }}</td>
                <td data-field="nama">{{ $item->nama ?? '-' }}</td>
                <td data-field="no_hp">{{ $item->no_hp ?? '-' }}</td>
                <td>{{ $item->kelas ? $item->kelas->tingkat . ' ' . $item->kelas->jurusan->kode_jurusan . ' ' . $item->kelas->no_kelas : '-' }}
                </td>
                <div class="action-buttons">
                    <td>
                        @include('admin.siswa.modal.edit', ['id' => $item->id, 'siswa' => $item])
                        <button class="btn-edit" onclick="openModalEdit('modalSiswaEdit{{ $item->id }}')">
                            <span class="material-icons-sharp">edit</span>
                        </button>
                        <button class="btn-delete" data-toggle="modal"
                            data-target="#modalKelasDestroy{{ $item->id }}">
                            <span class="material-icons-sharp">delete</span>
                        </button>
                    </td>
                </div>
                {{-- @include('admin.siswa.modal') --}}
            </tr>
        @empty
            <tr>
                <td colspan="7" class="py-4 text-center">
                    <div class="empty-state">
                        <img src="{{ asset('assets\img\not-found.png') }}" alt="No Data" width="120">
                        <p>Tidak ada data siswa yang ditemukan</p>
                        {{-- @include('admin.siswa.modal-create') --}}
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modalSiswaCreate">
                            <i class="mr-1 fas fa-plus"></i> Tambah Siswa
                        </button>
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
